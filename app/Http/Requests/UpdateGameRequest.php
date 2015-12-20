<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UpdateGameRequest extends Request
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
			'data.bcf' => 'string'
			'data.owner_id' => 'integer|min:1|exists:users,id',
        ];
    }
}