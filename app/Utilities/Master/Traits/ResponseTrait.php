<?php

namespace Apithy\Utilities\Master\Traits;

use NotificationChannels\AwsSns\SnsChannel;
use NotificationChannels\Twilio\TwilioChannel;

trait ResponseTrait
{
    /**
     * Retorna una respuesta de datos correctos
     *
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function successResponse($data = [])
    {
        return response()->json([
            'error' => '',
            'code' => 200,
            'hint' => 'success',
            'message' => "success",
            'data' => $data
        ]);
    }

    /**
     * retorna un error estandarizado
     *
     * @param string $module - Nombre del modulo
     * @param string $error - Tipo de error "create|update|delete|''"
     * @param string $hint - Mensaje para developer
     * @param int $code - Codigo de error (basado en codigos de servidor)
     * @param array $data - Datos extras para elerror
     * @return \Illuminate\Http\JsonResponse
     */
    public static function errorResponse($module = '', $error = 'create', $hint = '', $code = 409, $data = [])
    {
        return response()->json([
            'error' => self::errorType($error),
            'code' => $code,
            'hint' => $hint,
            'message' => "Can't {$error} {$module}",
            'data' => $data
        ], $code);
    }

    public static function exceptionResponse(\Exception $exception, $module = 'Unknown')
    {
        return response()->json([
            'error' => 'exception',
            'code' => $exception->getCode(),
            'hint' => 'exception',
            'message' => "Exception in module: {$module}",
            'data' => [
                'message' => $exception->getMessage(),
                'details' => $exception->getTraceAsString()
            ]
        ], 500);
    }

    private static function errorType($error)
    {
        switch ($error) {
            case 'create':
                return 'not_be_created';
            case 'update':
                return 'not_be_updated';
            case 'delete':
                return 'not_be_deleted';
            default:
                return 'Unknown';
        }
    }
}
