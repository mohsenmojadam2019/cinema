<?php
namespace App\Services;
use App\Models\{Order,Payment}; use Illuminate\Support\Str;
class PaymentService { public function start(Order $order):Payment {return Payment::create(['order_id'=>$order->id,'gateway'=>config('services.payment.driver','mock'),'authority'=>'MOCK-'.Str::upper(Str::random(16)),'amount'=>$order->total,'status'=>'paid','response'=>['mode'=>'mock','created_at'=>now()->toIso8601String()]]);} }
