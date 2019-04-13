<?php

namespace App\Http\Middleware;

use Closure;

class Jwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Authorization',false);
        if ($token) {
            $jwtAuth = new \JwtAuth();
            $auth = $jwtAuth->checkToken($token);
            if($auth) {
                $request->headers->set('DataUser',$auth);
                return $next($request);
            } else {
                return response()->json(
                    array('errors' => 'Token invalido'),
                    403
                );
            }
        } else {
            return response()->json(
                array('errors' => 'No existe el token'),
                401
            );
        }
    }
}
