<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model { protected $guarded=[]; protected $casts=['paid_at'=>'datetime','expires_at'=>'datetime']; public function user(){return $this->belongsTo(User::class);} public function show(){return $this->belongsTo(Show::class);} public function tickets(){return $this->hasMany(Ticket::class);} }
