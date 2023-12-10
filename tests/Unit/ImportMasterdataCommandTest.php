<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ImportMasterdataCommandTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    /**
     * A basic unit test example.
     */
    public function test_import_masterdata_artisan_cmd(): void
    {
        $this->artisan('import:master-data')->assertSuccessful();
        $this->assertDatabaseCount('customers', 10000);
        $this->assertDatabaseCount('products', 100);
    }
}
