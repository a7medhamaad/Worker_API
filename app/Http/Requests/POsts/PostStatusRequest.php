<?php

namespace App\Http\Requests\POsts;

use Illuminate\Foundation\Http\FormRequest;

class PostStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "post_id"=>"required|exists:posts,id",
            "status"=>"required|in:approved,rejected",
            "reject_reason"=>"required_if:status,rejected",
        ];
    }
}
