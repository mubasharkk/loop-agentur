<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ImportMasterData extends Command
{

    const PRODUCTS_SOURCE = 'https://backend-developer.view.agentur-loop.com/products.csv';

    const CUSTOMERS_SOURCE = 'https://backend-developer.view.agentur-loop.com/customers.csv';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:master-data {--user=loop} {--pass=backend_dev}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import master data from provided sources';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->importProducts();
            $this->importCustomers();
        } catch (\Exception $ex) {
            $this->error($ex->getMessage());
        }
    }

    private function getDataFromCsv(string $uri): Collection
    {
        $this->info("Importing data from `$uri`");
        $context = stream_context_create([
            "http" => [
                // This can be handled via config
                // Skipped because of time
                "header" => "Authorization: Basic ".base64_encode(
                        implode(
                            ':',
                            [$this->option('user'), $this->option('pass')]
                        )
                    ),
            ],
        ]);
        $content = file_get_contents($uri, false, $context);

        $rows = array_map('str_getcsv', explode("\n", $content));
        $header = array_shift($rows);
        $data = [];
        foreach ($rows as $row) {
            $data[] = array_combine($header, $row);
        }

        $this->info(count($rows)." rows fetched.");

        return collect($data);
    }

    private function importProducts()
    {
        $this->info('Importing products...');
        // import products
        $productsData = $this->getDataFromCsv(self::PRODUCTS_SOURCE);

        $affected = Product::insertOrIgnore(
            $productsData->map(function (array $item) {
                return [
                    'id'         => intval($item['ID']),
                    'name'       => $item['productname'],
                    'price'      => floatval($item['price']),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            })->toArray()
        );

        $this->info("{$affected} products added to the `products` table!");
    }

    private function importCustomers()
    {
        $this->info('Importing customers...');
        // import customers
        $customersData = $this->getDataFromCsv(self::CUSTOMERS_SOURCE)
            ->map(function (array $item) {
                [$firstName, $lastName] = explode(' ', $item['FirstName LastName']);

                return [
                    'id'               => intval($item['ID']),
                    'job_title'        => $item['Job Title'],
                    'email_address'    => $item['Email Address'],
                    'first_name'       => $firstName,
                    'last_name'        => $lastName,
                    'registered_since' => Carbon::createFromFormat('l,F d,Y', $item['registered_since']),
                    'phone'            => $item['phone'],
                    'created_at'       => Carbon::now(),
                    'updated_at'       => Carbon::now(),
                ];
            });

        $chunks = $customersData->chunk(1000);

        $affected = 0;
        foreach ($chunks as $chunk) {
            $affected += Customer::insertOrIgnore(
                $chunk->toArray()
            );
        }

        $this->info("{$affected} customers added to the `customers` table!");
    }
}
