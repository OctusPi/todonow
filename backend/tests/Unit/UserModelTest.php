<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserModelTest extends TestCase
{
    public function testRulesAreDefinedCorrectly()
    {
        $user = new User();
        $rules = $user->rules();

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('password', $rules);

        $this->assertEquals('required', $rules['name']);
        $this->assertEquals('required', $rules['password']);

        $this->assertContains('required', $rules['email']);

        $uniqueRule = collect($rules['email'])->first(fn($rule) => $rule instanceof Unique);
        $this->assertInstanceOf(Unique::class, $uniqueRule);
    }

    public function testActivateSecurityReturnsTrue()
    {
        $user = new User();
        $this->assertTrue($user->activateSecurity());
    }

    public function testModelNameReturnsCorrectValue()
    {
        $user = new User();
        $this->assertEquals('user', $user->modelName());
    }

    public function testTasksRelationship()
    {
        $user = new User();
        $relation = $user->tasks();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals(Task::class, $relation->getRelated()::class);
    }

    public function testFillableAttributes()
    {
        $user = new User();
        $this->assertEquals(['id', 'name', 'email', 'password'], $user->getFillable());
    }

    public function testHiddenAttributes()
    {
        $user = new User();
        $this->assertEquals(['password', 'remember_token'], $user->getHidden());
    }

    public function testCastsAreDefinedCorrectly()
    {
        $user = new User();
        $casts = $user->casts();

        $this->assertArrayHasKey('email_verified_at', $casts);
        $this->assertArrayHasKey('password', $casts);

        $this->assertEquals('datetime', $casts['email_verified_at']);
        $this->assertEquals('hashed', $casts['password']);
    }
}
