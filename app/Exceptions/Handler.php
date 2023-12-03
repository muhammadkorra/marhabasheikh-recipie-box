<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function(Throwable $e, $request){
            // handle not found and return json
            if($e instanceof NotFoundHttpException){
                return response()->json(['message' => 'not found', 'status_code' => 404], 404);
            }

            // handle method not allowed and return json
            if($e instanceof MethodNotAllowedHttpException){
                return response()->json(['message' => 'method not allowed', 'status_code' => 405], 405);
            }
        });
    }
}
