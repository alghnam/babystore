<?php

namespace Modules\Coupon\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class StoreCouponRequest.
 */
class StoreCouponRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * StoreCouponRequest constructor.
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Coupon is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        //store Coupon for only superadministrator , admins 
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
                           'name' => ['max:225',Rule::unique('coupons')],
                'order_id' => ['required','numeric','exists:orders,id'],
                'value' => ['required','max:225'],
                'status' => ['sometimes', 'in:1,0'],
                // 'is_used' => ['sometimes', 'in:1,0'],
                // 'end_date'=>['required','date']

        ];'status' => ['required'
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'Coupon_images.*.exists' => __('One or more Coupon images were not found or are not allowed to be associated with this Coupon.'),

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
        throw new AuthorizationException(__('Only the superadministrator and admins can update this Coupon'));
    }
}
