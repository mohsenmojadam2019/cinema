<?php
namespace Database\Seeders;
use App\Models\{Organization,Category,Venue,Seat,Event,Show};
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class CinemaSeeder extends Seeder {
 public function run(): void {
  $org=Organization::create(['name'=>'سینماپلاس','slug'=>'cinemaplus','phone'=>'02191000000','email'=>'info@cinemaplus.test']);
  $cats=[]; foreach([['فیلم','film'],['تئاتر','theater'],['کودک و خانواده','family'],['کمدی','comedy'],['درام','drama']] as [$name,$slug]) $cats[$slug]=Category::create(['name'=>$name,'slug'=>$slug,'type'=>'event']);
  $venues=[]; foreach([['پردیس ملت','تهران','خیابان ولیعصر'],['سینما آزادی','تهران','میدان انقلاب'],['تالار وحدت','تهران','میدان وحدت']] as [$name,$city,$address]){$v=Venue::create(['organization_id'=>$org->id,'name'=>$name,'city'=>$city,'address'=>$address,'capacity'=>96]); for($row=0;$row<8;$row++)for($num=1;$num<=12;$num++)Seat::create(['venue_id'=>$v->id,'row_label'=>chr(1575+$row),'number'=>$num,'type'=>$row===0?'vip':'standard']);$venues[]=$v;}
  $events=[['در آغوش خاک','درامی درباره انتخاب‌های سخت و امید','drama'],['شهر بی‌خواب','روایتی اجتماعی از یک شب پرماجرا','drama'],['ماه کوچک','انیمیشن خانوادگی برای همه سنین','family'],['پرده آخر','نمایشی صحنه‌ای با اجرای زنده','theater'],['عشق اتفاقی','کمدی عاشقانه','comedy']];
  foreach($events as $i=>[$title,$summary,$cat]){$e=Event::create(['organization_id'=>$org->id,'category_id'=>$cats[$cat]->id,'title'=>$title,'slug'=>Str::slug($title).'-'.$i,'summary'=>$summary,'type'=>$cat==='theater'?'theater':'cinema','duration'=>105,'is_featured'=>$i<3]); for($day=0;$day<7;$day++)Show::create(['event_id'=>$e->id,'venue_id'=>$venues[$i%3]->id,'starts_at'=>now()->addDays($day)->setTime(18+$i%3,30),'ends_at'=>now()->addDays($day)->setTime(20+$i%3,20),'price'=>$cat==='theater'?350000:220000,'vip_price'=>420000]);}
  foreach(['مدیر سیستم','مدیر فروش','اپراتور گیشه','مشتری'] as $name) \Spatie\Permission\Models\Role::firstOrCreate(['name'=>$name,'guard_name'=>'web']);
 }
}
