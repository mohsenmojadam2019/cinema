<?php
namespace Tests\Feature;
use App\Models\{Organization,Venue,Seat,Event,Show,User,Order,Ticket,Reservation};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendSmsJob;
use Tests\TestCase;
class SecurityAndBookingTest extends TestCase
{
 use RefreshDatabase;
 private function show():Show{$org=Organization::create(['name'=>'Test','slug'=>'test']);$venue=Venue::create(['organization_id'=>$org->id,'name'=>'سالن تست','city'=>'تهران','capacity'=>1]);$seat=Seat::create(['venue_id'=>$venue->id,'row_label'=>'الف','number'=>1,'type'=>'standard']);$event=Event::create(['organization_id'=>$org->id,'title'=>'اثر تست','slug'=>'test-event','type'=>'cinema','status'=>'published']);return Show::create(['event_id'=>$event->id,'venue_id'=>$venue->id,'starts_at'=>now()->addDay(),'price'=>1000,'status'=>'on_sale']);}
 public function test_account_api_requires_sanctum_token():void{$this->getJson('/api/account/orders')->assertUnauthorized();}
 public function test_qr_requires_authenticated_owner_or_operator():void{$show=$this->show();$user=User::factory()->create();$order=Order::create(['user_id'=>$user->id,'show_id'=>$show->id,'code'=>'QR-TEST','total'=>1000,'status'=>'paid']);$ticket=Ticket::create(['order_id'=>$order->id,'seat_id'=>$show->venue->seats->first()->id,'code'=>'T-QR-TEST','status'=>'valid']);$this->get(route('tickets.qr',$ticket))->assertRedirect(route('login'));}
 public function test_second_reservation_is_rejected():void{$show=$this->show();$user=User::factory()->create();$this->actingAs($user)->post(route('booking.checkout',$show),['seats'=>[$show->venue->seats->first()->id]])->assertRedirect();$this->actingAs($user)->post(route('booking.checkout',$show),['seats'=>[$show->venue->seats->first()->id]])->assertStatus(409);$this->assertDatabaseCount('reservations',1);}
 public function test_customer_otp_dispatches_notification_job():void{Queue::fake();$this->post(route('customer.login.send'),['phone'=>'09120000000'])->assertSessionHas('success');Queue::assertPushed(SendSmsJob::class,fn($job)=>$job->phone==='09120000000');$this->assertDatabaseHas('login_codes',['phone'=>'09120000000']);}
}
