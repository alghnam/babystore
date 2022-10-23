<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rule;
use Modules\Profile\Entities\Profile;
use Illuminate\Validation\Rules;
use Modules\Order\Entities\Order;
/**
 * Class FinishingOrderRequest.
 */
class FinishingOrderRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateOrderRequest constructor.
     *
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
        $authorizeRes= $this->baseRepo->authorizeUser();
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
                'order_id' => ['required','numeric','exists:orders,id'],
                'service_id' => ['required','numeric','exists:services,id'],
                'address_id' => ['required','numeric','exists:addresses,id'],
                'payment_id' => ['required','numeric','exists:payments,id','in:1,2,3,4'],//1: wallet,2: upon ,3: payment method
                'coupon_id' => ['numeric','exists:coupons,id'],

                
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
        throw new AuthorizationException(__('Only user can make this action'));
    }
    
}
