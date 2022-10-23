<?php

namespace Modules\Favorite\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class AddToFavoriteRequest.
 */
class AddToFavoriteRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * AddToFavoriteRequest constructor.
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Favorite is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $authorizeRes= $this->baseRepo->authorizeUser();
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
                        'product_id' => ['numeric','exists:products,id','required'],
            'user_id' => ['numeric','exists:users,id','required']

        ];
    }

    /**
     * @return array
     */
    public function messages()
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
        throw new AuthorizationException(__('Only user can make this action'));
    }
}
