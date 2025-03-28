<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOrManagerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar se o utilizador está autenticado e se o papel é admin ou manager
        if (Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'manager')) {
            return $next($request);
        }

        // Se não for admin ou manager, redireciona para outra página (por exemplo, a página inicial)
        return redirect()->route('dashboard')->with('error','Não está autorizado a aceder a página.');
    }
}
