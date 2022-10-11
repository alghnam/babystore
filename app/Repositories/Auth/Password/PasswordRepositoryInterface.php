<?php
namespace App\Repositories\Auth\Password;

interface PasswordRepositoryInterface{
    public function forgotPassword($request,$model1,$model2);
    public function checkCode($request,$model,$rand);
    public function resetPassword($request,$model,$rand);
}