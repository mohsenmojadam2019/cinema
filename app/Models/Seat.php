<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Seat extends Model { public $timestamps=false; protected $guarded=[]; public function venue(){return $this->belongsTo(Venue::class);} }
