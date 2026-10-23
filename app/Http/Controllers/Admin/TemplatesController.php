<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormTemplate;
use App\Models\TemplateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TemplatesController extends Controller
{
    //Function for
    private function authorizeStaff(): void
    {
        $u = currentUser();
        abort_unless($u && ($u->isAdmin() || $u->isSuperAdmin()), 403);
    }

    public function index()
    {
        $this->authorizeStaff();

        $templates = FormTemplate::with('category')->orderByDesc('id')->paginate(10);

        return view('admin.templates.index', compact('templates'));
    }

    public function create()
    {
        $this->authorizeStaff();

        return view('admin.templates.create', [
            'categories' => TemplateCategory::orderBy('sort_order')->get(),
            'fieldTypes' => FormTemplate::FIELD_TYPES,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeStaff();

        $attrs = $this->payload($request);
        $attrs['slug']       = $this->uniqueSlug($attrs['title']);
        $attrs['uses_count'] = 0;

        FormTemplate::create($attrs);   // fields_count model khud set karta hai

        return redirect()->route('admin_templates.index')->with('success', 'Template created successfully.');
    }

    public function edit($id)
    {
        $this->authorizeStaff();

        return view('admin.templates.edit', [
            'template'   => FormTemplate::findOrFail($id),
            'categories' => TemplateCategory::orderBy('sort_order')->get(),
            'fieldTypes' => FormTemplate::FIELD_TYPES,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeStaff();

        $template = FormTemplate::findOrFail($id);
        $template->update($this->payload($request));   // slug nahi badalta, purane links chalte rahenge

        return redirect()->route('admin_templates.index')->with('success', 'Template updated successfully.');
    }

    /** Popular / Active on-off */
    public function toggle(Request $request, $id)
    {
        $this->authorizeStaff();

        $request->validate(['field' => 'required|in:is_active,is_popular']);

        $t = FormTemplate::findOrFail($id);
        $t->{$request->field} = ! $t->{$request->field};
        $t->save();

        return back()->with('success', 'Template updated successfully.');
    }

    public function destroy($id)
    {
        $this->authorizeStaff();

        $t = FormTemplate::findOrFail($id);

        if (Form::where('form_template_id', $t->id)->exists()) {
            return back()->with('error', 'Users have already created forms from this template, so it cannot be deleted. Turn off "Active" to hide it instead.');
        }

        $t->delete();

        return back()->with('success', 'Template deleted.');
    }

    // ------------------------------------------------------------------

    private function payload(Request $request): array
    {
        $request->validate([
            'title'                => 'required|string|max:255',
            'description'          => 'nullable|string|max:500',
            'template_category_id' => 'nullable|exists:template_categories,id',
            'new_category'         => 'nullable|string|max:60',
            'fields_json'          => 'required|string',
        ]);

        $fields = $this->cleanFields(json_decode($request->fields_json, true) ?: []);

        if (! $fields) {
            throw ValidationException::withMessages(['fields_json' => 'Add at least one field and give it a label.']);
        }

        $categoryId = $this->resolveCategory($request);

        if (! $categoryId) {
            throw ValidationException::withMessages(['template_category_id' => 'Choose a category or type a new one.']);
        }

        return [
            'template_category_id' => $categoryId,
            'title'                => trim($request->title),
            'description'          => $request->description,
            'fields'               => $fields,
            'is_popular'           => $request->boolean('is_popular'),
            'is_active'            => $request->boolean('is_active'),
        ];
    }

    /** Sirf allowed type aur safe values rakhta hai. Editor ke format se match karta hai. */
    private function cleanFields(array $raw): array
    {
        $withOptions = ['select', 'radio', 'checkbox'];
        $withPlaceholder = ['text', 'email', 'phone', 'url', 'number', 'textarea'];
        $noRequired = ['heading', 'paragraph', 'hidden'];
        $out = [];

        foreach ($raw as $f) {
            if (! is_array($f) || ! in_array($f['type'] ?? null, FormTemplate::FIELD_TYPES, true)) {
                continue;
            }

            $label = trim((string) ($f['label'] ?? ''));
            if ($label === '') {
                continue;
            }

            $type = $f['type'];
            $item = [
                'type'     => $type,
                'label'    => Str::limit($label, 255, ''),
                'name'     => Str::snake($label),
                'required' => ! in_array($type, $noRequired, true) && ! empty($f['required']),
            ];

            if (in_array($type, $withPlaceholder, true) && ! empty($f['placeholder'])) {
                $item['placeholder'] = Str::limit(trim($f['placeholder']), 255, '');
            }

            if (! empty($f['help'])) {
                $item['help'] = Str::limit(trim($f['help']), 500, '');
            }

            if (in_array($type, $withOptions, true)) {
                $opts = collect($f['options'] ?? [])
                    ->map(fn ($o) => trim((string) $o))
                    ->filter()->unique()->values()->take(100)->all();

                $item['options'] = $opts ?: ['Option 1', 'Option 2'];
            }

            $out[] = $item;

            if (count($out) >= 100) {
                break;
            }
        }

        return $out;
    }

    private function resolveCategory(Request $request): ?int
    {
        $new = trim((string) $request->new_category);

        if ($new !== '') {
            $slug = Str::slug($new);
            $cat  = TemplateCategory::where('slug', $slug)->first();

            if (! $cat) {
                $letters = preg_replace('/[^A-Za-z]/', '', $new);
                $cat = TemplateCategory::create([
                    'name'       => $new,
                    'slug'       => $slug,
                    'code'       => Str::upper(Str::substr($letters, 0, 2)) ?: 'OT',
                    'sort_order' => (int) TemplateCategory::max('sort_order') + 1,
                ]);
            }

            return $cat->id;
        }

        return $request->template_category_id ? (int) $request->template_category_id : null;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'template';
        $slug = $base;
        $i = 2;

        while (FormTemplate::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
