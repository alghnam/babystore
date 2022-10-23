<?php

namespace Modules\Auth\Http\Requests\User;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rule;
use Modules\Profile\Entities\Profile;
use Illuminate\Validation\Rules;
use Modules\Auth\Entities\User;

/**
 * Class UpdateUserRequest.
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateUserRequest constructor.
     *
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //update user for only superadministrator  and prevent update on superadmin
        $authorizeRes= $this->baseRepo->authorize();
        if($authorizeRes==true){
            if($this->id==="1"){
                return $this->failedAuthorization();
            }else{
            //this user superadmin   
                return true;
            }
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
                'phone_no' => ['required','max:255',Rule::unique('users')->ignore($this->id)],
                
                'first_name' => ['required','max:255'],
                'last_name' => ['required','max:255'],
                'image'=>['nullable'],
                'image.*'=>['sometimes','mimes:jpeg,bmp,png,gif,svg,pdf'],
                'status' => ['required', 'in:0,1,-1'],
                'roles' => ['required'],
                'roles.*'=>['exists:roles,id']
    
            ];

    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'roles.*.exists' => __('One or more roles were not found or are not allowed to be associated with this user type.'),
            
        
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
        throw new AuthorizationException(__('Only the superadministrator can update this user, and prevent update on superadmin'));
    }
    
}
