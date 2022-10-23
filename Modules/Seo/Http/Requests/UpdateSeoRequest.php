<?php

namespace Modules\Seo\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rule;
use Modules\Profile\Entities\Profile;
use Illuminate\Validation\Rules;
use Modules\Seo\Entities\Seo;
/**
 * Class UpdateSeoRequest.
 */
class UpdateSeoRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateSeoRequest constructor.
     *
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
        //update Seo for only superadministrator  and admins
        $authorizeRes= $this->baseRepo->authorize();
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
        
        $Seo= Seo::where('id',$this->id)->first();
        if($Seo!==null){
            return [
                'slug' => ['required','max:255',Rule::unique('seos')->ignore($this->id)],
            'meta_description' => ['max:255'],
            'title' => ['max:255'],
            'product_id' => ['required','numeric','exists:products'],
            'seo_images'=>['nullable', 'array'],
            'seo_images.*'=>['sometimes','mimes:jpeg,bmp,png,gif,svg,pdf']
            ];

        }else{
            return [

            ];
        }
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
        throw new AuthorizationException(__('Only the superadministrator and admins can update this Seo'));
    }
    
}
