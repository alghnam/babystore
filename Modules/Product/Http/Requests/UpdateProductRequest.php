<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rule;
use Modules\Profile\Entities\Profile;
use Illuminate\Validation\Rules;
use Modules\Product\Entities\Product;
/**
 * Class UpdateProductRequest.
 */
class UpdateProductRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateProductRequest constructor.
     *
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
        //update Product for only superadministrator  and admins
        $authorizeRes= $this->baseRepo->authorize();
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
        
        // $product= Product::where('id',$this->id)->first();
        // if($product!==null){
            return [
                'name' => ['required','max:255',Rule::unique('products')->ignore($this->id)],
                'description' => ['string'],
                'category_id' => ['required','numeric','exists:categories,id,parent_id,!null'],
                'product_images'=>['nullable', 'array'],
                //  'product_images.*'=>['sometimes','mimes:jpeg,bmp,png,gif,svg,pdf'],
                'quantity'=>['required','numeric'],
                'counter_discount'=>['numeric'],
                'original_price'=>['numeric'],
                'price_after_discount'=>['numeric'],
                'price_discount_ends'=>['required','numeric'],
                'status' => ['required', 'in:1,0'],
                'featured' => ['sometimes', 'in:1,0'],
                'the_best' => ['sometimes', 'in:1,0'],
                'the_more_sale' => ['sometimes', 'in:1,0'],
                'popular' => ['sometimes', 'in:1,0'],
                'modern' => ['sometimes', 'in:1,0'],
            ];

        // }else{
        //     return [

        //     ];
        // }
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [

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
