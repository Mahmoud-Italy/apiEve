<?php

namespace App\Http\Requests;

use App\Models\Event;
use Urameshibr\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class EventUpdateRequest extends FormRequest
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
        $id      = decrypt(request('id')); // decrypt id

        return [
            'name'       => 'required',
            'venue'      => 'required',
            'latitude'   => 'required',
            'longitude'  => 'required',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date'
        ];
    }

    // in case you want to return single line of error instead of array of errors..
    protected function formatErrors (Validator $validator)
    {
        return ['message' => $validator->errors()->first()];
    }
}
