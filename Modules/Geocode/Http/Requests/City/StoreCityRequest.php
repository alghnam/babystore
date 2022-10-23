<?php

namespace Modules\Geocode\Http\Requests\City;


use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Class StoreCityRequest.
 */
class StoreCityRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * StoreCityRequest constructor.
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
        //Store City for only superadministrator  and admin      
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
        return [
            'name' => 'required|unique:cities|max:255',
            'code' => ['required', 'max:100'],
            'decription'=>['max:1000'],
            'country_id' => ['numeric','exists:countries,id'],

            'status' => ['required', 'in:1,0']
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'countries.*.exists' => __('One or more countries were not found or are not allowed to be associated with this Role type.')

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
        throw new AuthorizationException(__('Only the superadministrator and admin can Store this City.'));
    }
}
