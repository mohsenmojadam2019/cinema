<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up():void{Schema::table('events',function(Blueprint $t){$t->string('director')->nullable();$t->text('cast')->nullable();$t->string('country')->nullable();$t->string('language')->nullable();$t->string('trailer_url')->nullable();});} public function down():void{Schema::table('events',fn(Blueprint $t)=>$t->dropColumn(['director','cast','country','language','trailer_url']));} };
