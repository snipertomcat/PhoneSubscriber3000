<?php

namespace Apithy\Exceptions;

use Apithy\ErrorLog;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Stripe\Error\Card;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class Handler
 * @package Apithy\Exceptions
 */
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
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
     * @param \Exception $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        $user = request()->getUser();
        $user_id = null;

        if ($user) {
            $user_id = $user->id;
        }

        ErrorLog::create([
                'user_id' => $user_id,
                'trace' => json_encode($exception->getTrace()),
                'url' => request()->getRequestUri(),
                'message' => $exception->getMessage(),
                'status' => $exception->getCode(),
                'ip' => request()->getClientIp(),
                'line'=>$exception->getLine(),
                'class'=>get_class($exception),
                'file'=>$exception->getFile(),

            ]
        );

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        $is_ajax = $request->ajax();

        switch (true) {
            //return response('No auntenticado', '404');
            case $exception instanceof TokenMismatchException:
                if ($is_ajax) {
                    return response()->json([
                        'error' => 'unauthenticated',
                        'message' => 'Authentication Timeout'
                    ], 419);
                }
            //return response('modelo no encontrada', '404');
            case $exception instanceof ModelNotFoundException:
                if ($is_ajax) {
                    return response()->json([
                        'error' => 'resource_not_found',
                        'message' => 'Resource Not Found'
                    ], 404);
                }
            //return response('no autorizado', '404');
            case $exception instanceof AuthorizationException:
                if ($is_ajax) {
                    return response()->json([
                        'error' => 'authorization_error',
                        'message' => 'This action is unauthorized.'
                    ], 403);
                } else {
                    return redirect()->route('unauthorize');
                }
            //return response('pagina no encontrada', '404');
            case $exception instanceof NotFoundHttpException:
                if ($is_ajax) {
                    return response()->json([
                        'error' => 'page_not_found',
                        'message' => 'Page Not Found'
                    ], 404);
                } else {
                    return redirect()->route(404);
                }
            //return response('Conekta Params', '404');
            case $exception instanceof ParameterValidationError:
                if ($is_ajax) {
                    return response()->json([
                        'title' => 'Tarjeta Inválida',
                        'message' => str_replace('name', 'nombre', $exception->getMessage()),
                        'error' => 'Invalid Params'
                    ], 500);
                }
            //return response('StripeCardErros');
            case $exception instanceof Card:
                if ($is_ajax) {
                    return response()->json([
                        'title' => 'Error al procesaro lo datos',
                        'message' => trans("messages.stripe.{$exception->getStripeCode()}"),
                        'error' => 'Error al procesaro lo datos'
                    ], 500);
                }
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
