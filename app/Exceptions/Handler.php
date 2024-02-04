<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Psy\Exception\FatalErrorException;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use http\Exception\RuntimeException;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $ex = 'Entry for '.str_replace("App\Models\\", '', $exception->getModel()).' not found';
            \Log::error('Model issues', ['traceDetails' => $exception]);
            $error = ['error' => $ex];
            return response()->error($error, 'E_SYSTEM');
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $ex = $exception->getMessage();
            $error = ['error' => $ex];
            return response()->error($error, 'E_METHOD_NOT_ALLOWED');
        }

        if ($exception instanceof AuthorizationException) {
            $ex = $exception->getMessage();
            $error = ['error' => $ex];
            return response()->error($error, 'E_UNAUTHORIZED');
        }

        if ($exception instanceof \InvalidArgumentException) {
            $ex = $exception->getMessage();
            $error = ['error' => $ex];
            return response()->error($error, 'E_NOT_ACCEPTABLE');
        }

        if ($exception instanceof \HttpInvalidParamException) {
            $ex = $exception->getMessage();
            $error = ['error' => $ex];
            return response()->error($error, 'E_NOT_ACCEPTABLE');
        }

        if ($exception instanceof \ReflectionException or $exception instanceof NotFoundHttpException) {
            $ex = $exception->getMessage();
            $error = ['error' => 'Resource not found'];
            return response()->error($error, 'E_NOT_FOUND');
        }

        if ($exception instanceof \BadMethodCallException) {
            $ex = $exception->getMessage();
            $error = ['error' => $ex];
            return response()->error($error, 'E_SYSTEM');
        }

        if ($exception instanceof FatalError) {
            $ex = $exception->getMessage();
            $error = ['error' => $ex];
            return response()->error($error, 'E_SYSTEM');
        }

        if ($exception instanceof FatalErrorException) {
            $error = $exception->getMessage();
            return response()->error($error, 'E_SYSTEM');
        }

        if ($exception instanceof \ErrorException) {
            $ex = $exception->getMessage();
            $error = ['error' => $ex];
            return response()->error($error, 'E_SYSTEM');
        }

        if ($exception instanceof QueryException) {
            $ex = $exception->getMessage();
            $error = ['error' => $ex];
            return response()->error($error, 'E_SYSTEM');
        }

        if ($exception instanceof ThrottleRequestsException) {
            $ex = $exception->getMessage();
            $error = ['error' => $ex];
            return response()->error($error, 'E_THROTTLE');
        }

        if ($exception instanceof RuntimeException) {
            $ex = $exception->getMessage();
            $error = ['error' => $ex];
            return response()->error($error, 'E_SYSTEM');
        }

        if ($exception instanceof BindingResolutionException) {
            $ex = $exception->getMessage();
            $error = ['error' => $ex];
            return response()->error($error, 'E_SYSTEM');
        }

        if ($exception instanceof AuthenticationException) {
            $ex = $exception->getMessage();
            $error = ['error' => $ex];
            return response()->error($error, 'E_UNAUTHENTICATED');
        }

        if ($exception instanceof JWTException) {
            $ex = $exception->getMessage();
            $error = ['error' => $ex];
            return response()->error($error, 'E_UNAUTHENTICATED');
        }

        return parent::render($request, $exception);
    }
}
