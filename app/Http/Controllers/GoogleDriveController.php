<?php

namespace App\Http\Controllers;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\JsonResponse;

class GoogleDriveController extends Controller
{
    private function getDriveService(): Drive
    {
        $client = new Client;
        $credentialsPath = base_path(env('GOOGLE_APPLICATION_CREDENTIALS'));
        $client->setAuthConfig($credentialsPath);
        $client->addScope(Drive::DRIVE);

        return new Drive($client);
    }

    public function getFiles(): JsonResponse
    {
        $service = $this->getDriveService();
        $folderId = env('GOOGLE_DRIVE_FOLDER_ID');

        $optParams = [
            'q' => "'{$folderId}' in parents and trashed = false",
            'fields' => 'files(id, name, mimeType, webViewLink, webContentLink, size)',
            'pageSize' => 100,
        ];

        $results = $service->files->listFiles($optParams);
        $files = $results->getFiles();

        $data = [];
        foreach($files as $file){
            $data[] = [
                'id' => $file->getId(),
                'name' => $file->getName(),
                'mime_type' => $file->getMimeType(),
                'view_link' => $file->getWebViewLink(),
                'download_link' => $file->getWebContentLink(),
                'size' => $file->getSize(),
            ];
        }

        return response()->json([
            'status' => 'success',
            'count' => count($data),
            'data' => $data
        ]);
    }
    
}
