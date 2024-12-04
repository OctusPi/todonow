<?php

namespace Tests\Unit;

use App\Utils\Notify;
use Tests\TestCase;

class NotifyTest extends TestCase
{
    public function testNotificationsTypeReturn():void
    {
        $this->assertIsArray(Notify::success());
        $this->assertIsArray(Notify::warning());
        $this->assertIsArray(Notify::error());
        $this->assertIsArray(Notify::info());
    }
}
