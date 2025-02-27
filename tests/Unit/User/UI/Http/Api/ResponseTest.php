<?php
declare(strict_types=1);

namespace Tests\Unit\User\UI\Http\Api {

    use PHPUnit\Framework\TestCase;
    use App\User\UI\Http\Api\Response;

    class ResponseTest extends TestCase
    {
        private int $obLevel;

        protected function setUp(): void
        {
            parent::setUp();
            $this->obLevel = ob_get_level();
        }

        protected function tearDown(): void
        {
            while (ob_get_level() > $this->obLevel) {
                ob_end_clean();
            }
            parent::tearDown();
        }

        /**
         * @runInSeparateProcess
         * @preserveGlobalState disabled
         */
        public function testSendJsonResponseOutput(): void
        {
            $data = ['test' => 'data'];
            $expectedJson = json_encode([
                'statusCode' => 201,
                'message' => 'Created',
                'data' => $data,
            ]);

            $response = new Response(201, 'Created', $data);

            ob_start();
            $response->sendJsonResponse();
            $output = ob_get_clean();

            $this->assertEquals($expectedJson, $output);
            $this->assertEquals(201, http_response_code());
        }

        /**
         * @runInSeparateProcess
         * @preserveGlobalState disabled
         */
        public function testSendJsonResponseWithHeaders(): void
        {
            if (!function_exists('xdebug_get_headers')) {
                $this->markTestSkipped('Xdebug required for header testing');
            }

            $response = new Response();
            $response->addHeader('X-Custom-Header', 'SpecialValue')
                ->addHeader('Content-Language', 'en-US');

            ob_start();
            $response->sendJsonResponse();
            ob_clean();  // Limpiar sin cerrar el buffer
            ob_end_clean();  // Cerrar nuestro buffer

            $headers = xdebug_get_headers();

            $this->assertContains('Content-Type: application/json; charset=UTF-8', $headers);
            $this->assertContains('X-Custom-Header: SpecialValue', $headers);
            $this->assertContains('Content-Language: en-US', $headers);
        }



        public function testSetters(): void
        {
            $response = new Response();

            $modified = $response->setStatusCode(404)
                ->setMessage('Not Found')
                ->setData(['error' => true])
                ->addHeader('X-New-Header', 'Value');

            $this->assertEquals(404, $response->statusCode);
            $this->assertEquals('Not Found', $response->message);
            $this->assertEquals(['error' => true], $response->data);
            $this->assertEquals(['X-New-Header' => 'Value'], $response->getHeaders());

            $this->assertSame($response, $modified);
        }

        public function testSetHeaders(): void
        {
            $response = new Response();
            $headers = ['Cache-Control' => 'no-cache', 'Pragma' => 'no-cache'];

            $modified = $response->setHeaders($headers);

            $this->assertEquals($headers, $response->getHeaders());
            $this->assertSame($response, $modified); // Verificar retorno de instancia
        }

        public function testJsonEncodingFailure(): void
        {
            $response = new Response();
            $response->setStatusCode(500)
                ->setMessage('Error')
                ->setData(fopen('php://temp', 'r')); // Usar setData directamente

            ob_start();
            $response->sendJsonResponse();
            $output = ob_get_clean();

            $expected = json_encode([
                'statusCode' => 500,
                'message' => 'Error',
                'data' => null,
            ]);

            $this->assertEquals($expected, $output);
        }
    }
}