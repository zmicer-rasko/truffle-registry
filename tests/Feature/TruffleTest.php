<?php

namespace Tests\Feature;

use App\Models\Truffle;
use App\Models\User;
use App\Services\TruffleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TruffleTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_hunter_can_create_truffle_record()
    {
        // Given an authenticated user
        $user = User::factory()->create();
        $response = $this->post('/api/token', [
            'email' => $user->email, 'password' => 'password'
        ]);

        // Hunter can register a truffle
        $this->get('/api/register-truffle?weight=1&price=1.1', [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . json_decode($response->getContent())
        ])
            ->assertOk()
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll('status', 'data')
                    ->where('status', 'success')
            );
    }

    public function test_manufacturer_import_csv_file_can_create_truffle_record()
    {
        $sku = Truffle::generateSku();
        Storage::put(TruffleService::IMPORT_FILE, $sku . ',5,3.1,"2022-12-01 16:43:41"');

        // When the truffle importer handles it
        app()->make(TruffleService::class)->importTrufflesFromCsv();

        $this->assertDatabaseHas('truffles', [
            'sku' => $sku,
        ]);
    }
}
