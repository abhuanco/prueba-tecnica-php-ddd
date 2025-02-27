<?php
declare(strict_types=1);

namespace Tests\Unit\User\UI\Http\Api;

use PHPUnit\Framework\TestCase;
use App\User\UI\Http\Api\Request;
use ReflectionClass;

class RequestTest extends TestCase
{
    private array $backupServer;
    private array $backupPost;

    protected function setUp(): void
    {
        parent::setUp();
        $this->backupServer = $_SERVER;
        $this->backupPost = $_POST;
    }

    protected function tearDown(): void
    {
        $_SERVER = $this->backupServer;
        $_POST = $this->backupPost;
        parent::tearDown();
    }

    public function testGetContentType(): void
    {
        $_SERVER['CONTENT_TYPE'] = 'application/json';
        $request = new Request();
        $this->assertEquals('application/json', $request->getContentType());
    }

    public function testIsJson(): void
    {
        $_SERVER['CONTENT_TYPE'] = 'application/json';
        $request = new Request();
        $this->assertTrue($request->isJson());

        $_SERVER['CONTENT_TYPE'] = 'text/html';
        $request = new Request();
        $this->assertFalse($request->isJson());
    }

    public function testGetParsedBodyWhenJson(): void
    {
        $_SERVER['CONTENT_TYPE'] = 'application/json';
        $request = new Request();
        $this->assertEquals([], $request->getParsedBody());
    }

    public function testGetParsedBodyWhenFormData(): void
    {
        $_SERVER['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
        $_POST = ['key' => 'value'];
        $request = new Request();
        $this->assertEquals(['key' => 'value'], $request->getParsedBody());
    }

    public function testGetPost(): void
    {
        $_POST = ['test' => 'post-value'];
        $request = new Request();
        $this->assertEquals('post-value', $request->getPost('test'));
        $this->assertNull($request->getPost('non-existent'));
    }

    public function testGetJsonData(): void
    {
        $_SERVER['CONTENT_TYPE'] = 'text/html';
        $_POST = ['test' => 'post-value'];
        $request = new Request();
        $this->assertEquals('post-value', $request->getJsonData('test'));
        $this->assertNull($request->getJsonData('non-existent'));
    }

    public function testGetParam(): void
    {
        $_SERVER['CONTENT_TYPE'] = 'text/html';
        $_POST = ['param' => 'post-value'];
        $request = new Request();
        $this->assertEquals('post-value', $request->getParam('param'));
    }

    public function testGetMethod(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $request = new Request();
        $this->assertEquals('POST', $request->getMethod());
    }

    public function testGetHeader(): void
    {
        $_SERVER['HTTP_X_CUSTOM_HEADER'] = 'header-value';
        $request = new Request();
        $this->assertEquals('header-value', $request->getHeader('X-Custom-Header'));
        $this->assertEquals('', $request->getHeader('Non-Existent'));
    }

    public function testJsonDataWithReflection(): void
    {
        $request = new Request();
        $reflection = new ReflectionClass($request);
        $parsedBody = $reflection->getProperty('parsedBody');
        $parsedBody->setAccessible(true);
        $parsedBody->setValue($request, ['jsonKey' => 'jsonValue']);

        $this->assertEquals('jsonValue', $request->getJsonData('jsonKey'));
    }

    public function testParamPriorityWithReflection(): void
    {
        $request = new Request();
        $reflection = new ReflectionClass($request);
        $parsedBody = $reflection->getProperty('parsedBody');
        $parsedBody->setAccessible(true);
        $parsedBody->setValue($request, ['param' => 'parsedValue']);
        $_POST['param'] = 'postValue';

        $this->assertEquals('parsedValue', $request->getParam('param'));
    }
}