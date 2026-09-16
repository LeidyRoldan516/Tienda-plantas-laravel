<?php

/**
 * Autor: Simon Martinez Gomez
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuardarPreferenciasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'experiencia' => ['required', 'string', Rule::in(['principiante', 'intermedio', 'avanzado'])],
            'espacio' => ['required', 'string', Rule::in(['balcon', 'interior_pequeno', 'interior_amplio', 'jardin', 'oficina'])],
            'iluminacion' => ['required', 'string', Rule::in(['baja', 'media', 'alta'])],
            'tiempo_cuidado' => ['required', 'string', Rule::in(['bajo', 'medio', 'alto'])],
            'mascotas' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'experiencia.required' => __('messages.preferencias_experiencia_requerida'),
            'experiencia.in' => __('messages.preferencias_experiencia_invalida'),
            'espacio.required' => __('messages.preferencias_espacio_requerido'),
            'espacio.in' => __('messages.preferencias_espacio_invalido'),
            'iluminacion.required' => __('messages.preferencias_iluminacion_requerida'),
            'iluminacion.in' => __('messages.preferencias_iluminacion_invalida'),
            'tiempo_cuidado.required' => __('messages.preferencias_tiempo_requerido'),
            'tiempo_cuidado.in' => __('messages.preferencias_tiempo_invalido'),
            'mascotas.required' => __('messages.preferencias_mascotas_requeridas'),
            'mascotas.boolean' => __('messages.preferencias_mascotas_invalidas'),
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('mascotas')) {
            $this->merge([
                'mascotas' => filter_var($this->input('mascotas'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
        }
    }
}
