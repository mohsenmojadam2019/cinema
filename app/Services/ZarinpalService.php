<?php
namespace App\Services;
use App\Models\{Order,Payment}; use Shetabit\Multipay\Invoice; use Shetabit\Payment\Facade\Payment as ShetabitPayment; use Shetabit\Multipay\Exceptions\InvalidPaymentException; use RuntimeException;
class ZarinpalService
{
 public function request(Order $order):Payment{$payment=Payment::create(['order_id'=>$order->id,'gateway'=>'zarinpal','amount'=>$order->total,'status'=>'pending']);$transactionId=null;try{$invoice=(new Invoice)->amount($order->total)->via('zarinpal');ShetabitPayment::callbackUrl(config('services.zarinpal.callback_url'))->purchase($invoice,function($driver,$id)use(&$transactionId){$transactionId=(string)$id;});}catch(\Throwable $e){$payment->update(['status'=>'failed','response'=>['message'=>$e->getMessage()]]);throw new RuntimeException('خطا در ایجاد درخواست پرداخت زرین‌پال: '.$e->getMessage(),0,$e);}$payment->update(['authority'=>$transactionId,'response'=>['driver'=>'shetabit/payment','sandbox'=>config('services.zarinpal.sandbox')]]);return $payment;}
 public function url(Payment $payment):string{return (config('services.zarinpal.sandbox')?'https://sandbox.zarinpal.com/pg/StartPay/':'https://www.zarinpal.com/pg/StartPay/').$payment->authority;}
 public function verify(Payment $payment):bool{if($payment->status==='paid')return true;try{$receipt=ShetabitPayment::amount($payment->amount)->transactionId($payment->authority)->verify();$payment->update(['status'=>'paid','response'=>['driver'=>'shetabit/payment','reference_id'=>(string)$receipt->getReferenceId()]]);return true;}catch(InvalidPaymentException|\Throwable $e){$payment->update(['status'=>'failed','response'=>['driver'=>'shetabit/payment','message'=>$e->getMessage()]]);return false;}}
 public function refund(Payment $payment):bool{throw new RuntimeException('استرداد زرین‌پال در نسخه فعلی شتابیت برای این درایور ارائه نشده است؛ reverse باید با API رسمی انجام شود.');}
}
