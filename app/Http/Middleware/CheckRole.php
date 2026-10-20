<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Проверяем, залогинен ли пользователь вообще
        if (! $request->user()) {
            abort(403, 'Доступ запрещен. Необходима авторизация.');
        }

        // 2. Проверяем, совпадает ли роль пользователя с требуемой
        if ($request->user()->role !== $role) {
            abort(403, 'У вас нет прав для доступа к этой странице.');
        }

        // 3. Если всё в порядке — пропускаем запрос дальше
        return $next($request);
    }
}
