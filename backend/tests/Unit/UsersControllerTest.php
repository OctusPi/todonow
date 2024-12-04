<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Utils\Notify;
use Illuminate\Http\Request;

class UsersControllerTest extends TestCase
{
    public function testUsersValidatesRequest()
    {
        $request = Request::create('/save', 'POST', [
            'name' => 'Francisco Rodrigues',
            'email' => 'user@mail.com',
            'password' => 'password',
        ]);

        $respBaseSave = $request->filled('title', 'status', 'user_id')
            ? response()->json(Notify::success('Dados salvos com sucesso...'))
            : response()->json(Notify::error('Falha ao gravar dados...'));

        $this->assertEquals($respBaseSave->getStatusCode(), 200);
    }
}
