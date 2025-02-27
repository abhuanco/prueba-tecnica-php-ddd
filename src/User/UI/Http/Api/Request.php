<?php
declare(strict_types=1);

namespace App\User\UI\Http\Api {

    class Request
    {
        private array $parsedBody;
        private string $contentType;

        public function __construct()
        {
            $this->contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            $this->parseBody();
        }

        private function parseBody(): void
        {
            if (stripos($this->contentType, 'application/json') !== false) {
                $content = file_get_contents('php://input');
                $this->parsedBody = json_decode($content, true) ?? [];
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->parsedBody = [];
                }
            } else {
                $this->parsedBody = $_POST;
            }
        }

        public function getContentType(): string
        {
            return $this->contentType;
        }

        public function isJson(): bool
        {
            return stripos($this->contentType, 'application/json') !== false;
        }

        public function getParsedBody(): array
        {
            return $this->parsedBody;
        }

        public function getPost(string $key, $default = null)
        {
            return $_POST[$key] ?? $default;
        }

        public function getJsonData(string $key, $default = null)
        {
            return $this->parsedBody[$key] ?? $default;
        }

        /**
         * @param string $key
         * @param $default
         * @return mixed|null
         */
        public function getParam(string $key, $default = null)
        {
            return $this->parsedBody[$key] ?? $_POST[$key] ?? $default;
        }

        public function getMethod(): string
        {
            return $_SERVER['REQUEST_METHOD'];
        }

        public function getHeader(string $headerName): string
        {
            $normalizedName = 'HTTP_' . strtoupper(str_replace('-', '_', $headerName));
            return $_SERVER[$normalizedName] ?? '';
        }
    }
}