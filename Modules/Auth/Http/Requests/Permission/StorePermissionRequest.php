<?php

namespace Modules\Auth\Http\Requests\Permission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;

/**
 * Class StorePermissionRequest.
 */
class StorePermissionRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * StorePermissionRequest constructor.
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
        //Store Permission for only superadministrator        
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
            'name' => 'required|unique:permissions|max:255',
            'display_name' => ['required', 'max:100'],
            'description'=>['max:1000'],
            'module_name' => 'required|max:255',
           // 'roles' => ['sometimes', 'array'],
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
            'roles.*.exists' => __('One or more roles were not found or are not allowed to be associated with this roles type.'),
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
        throw new AuthorizationException(__('Only the superadministrator can Store this Permission.'));
    }
}
