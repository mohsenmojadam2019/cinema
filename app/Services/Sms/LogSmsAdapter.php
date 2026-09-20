<?php
namespace App\Services\Sms; use App\Contracts\SmsAdapter; use Illuminate\Support\Facades\Log;
class LogSmsAdapter implements SmsAdapter { public function send(string $receptor,string $message):bool{Log::info('SMS adapter message',['receptor'=>$receptor,'message'=>$message]);return true;} }
