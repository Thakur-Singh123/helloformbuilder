<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Form;
use Illuminate\Support\Facades\Auth;

class TempFormsController extends Controller
{
    //Function for get auth
    private function userId() {
        return Auth::guard('web')->id();
    }

    //Functio for form detail
    public function index() {
        //Get form detail
        $forms = Form::where('user_id', $this->userId())
            ->withCount('responses')
            ->orderByDesc('id')
            ->get();

        return view('user.tempforms.index', compact('forms'));
    }

    //Function for response
    public function responses(Form $form) {
        //Get auth
        abort_unless($this->userId() && (int) $form->user_id === (int) $this->userId(), 403);

        $columns = collect($form->fields)
            ->reject(fn ($f) => in_array($f['type'], ['heading', 'paragraph'], true))
            ->values();

        $responses = $form->responses()->orderByDesc('id')->paginate(20);

        return view('user.tempforms.responses', compact('form', 'columns', 'responses'));
    }
}
