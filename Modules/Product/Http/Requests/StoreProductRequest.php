<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class StoreProductRequest.
 */
class StoreProductRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * StoreProductRequest constructor.
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Product is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //store Product for only superadministrator , admins 
        $authorizeRes= $this->baseRepo->authorizeSuperAndAdmin();
        if($authorizeRes==true){
                return true;
            
        }else{
            return $this->failedAuthorization();
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required','max:255',Rule::unique('categories')],
            'description' => ['string'],
                        'quantity' => ['numeric'],
                        'counter_discount' => ['numeric'],
                        'original_price' => ['numeric'],
                        'price_after_discount' => ['numeric'],
                        'price_discount_ends' => ['numeric'],

            'category_id' => ['required','numeric','exists:categories,id,parent_id,!null'],
            'product_images'=>['nullable', 'array'],
           // 'product_images.*'=>['sometimes','mimes:jpeg,bmp,png,gif,svg,pdf'],
            'status' => ['required', 'in:1,0'],
            'featured' => ['sometimes', 'in:1,0'],
            'the_best' => ['sometimes', 'in:1,0'],
            'the_more_sale' => ['sometimes', 'in:1,0'],
            'popular' => ['sometimes', 'in:1,0'],
            'modern' => ['sometimes', 'in:1,0'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'product_images.*.exists' => __('One or more product images were not found or are not allowed to be associated with this product.'),

        ];
    }
        /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        throw new AuthorizationException(__('Only the superadministrator and admins can update this Product'));
    }
}
