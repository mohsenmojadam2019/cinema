<?php
namespace App\Services\Sms; use App\Contracts\SmsAdapter; use Kavenegar\KavenegarApi;
class KavenegarSmsAdapter implements SmsAdapter { public function send(string $receptor,string $message):bool{$key=config('services.kavenegar.key');if(!$key)return false;(new KavenegarApi($key))->Send(config('services.kavenegar.sender'),$receptor,$message);return true;} }
