<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use App\Exceptions\UnauthorizedError;  // Make sure you have this exception class

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     * Allow optional role(s) as arguments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            $token = JWTAuth::parseToken()->getToken();

            if (!$token) {
                throw new UnauthorizedError('Authentication required. Please log in.');
            }

            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                throw new UnauthorizedError('User not found.');
            }

            // If roles are specified, check user role
            if (!empty($roles) && !in_array($user->role, $roles)) {
                throw new UnauthorizedError('You are not authorized to access this route.');
            }

            // Attach user info to request (using merge so it’s in input data)
            $request->merge([
                'userId' => $user->id,
                'role' => $user->role,
                'username' => $user->username ?? $user->name ?? null,
            ]);

        } catch (TokenInvalidException $e) {
            throw new UnauthorizedError('Invalid token.');
        } catch (TokenExpiredException $e) {
            throw new UnauthorizedError('Token expired.');
        } catch (JWTException $e) {
            throw new UnauthorizedError('Failed to authenticate token. Please login again.');
        } catch (\Exception $e) {
            throw new UnauthorizedError('Authentication required.');
        }

        return $next($request);
    }
}
