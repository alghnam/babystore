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
        return true;
        //store Seo for only superadministrator , admins 
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
            'user_id' => ['required','numeric','exists:users,id'],
            'system_review_type_id' => ['required','numeric','exists:system_review_types,id'],
            'body' => ['required','string'],
            'name' => ['required','max:255'],
            'email' => ['required','email']
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            // 'seo_images.*.exists' => __('One or more Seo images were not found or are not allowed to be associated with this Seo.'),

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
        throw new AuthorizationException(__('Only the superadministrator and admins can update this Seo'));
    }
}
