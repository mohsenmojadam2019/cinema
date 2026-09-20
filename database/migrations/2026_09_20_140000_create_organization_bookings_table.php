<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('organization_bookings', function(Blueprint $t){$t->id();$t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();$t->foreignId('organization_id')->nullable()->constrained()->nullOnDelete();$t->foreignId('show_id')->constrained()->cascadeOnDelete();$t->string('organization_name');$t->string('contact_name');$t->string('phone');$t->string('email')->nullable();$t->unsignedInteger('guest_count');$t->string('kind')->default('seminar');$t->text('notes')->nullable();$t->string('status')->default('pending');$t->unsignedInteger('quoted_total')->nullable();$t->timestamps();}); }
 public function down(): void { Schema::dropIfExists('organization_bookings'); }
};
