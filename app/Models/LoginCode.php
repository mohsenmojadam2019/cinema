<?php
namespace App\Models; use Illuminate\Database\Eloquent\Model;
class LoginCode extends Model {protected $guarded=[];protected $casts=['expires_at'=>'datetime','used_at'=>'datetime'];}
