<?php

namespace Modules\ProductAttribute\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

/**
 * Class UpdateDetailsArrayProductAttributesRequest.
 */
class UpdateDetailsArrayProductAttributesRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateProductAttributeRequest constructor.
     *
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the ProductAttribute is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //update ProductAttribute for only superadministrator  and admins
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
        
            return [

                            'product_id' => ['required','numeric','exists:products,id'],
'quantity' => ['required','numeric'],
                'original_price' => ['required','numeric'],
                'price_discount_ends' => ['required','numeric'],
                'sku' => ['nullable','max:255'],
                'barcode' => ['nullable','max:255',Rule::unique('storage_details')],
                'weight' => ['nullable','max:255'],
                'image'=>['nullable'],
                'image.*'=>['sometimes','mimes:jpeg,bmp,png,gif,svg,pdf'],
            ];

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
        throw new AuthorizationException(__('Only the superadministrator and admins can update this ProductAttribute'));
    }
    
}
