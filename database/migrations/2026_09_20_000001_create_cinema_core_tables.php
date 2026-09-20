<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::create('organizations', fn(Blueprint $t)=>[$t->id(),$t->string('name'),$t->string('slug')->unique(),$t->string('phone')->nullable(),$t->string('email')->nullable(),$t->json('settings')->nullable(),$t->timestamps()]);
  Schema::create('categories', fn(Blueprint $t)=>[$t->id(),$t->string('name'),$t->string('slug')->unique(),$t->string('type')->default('event'),$t->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete(),$t->boolean('is_active')->default(true),$t->timestamps()]);
  Schema::create('venues', fn(Blueprint $t)=>[$t->id(),$t->foreignId('organization_id')->constrained()->cascadeOnDelete(),$t->string('name'),$t->string('city')->default('تهران'),$t->string('address')->nullable(),$t->unsignedInteger('capacity')->default(0),$t->json('seat_map')->nullable(),$t->timestamps()]);
  Schema::create('events', fn(Blueprint $t)=>[$t->id(),$t->foreignId('organization_id')->constrained()->cascadeOnDelete(),$t->foreignId('category_id')->nullable()->constrained()->nullOnDelete(),$t->string('title'),$t->string('slug')->unique(),$t->text('summary')->nullable(),$t->longText('description')->nullable(),$t->string('type')->default('cinema'),$t->string('age_rating')->nullable(),$t->unsignedInteger('duration')->nullable(),$t->date('release_date')->nullable(),$t->boolean('is_featured')->default(false),$t->string('status')->default('published'),$t->timestamps()]);
  Schema::create('shows', fn(Blueprint $t)=>[$t->id(),$t->foreignId('event_id')->constrained()->cascadeOnDelete(),$t->foreignId('venue_id')->constrained()->cascadeOnDelete(),$t->dateTime('starts_at'),$t->dateTime('ends_at')->nullable(),$t->unsignedInteger('price')->default(0),$t->unsignedInteger('vip_price')->nullable(),$t->unsignedInteger('held_seconds')->default(480),$t->string('status')->default('on_sale'),$t->timestamps()]);
  Schema::create('seats', fn(Blueprint $t)=>[$t->id(),$t->foreignId('venue_id')->constrained()->cascadeOnDelete(),$t->string('row_label'),$t->unsignedInteger('number'),$t->string('type')->default('standard'),$t->boolean('is_active')->default(true),$t->unique(['venue_id','row_label','number'])]);
  Schema::create('orders', fn(Blueprint $t)=>[$t->id(),$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete(),$t->foreignId('show_id')->constrained()->cascadeOnDelete(),$t->string('code')->unique(),$t->unsignedInteger('total')->default(0),$t->string('status')->default('pending'),$t->string('payment_ref')->nullable(),$t->timestamp('paid_at')->nullable(),$t->timestamp('expires_at')->nullable(),$t->timestamps()]);
  Schema::create('tickets', fn(Blueprint $t)=>[$t->id(),$t->foreignId('order_id')->constrained()->cascadeOnDelete(),$t->foreignId('seat_id')->constrained()->cascadeOnDelete(),$t->string('code')->unique(),$t->string('status')->default('valid'),$t->timestamp('checked_in_at')->nullable(),$t->timestamps(),$t->unique(['order_id','seat_id'])]);
 }
 public function down(): void { foreach(['tickets','orders','seats','shows','events','venues','categories','organizations'] as $table) Schema::dropIfExists($table); }
};
