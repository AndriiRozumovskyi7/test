<?php

namespace App\Http\Middleware;

use App\Models\Token;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->has('token')) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Token not provided');
        }

        $token = Token::query()->where('token', $request->get('token'))->first();

        if (!$token || $token->expires_at->lessThan(Carbon::now())) {
            $token?->delete();

            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'Token expired');
        }

        $token->delete();

        return $next($request);
    }
}
