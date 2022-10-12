<?php

namespace Modules\Wallet\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class AddToWalletRequest.
 */
class AddToWalletRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * AddToWalletRequest constructor.
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the Wallet is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //store Wallet for only user
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
            'amount' => ['numeric','required'],
            'payment_id' => ['numeric','required','exists:payments,id','in:3,4'],
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'amount.required'=>'يجب عليك وضع المبلغ ',
            'amount.numeric'=>'يجب عليك كتابة المبلغ كرقم ',
            'payment_id.required'=>'يجب عليك وضع وسيلة الدفع ',
            'payment_id.in'=>'يجب ان تكون رقم وسيلة الدفع 1 او 2 او 3 او 4  ',

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
        throw new AuthorizationException(__('Only the user can make this action'));
    }
}
