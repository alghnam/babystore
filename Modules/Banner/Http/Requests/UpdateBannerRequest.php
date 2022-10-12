<?php

namespace Modules\Banner\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rule;
use Modules\Profile\Entities\Profile;
use Illuminate\Validation\Rules;
use Modules\Banner\Entities\Banner;
/**
 * Class UpdateBannerRequest.
 */
class UpdateBannerRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateBannerRequest constructor.
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
        
        // $Banner= Banner::where('id',$this->id)->first();
        // if($Banner!==null){
              
            return [
               'title' => ['required','max:255',Rule::unique('banners')->ignore($this->id)],
            'description' => ['nullable'],
            'product_id' => ['numeric','exists:products,id'],
            'image'=>['nullable'],
            'image.*'=>['sometimes','mimes:jpeg,bmp,png,gif,svg,pdf'],
            'status' => ['sometimes', 'in:1,0'],

            ];

        // }else{
        //     return [

        //     ];
        // }
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
        throw new AuthorizationException(__('Only the superadministrator and admins can make this action'));
    }
    
}
