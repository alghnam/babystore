<?php

namespace Modules\ProductAttribute\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

/**
 * Class SaveDetailsArrayProductAttributesRequest.
 */
class SaveDetailsArrayProductAttributesRequest extends FormRequest
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

                            'product_id' => ['numeric','exists:products,id','required'],
'quantity' => ['numeric'],
                        'counter_discount' => ['numeric'],
                        'original_price' => ['numeric'],
                        'price_after_discount' => ['numeric'],
                        'price_discount_ends' => ['numeric'],
                        'sku' => ['required','max:255'],
            'barcode' => ['max:255',Rule::unique('storage_details')],
            'weight' => ['max:255']
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
