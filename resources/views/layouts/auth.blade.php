<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | HelloFormBuilder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{brand:{navy:'#0e2a6b',blue:'#1e88ff',deep:'#1747c9',sky:'#f1f7ff',line:'#d9e6fa'}}}}}</script>
    <style>
        .btn-grad{background-image:linear-gradient(135deg,#1747c9,#1e9bff)}
        .btn-grad:hover{filter:brightness(1.08)}
        .field{width:100%;border:1px solid #d9e6fa;border-radius:.5rem;padding:.65rem .9rem;font-size:.875rem;background:#fff}
        .field:focus{outline:none;border-color:#1e88ff;box-shadow:0 0 0 3px rgba(30,136,255,.2)}
        .field-err{border-color:#f87171}
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-brand-sky to-[#e3edfd] text-slate-800 antialiased">
<div class="mx-auto flex min-h-screen max-w-5xl items-center px-4 py-8">
    <div class="grid w-full overflow-hidden rounded-2xl border border-brand-line bg-white shadow-xl shadow-blue-900/10 md:grid-cols-5">

        {{-- Left brand panel --}}
        <aside class="hidden flex-col justify-between bg-gradient-to-br from-brand-navy to-brand-deep p-8 text-white md:col-span-2 md:flex">
            <a href="{{ route('templates.index') }}" class="text-xl font-semibold">Hello<span class="text-sky-300">Form</span>Builder</a>
            <div>
                <h2 class="text-3xl font-bold leading-tight">Build forms in minutes, not hours.</h2>
                <ul class="mt-6 space-y-3 text-sm text-blue-100">
                    <li class="flex gap-2"><span>✓</span> Ready-made templates for every kind of work</li>
                    <li class="flex gap-2"><span>✓</span> Drag &amp; drop editor with autosave</li>
                    <li class="flex gap-2"><span>✓</span> Publish, share a link and collect responses</li>
                </ul>
            </div>
            <p class="text-xs text-blue-200">© {{ date('Y') }} HelloFormBuilder</p>
        </aside>

        {{-- Form side --}}
        <main class="p-6 sm:p-10 md:col-span-3">

            {{-- Back to templates --}}
            <a href="{{ route('templates.index') }}"
               class="mb-6 inline-flex items-center gap-1.5 rounded-lg border border-brand-line px-3 py-1.5 text-sm font-medium text-brand-deep hover:border-brand-blue hover:bg-brand-sky">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to templates
            </a>

            {{-- Mobile logo --}}
            <a href="{{ route('templates.index') }}" class="mb-6 block text-lg font-semibold text-brand-navy md:hidden">Hello<span class="text-brand-blue">Form</span>Builder</a>

            @if(session('info'))    <div class="mb-5 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">{{ session('info') }}</div> @endif
            @if(session('success')) <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div> @endif
            @if(session('error'))   <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div> @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
document.querySelectorAll('[data-toggle-pw]').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var input = document.getElementById(btn.dataset.togglePw);
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.textContent = show ? 'Hide' : 'Show';
    });
});
</script>
</body>
</html>