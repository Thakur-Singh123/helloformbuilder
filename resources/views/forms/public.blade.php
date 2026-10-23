<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $form->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
@php($color = $form->settings['color'] ?? '#1e88ff')
@php($prefill = ($form->settings['prefill'] ?? true) ? request()->query() : [])
<body class="bg-slate-50 text-slate-800 antialiased">
<main class="mx-auto max-w-xl px-4 py-10">
    <div class="rounded-xl border border-t-4 border-slate-200 bg-white p-6 sm:p-8" style="border-top-color: {{ $color }}">
        <h1 class="text-2xl font-semibold text-slate-900">{{ $form->title }}</h1>
        @if ($form->description)<p class="mt-2 text-slate-500">{{ $form->description }}</p>@endif
        @if (session('submitted'))
            <div class="mt-6 rounded-xl border border-green-200 bg-green-50 p-5 text-green-800" role="status">
                <h2 class="font-semibold">Response submitted successfully!</h2>
                <p class="mt-2">{{ session('submitted') }}</p>
                <p class="mt-3 text-sm">Returning to form in 5 seconds...</p>
            </div>

            <script>
                setTimeout(function () {
                    window.location.replace(@json(route('templates.index')));
                }, 5000);
            </script>
        @else
        <form method="POST" action="{{ route('forms.submit', $form->slug) }}" class="mt-6" novalidate>
            @csrf
            @foreach ($form->fields as $f)
                @php($name = "f[{$f['id']}]")
                @php($val = old("f.{$f['id']}", $prefill[\Illuminate\Support\Str::snake($f['label'] ?? '')] ?? ''))
                @php($opts = $f['options'] ?? [])
                @php($req = ! empty($f['required']) ? 'required' : '')
                <div class="mb-5">
                    @if ($f['type'] === 'heading')
                        <h2 class="text-lg font-semibold">{{ $f['label'] }}</h2>
                    @elseif ($f['type'] === 'paragraph')
                        <p class="text-sm text-slate-500">{{ $f['help'] ?? '' }}</p>
                    @elseif ($f['type'] === 'hidden')
                        <input type="hidden" name="{{ $name }}" value="{{ $val }}">
                    @else
                        <label class="mb-1.5 block text-sm font-medium" for="{{ $f['id'] }}">{{ $f['label'] }} @if($req)<span class="text-red-600">*</span>@endif</label>
                        @switch($f['type'])
                            @case('textarea')<textarea id="{{ $f['id'] }}" name="{{ $name }}" rows="4" {{ $req }} placeholder="{{ $f['placeholder'] ?? '' }}" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">{{ $val }}</textarea>@break
                            @case('select')
                                <select id="{{ $f['id'] }}" name="{{ $name }}" {{ $req }} class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                                    <option value="">Choose an option</option>
                                    @foreach ($opts as $o)<option @selected($val === $o)>{{ $o }}</option>@endforeach
                                </select>@break
                            @case('radio')
                            @case('checkbox')
                                @foreach ($opts as $o)
                                    <label class="mb-1 flex items-center gap-2 text-sm">
                                        <input type="{{ $f['type'] }}" name="{{ $name }}{{ $f['type']==='checkbox' ? '[]' : '' }}" value="{{ $o }}" class="h-4 w-4"> {{ $o }}
                                    </label>
                                @endforeach @break
                            @case('rating')
                                <select id="{{ $f['id'] }}" name="{{ $name }}" {{ $req }} class="rounded-md border border-slate-300 px-3 py-2 text-sm"><option value="">Rate</option>@foreach (range(1,5) as $n)<option>{{ $n }}</option>@endforeach</select>@break
                            @case('scale')
                                <select id="{{ $f['id'] }}" name="{{ $name }}" {{ $req }} class="rounded-md border border-slate-300 px-3 py-2 text-sm"><option value="">Select</option>@foreach (range(0,10) as $n)<option>{{ $n }}</option>@endforeach</select>@break
                            @case('address')
                            @case('signature')
                                <input id="{{ $f['id'] }}" name="{{ $name }}" value="{{ $val }}" {{ $req }} class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">@break
                            @case('file')
                                <p class="text-sm text-slate-500">File uploads need your storage setup. Wire this input to your uploader.</p>@break
                            @default
                                <input id="{{ $f['id'] }}" name="{{ $name }}" value="{{ $val }}" {{ $req }} placeholder="{{ $f['placeholder'] ?? '' }}"
                                       type="{{ ['phone'=>'tel'][$f['type']] ?? (in_array($f['type'],['email','number','date','time','url']) ? $f['type'] : 'text') }}"
                                       class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm">
                        @endswitch
                        @if (! empty($f['help']))<p class="mt-1 text-xs text-slate-500">{{ $f['help'] }}</p>@endif
                        @error("f.{$f['id']}")<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    @endif
                </div>
            @endforeach
            <button class="rounded-md px-5 py-2.5 text-sm font-semibold text-white" style="background: {{ $color }}">{{ $form->settings['submit_text'] ?? 'Submit' }}</button>
        </form>
        @endif
    </div>
</main>
<script>
(function () {
    var f = document.querySelector('form'); if (!f) return;
    try { if (window.self === window.top) return; } catch (e) {}
    var u = new URLSearchParams(location.search).get('ref') || document.referrer || (location.ancestorOrigins && location.ancestorOrigins[0]) || '';
    if (u.indexOf(location.origin) === 0) u = '';
    var i = document.createElement('input'); i.type = 'hidden'; i.name = '_source'; i.value = u; f.appendChild(i);
})();
</script>
</body>
</html>
