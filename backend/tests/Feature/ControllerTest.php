<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Mockery as m;
use Illuminate\Support\Facades\Response;

class ControllerTest extends TestCase
{
    protected $modelMock;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->modelMock = m::mock('Illuminate\Database\Eloquent\Model');
        $this->controller = new class ($this->modelMock) extends Controller {
        };
    }

    protected function tearDown(): void
    {
        m::close();
    }


    public function testIndexReturnsAuthResponse()
    {
        $requestMock = m::mock(Request::class);

        // Mock para Guardian::AuthAccess
        m::mock('alias:App\Security\Guardian')
            ->shouldReceive('AuthAccess')
            ->with(m::any(), $requestMock)
            ->andReturn(true);

        // Substitua o helper `response()` com um mock
        $this->mockResponseHelper('access-granted', 200);

        $response = $this->controller->index($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->status());
        $this->assertEquals('access-granted', $response->getData());
    }




    public function testBaseSaveCreatesOrUpdatesRecord()
    {
        $requestMock = m::mock(Request::class);
        $requestMock->shouldReceive('all')->andReturn(['name' => 'Task']);
        $requestMock->shouldReceive('user')->andReturn((object) ['id' => 1]);

        $this->modelMock->shouldReceive('fill')
            ->once()
            ->with(['name' => 'Task', 'user_id' => 1]);

        $this->modelMock->shouldReceive('save')
            ->once()
            ->andReturn(true);

        // Mock do Guardian
        m::mock('alias:App\Security\Guardian')
            ->shouldReceive('AuthCheck')
            ->with($this->modelMock, $requestMock)
            ->andReturn(true);

        $response = $this->controller->baseSave($requestMock);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->status());
    }


}
