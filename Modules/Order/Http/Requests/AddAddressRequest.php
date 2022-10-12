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
 * Class AddAddressRequest.
 */
class AddAddressRequest extends FormRequest
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

                'first_name'=>['required','max:225'],
                'last_name'=>['required','max:225'],
                'phone_no'=>['required','max:225','unique:users,phone_no'],
                'country_id' => ['required','string','max:255','exists:countries,id'],
                'city_id' => ['required','string','max:255','exists:cities,id'],
                'town_id' => ['required','string','max:255','exists:towns,id'],
                 'latitude' => ['required','numeric'],
                'longitute' => ['required','numeric'],
                'piece_number' => ['required','max:225'],
                'street_number' => ['max:20'],
                'jada_number' => ['max:20'],
                'home_no' => ['required','max:20','unique:users,home_no'],
                'floor_number' => ['max:20'],
                'apartment_number' => ['max:20'],
                'additional_tips' => ['max:2000'],
                'default_address' => ['sometimes', 'in:1,0'],


            ];


    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
        'phone_no.required'=>'يجب عليك كتابة رقم الهاتف ',
            'phone_no.max:255'=>'يجب الا يكون رقم الهاتف اكثر من 20',
            'phone_no.unique'=>'يجب ان يكون رقم الهاتف موجود بالنظام',
            'first_name.required'=>'يجب عليك كتابة الاسم الاول ',
            'first_name.max:255'=>'يجب الا يكون الاسم الاول اكثر من 225حرف',
            'last_name.required'=>'يجب عليك كتابة الاسم الاخير ',
            'last_name.max:255'=>'يجب الا يكون الاسم الاخير اكثر من 225حرف',
            'latitude.required'=>'يجب ان تضع خطوط العرض',
            'latitude.numeric'=>'يجب ان يكون خطوط العرض رقم',
            'longitute.required'=>'يجب ان تضع خطوط الطول',
            'longitute.numeric'=>'يجب ان يكون خطوط  الطول رقم',
            'piece_number.required'=>'يجب عليك وضع رقم القطعة',
            'piece_number.numeric'=>'يجب ان يكون رقم القطعة رقم',
            'street_number.max:20'=>'يجب الا يتجاوز رقم الشارع 20 خانة',
            'jada_number.max:20'=>'يجب الا يتجاوز رقم الشارع 20 خانة',
            'floor_number.max:20'=>'يجب الا يتجاوز رقم الشارع 20 خانة',
            'apartment_number.max:20'=>'يجب الا يتجاوز رقم الشارع 20 خانة',
            'phone_no.required'=>'يجب عليك كتابة رقم الهاتف ',
            'phone_no.max:255'=>'يجب الا يكون رقم الهاتف اكثر من 20',
            'phone_no.unique'=>'رقم الهاتف الذي كتبته مكرر بالتظام لذلك اكتب رقما اخر',
             'home_no.required'=>'يجب عليك كتابة رقم المنزل ',
            'home_no.max:255'=>'يجب الا يكون رقم المنزل اكثر من 20',
            'home_no.unique'=>'رقم المنزل الذي كتبته مكرر بالنظام لذلك اكتب رقما اخر ',
            'additional_tips'=>'يجب الا تتجاوز النصائح ال 2000حرف'
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
