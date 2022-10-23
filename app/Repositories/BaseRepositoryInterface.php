<?php
namespace App\Repositories;

interface BaseRepositoryInterface
{
   public function redirectTo();
   public function authorize();
   public function getStatuses();
}
