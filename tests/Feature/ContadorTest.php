<?php

namespace Tests\App\Http\Controllers;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ContadorTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    //use RefreshDatabase;

    public function testSettingUp() :void {

        DB::connection()->getPdo()->exec("DROP DATABASE IF EXISTS `evidentia`;");
        DB::connection()->getPdo()->exec("CREATE DATABASE IF NOT EXISTS `evidentia`");
        DB::connection()->getPdo()->exec("ALTER SCHEMA `evidentia`  DEFAULT CHARACTER SET utf8mb4  DEFAULT COLLATE utf8mb4_unicode_ci");
        exec("php artisan migrate");
        exec("php artisan db:seed");
        exec('php artisan db:seed --class=InstancesTableSeeder');

        $this->assertTrue(true);

    }

    public function testLoginAlumno(){
        $request = [
            'username' => 'alumno1',
            'password' => 'alumno1'
        ];
        $response = $this->post('login_p',$request);
        $response->assertSessionDoesntHaveErrors();

    }

    public function testListContador()
    {  
        $this->testLoginAlumno();

        $response = $this->get('/contador/list');
        $response->assertStatus(302);
    }

    public function testCreateContador()
    {
        
        $this->testLoginAlumno();

        $request = [
            'title' => 'Clock Test Title',
            'status' => 'pausa',
        ];

        $response = $this->post('/contador/create/',$request);

        $response->assertStatus(302);
    }
}