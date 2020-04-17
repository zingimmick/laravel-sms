<?php

namespace Zing\LaravelSms\Tests\Drivers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Mockery\Matcher\AnyArgs;
use Psr\Http\Message\ResponseInterface;
use Zing\LaravelSms\Concerns\HasHttpRequest;
use Zing\LaravelSms\Tests\TestCase;

class HttpDriverTest extends TestCase
{
    public function test_request()
    {
        $object = Mockery::mock(DummyHasHttpRequest::class)
            ->shouldAllowMockingProtectedMethods();
        $mockBaseOptions = ['base_uri' => 'https://mock-base-options'];
        $mockResponse = Mockery::mock(ResponseInterface::class);
        $mockHttpClient = Mockery::mock(Client::class);
        $object->expects()->getHttpClient($mockBaseOptions)
            ->andReturn($mockHttpClient)
            ->once();
        $object->expects()->getBaseOptions()->andReturn($mockBaseOptions);
        $body = 'unwrapped-api-result';
        $object->expects()->unwrapResponse($mockResponse)->andReturn($body);

        $options = ['form_params' => ['foo' => 'bar']];
        $endpoint = 'mock-endpoint';
        $mockHttpClient->allows()->get($endpoint, $options)->andReturn($mockResponse)->once();
        $object->allows()->request(new AnyArgs())->passthru();

        $this->assertSame($body, $object->request('get', $endpoint, $options));
    }

    public function test_get()
    {
        $driver = Mockery::mock(DummyHasHttpRequest::class)->shouldAllowMockingProtectedMethods();
        $endpoint = 'mock-endpoint';
        $headers = ['content-type' => 'application/json'];
        $query = ['foo' => 'bar'];
        $driver->expects()
            ->request('get', $endpoint, [
                'headers' => $headers,
                'query' => $query,
            ])->times(1);
        $driver->allows()->get(new AnyArgs())->passthru();
        $driver->get($endpoint, $query, $headers);
    }

    public function test_post()
    {
        $driver = Mockery::mock(DummyHasHttpRequest::class)->shouldAllowMockingProtectedMethods();
        $endpoint = 'mock-endpoint';
        $headers = ['content-type' => 'application/json'];
        $params = ['foo' => 'bar'];
        $driver->expects()
            ->request('post', $endpoint, [
                'headers' => $headers,
                'form_params' => $params,
            ])->times(1);
        $driver->allows()->post(new AnyArgs())->passthru();
        $driver->post($endpoint, $params, $headers);
    }

    public function test_post_json()
    {
        $driver = Mockery::mock(DummyHasHttpRequest::class)->shouldAllowMockingProtectedMethods();
        $endpoint = 'mock-endpoint';
        $headers = ['content-type' => 'application/json'];
        $json = ['foo' => 'bar'];
        $driver->expects()
            ->request('post', $endpoint, [
                'headers' => $headers,
                'json' => $json,
            ])->times(1);
        $driver->allows()->postJson(new AnyArgs())->passthru();
        $driver->postJson($endpoint, $json, $headers);
    }

    public function test_unwrap_response_with_json_response()
    {
        $object = Mockery::mock(DummyHasHttpRequest::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $object->allows()->unwrapResponse(new AnyArgs())->passthru();

        $body = ['foo' => 'bar'];
        $response = new Response(200, ['content-type' => 'application/json'], json_encode($body));

        $this->assertSame($body, $object->unwrapResponse($response));
    }

    public function test_unwrap_response_with_xml_response()
    {
        $object = Mockery::mock(DummyHasHttpRequest::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $object->allows()->unwrapResponse(new AnyArgs())->passthru();

        $body = '<xml>
                    <foo>hello</foo>
                    <bar>world</bar>
                </xml>';
        $response = new Response(200, ['content-type' => 'application/xml'], $body);

        $this->assertSame(['foo' => 'hello', 'bar' => 'world'], $object->unwrapResponse($response));
    }

    public function test_unwrap_response_with_unsupported_response()
    {
        $object = Mockery::mock(DummyHasHttpRequest::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();
        $object->allows()->unwrapResponse(new AnyArgs())->passthru();

        $body = 'something here.';
        $response = new Response(200, ['content-type' => 'text/plain'], $body);

        $this->assertSame($body, $object->unwrapResponse($response));
    }
}

class DummyHasHttpRequest
{
    use HasHttpRequest;
}
