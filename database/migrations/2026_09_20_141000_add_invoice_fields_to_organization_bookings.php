<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up():void{Schema::table('organization_bookings',function(Blueprint $t){$t->string('invoice_number')->nullable()->unique();$t->timestamp('quoted_at')->nullable();$t->timestamp('confirmed_at')->nullable();});} public function down():void{Schema::table('organization_bookings',function(Blueprint $t){$t->dropColumn(['invoice_number','quoted_at','confirmed_at']);});} };
