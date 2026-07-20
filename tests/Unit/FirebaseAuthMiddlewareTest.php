<?php

namespace Tests\Unit;

use App\Http\Middleware\FirebaseAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Mockery;
use Tests\TestCase;

class FirebaseAuthMiddlewareTest extends TestCase
{
    public function test_it_populates_the_logged_in_user_name_from_firebase_employee_data()
    {
        $database = Mockery::mock();
        $reference = Mockery::mock();

        $database->shouldReceive('getReference')
            ->with('Employee')
            ->andReturn($reference);

        $reference->shouldReceive('orderByChild')
            ->with('email')
            ->andReturnSelf();

        $reference->shouldReceive('equalTo')
            ->with('jane@example.com')
            ->andReturnSelf();

        $reference->shouldReceive('getValue')
            ->andReturn([
                '-employee-1' => [
                    'email' => 'jane@example.com',
                    'name' => 'Jane Doe',
                    'dept' => 'Finance',
                    'usertype' => 'employee',
                    'guid' => 'guid-1',
                ],
            ]);

        $this->app->instance('firebase.database', $database);
        Session::forget('firebase_user');
        Session::put('firebase_user', [
            'email' => 'jane@example.com',
        ]);

        $middleware = new FirebaseAuthMiddleware();
        $request = Request::create('/test', 'GET');

        $response = $middleware->handle($request, function ($request) {
            return response('ok');
        });

        $this->assertSame('Jane Doe', Session::get('firebase_user.name'));
        $this->assertSame('Jane Doe', Session::get('firebase_user')['name']);
        $this->assertSame(200, $response->getStatusCode());
    }
}
