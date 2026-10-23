<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormResponse;
use App\Models\User;
use Illuminate\Http\Request;

class TemplateFormsController extends Controller
{
     private const USER_COLS = 'user:id,name,first_name,last_name,email';

    private function authorizeStaff(): void
    {
        $u = currentUser();
        abort_unless($u && ($u->isAdmin() || $u->isSuperAdmin()), 403);
    }

    /**
     * Superadmin: sab forms.
     * Admin: apne forms + un clients ke forms jinka admin_id khali ya is admin ka ho.
     */
    public function visibleForms()
    {
        $u = currentUser();

        if ($u->isSuperAdmin()) {
            return Form::query();
        }

        return Form::where(function ($w) use ($u) {
            $w->where('user_id', $u->id)
              ->orWhereHas('user', function ($c) use ($u) {
                  $c->where('user_type', 'client')
                    ->where(fn ($x) => $x->whereNull('admin_id')->orWhere('admin_id', $u->id));
              });
        });
    }

    public function index(Request $request)
    {
        $this->authorizeStaff();

        $base = $this->visibleForms();

        // Filter dropdown ke liye: jin users ke forms dikh rahe hain
        $owners = User::whereIn('id', (clone $base)->select('user_id'))
            ->orderBy('first_name')
            ->get(['id', 'name', 'first_name', 'last_name', 'email']);

        $forms = (clone $base)
            ->when($request->filled('client'), fn ($q) => $q->where('user_id', $request->query('client')))
            ->with(self::USER_COLS)
            ->withCount('responses')
            ->withMax('responses', 'created_at')
            ->orderByDesc('id')
            ->get();

        $stats = [
            'clients'   => $forms->pluck('user_id')->unique()->count(),
            'forms'     => $forms->count(),
            'responses' => $forms->sum('responses_count'),
        ];

        return view('admin.tempforms.index', compact('forms', 'owners', 'stats'));
    }

    public function responses(Form $form)
    {
        $this->authorizeStaff();
        abort_unless($this->visibleForms()->whereKey($form->id)->exists(), 403);

        $form->load(self::USER_COLS);

        $columns = collect($form->fields)
            ->reject(fn ($f) => in_array($f['type'], ['heading', 'paragraph'], true))
            ->values();

        $responses = $form->responses()->orderByDesc('id')->paginate(20);

        return view('admin.tempforms.responses', compact('form', 'columns', 'responses'));
    }

    /** Dashboard widget ke liye latest responses */
    public static function recentResponses(int $limit = 5)
    {
        $ids = app(self::class)->visibleForms()->pluck('forms.id');

        return FormResponse::with('form.user:id,name,first_name,last_name,email')
            ->whereIn('form_id', $ids)
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }
}
