<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;


/**
 * Class AddReviewOrderRequest.
 */
class AddReviewOrderRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * StoreOrderRequest constructor.
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Order is authorized to make this request.
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
            'order_id' => ['required','numeric','exists:orders,id','required'],
            'user_id' => ['required','numeric','exists:users,id'],
            'description'=>['max:2000'],
            'rating'=>['numeric','required'],
            'image'=>['sometimes','mimes:jpeg,bmp,png,gif,svg,pdf']
            ];
    }

  
      public function messages()
    {
        return [
            'description.max:2000'=>'يجب الا يكون الوصف اكثر من 2000حرف',
            'rating.required'=>'يجب عليك كتابة التقييم ',
            'rating.numeric'=>'يجب عليك ادخال التقييم كرقم ',
            'image.mimes'=>'يجب ان تكون الصورة  png, bmp,gif,svg',
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
