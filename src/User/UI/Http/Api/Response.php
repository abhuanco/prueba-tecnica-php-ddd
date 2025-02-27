<?php
declare(strict_types=1);

namespace App\User\UI\Http\Api {

    class Response
    {
        public int $statusCode;
        public string $message;
        public mixed $data;
        private array $headers = [];

        public function __construct(int $statusCode = 200, string $message = "", mixed $data = null, array $headers = [])
        {
            $this->statusCode = $statusCode;
            $this->message = $message;
            $this->data = $data;
            $this->headers = $headers;
        }

        public function sendJsonResponse(): void
        {
            http_response_code($this->statusCode);
            header('Content-Type: application/json; charset=UTF-8');

            foreach ($this->headers as $name => $value) {
                header("$name: $value");
            }

            $responseData = [
                'statusCode' => $this->statusCode,
                'message' => $this->message,
                'data' => $this->data,
            ];

            try {
                $json = json_encode($responseData, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                $responseData['data'] = null;
                $json = json_encode($responseData);
            }

            echo $json;
        }

        public function setStatusCode(int $statusCode): self
        {
            $this->statusCode = $statusCode;
            return $this;
        }

        public function setMessage(string $message): self
        {
            $this->message = $message;
            return $this;
        }

        public function setData(mixed $data): self
        {
            $this->data = $data;
            return $this;
        }

        public function addHeader(string $name, string $value): self
        {
            $this->headers[$name] = $value;
            return $this;
        }

        public function setHeaders(array $headers): self
        {
            $this->headers = $headers;
            return $this;
        }

        public function getHeaders(): array
        {
            return $this->headers;
        }
    }
}