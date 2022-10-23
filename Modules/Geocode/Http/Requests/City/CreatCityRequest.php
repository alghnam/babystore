<?php

namespace Modules\Geocode\Http\Requests\City;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;

/**
 * Class CreateCityRequest.
 */
class CreateCityRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * CreateCityRequest constructor.
     *
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Category is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //Create Category for only superadministrator and admin 
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
        throw new AuthorizationException(__('Only the superadministrator and Admin can Create this Category.'));
    }
}
