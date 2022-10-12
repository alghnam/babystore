<?php

namespace Modules\Coupon\Http\Requests;

use Modules\Coupon\Entities\Coupon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
/**
 * Class UpdateCouponRequest.
 */
class UpdateCouponRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateCouponRequest constructor.
     *
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
        //update Coupon for only superadministrator  and admins
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
                'name' => ['max:225',Rule::unique('coupons')->ignore($this->id)],
                'value' => ['required','max:225','numeric'],
                 'end_date'=>['required','date'],
                                 'is_used' => ['in:1,0'],

                'status' => ['required', 'in:1,0']

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
        throw new AuthorizationException(__('Only the superadministrator and admins can update this Coupon'));
    }
    
}
