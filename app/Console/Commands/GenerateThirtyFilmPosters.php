<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateThirtyFilmPosters extends Command
{
    protected $signature = 'cinema:thirty-posters {--force}';
    protected $description = 'Generate 30 distinct cinematic poster artworks for seeded films';

    public function handle(): int
    {
        if (! function_exists('imagecreatetruecolor')) {
            $this->error('GD extension is required.');
            return self::FAILURE;
        }
        $names = ['koochehaye-khamosh','saye-roshan','tehran-saat-panj','khandeye-kouchak','jazire-abi','namehaye-binâm','roozhaye-narenji','akharin-qab','bad-shomal','naghshi-barân','rooye-khat-e-roya','safar-bi-payân','yek-fenjan-zendegi','fardaye-roshan','sedaye-akhar-salon','shabhaye-shiraz','raz-e-bagh','ghahreman-kouchak','khaterat-bilet','fasl-e-taze','parvaz-ta-farda','masir-e-barân','yek-shab-muzeh','khane-roy-teppeh','nabz-e-shahr','ghesse-akhir-hafté','avaz-e-dordast','cheraghaye-shahr','paeiz-e-badi','sinemaye-iran'];
        $dir = public_path('images/posters');
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        foreach ($names as $i => $name) {
            $path = $dir.'/'.$name.'.png';
            if (is_file($path) && !$this->option('force')) continue;
            $w=900; $h=1350; $im=imagecreatetruecolor($w,$h);
            $palettes=[[12,27,58],[66,22,45],[18,65,74],[86,49,20],[34,28,75],[16,61,45]];
            [$r,$g,$b]=$palettes[$i%count($palettes)];
            for($y=0;$y<$h;$y++){ $t=$y/$h; $c=imagecolorallocate($im,(int)($r*(1-$t)+4*$t),(int)($g*(1-$t)+8*$t),(int)($b*(1-$t)+20*$t)); imagefilledrectangle($im,0,$y,$w,$y,$c); }
            $gold=imagecolorallocate($im,242,190,76); $light=imagecolorallocate($im,238,241,244); $accent=imagecolorallocate($im,210,42+($i*7)%70,78+($i*11)%100);
            imagefilledellipse($im, $w-110-($i%3)*60, 220+($i%5)*60, $w+260, 640+($i%4)*70, $accent);
            imagefilledellipse($im, 80+($i%4)*70, 800, 680, 1500, $light);
            imagefilledpolygon($im,[0,1050,900,650+($i%4)*55,900,1350,0,1350],4,imagecolorallocate($im,5,10,24));
            for($n=0;$n<5;$n++){ $x=90+$n*170; imageline($im,$x,250,$x+($i%2?120:-90),950,$gold); }
            imagestring($im,5,58,70,'CINEMA PLUS', $light);
            imagestring($im,4,58,1190,strtoupper(str_replace('-',' ',$name)),$gold);
            imagepng($im,$path,6); imagedestroy($im);
        }
        $this->info('30 distinct poster assets generated.');
        return self::SUCCESS;
    }
}
