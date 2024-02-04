<?php

namespace App\Http\Middleware;

use App\Services\DriverLoginHistoryService;
use Closure;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenValidation
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
        $refreshed = null;
        $error = ['msg' => trans('custom.authError')];
        $logError = [
            'token' => null,
            'error' => 'Cannot authenticate by parsing'
        ];
        try {
            $token = JWTAuth::getToken();
            $payload = JWTAuth::getPayload($token)->toArray();
            $logError['token'] = $token;

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                \Log::channel('slack')->critical($logError);
                return response()->fail($error, 'E_UNAUTHORIZED');
            }

            $lastLogin = date('Y-m-d H:i:s', $payload['iat']);
            $activeSessions = DriverLoginHistoryService::getActiveSessions($user->driverDetailId, $lastLogin);
            if ((int)$activeSessions > 0) {
                \Cookie::forget('token');
                \Log::error('More than one session available. Logging out. Token forbidden');
                $logError['error'] = 'More than one session available. Logging out. Token forbidden';
                \Log::channel('slack')->critical($logError);
                auth()->logout();
                JWTAuth::invalidate(JWTAuth::parseToken());
                return response()->fail($error, 'E_UNAUTHORIZED');
            }
        } catch (TokenExpiredException $e) {
            // If the token is expired, then it will be refreshed and added to the headers
            try {
                $refreshed = JWTAuth::refresh(JWTAuth::getToken());
                $user = JWTAuth::setToken($refreshed)->toUser();
                $request->headers->set('Authorization', 'Bearer '.$refreshed);
            } catch (JWTException $e) {
                \Log::error('jwt.auth.error',['error' => $e->getMessage()]);
                $token = JWTAuth::getToken();
                $logError['token'] = $token;
                $logError['error'] = 'Token refresh error. Logout';
                \Log::channel('slack')->critical($logError);
                return response()->fail($error, 'E_UNAUTHORIZED');
            }
        } catch (JWTException $e) {
            \Log::error('jwt.auth.error',['error' => $e->getMessage()]);
            $logError['error'] = 'Token Authenticate error. Logout '.$e->getMessage();
            \Log::channel('slack')->critical($logError);
            return response()->fail($error, 'E_UNAUTHENTICATED');
        }

        // add customerId to request make it globally available over request
        $request->merge([
            'driverId' => $user->driverDetailId,
            'driverStatus' => $user->driverStatus,
            'driverMobileNumber' => $user->driverMobileNumber,
            'driverEmail' => $user->driverEmail,
        ]);

        $response = $next($request);

        if ($refreshed) {
            $response->headers->set('refresh_token', $refreshed);
        }

        return $response;
    }
}
