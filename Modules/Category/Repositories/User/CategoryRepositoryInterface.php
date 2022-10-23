<?php
namespace Modules\Category\Repositories\User;

interface CategoryRepositoryInterface
{
   public function getMainCategoriesPaginate($model,$request);
   public function getSubCategoriesForMainCategoryPaginate($model,$request);

}
