<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;

class FormController extends Controller
{
    /** The editor (opened by "Use template"). */
    public function edit(Request $request, Form $form)
    {
        $this->ownedBy($request, $form);

        return view('forms.edit', ['form' => $form]);
    }

    /** Autosave from the editor (JSON). */
    public function update(Request $request, Form $form): JsonResponse
    {
        $this->ownedBy($request, $form);

        $data = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'description'        => ['nullable', 'string', 'max:2000'],
            'fields'             => ['required', 'array', 'max:100'],
            'fields.*.id'        => ['required', 'string', 'max:40'],
            'fields.*.type'      => ['required', Rule::in(FormTemplate::FIELD_TYPES)],
            'fields.*.label'     => ['nullable', 'string', 'max:255'],
            'fields.*.required'  => ['nullable', 'boolean'],
            'fields.*.placeholder' => ['nullable', 'string', 'max:255'],
            'fields.*.help'      => ['nullable', 'string', 'max:500'],
            'fields.*.options'   => ['nullable', 'array', 'max:100'],
            'fields.*.options.*' => ['nullable', 'string', 'max:255'],
            'settings'           => ['nullable', 'array'],
            'settings.color'     => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'settings.notify_email' => ['nullable', 'email'],
        ]);

        $form->update($data);

        return response()->json(['ok' => true, 'saved_at' => now()->toIso8601String()]);
    }

    public function publish(Request $request, Form $form): JsonResponse
    {
        $this->ownedBy($request, $form);

        $form->update(['status' => 'published', 'published_at' => $form->published_at ?? now()]);

        return response()->json(['ok' => true, 'url' => route('forms.public', $form->slug)]);
    }

    /** Public page. */
    public function showPublic(string $slug)
    {
        $form = Form::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return view('forms.public', ['form' => $form]);
    }

    // public function submit(Request $request, string $slug)
    // {
    //     $form = Form::where('slug', $slug)->where('status', 'published')->firstOrFail();

    //     $input = (array) $request->input('f', []);
    //     $rows  = [];
    //     $errors = [];

    //     foreach ($form->fields as $field) {
    //         if (in_array($field['type'], ['heading', 'paragraph'], true)) {
    //             continue;
    //         }
    //         $value = $field['type'] === 'file' ? null : ($input[$field['id']] ?? null);
    //         $empty = $value === null || $value === '' || $value === [];

    //         if (! empty($field['required']) && $empty && $field['type'] !== 'file') {
    //             $errors["f.{$field['id']}"] = ($field['label'] ?: 'This field') . ' is required.';
    //             continue;
    //         }
    //         $rows[] = ['id' => $field['id'], 'label' => $field['label'] ?? '', 'value' => $value];
    //     }

    //     if ($errors) {
    //         return back()->withErrors($errors)->withInput();
    //     }

    //     $form->responses()->create(['data' => $rows, 'ip' => $request->ip()]);

    //     // Hook for notifications: $form->settings['notify_enabled'], $form->settings['notify_email'].

    //     return back()->with('submitted', $form->settings['success_message'] ?? 'Thanks! Your response has been recorded.');
    // }

    public function submit(Request $request, string $slug)
    {
        $form = Form::where('slug', $slug)->where('status', 'published')->firstOrFail();

        $input = (array) $request->input('f', []);
        $rows  = [];
        $errors = [];
        $email = null;

        foreach ($form->fields as $field) {
            if (in_array($field['type'], ['heading', 'paragraph'], true)) {
                continue;
            }
            $value = $field['type'] === 'file' ? null : ($input[$field['id']] ?? null);
            $empty = $value === null || $value === '' || $value === [];

            if (! empty($field['required']) && $empty && $field['type'] !== 'file') {
                $errors["f.{$field['id']}"] = ($field['label'] ?: 'This field') . ' is required.';
                continue;
            }
            if ($email === null && $field['type'] === 'email' && is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $email = strtolower($value);
            }
            $rows[] = ['id' => $field['id'], 'label' => $field['label'] ?? '', 'value' => $value];
        }

        if ($errors) {
            return back()->withErrors($errors)->withInput();
        }

        $source = preg_match('#^https?://\S{3,300}$#i', (string) $request->input('_source')) ? $request->input('_source') : null;
        $saved = $source ? array_merge($rows, [['id' => '_source', 'label' => 'Submitted from', 'value' => $source]]) : $rows;
        $form->responses()->create(['data' => $saved, 'ip' => $request->ip()]);

        try {
            $data = ['form' => $form, 'rows' => $rows, 'source' => $source, 'email' => $email];

            //Admin
            if ($owner = $form->user?->email) {
                Mail::send('emails.response', $data + ['admin' => true], function ($m) use ($owner, $form, $email) {
                    $m->to($owner)->subject('New response: ' . $form->title);
                    if ($email) $m->replyTo($email);
                });
            }

            //Customer
            if ($email) {
                Mail::send('emails.response', $data + ['admin' => false], fn ($m) => $m->to($email)->subject('We received your response: ' . $form->title));
            }
        } catch (\Throwable $e) {
            report($e); 
        }
        return response('<div style="font-family:sans-serif;text-align:center;padding:40px"><h2>✓ Thank you!</h2><p>'
        . e($form->settings['success_message'] ?? 'Your response has been recorded.') . '</p></div>');
    }

    private function ownedBy(Request $request, Form $form): void
    {
        abort_unless($form->user_id === $request->user()->id, 403);
    }
}
