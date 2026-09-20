<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\{Cache,DB};
use Throwable;

class HealthController extends Controller
{
    public function __invoke()
    {
        $checks=['database'=>'ok','cache'=>'ok','storage'=>is_writable(storage_path())?'ok':'failed'];
        try { DB::select('select 1'); } catch (Throwable $e) { $checks['database']='failed'; }
        try { Cache::put('cinema-health',now()->timestamp,10); Cache::get('cinema-health'); } catch (Throwable $e) { $checks['cache']='failed'; }
        $healthy=!in_array('failed',$checks,true);
        return response()->json(['status'=>$healthy?'ok':'degraded','checks'=>$checks,'timestamp'=>now()->toIso8601String()],$healthy?200:503);
    }
}
