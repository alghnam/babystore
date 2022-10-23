<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
/**
 * Class UpdatePaymentRequest.
 */
class UpdatePaymentRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateCartRequest constructor.
     *
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Cart is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //update Cart for only superadministrator  and admins
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
            if($this->id=="1"||$this->id=="2"||$this->id=="3"||$this->id=="4"){
                
                return $this->failedAction();
            }else{
                return [
                    'name' => ['required','max:255',Rule::unique('payments')->ignore($this->id)],
            'type' => ['required', 'in:1,0'],
            'status' => ['required', 'in:1,0'],
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
        protected function failedAction()
    {
        // throw new AuthorizationException(__('Cannt make any action here'));
        throw new AuthorizationException('لا تستطيع فعل هذا الامر هنا');
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
        throw new AuthorizationException(__('Only the superadministrator and admins can make this action'));
    }
    
    
    
}
