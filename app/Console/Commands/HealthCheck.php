<?php
namespace App\Console\Commands; use Illuminate\Console\Command; use Illuminate\Support\Facades\{DB,Cache};
class HealthCheck extends Command {protected $signature='cinema:health';protected $description='Check database, cache and storage readiness';public function handle():int{try{DB::select('select 1');Cache::put('cinema-health','ok',10);$ok=Cache::get('cinema-health')==='ok'&&is_writable(storage_path());$this->info($ok?'OK':'DEGRADED');return $ok?self::SUCCESS:self::FAILURE;}catch(\Throwable $e){$this->error($e->getMessage());return self::FAILURE;}}}
