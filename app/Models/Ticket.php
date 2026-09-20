<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Ticket extends Model { protected $guarded=[]; protected $casts=['checked_in_at'=>'datetime']; public function order(){return $this->belongsTo(Order::class);} public function seat(){return $this->belongsTo(Seat::class);} }
