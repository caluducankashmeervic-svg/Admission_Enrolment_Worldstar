<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        if (! $user || ! $user->is_active || ! in_array($user->role, $roles, true)) {
            if (! $user) {
                return redirect()->route('login');
            }
            return match ($user->role) {
                'admin'     => redirect()->route('admin.dashboard'),
                'registrar' => redirect()->route('registrar.applicants.index'),
                default     => redirect()->route('applicant.admission.create'),
            };
        }
        return $next($request);
    }
}
