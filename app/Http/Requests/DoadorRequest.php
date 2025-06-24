<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoadorRequest extends FormRequest
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
        $doadorId = $this->route('doador');

        return [
            'nome' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:doadores,email,' . $doadorId,
            'telefone' => 'required|string|max:30',
            'endereco' => 'required|string|max:50'
        ];
    }

    /**
     * Return custom messages for incorrect parameters.
     * 
     * @return array<string>
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório',
            'nome.max' => 'O nome deve ter no máximo 50 caracteres',
            'email.required' => 'O e-mail é obrigatório',
            'email.email' => 'O e-mail deve ter um formato válido',
            'email.unique' => 'Este e-mail já está cadastrado',
            'telefone.required' => 'O telefone é obrigatório',
            'endereco' => 'O endereço é obrigatório'
        ];
    }
}
