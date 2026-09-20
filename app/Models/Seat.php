<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Seat extends Model { public $timestamps=false; protected $guarded=[]; protected $casts=['is_active'=>'boolean']; public function venue(){return $this->belongsTo(Venue::class);} public function reservations(){return $this->hasMany(Reservation::class);} public function tickets(){return $this->hasMany(Ticket::class);} public function showSeats(){return $this->reservations();} }
