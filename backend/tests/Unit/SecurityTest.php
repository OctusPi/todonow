<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Security\Guardian;
use Tests\TestCase;
use Illuminate\Http\Request;


class SecurityTest extends TestCase
{

    protected $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new Request();
    }


    public function testAuthAccess():void
    {
        $authUser = Guardian::AuthAccess(new User(), $this->request);
        $authTask = Guardian::AuthAccess(new Task(), $this->request);

        $this->assertNull($this->request?->user());

        $this->assertIsBool($authUser);
        $this->assertIsBool($authTask);
        $this->assertIsBool($authTask);

        $this->assertEquals($authUser, false);
        $this->assertEquals($authTask, false);
    }
}
