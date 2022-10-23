<?php

namespace Modules\Question\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class StoreQuestionRequest.
 */
class StoreQuestionRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * StoreQuestionRequest constructor.
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
        //store Cart for only superadministrator , admins 
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
                'question' => ['required'],
                'question_category_id' => ['required','max:255','exists:question_categories,id'],
                'answer' => ['required'],
                'status' => ['sometimes', 'in:1,0'],

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
        throw new AuthorizationException(__('Only the superadministrator and admins can update this Cart'));
    }
}
