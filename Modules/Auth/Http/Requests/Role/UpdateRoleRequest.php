<?php

namespace Modules\Auth\Http\Requests\Role;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rule;

/**
 * Class UpdateRoleRequest.
 */
class UpdateRoleRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateRoleRequest constructor.
     *
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Role is authorized to make this request.
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
            'name' => ['required', 'max:255',  Rule::unique('roles')->ignore($this->id)],
            'display_name' => ['required', 'max:100'],
            'decription'=>['max:1000'],
            'permissions' => ['required', 'array'],
            'permissions.*'=>['exists:permissions,id'],
            'status' => ['sometimes', 'in:1,0']
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'permissions.*.exists' => __('One or more permissions were not found or are not allowed to be associated with this Role type.')
        
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
        throw new AuthorizationException(__('Only the superadministrator can update this Role,and prevent delete on superadmin'));
    }
}
