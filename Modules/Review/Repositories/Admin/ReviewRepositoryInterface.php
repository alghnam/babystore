<?php
namespace Modules\Review\Repositories\Admin;

interface ReviewRepositoryInterface
{
   public function getAllReviewsPaginate($model,$request);
}
