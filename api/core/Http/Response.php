<?php

declare(strict_types=1);

namespace App\Core\Http;

final class Response
{
    public function __construct(
        private string $body,
        private int $statusCode = 200,
        private array $headers = []
    ) {
    }

    public static function jsonSuccess(mixed $data, int $statusCode = 200): self
    {
        $payload = [
            'ok' => true,
            'data' => $data,
        ];

        return self::jsonPayload($payload, $statusCode);
    }

    /**
     * @param array<string, mixed>|null $details Detalle opcional (p. ej. validación futura); evitar datos sensibles en producción.
     */
    public static function jsonError(
        string $code,
        string $message,
        ?array $details = null,
        int $statusCode = 400
    ): self {
        $error = [
            'code' => $code,
            'message' => $message,
        ];
        if ($details !== null && $details !== []) {
            $error['details'] = $details;
        }

        $payload = [
            'ok' => false,
            'error' => $error,
        ];

        return self::jsonPayload($payload, $statusCode);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private static function jsonPayload(array $payload, int $statusCode): self
    {
        $json = json_encode(
            $payload,
            JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );

        return new self(
            $json,
            $statusCode,
            ['Content-Type' => 'application/json; charset=utf-8']
        );
    }

    public function send(): void
    {
        http_response_code($this->statusCode);
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
        echo $this->body;
    }
}
