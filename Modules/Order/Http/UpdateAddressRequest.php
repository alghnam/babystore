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
        //update Order for only superadministrator  and admins
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
        
        $Address= Address::where('id',$this->id)->first();
        if(Address!==null){
            return [
                'first_name'=>['max:225'],
                'last_name'=>['max:225'],
                'country' => ['required','string','max:255','exists:countries,id'],
                'city' => ['required','string','max:255','exists:cities,id'],
                'town' => ['required','string','max:255','exists:towns,id'],
                'piece_number' => ['numeric'],
                'street_number' => ['numeric'],
                'jada_number' => ['numeric'],
                'home_no' => ['required','numeric','unique:users'],
                'floor_number' => ['numeric'],
                'apartment_number' => ['numeric'],
                'additional_tips' => ['max:225'],
                'default_address' => ['sometimes', 'in:1,0'],

            ];

        }else{
            return [

            ];
        }
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
        throw new AuthorizationException(__('Only the superadministrator and admins can update this Order'));
    }
    
}
