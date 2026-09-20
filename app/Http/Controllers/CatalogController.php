<?php
namespace App\Http\Controllers;
use App\Models\{Event,Category}; use Illuminate\Http\Request;
class CatalogController extends Controller { public function search(Request $r){$q=Event::with('category')->where('status','published');if($r->filled('q'))$q->where(fn($x)=>$x->where('title','like','%'.$r->q.'%')->orWhere('summary','like','%'.$r->q.'%'));if($r->filled('type'))$q->where('type',$r->type);if($r->filled('category'))$q->where('category_id',$r->category);return view('catalog.search',['events'=>$q->latest()->paginate(18)->withQueryString(),'categories'=>Category::where('type','event')->get()]);} }
