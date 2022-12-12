<?php

namespace Tests\Feature;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\Task;

class TareaTest extends TestCase
{

    /**
     * A basic test example.
     *
     * @return void
     */

    //use RefreshDatabase;

    public function testSettingUp() :void {

        DB::connection()->getPdo()->exec("DROP DATABASE IF EXISTS `tarea`;");
        DB::connection()->getPdo()->exec("CREATE DATABASE IF NOT EXISTS `tarea`");
        DB::connection()->getPdo()->exec("ALTER SCHEMA `tarea`  DEFAULT CHARACTER SET utf8mb4  DEFAULT COLLATE utf8mb4_unicode_ci");
        exec("php artisan migrate");
        exec("php artisan db:seed");
        exec('php artisan db:seed --class=InstancesTableSeeder');

        $this->assertTrue(true);

    }

    public function testLoginWithAlumno1(){
        $request = [
            'username' => 'alumno1',
            'password' => 'alumno1'
        ];
        $response = $this->post('login',$request);
        $response->assertSessionDoesntHaveErrors();

    }

    public function testListTareas()
    {  
        $this->testLoginWithAlumno1();


        $response = $this->get('/tarea/list');
        $response->assertStatus(302);
    }

    public function testExportTareaListPDF(){
        $this->testLoginWithAlumno1();

        $response = $this->get('/tarea/list/export/pdf/');
        $response->assertStatus(302);
    }

    public function testCreateTareaPositive()
    {

        $this->testLoginWithAlumno1();

        $request = [
            'user_id' => '1',
            'titulo' => 'Tarea de prueba',
            'descripcion' => 'Esto es una tarea de prueba',
            'cantidad_total' => '0'
        ];

        $response = $this->post('/tarea/create/',$request);

        $response->assertStatus(302);
    }

    public function testCreateTaskNegative()
    {

        $this->testLoginWithAlumno1();

        $request = [
            'user_id' => '1',
            'titulo' => '',
            'descripcion' => 'Esto es una tarea de prueba',
            'cantidad_total' => '0'
        ];

        $response = $this->post('/tarea/create/',$request);

        $response->assertStatus(302);
    }


}