<?php

namespace Baytek\Laravel\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberRequest extends FormRequest
{
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
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->member?$this->member->id:null, 'id')
            ],
            'meta.first_name' => 'required|max:127',
            'meta.last_name'  => 'required|max:127',
            'meta.home_phone' => 'max:255',
            'meta.work_phone' => 'max:255',
            'meta.company'    => 'max:255',
        ];
    }
}
