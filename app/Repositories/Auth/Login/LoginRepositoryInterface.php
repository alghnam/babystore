<?php
namespace App\Repositories\Auth\Login;

interface LoginRepositoryInterface{
    public function login($request);
    public function logout($request);
}