<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
class GeneratePosterAssets extends Command
{
    protected $signature='cinema:posters {--force : overwrite existing generated posters}';
    protected $description='Generate deterministic unique poster artwork for seeded events';
    public function handle(): int
    {
        if (!function_exists('imagecreatetruecolor')) { $this->error('GD extension is required.'); return self::FAILURE; }
        $items=[['yek-rooz-mamooli',[35,55,90]],['setare-shomali',[20,80,95]],['nime-shab-tehran',[55,30,85]],['ghessehaye-madarbozorg',[120,65,35]],['masire-bargasht',[25,100,65]],['sedaye-baran',[30,70,120]],['roozhaye-talayi',[150,85,25]],['cinemaye-farda',[45,45,55]]];$dir=public_path('images/posters');if(!is_dir($dir))mkdir($dir,0777,true);$made=0;
        foreach($items as [$slug,$rgb]){$path="$dir/$slug.png";if(is_file($path)&&!$this->option('force'))continue;$im=imagecreatetruecolor(900,1350);for($y=0;$y<1350;$y++){$t=$y/1350;$c=imagecolorallocate($im,(int)($rgb[0]*(1-$t)+8*$t),(int)($rgb[1]*(1-$t)+12*$t),(int)($rgb[2]*(1-$t)+30*$t));imageline($im,0,$y,900,$y,$c);}$gold=imagecolorallocate($im,245,190,80);$light=imagecolorallocate($im,235,240,245);imagefilledellipse($im,700,300,1100,700,$gold);imagefilledellipse($im,80,760,720,1400,$light);imagefilledpolygon($im,[0,1050,900,650,900,1350,0,1350],4,imagecolorallocate($im,10,15,30));imagestring($im,5,70,90,'CINEMA PLUS',$light);imagestring($im,5,70,1180,strtoupper($slug),$gold);imagepng($im,$path,6);imagedestroy($im);$made++;}$this->info("Generated {$made} poster assets.");return self::SUCCESS;
    }
}
