<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncCurrenciesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:currencies:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync currencies from external API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // fallback chains
        $chains = [
            'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies.min.json',
            'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies.json',
            'https://raw.githubusercontent.com/fawazahmed0/currency-api/1/latest/currencies.min.json',
            'https://raw.githubusercontent.com/fawazahmed0/currency-api/1/latest/currencies.json',
        ];

        // try to get currencies from external API
        $currencies = [];

        foreach ($chains as $chain) {
            $currencies = Http::get($chain)->json();

            if (is_array($currencies)) {
                break;
            }
        }

        // if currencies are not fetched, then exit
        if (!is_array($currencies)) {
            $this->error('Currencies could not be fetched from external API.');

            return;
        }

        // if currencies are fetched, then sync them
        $this->syncCurrencies($currencies);

        $this->info('Currencies synced successfully.');
    }

    /**
     * Sync currencies.
     *
     * @param array $currencies_arr
     */
    protected function syncCurrencies(array $currencies_arr): void
    {
        // $currencies_arr should be like this:
        // [ "AED" => "United Arab Emirates Dirham", ... ]

        // upsert currencies
        $currencies = collect($currencies_arr)->map(function ($name, $code) {
            return [
                'code' => $code,
                'name' => $name,
                'symbol' => Str::upper($code),
            ];
        })->toArray();

        // update currencies
        Currency::query()
            ->lockForUpdate()
            ->upsert($currencies, 'code', ['name', 'symbol']);
    }
}
