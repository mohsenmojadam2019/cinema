<?php
namespace App\Console\Commands; use App\Models\Reservation; use Illuminate\Console\Command;
class ReleaseExpiredReservations extends Command {protected $signature='cinema:release-reservations'; protected $description='Release expired seat holds'; public function handle():int{$count=Reservation::where('status','held')->where('expires_at','<=',now())->update(['status'=>'expired']);$this->info("Released {$count} reservations.");return self::SUCCESS;}}
