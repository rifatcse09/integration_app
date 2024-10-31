<?php

namespace App\Exceptions;

use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\App;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use \Illuminate\Http\Exceptions\PostTooLargeException;
use PDOException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use MailchimpMarketing;


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
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {

        if ($request->expectsJson() || $request->is('back/api/*')) {
            // Handle API-specific errors
            return $this->apiException($exception);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [], 404);
        }

        if ($exception instanceof ThrottleRequestsException) {
            return  $this->errorResponse('Too many requests. Please try again later.', 429);
        }


        return parent::render($request, $exception);
    }

    public function apiException($exception)
    {
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse($exception->getMessage() ?: 'You are not authorized to access this resource', 401);
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->errorResponse($exception->getMessage(), 404);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('The specified URL can\'t be found', 404);
        }

        if ($exception instanceof ValidationException) {
            return $this->errorResponse($exception->errors(), 422);
        }

        if ($exception instanceof PostTooLargeException) {
            return $this->errorResponse('File too large', $exception->getStatusCode());
        }

        if ($exception instanceof PDOException) {
            return $this->errorResponse('Database error', 500);
        }

        if ($exception instanceof MailchimpMarketing\ApiException) {
            return  $this->errorResponse($exception->getMessage(), 429);
        }

        return $this->errorResponse(App::environment('local') ? $exception->getMessage() : 'Something went wrong in the API request', 500);
    }

    protected function errorResponse($message, $statusCode)
    {
        return api()->fails($message, $statusCode);
    }
}
