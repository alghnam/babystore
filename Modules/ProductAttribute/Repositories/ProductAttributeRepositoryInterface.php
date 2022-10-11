<?php
namespace Modules\ProductAttribute\Repositories;

interface ProductAttributeRepositoryInterface
{
   public function getAllProductAttributesPaginate($model,$request);
}
