<?php
namespace Modules\Seo\Repositories;

interface SeoRepositoryInterface
{
   public function store($model,$request);
   public function update($request,$id,$model);

}
