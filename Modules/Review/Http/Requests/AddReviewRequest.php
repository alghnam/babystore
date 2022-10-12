<?php

namespace Modules\Review\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class AddReviewRequest.
 */
class AddReviewRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * AddReviewRequest constructor.
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
        return true;
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
            'first_name'=>['max:255','required'],
            'last_name'=>['max:255'],
            // 'user_id' => ['numeric','exists:users,id','required'],
            'description'=>['max:2000'],
            'rating'=>['numeric','required'],
            'status' => ['required', 'in:1,0']


        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required'=>'يجب عليك كتابة الاسم الاول ',
            'first_name.max:255'=>'يجب الا يكون الاسم الاول اكثر من 225حرف',
            'last_name.max:255'=>'يجب الا يكون الاسم الاخير اكثر من 225حرف',
            'description.max:2000'=>'يجب الا يكون الوصف اكثر من 2000حرف',
            'rating.required'=>'يجب عليك كتابة التقييم ',
            'rating.numeric'=>'يجب عليك ادخال التقييم كرقم '
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
