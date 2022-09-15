<?php



namespace App\Http\Middleware;



use Closure;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

use Tymon\JWTAuth\Exceptions\JWTException;

use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

use Tymon\JWTAuth\Facades\JWTAuth;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;

use Tymon\JWTAuth\Exceptions\TokenInvalidException;

use App\Traits\ApiResponser;

class JWTRoleAuth extends BaseMiddleware

{

    use ApiResponser;

    /**

     * Handle an incoming request.

     *

     * @param  \Illuminate\Http\Request  $request

     * @param  \Closure  $next

     * @return mixed

     */



    public function handle($request, Closure $next, ...$roles)

    {

        try {

            //Access token from the request

            $token = JWTAuth::parseToken();


            //Try authenticating user

            $user = $token->authenticate();



            if ($user && in_array($user->role, $roles)) {


                return $next($request);

            }

            return $this->error('You are not authorized to access this resource as '. $user->role .' but required role is '.$roles[0], 401);

        //}
        } catch (JWTException $e){

             return $this->error($e->getMessage(), 401);

        }

        //If user was authenticated successfully and user is in one of the acceptable roles, send to next request.


    }



}





     ////////////////////////////////////////////////////////////////////////////////////////////////

    // public function handle($request, Closure $next, ...$roles)

    // {

    //     try{

    //         //$role = '0';

    //         $token_role = $this->auth->parseToken()->getClaim('role');

    //          if ($token_role == $roles) {

    //             return $next($request);

    //         }

    //         return $this->error('You are not authorized to access this resource as '.$token_role .' but required role is ', 401);

    //     }catch (JWTException $e){

    //         return $this->error($e->getMessage(), 401);



    //       }

    // }

//}



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

    //     }catch (JWTException $e){$user && in_array($user->role, $roles)

    //         return response()->json(['error' => 'token_invalid'], 401);

    //         }

    //     if($token_role != $role){

    //         return response()->json(['error' => 'token_not_found'], 404);

    //     }

    //     return $next($request);

    // }



