<?php

namespace Modules\Geocode\Http\Requests\Town;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Class StoreTownRequest.
 */
class StoreTownRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * StoreTownRequest constructor.
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Town is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //Store Town for only superadministrator  and admin   
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
            'name' => 'required|unique:countries|max:255',
            'code' => ['required', 'max:100'],
            'decription'=>['max:1000'],
            'city_id' => ['numeric','exists:cities,id'],

            'status' => ['required', 'in:1,0']
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'cities.*.exists' => __('One or more cities were not found or are not allowed to be associated with this Role type.')

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
        throw new AuthorizationException(__('Only the superadministrator and admin can Store this Town.'));
    }
}
