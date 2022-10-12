<?php

namespace Modules\SystemReview\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class AddSystemReviewRequest.
 */
class AddSystemReviewRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * AddSystemReviewRequest constructor.
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Seo is authorized to make this request.
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
            'user_id' => ['required','numeric','exists:users,id'],
            'system_review_type_id' => ['required','numeric','exists:system_review_types,id'],
            'body' => ['required'],
            'name' => ['required','max:225'],
            'email' => ['required','email','max:225']
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'system_review_type_id.required'=>'يجب عليك ادخال نوع التقييم',
            'body.required'=>'يجب عليك وضع الوصف ',
            'name.required'=>'يجب عليك وضع الاسم ',
            'name.max:225'=>'يجب عليك ادخال الاسم لا يتراوح 225 حرف',
            'email.max:225'=>'يجب عليك ادخال الايميل لا يتراوح 225 حرف',
            'email.required'=>'يجب عليك ادخال الايميل',
            'email.email'=>'يجب ان الايميل بشكل صحيح',
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
