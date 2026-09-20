<?php
namespace Tests\Feature;
use App\Models\{Organization,Venue,Event,Show,Order,Payment};
use App\Services\ZarinpalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
class ZarinpalServiceTest extends TestCase
{
 use RefreshDatabase;
 private function order():Order{$org=Organization::create(['name'=>'Gateway Test','slug'=>'gateway-test']);$venue=Venue::create(['organization_id'=>$org->id,'name'=>'سالن','city'=>'تهران','capacity'=>1]);$event=Event::create(['organization_id'=>$org->id,'title'=>'رویداد','slug'=>'gateway-event','type'=>'cinema','status'=>'published']);$show=Show::create(['event_id'=>$event->id,'venue_id'=>$venue->id,'starts_at'=>now()->addDay(),'price'=>50000]);return Order::create(['show_id'=>$show->id,'code'=>'PAY-TEST','total'=>50000,'status'=>'pending']);}
 public function test_request_verify_and_refund_use_sandbox_contract():void{$order=$this->order();Http::fakeSequence()->push(['data'=>['code'=>100,'authority'=>'A-123']])->push(['data'=>['code'=>100,'ref_id'=>987]])->push(['data'=>['code'=>100]]);$z=app(ZarinpalService::class);$payment=$z->request($order);$this->assertSame('A-123',$payment->authority);$this->assertTrue($z->verify($payment));$this->assertTrue($z->refund($payment));$this->assertSame('refunded',$payment->fresh()->status);$this->assertNotNull($payment->fresh()->response);Http::assertSentCount(3);}
 public function test_failed_verify_marks_payment_failed():void{$order=$this->order();Http::fake(['*verify.json'=>Http::response(['data'=>['code'=>-21]],200)]);$payment=Payment::create(['order_id'=>$order->id,'gateway'=>'zarinpal','authority'=>'A-FAIL','amount'=>50000,'status'=>'pending']);$this->assertFalse(app(ZarinpalService::class)->verify($payment));$this->assertSame('failed',$payment->fresh()->status);}
 public function test_refund_is_idempotent_after_success():void{$order=$this->order();$payment=Payment::create(['order_id'=>$order->id,'gateway'=>'zarinpal','authority'=>'A-REPEAT','amount'=>50000,'status'=>'paid']);Http::fake(['*reverse.json'=>Http::response(['data'=>['code'=>100]],200)]);$z=app(ZarinpalService::class);$this->assertTrue($z->refund($payment));$this->assertTrue($z->refund($payment->fresh()));Http::assertSentCount(1);$this->assertSame('refunded',$payment->fresh()->status);}
}
