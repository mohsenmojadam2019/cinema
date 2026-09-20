<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Venue extends Model { protected $guarded=[]; protected $casts=['seat_map'=>'array']; public function organization(){return $this->belongsTo(Organization::class);} public function seats(){return $this->hasMany(Seat::class);} public function shows(){return $this->hasMany(Show::class);} }
