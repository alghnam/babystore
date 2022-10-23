<?php
namespace App\Repositories\Auth\Register;

interface RegisterRepositoryInterface{
    public function register($request,$model);
}