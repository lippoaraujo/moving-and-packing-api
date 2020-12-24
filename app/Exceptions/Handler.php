<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponser;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        TokenExpiredException::class,
        TokenInvalidException::class
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        // foreach ($this->dontReport as $type)
        // {
        //     if ($exception instanceof $type)
        //         parent::report($exception);
        // }

        // $bugsnag = app('bugsnag');

        // if ($bugsnag) {
        //     $bugsnag->notifyException($exception, null, "error");
        // }
        // parent::report($exception);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $exc, $request) {

            // $message = $exc->getMessage();
            // $status  = method_exists($exc, 'getStatusCode') ? $exc->getStatusCode() : 400;

            // if($exc instanceof ValidationException) {
            //     $message = $exc->validator->errors()->getMessages();
            // }

            // if($exc instanceof NotFoundHttpException) {
            //     $message = 'route not found!';
            //     $previous = method_exists($exc, 'getPrevious') ? $exc->getPrevious() : null;
            //     if(!empty($previous)) {
            //         $message = $previous->getMessage();
            //     }

            //     return $this->errorResponse($message, $status);
            // }


        });
    }
}
