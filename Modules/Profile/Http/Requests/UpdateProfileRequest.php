<?php

namespace Modules\Profile\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class UpdateProfileRequest.
 */
class UpdateProfileRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * StoreUserRequest constructor.
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
        if($this->userId==1){
            //show user for only superadministrator     
            //this user superadmin   
            $authorizeRes= $this->baseRepo->authorize();
            if($authorizeRes==true){
                return true;
            }else{//this user not superadmin
                return $this->failedAuthorization();
            }

        }else{
            return true;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
                $userId=auth()->guard('api')->user()->id;

        return [
            //'phone_no' => ['required','max:255',Rule::unique('users')->ignore($userId)],
            'phone_no' => ['required','max:255'],
            'first_name' => ['required','max:255'],
            'last_name' => ['required','max:255'],            
           // 'email' => ['required','max:255','email',Rule::unique('users')->ignore($userId)],            
            'email' => ['max:255','email'],            
            'image'=>['nullable'],
            'image.*'=>['sometimes','mimes:jpeg,bmp,png,gif,svg,pdf']
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
        throw new AuthorizationException(__('Only the superadministrator can enter into it.'));
    }
}
