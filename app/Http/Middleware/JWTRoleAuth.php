<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JWTRoleAuth extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role = null)
    {
        try{
            $token_role = $this->auth->parseToken()->getClaim('role');
        }catch (JWTException $e){
            return response()->json(['error' => 'Unauthorized'], 401);
            }
        if($token_role != $role){
            return response()->json(['error' => 'Unauthorized User'], 404);
        }
        return $next($request);
        // if (! $token = $this->auth->setRequest($request)->getToken()) {
        //     throw new UnauthorizedHttpException('jwt-auth', 'Token not provided');
        // }
        // try {
        //     $user = $this->auth->authenticate($token);
        // } catch (JWTException $e) {
        //     throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
        // }
        // if (! $user) {
        //     throw new UnauthorizedHttpException('jwt-auth', 'User not found');
        // }
        // if (! $this->auth->validateRole($roles)) {
        //     throw new UnauthorizedHttpException('jwt-auth', 'User does not have the right roles');
        // }
        // return $next($request);
    }
}
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // public function handle(Request $request, Closure $next, $role = null)
    // {
    //     try{
    //         $token_role = $this->auth->parseToken()->getPayload()->get('role');
    //     }catch (JWTException $e){
    //         return response()->json(['error' => 'token_invalid'], 401);
    //         }
    //     if($token_role != $role){
    //         return response()->json(['error' => 'token_not_found'], 404);
    //     }
    //     return $next($request);
    // }
}
