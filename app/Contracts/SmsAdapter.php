<?php
namespace App\Contracts;
interface SmsAdapter { public function send(string $receptor,string $message):bool; }
