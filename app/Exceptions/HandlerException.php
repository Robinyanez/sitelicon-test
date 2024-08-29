<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Fruitcake\Cors\CorsService;
use Illuminate\Support\Facades\Log;
use App\Interfaces\HttpCodeInterface;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class HandlerException extends Exception implements HttpCodeInterface
{
    use ApiResponse;

    public function render(Request $request, Throwable $e)
    {
        $response = $this->handleException($e);
        app(CorsService::class)->addActualRequestHeaders($response, $request);
        return $response;
    }

    public function handleException(Throwable $e)
    {
        if ($e instanceof ValidationException) {

            $errors = $e->validator->errors()->getMessages();

            return $this->errorResponse($errors, self::UNPROCESSABLE_ENTITY);
        }

        if ($e instanceof ModelNotFoundException) {

            $model = strtolower(class_basename($e->getModel()));
            return $this->errorResponse("No existe ninguna instancia de {$model} con el id especificado", self::NOT_FOUND);
        }

        if ($e instanceof AuthenticationException) {

            return $this->errorResponse('No autenticado.', self::UNAUTHORIZED);
        }

        if ($e instanceof AuthorizationException) {

            return $this->errorResponse('No posee permisos para ejecutar esta acción', self::FORBIDDEN);
        }

        if ($e instanceof NotFoundHttpException) {

            return $this->errorResponse('No se encontró la URL especificada', self::NOT_FOUND);
        }

        if ($e instanceof MethodNotAllowedHttpException) {

            return $this->errorResponse('El método especificado en la petición no es válido', self::METHOD_NOT_ALLOWED);
        }

        if ($e instanceof HttpException) {

            return $this->errorResponse($e->getMessage(), $e->getCode());
        }

        if ($e instanceof QueryException) {

            $code = $e->errorInfo[1];

            if ($code == 1451) {

                return $this->errorResponse('No se puede eliminar de forma permamente el recurso porque está relacionado con algún otro.', self::CONFLICT);
            }
        }

        Log::error($e->getCode());
        Log::error($e->getMessage());

        return $this->errorResponse('Falla inesperada. Intente luego', self::INTERNAL_SERVER_ERROR);
    }

}
