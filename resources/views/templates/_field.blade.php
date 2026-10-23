{{-- Read-only field renderer used by the preview page. $field is one entry of template->fields --}}
@php($type = $field['type'] ?? 'text')
@php($opts = $field['options'] ?? [])
<div class="mb-5">
    @if ($type === 'heading')
        <h3 class="text-lg font-semibold">{{ $field['label'] }}</h3>
    @elseif ($type === 'paragraph')
        <p class="text-sm text-slate-500">{{ $field['label'] }}</p>
    @elseif ($type !== 'hidden')
        <label class="mb-1.5 block text-sm font-medium text-brand-navy">
            {{ $field['label'] }} @if (!empty($field['required']))<span class="text-red-600" aria-label="required">*</span>@endif
        </label>

        @switch($type)
            @case('textarea')
                <textarea rows="4" disabled class="w-full rounded-md border border-brand-line bg-white px-3 py-2 text-sm"></textarea>
                @break
            @case('select')
                <select disabled class="w-full rounded-md border border-brand-line bg-white px-3 py-2 text-sm">
                    <option>Select…</option>
                    @foreach ($opts as $o)<option>{{ $o }}</option>@endforeach
                </select>
                @break
            @case('radio')
            @case('checkbox')
                <div class="space-y-1.5">
                    @foreach ($opts as $o)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="{{ $type }}" disabled class="h-4 w-4"> {{ $o }}
                        </label>
                    @endforeach
                </div>
                @break
            @case('rating')
                <div class="flex gap-1 text-2xl text-brand-blue/50" aria-hidden="true">★★★★★</div>
                @break
            @case('scale')
                <div class="flex flex-wrap gap-1.5">
                    @foreach (range(0, 10) as $n)
                        <span class="flex h-8 w-8 items-center justify-center rounded border border-brand-line text-xs">{{ $n }}</span>
                    @endforeach
                </div>
                @break
            @case('file')
                <div class="rounded-md border border-dashed border-brand-line px-3 py-4 text-center text-sm text-slate-500">Choose a file</div>
                @break
            @case('signature')
                <div class="h-24 rounded-md border border-dashed border-brand-line"></div>
                @break
            @case('address')
                <div class="grid gap-2 sm:grid-cols-2">
                    <input disabled placeholder="Street" class="sm:col-span-2 rounded-md border border-brand-line px-3 py-2 text-sm">
                    <input disabled placeholder="City" class="rounded-md border border-brand-line px-3 py-2 text-sm">
                    <input disabled placeholder="Postal code" class="rounded-md border border-brand-line px-3 py-2 text-sm">
                </div>
                @break
            @default
                <input type="{{ in_array($type, ['email','date','time','number','url']) ? $type : 'text' }}" disabled
                       class="w-full rounded-md border border-brand-line bg-white px-3 py-2 text-sm">
        @endswitch
    @endif
</div>
