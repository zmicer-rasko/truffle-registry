<?php

namespace Tests\Feature;

use App\Jobs\ImportTruffle;
use App\Jobs\ProcessTruffle;
use App\Models\Truffle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class TruffleTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_token()
    {
        // Given an existing user
        $user = User::factory()->create();

        // A user who knows their credentials can obtain a token
        $this->post('/api/token', [
            'email' => $user->email,
            'password' => 'password'
        ])->assertOk();
    }

    public function test_authenticated_user_can_register_truffle()
    {
        // Given an authenticated user
        $user = User::factory()->create();
        $response = $this->post('/api/token', [
            'email' => $user->email, 'password' => 'password'
        ]);

        // He/she can register a truffle
        $this->get('/api/register-truffle?weight=1&price=1.1', [
            'Authorization' => 'Bearer ' . $response->getContent()
        ])
            ->assertOk()
            ->assertContent('{"status":"success"}');
    }

    public function test_truffle_processor_exports_truffles()
    {
        // Given a truffle
        app()->useStoragePath(base_path() . DIRECTORY_SEPARATOR . 'tests');
        $truffle = Truffle::factory()->create(['sku' => Str::uuid()]);

        // When the truffle processor handles it
        (new ProcessTruffle($truffle))->handle();

        // The export file appears
        $resultFile = storage_path('app') . DIRECTORY_SEPARATOR . 'export.csv';
        $this->assertFileExists($resultFile);

        unlink($resultFile);
        rmdir(dirname($resultFile));
    }

    public function test_truffle_importer_also_exports_truffles()
    {
        // Given a source file
        app()->useStoragePath(base_path() . DIRECTORY_SEPARATOR . 'tests');
        $importFile = storage_path('app') . DIRECTORY_SEPARATOR . 'import.csv';
        file_exists(dirname($importFile)) || mkdir(dirname($importFile), 0777, true);
        file_put_contents($importFile,
            '6ba5d6f0-08b4-46ac-b82f-060cb5d369da,5,3.1,"2022-12-01 16:43:41"');

        // When the truffle importer handles it
        (new ImportTruffle())->handle();

        // The export file appears
        $resultFile = storage_path('app') . DIRECTORY_SEPARATOR . 'export.csv';
        $this->assertFileExists($resultFile);

        unlink($importFile);
        unlink($resultFile);
        rmdir(dirname($resultFile));
    }
}
