<?php

namespace App\Http\Controllers;
use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GoogleDriveController extends Controller
{
    protected function getDriveService(): Drive
    {
        $client = new Client;
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));

        $client->refreshToken(env('GOOGLE_REFRESH_TOKEN'));

        return new Drive($client);
    }

    public function getFiles(): JsonResponse
    {
        try {
            $service = $this->getDriveService();
            $folderId = env('GOOGLE_DRIVE_FOLDER_ID');

            $optParams = [
                'q' => "'{$folderId}' in parents and trashed = false",
                'fields' => 'files(id, name, mimeType, webViewLink, webContentLink, size)',
                'pageSize' => 50,
            ];

            $results = $service->files->listFiles($optParams);

            return response()->json([
                'status' => 'success',
                'count' => count($results->getFiles()),
                'data' => $results->getFiles(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function uploadFile(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        try {
            $service = $this->getDriveService();
            $uploadedFile = $request->file('file');
            $folderId = env('GOOGLE_DRIVE_FOLDER_ID');

            $fileMetadata = new DriveFile([
                'name' => $uploadedFile->getClientOriginalName(),
                'parents' => [$folderId]
            ]);

            $content = file_get_contents($uploadedFile->getRealPath());

            $file = $service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $uploadedFile->getMimeType(),
                'uploadType' => 'multipart',
                'fields' => 'id, name, webViewLink, size',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'File berhasil diupload ke Drive Akun A',
                'file' => $file,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
}
