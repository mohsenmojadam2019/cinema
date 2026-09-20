<?php
namespace App\Services;
use App\Contracts\SmsAdapter; use App\Services\Sms\KavenegarSmsAdapter; use App\Services\Sms\LogSmsAdapter;
class SmsService { public function __construct(private ?SmsAdapter $adapter=null){$this->adapter=$adapter?:match(config('services.sms.driver','log')){'kavenegar'=>app(KavenegarSmsAdapter::class),default=>app(LogSmsAdapter::class)};} public function send(string $receptor,string $message):bool{return $this->adapter->send($receptor,$message);} }
