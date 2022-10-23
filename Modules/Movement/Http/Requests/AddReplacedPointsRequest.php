<?php

namespace Modules\Movement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class AddReplacedPointsRequest.
 */
class AddReplacedPointsRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * StoreReviewRequest constructor.
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Review is authorized to make this request.
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
            'amount'=>['max:20','required']

        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'amount.max:20'=>'يجب ان يكون المبلغ لا يتحاوز ال 20 رقم',
            'amount.required'=>'يجب عليك وضع المبلغ'
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
