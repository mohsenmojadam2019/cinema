<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Show extends Model { protected $guarded=[]; protected $casts=['starts_at'=>'datetime','ends_at'=>'datetime']; public function event(){return $this->belongsTo(Event::class);} public function venue(){return $this->belongsTo(Venue::class);} public function orders(){return $this->hasMany(Order::class);} }
