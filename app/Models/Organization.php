<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Organization extends Model { protected $guarded=[]; protected $casts=['settings'=>'array']; public function venues(){return $this->hasMany(Venue::class);} public function events(){return $this->hasMany(Event::class);} }
