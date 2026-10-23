@extends('layouts.site')

@section('title', 'Templates | HelloFormBuilder')

@section('meta')
    <meta name="description" content="Browse {{ $totalTemplates }} ready-made form templates. Pick one, customize it, and publish in minutes.">
@endsection

@section('content')

<section class="border-b border-brand-line bg-gradient-to-b from-white to-blue-100/70">
    <div class="mx-auto max-w-6xl px-4 py-8 sm:py-12">
        <h1 class="max-w-2xl text-3xl font-semibold tracking-tight text-brand-navy sm:text-5xl">Form templates for <span class="text-brand-blue">every kind</span> of work</h1>
        <p class="mt-3 max-w-xl text-slate-500">Start from one of {{ number_format($totalTemplates) }} ready-made forms. Change anything, then embed it on your site.</p>

        <form method="GET" action="{{ route('templates.index') }}" class="mt-8 flex max-w-xl gap-2" role="search">
            @if ($activeCategory)<input type="hidden" name="category" value="{{ $activeCategory }}">@endif
            <input type="search" name="q" value="{{ $search }}" placeholder="Search by form name or field, e.g. “appointment”"
                   class="w-full rounded-md border border-brand-line bg-white px-4 py-2.5 text-sm focus:border-brand-blue focus:outline-none focus:ring-2 focus:ring-brand-blue/25">
            <button class="rounded-md btn-grad px-5 py-2.5 text-sm font-medium text-white">Search</button>
        </form>
    </div>
</section>

<main class="mx-auto max-w-6xl px-4 py-10">
    <div class="grid gap-10 lg:grid-cols-[220px_1fr]">

        {{-- Industries --}}
        <nav aria-label="Industries" class="lg:sticky lg:top-24 lg:self-start">
            <h2 class="mb-3 text-sm font-semibold text-brand-navy">Industries</h2>
            <ul class="flex flex-wrap gap-2 lg:block lg:space-y-0.5">
                <li>
                    <a href="{{ route('templates.index', array_filter(['q' => $search])) }}"
                       class="block rounded-md px-3 py-1.5 text-sm {{ ! $activeCategory ? 'btn-grad text-white' : 'bg-white text-brand-navy hover:bg-white lg:bg-transparent' }}">
                        Popular
                    </a>
                </li>
                @foreach ($categories as $c)
                    @continue($c->templates_count === 0 && $activeCategory !== $c->slug)
                    <li>
                        <a href="{{ route('templates.index', array_filter(['category' => $c->slug, 'q' => $search])) }}"
                           class="flex items-center justify-between gap-3 rounded-md px-3 py-1.5 text-sm {{ $activeCategory === $c->slug ? 'btn-grad text-white' : 'bg-white text-brand-navy hover:bg-white lg:bg-transparent' }}">
                            <span class="flex items-center gap-2"><i class="h-2 w-2 rounded-full" style="background:{{ $c->accent }}"></i>{{ $c->name }}</span>
                            <span class="text-xs opacity-60">{{ $c->templates_count }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- Results --}}
        <section aria-live="polite">
            <h2 class="mb-4 text-lg font-semibold">
                @if ($search) Results for “{{ $search }}”
                @elseif ($activeCategory) {{ optional($categories->firstWhere('slug', $activeCategory))->name }} templates
                @else Popular starting points @endif
                <span class="ml-1 text-sm font-normal text-slate-500">{{ $templates->total() }}</span>
            </h2>

            @forelse ($templates as $t)
                @if ($loop->first)<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">@endif

                <article class="flex flex-col rounded-xl border border-t-4 border-brand-line bg-white p-5" style="border-top-color:{{ $t->category->accent }}">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <span class="flex h-6 w-7 items-center justify-center rounded text-white font-semibold" style="background:{{ $t->category->accent }}">{{ $t->category->code }}</span>
                        {{ $t->category->name }}
                    </div>
                    <h3 class="mt-3 font-semibold text-brand-navy">{{ $t->title }}</h3>
                    <p class="mt-1 flex-1 text-sm text-slate-500">{{ $t->description }}</p>
                    <p class="mt-3 text-xs text-slate-500">{{ $t->fields_count }} fields</p>

                 <div class="mt-4 flex gap-2">
                    <a href="{{ route('templates.show', $t) }}"
                    class="rounded-md border border-brand-line px-3 py-1.5 text-sm hover:border-brand-blue">
                        Preview
                    </a>

                    @auth
                        <form method="POST" action="{{ route('templates.use', $t) }}">
                            @csrf
                            <button type="submit"
                                    class="rounded-md btn-grad px-3 py-1.5 text-sm font-medium text-white">
                                Use template
                            </button>
                        </form>
                    @else
                        <button type="button"
                                onclick="openTemplateModal('{{ route('templates.use', $t) }}')"
                                class="rounded-md btn-grad px-3 py-1.5 text-sm font-medium text-white">
                            Use template
                        </button>
                    @endauth
                </div>

                </article>

                @if ($loop->last)</div>@endif
            @empty
                <div class="rounded-lg border border-dashed border-brand-line bg-white p-10 text-center">
                    <p class="font-medium">No templates match that search.</p>
                    <p class="mt-1 text-sm text-slate-500">Try a shorter word, or <a class="underline" href="{{ route('templates.index') }}">browse the popular templates</a>.</p>
                </div>
            @endforelse

            <div class="mt-8">{{ $templates->links() }}</div>
        </section>
    </div>
</main>
@include('templates.gate-modal')
@endsection