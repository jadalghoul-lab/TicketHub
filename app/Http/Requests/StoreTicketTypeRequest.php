<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTicketTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $eventId = $this->input('event_id');
        $event = \App\Models\Event::find($eventId);
        
        return $event && auth()->check() && auth()->user()->can('update', $event);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => 'required|exists:events,id',
            'zone_id' => 'nullable|exists:zones,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'sales_start' => 'nullable|date',
            'sales_end' => 'nullable|date|after_or_equal:sales_start',
            'max_per_order' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ];
    }
}
