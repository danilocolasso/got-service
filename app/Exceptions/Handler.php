<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): JsonResponse
    {
        return match (true) {
            $e instanceof InvalidApiKeyException, $e instanceof ApiKeyNotInformedException => response()
                ->json([
                    'message' => $e->getMessage(),
                    'error' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED]
                ], Response::HTTP_UNAUTHORIZED),
            $e instanceof ValidationException => response()
                ->json([
                    'message' => trans('errors.invalid-params'),
                    'error' => $e->validator->errors()
                ], Response::HTTP_NOT_ACCEPTABLE),
            $e instanceof QueryException => response()->json([
                'message' => trans('errors.database-error'),
                'error' => Response::$statusTexts[Response::HTTP_BAD_GATEWAY]
            ], Response::HTTP_BAD_GATEWAY),
            $e instanceof NotFoundHttpException => response()->json([
                'message' => trans('errors.not-found'),
                'error' => Response::$statusTexts[Response::HTTP_NOT_FOUND]
            ], Response::HTTP_NOT_FOUND),
            $e instanceof MethodNotAllowedHttpException => response()->json([
                'message' => trans('errors.method-not-allowed'),
                'error' => Response::$statusTexts[Response::HTTP_METHOD_NOT_ALLOWED]
            ], Response::HTTP_METHOD_NOT_ALLOWED),
            default => response()->json([
                'message' => trans('errors.generic-error'),
                'error' => Response::$statusTexts[Response::HTTP_INTERNAL_SERVER_ERROR],
                'details' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR),
        };
    }
}
