<?php

namespace Modules\Auth\Http\Requests\Role;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;


/**
 * Class DeleteRoleRequest.
 */
class DeleteRoleRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * DeleteRoleRequest constructor.
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
        throw new AuthorizationException(__('Only the superadministrator can Delete this Role,and prevent delete on superadmin'));
    }
}
