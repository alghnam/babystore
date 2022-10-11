<?php

namespace Modules\StorageDetail\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\BaseRepository;
use Illuminate\Validation\Rule;
use Modules\Profile\Entities\Profile;
use Illuminate\Validation\Rules;
use Modules\StorageDetail\Entities\StorageDetail;
/**
 * Class UpdateStorageDetailRequest.
 */
class UpdateStorageDetailRequest extends FormRequest
{
    /**
     * @var BaseRepository
    */
    protected $baseRepo;
    /**
     * 
     *  UpdateStorageDetailRequest constructor.
     *
     */
    public function __construct(BaseRepository $baseRepo)
    {
        $this->baseRepo = $baseRepo;
    }
    /**
     * Determine if the StorageDetail is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //update StorageDetail for only superadministrator  and admins
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
        
        $StorageDetail= StorageDetail::where('id',$this->id)->first();
        if($StorageDetail!==null){
            return [
            'sku' => ['required','max:255'],
            'barcode' => ['max:255',Rule::unique('storage_details')->ignore($this->id)],
            'wight' => ['max:255']
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
        throw new AuthorizationException(__('Only the superadministrator and admins can update this StorageDetail'));
    }
    
}
