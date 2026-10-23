<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $template->title }} template | {{ config('app.name') }}</title>
    <meta name="description" content="{{ $template->description }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{brand:{navy:'#0e2a6b',blue:'#1e88ff',deep:'#1747c9',sky:'#f1f7ff',line:'#d9e6fa'}}}}}</script>
    <style>.btn-grad{background-image:linear-gradient(135deg,#1747c9,#1e9bff)}.btn-grad:hover{filter:brightness(1.08)}</style>
</head>
<body class="bg-brand-sky text-slate-800 antialiased">
<div class="mx-auto max-w-5xl px-4 py-10">
    <a href="{{ route('templates.index', ['category' => $template->category->slug]) }}" class="text-sm text-slate-500 underline">
        All {{ $template->category->name }} templates
    </a>

    <div class="mt-4 grid gap-10 lg:grid-cols-[1fr_320px]">
        <section class="rounded-xl border border-t-4 border-brand-line bg-white p-6 sm:p-8" style="border-top-color:{{ $template->category->accent }}" aria-label="Form preview">
            <h1 class="mb-6 text-2xl font-semibold text-brand-navy">{{ $template->title }}</h1>
            @foreach ($template->fields as $field)
                @include('templates._field', ['field' => $field])
            @endforeach
            <button disabled class="rounded-md bg-brand-blue/40 px-5 py-2 text-sm font-medium text-white">Submit</button>
        </section>

        <aside class="lg:sticky lg:top-6 lg:self-start">
            <p class="text-sm text-slate-500">{{ $template->description }}</p>
            <p class="mt-3 text-xs text-slate-500">{{ $template->fields_count }} fields · {{ number_format($template->uses_count) }} uses</p>

            @auth
                <form method="POST" action="{{ route('templates.use', $template) }}" class="mt-5">@csrf
                    <button class="w-full rounded-md btn-grad px-4 py-2.5 text-sm font-medium text-white">Use this template</button>
                </form>
            @else
                <a href="{{ route('templates.use', $template) }}" class="mt-5 block rounded-md btn-grad px-4 py-2.5 text-center text-sm font-medium text-white">Sign in to use this template</a>
            @endauth

            @if ($related->isNotEmpty())
                <h2 class="mt-8 text-sm font-semibold">More in {{ $template->category->name }}</h2>
                <ul class="mt-2 space-y-1">
                    @foreach ($related as $r)
                        <li><a class="text-sm underline" href="{{ route('templates.show', $r) }}">{{ $r->title }}</a></li>
                    @endforeach
                </ul>
            @endif
        </aside>
    </div>
</div>
</body>
</html>
