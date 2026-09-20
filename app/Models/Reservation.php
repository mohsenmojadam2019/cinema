<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model;
class Reservation extends Model {protected $guarded=[]; protected $casts=['expires_at'=>'datetime']; public function show(){return $this->belongsTo(Show::class);} public function seat(){return $this->belongsTo(Seat::class);} public function scopeActive($q){return $q->where('status','held')->where('expires_at','>',now());}}
