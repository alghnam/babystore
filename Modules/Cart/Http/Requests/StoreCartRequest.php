<?php

namespace Modules\Cart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class StoreCartRequest.
 */
class StoreCartRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * StoreCartRequest constructor.
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Cart is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //store Cart for only superadministrator , admins 
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
            
            'products' => ['sometimes','array'],
            'products.*'=>['exists:products,id'],
            'user_id' => ['required','numeric','exists:users,id'],
            
            'quantity'=>['numeric'],
                        'status' => ['sometimes', 'in:1,0'],

        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'Cart_images.*.exists' => __('One or more Cart images were not found or are not allowed to be associated with this Cart.'),

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
        throw new AuthorizationException(__('Only the superadministrator and admins can update this Cart'));
    }
}
