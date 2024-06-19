<?php

use App\Services\BaseService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // health: '/up',
        // api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        using: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api/v1.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $baseService = new BaseService();

        $exceptions->render(function (NotFoundHttpException $e, Request $request) use ($baseService) {
            if ($request->is('api/*')) {
                $message      = 'The page you have requested does not exist. Please contact support if you think this is a problem';
                $responseData = $baseService->setPayload(false, $message);

                return $baseService->payload($responseData, Response::HTTP_NOT_FOUND);
            }
        });
        $exceptions->render(function (AuthenticationException  $e, Request $request) use ($baseService) {
            if ($request->is('api/*')) {
                $message      = 'Unauthenticated, Please login.';
                $responseData = $baseService->setPayload(false, $message);

                return $baseService->payload($responseData, Response::HTTP_UNAUTHORIZED);
            }
        });
        $exceptions->render(function (MethodNotAllowedHttpException  $e, Request $request) use ($baseService) {
            if ($request->is('api/*')) {
                $message      = $e->getMessage();
                $responseData = $baseService->setPayload(false, $message);

                return $baseService->payload($responseData, Response::HTTP_METHOD_NOT_ALLOWED);
            }
        });
        $exceptions->render(function (ValidationException $e, Request $request) use ($baseService) {
            if ($request->is('api/*')) {
                foreach ($e->errors() as $key => $value)
                    foreach ($value as $message) {
                        $data[] = $message;
                    }

                $message      = 'Problems encountered while signing up. Please contact support.';
                $responseData = $baseService->setPayload(false, $message, $data);

                return $baseService->payload($responseData, Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        });
        $exceptions->render(function (BadMethodCallException $e, Request $request) use ($baseService) {
            if ($request->is('api/*')) {
                $message      = "Something went wrong. Please contact support.'";
                $responseData = $baseService->setPayload(false, $message);

                return $baseService->payload($responseData, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    })->create();
