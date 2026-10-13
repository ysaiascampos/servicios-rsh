<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DatosGlobalesTest extends TestCase
{
    private $basePath = '/api/datos-globales';

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testRespuesta()
    {
        $response = $this->post($this->basePath, [
            "origen-registro" => 1,
            "run-ciudadano" => 24796811
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }
}
