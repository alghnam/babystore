<?php

namespace Modules\Auth\Http\Requests\Permission;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rule;

/**
 * Class UpdatePermissionRequest.
 */
class UpdatePermissionRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdatePermissionRequest constructor.
     *
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Permission is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //update Permission for only superadministrator        
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
        $data=$this->request->all();
        return [
            'name' => ['required', 'max:255',  Rule::unique('permissions')->ignore($this->id)],

            'display_name' => ['required', 'max:100'],
            'decription'=>['max:1000'],
            'module_name' => 'required|max:255',
         //   'roles' => ['sometimes', 'array'],
           // 'roles.*'=>['exists:roles,id'],
            'status' => ['required', 'in:1,0']

        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'roles.*.exists' => __('One or more roles were not found or are not allowed to be associated with this Permission type.')
        
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
        throw new AuthorizationException(__('Only the superadministrator can update this Permission.'));
    }
}
