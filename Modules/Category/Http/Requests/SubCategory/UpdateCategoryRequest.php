<?php

namespace Modules\Category\Http\Requests\SubCategory;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rule;
use Modules\Profile\Entities\Profile;
use Illuminate\Validation\Rules;
use Modules\Category\Entities\Category;
/**
 * Class UpdateCategoryRequest.
 */
class UpdateCategoryRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateCategoryRequest constructor.
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
        //update Category for only superadministrator  and admins
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
                'name' => ['required','max:255'],
            'category_id' => ['required','numeric','exists:categories,id,parent_id,!null'],

                'image'=>['nullable'],
                'image.*'=>['sometimes','mimes:jpeg,bmp,png,gif,svg,pdf'],
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
        throw new AuthorizationException(__('Only the superadministrator and admins can make this action'));
    }
    
}
