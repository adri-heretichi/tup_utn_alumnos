<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
                'ends_with:@gmail.com',
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'professional_url' => ['nullable', 'url', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    /**
     * Get custom validation messages in Spanish.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.string' => 'El nombre debe ser un texto válido.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección válida.',
            'email.unique' => 'Este correo electrónico ya pertenece a otro usuario.',
            'email.ends_with' => 'El correo electrónico debe ser una dirección de Gmail (@gmail.com).',
            'professional_url.url' => 'La red profesional debe ser una URL válida (ej: https://linkedin.com/in/usuario).',
            'photo.image' => 'La foto de perfil debe ser una imagen válida.',
            'photo.mimes' => 'La foto de perfil debe tener un formato válido (JPG, JPEG, PNG o WEBP).',
            'photo.max' => 'La foto de perfil no debe superar los 2 MB (2048 KB).',
        ];
    }
}
