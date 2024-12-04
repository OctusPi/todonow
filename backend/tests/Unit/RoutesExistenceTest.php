<?php

namespace Tests\Unit;


use Tests\TestCase;
use Illuminate\Support\Facades\Route;

class RoutesExistenceTest extends TestCase
{
    public function testBaseRoutesExist()
    {
        $expectedRoutes = [
            'api/auth',
            'api/auth/newuser',
            'api/auth/logout',
            'api/auth/check',
            'api/users',
            'api/users/save',
            'api/users/list/{page?}/{total_page?}',
            'api/users/destroy/{id}',
            'api/users/details/{id}',
            'api/tasks',
            'api/tasks/save',
            'api/tasks/list/{page?}/{total_page?}',
            'api/tasks/destroy/{id}',
            'api/tasks/details/{id}',
        ];

        $registeredRoutes = collect(Route::getRoutes())->map(function ($route) {
            return $route->uri();
        })->toArray();

        foreach ($expectedRoutes as $route) {
            $this->assertContains($route, $registeredRoutes, "A rota ". $route." não está registrada.");
        }
    }

    public function testFallbackRoute()
    {
        $response = $this->get('/rota-inexistente');
        $response->assertStatus(404);
    }
}
