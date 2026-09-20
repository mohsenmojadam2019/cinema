<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;

class ProductionConfigCheck extends Command
{
    protected $signature='cinema:config-check';
    protected $description='Validate production configuration without printing secrets';
    public function handle(): int
    {
        $required=['APP_KEY'=>config('app.key'),'APP_URL'=>config('app.url'),'QUEUE_CONNECTION'=>env('QUEUE_CONNECTION'),'CACHE_STORE'=>env('CACHE_STORE'),'REDIS_HOST'=>env('REDIS_HOST'),'KAVENEGAR_API_KEY'=>env('KAVENEGAR_API_KEY'),'ZARINPAL_MERCHANT_ID'=>config('services.zarinpal.merchant_id')];$failed=[];
        foreach($required as $key=>$value)if(!$value||in_array($value,['xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx','http://localhost:8000'],true))$failed[]=$key;
        if(config('app.env')==='production'&&config('app.debug'))$failed[]='APP_DEBUG=false';
        if(config('app.env')==='production'&&!str_starts_with((string)config('app.url'),'https://'))$failed[]='APP_URL=https://...';
        foreach($required as $key=>$value)if(!in_array($key,$failed,true))$this->line("OK $key");
        foreach(array_unique($failed) as $key)$this->error("MISSING/INVALID $key");
        return $failed?self::FAILURE:self::SUCCESS;
    }
}
