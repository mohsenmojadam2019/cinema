<?php
namespace App\Http\Controllers;
use App\Models\{Event,Category,Venue};
use Illuminate\View\View;
class HomeController extends Controller { public function __invoke():View{return view('home',['featured'=>Event::with('category')->where('status','published')->where('is_featured',true)->latest()->get(),'events'=>Event::with('category')->where('status','published')->latest()->get(),'venues'=>Venue::latest()->get(),'categories'=>Category::whereNull('parent_id')->get()]);} }
