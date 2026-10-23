<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormTemplate;
use App\Models\TemplateCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TemplateController extends Controller
{
    /** Gallery: popular starting points, industry list, search + category filter. */
    public function index(Request $request)
    {
        $search   = trim((string) $request->query('q', ''));
        $category = $request->query('category');

        $categories = TemplateCategory::withCount(['templates' => fn ($q) => $q->active()])
            ->orderBy('sort_order')->get();

        $filtering = $search !== '' || $category;

        $templates = FormTemplate::active()->with('category')
            ->when($category, fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', $category)))
            ->search($search)
            ->when(! $filtering, fn ($q) => $q->where('is_popular', true))
            ->orderByDesc('uses_count')->orderBy('title')
            ->paginate(18)->withQueryString();

        return view('templates.index', [
            'categories'     => $categories,
            'templates'      => $templates,
            'search'         => $search,
            'activeCategory' => $category,
            'filtering'      => $filtering,
            'totalTemplates' => FormTemplate::active()->count(),
        ]);
    }

    /** Preview page for one template. */
    public function show(FormTemplate $template)
    {
        abort_unless($template->is_active, 404);

        return view('templates.show', [
            'template' => $template->load('category'),
            'related'  => FormTemplate::active()
                ->where('template_category_id', $template->template_category_id)
                ->whereKeyNot($template->id)->limit(3)->get(),
        ]);
    }

    /**
     * "Use template": copy it into the signed-in user's forms, then open the editor.
     * Reachable by GET too, so a guest who clicks it is sent to login and lands here afterwards.
     */
    public function use(Request $request, FormTemplate $template)
    {
        abort_unless($template->is_active, 404);

         if ($request->isMethod('get')) {
        return redirect()
            ->route('templates.show', $template)
            ->with('info', 'You are logged in. Click "Use template" to continue.');
    }

        $fields = collect($template->fields)->map(fn ($f) => ['id' => Str::lower(Str::random(8))] + $f)->all();

        $form = Form::create([
            'user_id'          => $request->user()->id,
            'form_template_id' => $template->id,
            'title'            => $template->title,
            'description'      => $template->description,
            'slug'             => Str::slug($template->title) . '-' . Str::lower(Str::random(6)),
            'fields'           => $fields,
            'settings'         => ['submit_text' => 'Submit', 'color' => '#1e88ff'],
            'status'           => 'draft',
        ]);

        $template->increment('uses_count');

        return redirect()->route('forms.edit', $form);
    }
}
