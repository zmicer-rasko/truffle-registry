<?php

namespace Tests\Integration;

use App\Jobs\TruffleFtpExport;
use App\Models\Truffle;
use App\Models\User;
use App\Services\TruffleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Support\Facades\Bus;

class TruffleTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_job_pushed_after_hunter_created_truffle()
    {
        Bus::fake();

        // Given an authenticated user
        $user = User::factory()->create();
        $response = $this->post('/api/token', [
            'email' => $user->email, 'password' => 'password'
        ]);

        // Hunter can register a truffle
        $weight = fake()->randomDigitNotNull();
        $price  = fake()->randomFloat(2, 1);
        $this->get("/api/register-truffle?weight={$weight}&price={$price}", [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . json_decode($response->getContent())
        ]);

        Bus::assertDispatched(function (TruffleFtpExport $job) use ($weight, $price) {
            return $job->truffle->weight == $weight && $job->truffle->price == $price;
        });
    }

    public function test_export_job_pushed_after_manufacturer_csv_row_created_truffle()
    {
        Bus::fake();

        $weight = fake()->randomDigitNotNull();
        $price  = fake()->randomFloat(2, 1);
        $sku = Truffle::generateSku();
        Storage::put(TruffleService::IMPORT_FILE, $sku . ',' . $weight . ',' . $price . ",2022-12-01 16:43:41");

        app()->make(TruffleService::class)->importTrufflesFromCsv();

        Bus::assertDispatched(function (TruffleFtpExport $job) use ($weight, $price) {
            return $job->truffle->weight == $weight && $job->truffle->price == $price;
        });
    }

    public function test_truffle_from_hunter_is_exported()
    {
        $this->assertDatabaseCount('truffles', 0);

        // Given an authenticated user
        $user = User::factory()->create();
        $response = $this->post('/api/token', [
            'email' => $user->email, 'password' => 'password'
        ]);

        // Hunter can register a truffle
        $weight = fake()->randomDigitNotNull();
        $price  = fake()->randomFloat(2, 1);
        $this->get("/api/register-truffle?weight={$weight}&price={$price}", [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . json_decode($response->getContent())
        ]);

        $this->assertDatabaseCount('truffles', 1);
        $model = Truffle::all()->first();

        $this->assertStringContainsString($model->sku, Storage::get(TruffleService::EXPORT_FILE));
    }

    public function test_truffles_from_import_file_is_exported()
    {
        $this->assertDatabaseCount('truffles', 0);

        $weight1 = fake()->randomDigitNotNull();
        $price1  = fake()->randomFloat(2, 1);
        $sku1 = Truffle::generateSku();
        Storage::put(TruffleService::IMPORT_FILE, $sku1 . ',' . $weight1 . ',' . $price1 . ",2022-12-01 16:43:41");

        $weight2 = fake()->randomDigitNotNull();
        $price2  = fake()->randomFloat(2, 1);
        $sku2 = Truffle::generateSku();
        Storage::append(TruffleService::IMPORT_FILE, $sku2 . ',' . $weight2 . ',' . $price2 . ",2022-12-01 16:43:41");

        $weight3 = fake()->randomDigitNotNull();
        $price3  = fake()->randomFloat(2, 1);
        $sku3 = Truffle::generateSku();
        Storage::append(TruffleService::IMPORT_FILE, $sku3 . ',' . $weight3 . ',' . $price3 . ",2022-12-01 16:43:41");

        app()->make(TruffleService::class)->importTrufflesFromCsv();

        $this->assertDatabaseCount('truffles', 3);

        $this->assertStringContainsString($sku1, Storage::get(TruffleService::EXPORT_FILE));
        $this->assertStringContainsString($sku2, Storage::get(TruffleService::EXPORT_FILE));
        $this->assertStringContainsString($sku3, Storage::get(TruffleService::EXPORT_FILE));
    }
}
