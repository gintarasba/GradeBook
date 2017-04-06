<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    /**
     * Login Test.
     *
     * @return void
     */
    public function testLogIn()
    {
        $this->visit('/')
             ->see('Prisijungimas');
    }

    public function testPostLogin()
    {
        $credentials = [
            'name' => 'Admin',
            'password' => 'admin123',
            'rememberMe' => 'true'
        ];
        $response = $this->call('POST', '/auth/pLogin', $credentials);
        $this->assertViewHas('Valdyti');
    }
}
