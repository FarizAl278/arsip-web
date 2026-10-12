<?php

namespace Tests\Feature;

use App\Http\Controllers\GoogleDriveController;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\FileList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GoogleDriveControllerTest extends TestCase
{
    // ─────────────────────────────────────────────────────────────────────────
    //  Helpers
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Buat partial mock controller dengan getDriveService() di-override
     * supaya tidak perlu credentials Google asli saat testing.
     */
    private function makeController(MockInterface $mockDrive): GoogleDriveController
    {
        /** @var GoogleDriveController&MockInterface $controller */
        $controller = Mockery::mock(GoogleDriveController::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $controller->allows('getDriveService')->andReturn($mockDrive);

        return $controller;
    }

    /** Buat mock Drive service dengan property files yang bisa di-set. */
    private function mockDrive(): MockInterface
    {
        return Mockery::mock(Drive::class);
    }

    /**
     * Buat mock DriveFile yang mengembalikan nilai properti tertentu.
     *
     * @param array{id:string,name:string,mimeType:string,webViewLink:string,webContentLink:string,size:string} $attrs
     */
    private function makeDriveFile(array $attrs): DriveFile
    {
        /** @var DriveFile&MockInterface $file */
        $file = Mockery::mock(DriveFile::class);
        $file->allows('getId')->andReturn($attrs['id']);
        $file->allows('getName')->andReturn($attrs['name']);
        $file->allows('getMimeType')->andReturn($attrs['mimeType'] ?? $attrs['mime_type'] ?? '');
        $file->allows('getWebViewLink')->andReturn($attrs['webViewLink'] ?? $attrs['view_link'] ?? null);
        $file->allows('getWebContentLink')->andReturn($attrs['webContentLink'] ?? $attrs['download_link'] ?? null);
        $file->allows('getSize')->andReturn($attrs['size'] ?? null);

        return $file;
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  GET /drive/files — getFiles
    // ─────────────────────────────────────────────────────────────────────────

    #[Test]
    public function it_returns_list_of_files_successfully(): void
    {
        $fakeFiles = [
            $this->makeDriveFile([
                'id'            => 'file-aaa',
                'name'          => 'Surat Keputusan.pdf',
                'mimeType'      => 'application/pdf',
                'webViewLink'   => 'https://drive.google.com/file/d/file-aaa/view',
                'webContentLink'=> 'https://drive.google.com/uc?id=file-aaa',
                'size'          => '102400',
            ]),
            $this->makeDriveFile([
                'id'            => 'file-bbb',
                'name'          => 'Laporan Tahunan.docx',
                'mimeType'      => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'webViewLink'   => 'https://drive.google.com/file/d/file-bbb/view',
                'webContentLink'=> 'https://drive.google.com/uc?id=file-bbb',
                'size'          => '204800',
            ]),
        ];

        $fileList = Mockery::mock(FileList::class);
        $fileList->allows('getFiles')->andReturn($fakeFiles);

        $mockFiles = Mockery::mock();
        $mockFiles->allows('listFiles')->andReturn($fileList);

        $drive        = $this->mockDrive();
        $drive->files = $mockFiles;

        $controller = $this->makeController($drive);
        $this->app->instance(GoogleDriveController::class, $controller);

        $response = $this->getJson('/drive/files');

        $response
            ->assertOk()
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('count', 2);

        // getFiles() return raw DriveFile objects — JSON key mengikuti Google SDK
        // (id, name, mimeType, webViewLink, webContentLink, size)
        $data = $response->json('data');
        $this->assertCount(2, $data);
    }

    #[Test]
    public function it_returns_empty_list_when_folder_has_no_files(): void
    {
        $fileList = Mockery::mock(FileList::class);
        $fileList->allows('getFiles')->andReturn([]);

        $mockFiles = Mockery::mock();
        $mockFiles->allows('listFiles')->andReturn($fileList);

        $drive        = $this->mockDrive();
        $drive->files = $mockFiles;

        $controller = $this->makeController($drive);
        $this->app->instance(GoogleDriveController::class, $controller);

        $response = $this->getJson('/drive/files');

        $response
            ->assertOk()
            ->assertJson([
                'status' => 'success',
                'count'  => 0,
                'data'   => [],
            ]);
    }

    #[Test]
    public function it_returns_500_when_drive_throws_exception_on_list(): void
    {
        $mockFiles = Mockery::mock();
        $mockFiles->allows('listFiles')->andThrow(new \Exception('Drive API Error'));

        $drive        = $this->mockDrive();
        $drive->files = $mockFiles;

        $controller = $this->makeController($drive);
        $this->app->instance(GoogleDriveController::class, $controller);

        $response = $this->getJson('/drive/files');

        $response
            ->assertStatus(500)
            ->assertJson([
                'status'  => 'error',
                'message' => 'Drive API Error',
            ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  POST /drive/upload — uploadFile
    // ─────────────────────────────────────────────────────────────────────────

    #[Test]
    public function it_uploads_a_file_successfully(): void
    {
        $driveResult = $this->makeDriveFile([
            'id'            => 'uploaded-id-xyz',
            'name'          => 'dokumen-test.pdf',
            'mimeType'      => 'application/pdf',
            'webViewLink'   => 'https://drive.google.com/file/d/uploaded-id-xyz/view',
            'webContentLink'=> null,
            'size'          => '2048',
        ]);

        $mockFiles = Mockery::mock();
        $mockFiles->allows('create')->andReturn($driveResult);

        $drive        = $this->mockDrive();
        $drive->files = $mockFiles;

        $controller = $this->makeController($drive);
        $this->app->instance(GoogleDriveController::class, $controller);

        $fakeFile = UploadedFile::fake()->create('dokumen-test.pdf', 10, 'application/pdf');

        $response = $this->postJson('/drive/upload', ['file' => $fakeFile]);

        $response
            ->assertOk()
            ->assertJson([
                'status'  => 'success',
                'message' => 'File berhasil diupload ke Drive Akun A',
            ])
            ->assertJsonStructure(['status', 'message', 'file']);
    }

    #[Test]
    public function it_returns_422_when_no_file_is_provided(): void
    {
        // Controller tidak diperlukan karena validasi gagal sebelum sampai controller
        $response = $this->postJson('/drive/upload', []);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    #[Test]
    public function it_returns_500_when_drive_throws_exception_on_upload(): void
    {
        $mockFiles = Mockery::mock();
        $mockFiles->allows('create')->andThrow(new \Exception('Upload gagal: quota exceeded'));

        $drive        = $this->mockDrive();
        $drive->files = $mockFiles;

        $controller = $this->makeController($drive);
        $this->app->instance(GoogleDriveController::class, $controller);

        $fakeFile = UploadedFile::fake()->create('fail.pdf', 5, 'application/pdf');

        $response = $this->postJson('/drive/upload', ['file' => $fakeFile]);

        $response
            ->assertStatus(500)
            ->assertJson([
                'status'  => 'error',
                'message' => 'Upload gagal: quota exceeded',
            ]);
    }

    // ─────────────────────────────────────────────────────────────────────────

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
