<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia; use Spatie\MediaLibrary\InteractsWithMedia;
class Event extends Model implements HasMedia { use InteractsWithMedia; protected $guarded=[]; protected $casts=['release_date'=>'date','is_featured'=>'boolean']; public function organization(){return $this->belongsTo(Organization::class);} public function category(){return $this->belongsTo(Category::class);} public function shows(){return $this->hasMany(Show::class);} public function registerMediaCollections():void{$this->addMediaCollection('poster')->single();$this->addMediaCollection('gallery');$this->addMediaCollection('trailer')->single();} public function registerMediaConversions(?\Spatie\MediaLibrary\MediaCollections\Models\Media $media=null):void{$this->addMediaConversion('thumb')->width(480)->height(640)->performOnCollections('poster','gallery')->queued();} }
