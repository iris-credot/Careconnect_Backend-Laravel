<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Exceptions\UnauthorizedException;

class JwtMiddleware
{
    /**
     * Handle an incoming request with optional role restriction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            // Attempt to parse and validate the token
            $token = JWTAuth::parseToken()->getToken();

            if (!$token) {
                throw new UnauthorizedException('Authentication required. Please log in.');
            }

            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                throw new UnauthorizedException('User not found.');
            }

            // Check roles, if any are defined
            if (!empty($roles) && !in_array($user->role, $roles)) {
                throw new UnauthorizedException('You are not authorized to access this route.');
            }

            // Inject authenticated user data into the request
            $request->merge([
                'userId'   => $user->id,
                'role'     => $user->role,
                'username' => $user->username ?? $user->name ?? null,
            ]);

        } catch (TokenInvalidException $e) {
            throw new UnauthorizedException('Invalid token.');
        } catch (TokenExpiredException $e) {
            throw new UnauthorizedException('Token expired.');
        } catch (JWTException $e) {
            throw new UnauthorizedException('Failed to authenticate token. Please login again.');
        } catch (\Exception $e) {
            throw new UnauthorizedException('Authentication required.');
        }

        return $next($request);
    }
}
