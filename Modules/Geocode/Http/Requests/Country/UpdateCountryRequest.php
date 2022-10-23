<?php

namespace Modules\Geocode\Http\Requests\Country;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\Rule;

/**
 * Class UpdateCountryRequest.
 */
class UpdateCountryRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateCountryRequest constructor.
     *
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Country is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //update Country for only superadministrator  and admin     
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
        if($this->id=="1"){
                
                return $this->failedAction();
            }else{
                return [
                    'name' => ['required','max:225',Rule::unique('countries')->ignore($this->id)],
                    'code' => ['required', 'max:100'],
                    'status' => ['required', 'in:1,0']
        
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
        throw new AuthorizationException(__('Only the superadministrator and admin can update this Country.'));
    }
}
