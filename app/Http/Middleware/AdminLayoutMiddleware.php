<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class AdminLayoutMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if ($request->is('admin*') && str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            $html = $response->getContent();
            $html = preg_replace('/<footer class="site-footer">.*?<\/footer>/s', '', $html);
            if (! str_contains($html, 'admin-sidebar') && ! str_contains($html, 'class="sidebar"')) {
                $html = preg_replace('/<body([^>]*)>/', '<body$1>'.view('admin.partials.sidebar')->render(), $html, 1);
                $html = str_replace('<body class="admin-body"', '<body class="admin-body admin-crud"', $html);
                $response->setContent($html);
            }
        }
        return $response;
    }
}
