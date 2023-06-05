<?php

namespace App\Http\Requests;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ReferralRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', Rule::unique(User::class)],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This user is already registered',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                // Check if the user has reached the referral invitation limit
                if ($this->user()->referrals_count >= 10) {
                    $validator->errors()->add(
                        'email',
                        'You have reached the referral invitation limit.'
                    );
                }

                // Check if the user is already invited

                if (Referral::where('email', $this->email)->where('is_registered', false)->exists()) {
                    $validator->errors()->add(
                        'email',
                        'This user has already been invited.'
                    );
                }
            },
        ];
    }
}
