<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class TaskModelTest extends TestCase
{

    public function testRulesAreDefinedCorrectly()
{
    $task = new Task();
    $rules = $task->rules();

    $this->assertArrayHasKey('title', $rules);
    $this->assertArrayHasKey('user_id', $rules);
    $this->assertEquals('required', $rules['user_id']);
    $this->assertContains('required', (array)$rules['title']);


    $uniqueRule = collect((array)$rules['title'])->first(fn($rule) => $rule instanceof Unique);
    $this->assertInstanceOf(Unique::class, $uniqueRule);
}


    public function testValidationMessagesAreDefinedCorrectly()
    {
        $task = new Task();
        $messages = $task->messages();

        $this->assertArrayHasKey('required', $messages);
        $this->assertArrayHasKey('unique', $messages);

        $this->assertEquals('Campo obrigatório não informado!', $messages['required']);
        $this->assertEquals('Já existe uma tarefa com esse título...', $messages['unique']);
    }


    public function testActivateSecurityReturnsTrue()
    {
        $task = new Task();
        $this->assertTrue($task->activateSecurity());
    }


    public function testModelNameReturnsCorrectValue()
    {
        $task = new Task();
        $this->assertEquals('task', $task->modelName());
    }


    public function testUserRelationship()
    {
        $task = new Task();
        $relation = $task->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals(User::class, $relation->getRelated()::class);
    }

    public function testFillableAttributes()
    {
        $task = new Task();
        $this->assertEquals(['id', 'title', 'description', 'status', 'user_id'], $task->getFillable());
    }
}
