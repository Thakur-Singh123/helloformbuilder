@guest
<style>
    .gate-field{width:100%;border:1px solid #d9e6fa;border-radius:.5rem;padding:.65rem .9rem;font-size:.875rem;background:#fff}
    .gate-field:focus{outline:none;border-color:#1e88ff;box-shadow:0 0 0 3px rgba(30,136,255,.2)}
    .gate-field.err{border-color:#f87171}
</style>

<div id="templateModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4"
     role="dialog" aria-modal="true" aria-labelledby="gateTitle">
    <div class="relative w-full max-w-md rounded-xl bg-white p-6 shadow-xl">

        <button type="button" onclick="closeTemplateModal()" aria-label="Close"
                class="absolute right-4 top-3 text-2xl leading-none text-slate-500 hover:text-black">&times;</button>

        <h2 id="gateTitle" class="text-2xl font-bold text-brand-navy">Continue with this template</h2>
        <p class="mt-1 text-sm text-slate-500">Enter your email. We'll log you in or create your account.</p>

        <form method="POST" action="{{ route('template.gate.check') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="template_url" id="templateUrl" value="{{ old('template_url') }}">

            <div>
                <label class="mb-1 block text-sm font-medium text-brand-navy">Email</label>
                <input type="email" name="email" id="templateEmail" value="{{ old('email') }}" required
                       placeholder="you@example.com"
                       class="gate-field @error('email') err @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-grad w-full rounded-lg px-4 py-2.5 text-sm font-semibold text-white">Continue</button>
        </form>
    </div>
</div>

<script>
    function openTemplateModal(url) {
        document.getElementById('templateUrl').value = url;
        var m = document.getElementById('templateModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
        document.body.style.overflow = 'hidden';
        setTimeout(function () { document.getElementById('templateEmail').focus(); }, 50);
    }

    function closeTemplateModal() {
        var m = document.getElementById('templateModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.getElementById('templateModal').addEventListener('click', function (e) {
        if (e.target === this) closeTemplateModal();
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeTemplateModal();
    });

    @if ($errors->has('email') && old('template_url'))
        document.addEventListener('DOMContentLoaded', function () {
            openTemplateModal(@json(old('template_url')));
        });
    @endif
</script>
@endguest