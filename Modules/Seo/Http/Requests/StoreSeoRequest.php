<?php

namespace Modules\Seo\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Access\AuthorizationException;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rules;

/**
 * Class StoreSeoRequest.
 */
class StoreSeoRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * StoreSeoRequest constructor.
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
            'slug' => ['required','max:255',Rule::unique('seos')],
            'meta_description' => ['max:255'],
            'title' => ['max:255'],
            'product_id' => ['required','numeric','exists:products,id'],
            'seo_images'=>['nullable', 'array'],
            'seo_images.*'=>['sometimes','mimes:jpeg,bmp,png,gif,svg,pdf']
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'seo_images.*.exists' => __('One or more Seo images were not found or are not allowed to be associated with this Seo.'),

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
