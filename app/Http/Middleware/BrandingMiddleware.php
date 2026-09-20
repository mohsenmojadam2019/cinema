<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class BrandingMiddleware
{
 public function handle(Request $request, Closure $next): Response
 {
  $response=$next($request); $content=$response->getContent();
  if($response->headers->get('Content-Type') && str_contains($response->headers->get('Content-Type'),'text/html')){
   $content=preg_replace('/<div class="brand">سینما<span>پلاس<\/span><\/div>/', '<a class="brand" href="/"><img src="/images/branding/cinemaplus-logo.png" alt="سینماپلاس" style="height:52px;width:52px;object-fit:contain;vertical-align:middle"><span>سینماپلاس</span></a>', $content, 1);
   $footer=view('components.site-footer')->render(); $content=str_replace('</body>',$footer.'</body>',$content);
   $response->setContent($content);
  }
  return $response;
 }
}
