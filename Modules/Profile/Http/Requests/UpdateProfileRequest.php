<?php

namespace Modules\Profile\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class UpdateProfileRequest.
 */
class UpdateProfileRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * StoreUserRequest constructor.
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
                return true;
        
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
                $userId=auth()->guard('api')->user()->id;

        return [
            'phone_no' => ['required','max:20',Rule::unique('users')->ignore($userId)],
            'first_name' => ['max:255'],
            'last_name' => ['max:255'],            
            'email' => ['nullable','max:255',Rule::unique('users')->ignore($userId)],            
            'image'=>['nullable','mimes:jpeg,bmp,png,gif,svg']
        ];
    }
  /**
     * @return array
     */
    public function messages()
    {
        return [
            'phone_no.required'=>'يجب عليك كتابة رقم الهاتف ',
            'phone_no.max:255'=>'يجب الا يكون رقم الهاتف اكثر من 20',
            'phone_no.unique'=>'يجب ان يكون رقم الهاتف موجود بالنظام',
            'first_name.max:255'=>'يجب الا يكون الاسم الاول اكثر من 225حرف',
            'last_name.max:255'=>'يجب الا يكون الاسم الاخير اكثر من 225حرف',
            'email.max:255'=>'يجب الا يكون رقم الهاتف اكثر من 20',
            'email.unique'=>'يجب ان يكون رقم الهاتف موجود بالنظام',
            'image.mimes'=>'يجب ان تكون الصورة  png, bmp,gif,svg',
            // 'images.*.exists' => __('One or more  images were not found or are not allowed to be associated with this Seo.'),
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
        throw new AuthorizationException(__('Only the superadministrator can enter into it.'));
    }
}
