<?php

namespace Modules\Geocode\Http\Requests\City;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\Rule;

/**
 * Class UpdateCityRequest.
 */
class UpdateCityRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateCityRequest constructor.
     *
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the City is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //update City for only superadministrator and admin       
        $authorizeRes= $this->baseRepo->authorizeSuperAndAdmin();
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
            'name' => ['required','max:225',Rule::unique('cities')->ignore($this->id)],
            'status' => ['sometimes', 'in:1,0'],
                        'country_id' => ['numeric','exists:countries,id'],

        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [ 
            'countries.*.exists' => __('One or more countries were not found')

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
        throw new AuthorizationException(__('Only the superadministrator and admin can update this City.'));
    }
}
