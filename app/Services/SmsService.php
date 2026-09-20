<?php
namespace App\Services;
use Kavenegar\KavenegarApi;
class SmsService { public function send(string $receptor,string $message):bool { $key=config('services.kavenegar.key'); if(!$key)return false; (new KavenegarApi($key))->Send(config('services.kavenegar.sender'),$receptor,$message); return true; } }
