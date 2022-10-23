<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class AddReviewOrderRequest.
 */
class AddReviewOrderRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * StoreOrderRequest constructor.
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Order is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        //store Order for only superadministrator , admins 
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
            'product_id' => ['required','numeric','exists:products,id','required'],
            'user_id' => ['required','numeric','exists:users,id'],
            'description'=>['required','max:255'],
            'rating'=>['required','numeric'],
            'image'=>['nullable'],
            'image.*'=>['sometimes','mimes:jpeg,bmp,png,gif,svg,pdf']
            ];
    }
    

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'Order_images.*.exists' => __('One or more Order images were not found or are not allowed to be associated with this Order.'),

        ];
    }
        /**
     * Handle a failed authorization attempt.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    // protected function failedAuthorization()
    // {
    //     throw new AuthorizationException(__('Only the superadministrator and admins can update this Order'));
    // }
}
