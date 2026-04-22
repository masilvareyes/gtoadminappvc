<?php

declare(strict_types=1);

namespace App\Core\Routing;

use App\Core\Http\Request;
use App\Core\Http\Response;

final class Route
{
    /** @var callable(Request): Response */
    private $handler;

    /**
     * @param callable(Request): Response $handler
     */
    public function __construct(
        public readonly string $method,
        public readonly string $pattern,
        callable $handler
    ) {
        $this->handler = $handler;
    }

    public function run(Request $request): Response
    {
        return ($this->handler)($request);
    }
}
