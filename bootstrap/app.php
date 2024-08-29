<?php

use Illuminate\Http\Request;
use App\Exceptions\HandlerException;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        //api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using: function () {
            Route::middleware('api')
                ->prefix('api/auth')
                ->group(base_path('routes/auth.php'));

            Route::middleware(['api','auth:sanctum'])
                ->prefix('api/')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->report(function (Throwable $e) {

            $exceptionArray = [
                'message'   => $e->getMessage(),
                'code'      => $e->getCode(),
                'file'      => $e->getFile(),
                'line'      => $e->getLine(),
                'trace'     => json_encode($e->getTrace()),
            ];

            $content_mail = [
                'url'       => \request()->url(),
                'request'   => \request()->all(),
                'response'  => null,
                'ip'        => \request()->ip(),
                'code'      => null,
                'error'     => $exceptionArray,
            ];

            if (\request()->is('api/*')) {

                saveAndSendError($content_mail);
            }
        });

        $exceptions->render(function (Throwable $e, Request $request) {

            if ($request->is('api/*')) {

                $handlerException = new HandlerException;
                $response = $handlerException->render($request, $e);
                return $response;
            }
        });

    })->create();
