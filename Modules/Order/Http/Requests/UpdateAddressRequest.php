<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rule;
use Modules\Profile\Entities\Profile;
use Illuminate\Validation\Rules;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\Address;
/**
 * Class UpdateAddressRequest.
 */
class UpdateAddressRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateAddressRequest constructor.
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
                'phone_no'=>['required','max:225'],
                'country_id' => ['required','string','max:255','exists:countries,id'],
                'city_id' => ['required','string','max:255','exists:cities,id'],
                'town_id' => ['required','string','max:255','exists:towns,id'],
                                'latitude' => ['required','numeric'],
                'longitute' => ['required','numeric'],
                'piece_number' => ['required','max:225'],
                'street_number' => ['max:225'],
                'jada_number' => ['max:225'],
                'home_no' => ['required','max:225','unique:users,phone_no'],
                'floor_number' => ['max:225'],
                'apartment_number' => ['max:225'],
                'additional_tips' => ['max:225'],
                'default_address' => ['sometimes', 'in:1,0'],

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
