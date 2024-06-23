<?php

declare(strict_types=1);

namespace App\Dto;

readonly class DefaultResponseDto implements DtoInterface
{
    /**
     * @param int $status
     * @param string $message
     * @param array<mixed> $data
     */
    public function __construct(
        public int $status,
        public string $message,
        public array $data = [],
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ];
    }
}
