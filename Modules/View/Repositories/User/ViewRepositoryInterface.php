<?php
namespace Modules\View\Repositories\User;

interface ViewRepositoryInterface
{
   public function myViews($model);
   public function addToView($model,$request);
}
