<?php

namespace App\Http\Requests;

use App\Enums\TaskCompletion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // $projectTask = \App\Models\ProjectTask::find($this->projectTask);
        // dd($this->projectTask->project);
        return auth()->user()->is($this->projectTask->project->owner);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body' => 'required',
            'completed' => ['required', Rule::enum(TaskCompletion::class)]
        ];
    }
}
