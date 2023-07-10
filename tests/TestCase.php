<?php

namespace Tests;

use App\Services\TruffleService;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $importFile;
    protected $exportFile;

    protected function setUp() :void
    {
        parent::setUp();

        Config::set('filesystems.default', 'test');
    }

    protected function tearDown() :void
    {
        if (Storage::exists(TruffleService::IMPORT_FILE)) {
            Storage::delete(TruffleService::IMPORT_FILE);
        }

        if (Storage::exists(TruffleService::EXPORT_FILE)) {
            Storage::delete(TruffleService::EXPORT_FILE);
        }

        parent::tearDown();
    }
}
