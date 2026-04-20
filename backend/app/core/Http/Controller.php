<?php

declare(strict_types=1);

namespace App\Core\Http;

/**
 * Clase base mínima para controladores futuros; BL-001 solo la deja lista para extender.
 */
abstract class Controller
{
    protected function jsonOk(mixed $data, int $status = 200): Response
    {
        return Response::jsonSuccess($data, $status);
    }

    protected function jsonErr(
        string $code,
        string $message,
        ?array $details = null,
        int $status = 400
    ): Response {
        return Response::jsonError($code, $message, $details, $status);
    }
}
