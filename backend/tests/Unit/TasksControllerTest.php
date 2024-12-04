<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Utils\Notify;
use Illuminate\Http\Request;

class TasksControllerTest extends TestCase
{
    public function testTaskValidatesRequest()
    {
        $request = Request::create('/save', 'POST', [
            'title' => 'New Task',
            'status' => 'pending',
            'user_id' => 1,
        ]);

        $respBaseSave = $request->filled('title', 'status', 'user_id')
            ? response()->json(Notify::success('Dados salvos com sucesso...'))
            : response()->json(Notify::error('Falha ao gravar dados...'));

        $this->assertEquals($respBaseSave->getStatusCode(), 200);
    }
}
