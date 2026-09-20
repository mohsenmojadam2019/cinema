<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Settlement extends Model { protected $guarded=[]; protected $casts=['period_from'=>'date','period_to'=>'date','paid_at'=>'datetime']; public function organization(){return $this->belongsTo(Organization::class);} }
