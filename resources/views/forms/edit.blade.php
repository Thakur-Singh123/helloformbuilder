<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $form->title }} | Editor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{brand:{navy:'#0e2a6b',blue:'#1e88ff',deep:'#1747c9',sky:'#f1f7ff',line:'#d9e6fa'}}}}}</script>
    <style>[x-cloak]{display:none!important}.btn-grad{background-image:linear-gradient(135deg,#1747c9,#1e9bff)}.btn-grad:hover{filter:brightness(1.08)}</style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
</head>
<body class="h-screen overflow-hidden bg-brand-sky text-slate-800 antialiased" x-data="builder()" x-cloak>

@php
    $formData = [
        'title'       => $form->title,
        'description' => $form->description,
        'fields'      => $form->fields,
        'settings'    => $form->settings ?? new \stdClass,
        'status'      => $form->status,
        'publicUrl'   => $form->status === 'published' ? route('forms.public', $form->slug) : null,
        'urls'        => [
            'update'    => route('forms.update', $form),
            'publish'   => route('forms.publish', $form),
            'templates' => route('templates.index'),
        ],
    ];
@endphp

<script>
window.FORM = @json($formData);
window.FORM.csrf = document.querySelector('meta[name=csrf-token]').content;
</script>

<div class="flex h-full flex-col">
    {{-- Top bar --}}
    <header class="flex h-14 shrink-0 items-center gap-3 border-b border-brand-line bg-white px-4">
        <a :href="D.urls.templates" class="flex items-center gap-2 text-base font-semibold text-brand-navy" title="Back to templates">
            <img src="{{ asset('images/helloformbuilder-mark.png') }}" alt="" class="h-8 w-auto"><span>Hello<span class="text-brand-blue">Form</span>Builder</span>
        </a>
        <span class="text-slate-300">/</span>
        <input x-model="title" aria-label="Form title" class="min-w-0 flex-1 rounded px-2 py-1 text-sm font-medium text-brand-navy hover:bg-brand-sky focus:bg-brand-sky focus:outline-none sm:max-w-sm">
        <span class="ml-auto text-xs" :class="state==='error' ? 'text-red-600' : 'text-slate-500'"
              x-text="{saved:'All changes saved',saving:'Saving…',unsaved:'Unsaved changes',error:'Save failed. Retrying on next edit'}[state]"></span>
        <div class="flex gap-1">
            <button @click="undo()" :disabled="!history.length" class="rounded-md border border-brand-line px-2.5 py-1.5 text-sm disabled:opacity-40" title="Undo">↶</button>
            <button @click="redo()" :disabled="!future.length" class="rounded-md border border-brand-line px-2.5 py-1.5 text-sm disabled:opacity-40" title="Redo">↷</button>
        </div>
        <button @click="preview=!preview; selected=null" class="rounded-md border border-brand-line px-3 py-1.5 text-sm hover:border-brand-blue" x-text="preview ? 'Back to editing' : 'Preview'"></button>
    </header>

    {{-- Publish banner --}}
    <div class="flex shrink-0 items-center gap-3 bg-gradient-to-r from-brand-navy to-brand-deep px-4 py-3 text-white">
        <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold" x-text="status==='published' ? 'Your form is live' : (fields.length ? 'Your form is ready to publish' : 'Add your first field')"></p>
            <p class="truncate text-xs text-blue-100" x-text="fields.length + ' fields. Everything saves automatically as you work. Publish to go live, share your link, and start collecting responses.'"></p>
        </div>
        <button @click="publish()" :disabled="!fields.length || publishing" class="shrink-0 rounded-md bg-white px-4 py-1.5 text-sm font-semibold text-brand-navy disabled:opacity-50"
                x-text="publishing ? 'Publishing…' : (status==='published' ? 'Get link' : 'Publish form')"></button>
    </div>

    <div class="flex min-h-0 flex-1">
        {{-- Palette --}}
        <aside x-show="!preview" class="hidden w-72 shrink-0 overflow-y-auto border-r border-brand-line bg-white p-4 md:block">
            <h2 class="font-semibold text-brand-navy">Form fields</h2>
            <p class="mb-4 text-sm text-slate-500">Click a field, or drag it into the form.</p>
            <div class="space-y-2">
                <template x-for="t in types" :key="t.t">
                    <button draggable="true" @dragstart="dragType=t.t; dragFrom=null" @dragend="dragType=null" @click="add(t.t)"
                            class="flex w-full items-center gap-3 rounded-lg border border-brand-line bg-white px-3 py-2.5 text-left text-sm font-medium hover:border-brand-blue">
                        <span class="btn-grad flex h-8 w-8 items-center justify-center rounded-md text-xs font-bold text-white" x-text="t.i"></span>
                        <span x-text="t.n"></span>
                    </button>
                </template>
            </div>
        </aside>

        {{-- Canvas --}}
        <main class="min-w-0 flex-1 overflow-y-auto p-4 sm:p-8" @click.self="selected=null">
            <div class="mx-auto max-w-3xl space-y-4">
                <section class="rounded-xl border border-brand-line bg-white p-5" @click="selected=null">
                    <textarea x-model="description" rows="2" placeholder="Add a short description for people filling this out"
                              class="w-full resize-none rounded-md border border-brand-line px-3 py-2 text-base focus:border-brand-blue focus:outline-none focus:ring-2 focus:ring-brand-blue/25" :readonly="preview"></textarea>
                </section>

                <section class="rounded-xl border border-brand-line bg-white p-5" @dragover.prevent @drop.prevent="drop(fields.length)">
                    <div class="mb-3 flex items-baseline justify-between">
                        <h2 class="text-lg font-semibold text-brand-navy">Form fields</h2>
                        <span class="text-sm text-slate-500" x-text="fields.length + ' fields'"></span>
                    </div>

                    <div x-show="!fields.length" class="rounded-lg border-2 border-dashed border-brand-line p-10 text-center text-sm text-slate-500">
                        Drag a field here, or click one in the list.
                    </div>

                    <template x-for="(f,i) in fields" :key="f.id">
                        <div class="group relative mb-3 rounded-xl border bg-white p-4 transition"
                             :class="[selected===f.id && !preview ? 'border-brand-blue ring-2 ring-brand-blue/25' : 'border-brand-line', dragOver===i ? 'border-t-4 border-t-brand-blue' : '']"
                             :draggable="!preview" @dragstart="dragFrom=i; dragType=null" @dragend="dragFrom=null; dragOver=null"
                             @dragover.prevent="dragOver=i" @dragleave="dragOver=null" @drop.prevent.stop="drop(i)"
                             @click.stop="!preview && (selected=f.id)">

                            <div class="mb-2 flex items-start justify-between gap-2" x-show="f.type!=='hidden'">
                                <label class="text-sm font-medium text-brand-navy" :class="f.type==='heading' && 'text-lg font-semibold'">
                                    <span x-text="f.label || 'Untitled field'"></span><span x-show="f.required && f.type!=='heading' && f.type!=='paragraph'" class="ml-0.5 text-red-600">*</span>
                                </label>
                                <div x-show="!preview" class="flex shrink-0 gap-0.5 text-slate-500">
                                    <button class="rounded px-1.5 hover:bg-brand-sky disabled:opacity-30" :disabled="i===0" @click.stop="move(i,-1)" title="Move up">↑</button>
                                    <button class="rounded px-1.5 hover:bg-brand-sky disabled:opacity-30" :disabled="i===fields.length-1" @click.stop="move(i,1)" title="Move down">↓</button>
                                    <button class="rounded px-1.5 hover:bg-brand-sky" @click.stop="dup(i)" title="Duplicate">⧉</button>
                                    <button class="rounded px-1.5 text-red-500 hover:bg-red-50" @click.stop="remove(i)" title="Delete">🗑</button>
                                </div>
                            </div>

                            <div :class="!preview && 'pointer-events-none'">
                                <template x-if="['text','email','phone','url','number','date','time'].includes(f.type)">
                                    <input class="w-full rounded-md border border-brand-line px-3 py-2 text-sm" :type="inputType(f.type)" :placeholder="f.placeholder">
                                </template>
                                <template x-if="f.type==='textarea'"><textarea rows="4" class="w-full rounded-md border border-brand-line px-3 py-2 text-sm" :placeholder="f.placeholder"></textarea></template>
                                <template x-if="f.type==='select'">
                                    <select class="w-full rounded-md border border-brand-line px-3 py-2 text-sm"><option>Choose an option</option><template x-for="o in (f.options||[])"><option x-text="o"></option></template></select>
                                </template>
                                <template x-if="f.type==='radio' || f.type==='checkbox'">
                                    <div class="space-y-1.5"><template x-for="o in (f.options||[])"><label class="flex items-center gap-2 text-sm"><input :type="f.type" class="h-4 w-4"> <span x-text="o"></span></label></template></div>
                                </template>
                                <template x-if="f.type==='rating'"><div class="text-2xl tracking-widest text-brand-blue/40">★★★★★</div></template>
                                <template x-if="f.type==='scale'"><div class="flex flex-wrap gap-1.5"><template x-for="n in 11"><span class="flex h-8 w-8 items-center justify-center rounded border border-brand-line text-xs" x-text="n-1"></span></template></div></template>
                                <template x-if="f.type==='file'"><div class="rounded-md border border-dashed border-brand-line px-3 py-5 text-center text-sm text-slate-500">Choose a file</div></template>
                                <template x-if="f.type==='signature'"><div class="h-24 rounded-md border border-dashed border-brand-line"></div></template>
                                <template x-if="f.type==='address'"><div class="grid gap-2 sm:grid-cols-2"><input class="rounded-md border border-brand-line px-3 py-2 text-sm sm:col-span-2" placeholder="Street"><input class="rounded-md border border-brand-line px-3 py-2 text-sm" placeholder="City"><input class="rounded-md border border-brand-line px-3 py-2 text-sm" placeholder="Postal code"></div></template>
                                <template x-if="f.type==='paragraph'"><p class="text-sm text-slate-500" x-text="f.help || 'Add your text in the field settings.'"></p></template>
                                <template x-if="f.type==='hidden'"><p class="text-xs italic text-slate-500">Hidden field: <span x-text="f.label"></span></p></template>
                            </div>
                            <p x-show="f.help && f.type!=='paragraph'" class="mt-1.5 text-xs text-slate-500" x-text="f.help"></p>
                        </div>
                    </template>

                    <button x-show="preview" class="btn-grad mt-2 rounded-md px-5 py-2 text-sm font-semibold text-white" :style="'background:'+settings.color" x-text="settings.submit_text || 'Submit'"></button>
                </section>
            </div>
        </main>

        {{-- Settings --}}
        <aside x-show="!preview" class="hidden w-80 shrink-0 overflow-y-auto border-l border-brand-line bg-white p-5 lg:block">
            {{-- Field settings --}}
            <template x-if="current">
                <div>
                    <button class="mb-3 text-sm text-brand-deep underline" @click="selected=null">Form settings</button>
                    <h2 class="mb-4 font-semibold text-brand-navy" x-text="typeName(current.type) + ' settings'"></h2>

                    <label class="mb-1 block text-sm font-medium">Label</label>
                    <input x-model="current.label" class="mb-4 w-full rounded-md border border-brand-line px-3 py-2 text-sm focus:border-brand-blue focus:outline-none">

                    <template x-if="!['heading','paragraph','hidden','select','radio','checkbox','rating','scale','file','signature','address'].includes(current.type)">
                        <div><label class="mb-1 block text-sm font-medium">Placeholder</label>
                        <input x-model="current.placeholder" class="mb-4 w-full rounded-md border border-brand-line px-3 py-2 text-sm focus:border-brand-blue focus:outline-none"></div>
                    </template>

                    <template x-if="['select','radio','checkbox'].includes(current.type)">
                        <div><label class="mb-1 block text-sm font-medium">Options <span class="font-normal text-slate-500">(one per line)</span></label>
                        <textarea rows="6" :value="(current.options||[]).join('\n')" @input="current.options=$event.target.value.split('\n')" @blur="current.options=(current.options||[]).map(o=>o.trim()).filter(Boolean)"
                                  class="mb-4 w-full rounded-md border border-brand-line px-3 py-2 text-sm focus:border-brand-blue focus:outline-none"></textarea></div>
                    </template>

                    <label class="mb-1 block text-sm font-medium" x-text="current.type==='paragraph' ? 'Text' : 'Help text'"></label>
                    <textarea x-model="current.help" rows="2" class="mb-4 w-full rounded-md border border-brand-line px-3 py-2 text-sm focus:border-brand-blue focus:outline-none"></textarea>

                    <label class="mb-5 flex items-center gap-2 text-sm" x-show="!['heading','paragraph','hidden'].includes(current.type)">
                        <input type="checkbox" x-model="current.required" class="h-4 w-4"> Required field
                    </label>

                    <div class="flex gap-2 border-t border-brand-line pt-4">
                        <button class="rounded-md border border-brand-line px-3 py-1.5 text-sm hover:border-brand-blue" @click="dup(fields.indexOf(current))">Duplicate</button>
                        <button class="rounded-md border border-red-200 px-3 py-1.5 text-sm text-red-600 hover:bg-red-50" @click="remove(fields.indexOf(current))">Delete</button>
                    </div>
                </div>
            </template>

            {{-- Form settings --}}
            <template x-if="!current">
                <div>
                    <h2 class="mb-4 font-semibold text-brand-navy">Form settings</h2>
                    <div class="mb-5 grid grid-cols-4 gap-1 rounded-lg bg-brand-sky p-1 text-xs">
                        <template x-for="t in ['General','Style','Notify','Prefill']">
                            <button class="rounded-md py-1.5 font-medium" :class="tab===t ? 'bg-white text-brand-navy shadow-sm' : 'text-slate-500'" @click="tab=t" x-text="t"></button>
                        </template>
                    </div>

                    <div x-show="tab==='General'">
                        <label class="mb-1 block text-sm font-medium">Submit button text</label>
                        <input x-model="settings.submit_text" class="mb-4 w-full rounded-md border border-brand-line px-3 py-2 text-sm focus:border-brand-blue focus:outline-none">
                        <label class="mb-1 block text-sm font-medium">Message after submitting</label>
                        <textarea x-model="settings.success_message" rows="3" class="w-full rounded-md border border-brand-line px-3 py-2 text-sm focus:border-brand-blue focus:outline-none"></textarea>
                    </div>
                    <div x-show="tab==='Style'">
                        <label class="mb-1 block text-sm font-medium">Accent color</label>
                        <div class="flex items-center gap-3"><input type="color" x-model="settings.color" class="h-10 w-14 rounded border border-brand-line"><code class="text-sm" x-text="settings.color"></code></div>
                        <p class="mt-2 text-xs text-slate-500">Used for the submit button on the public form.</p>
                    </div>
                    <div x-show="tab==='Notify'">
                        <label class="mb-3 flex items-center gap-2 text-sm"><input type="checkbox" x-model="settings.notify_enabled" class="h-4 w-4"> Email me for each response</label>
                        <input type="email" x-model="settings.notify_email" :disabled="!settings.notify_enabled" placeholder="you@company.com" class="w-full rounded-md border border-brand-line px-3 py-2 text-sm disabled:bg-brand-sky">
                    </div>
                    <div x-show="tab==='Prefill'">
                        <label class="flex items-start gap-2 text-sm"><input type="checkbox" x-model="settings.prefill" class="mt-0.5 h-4 w-4"> Allow prefilling fields from the link, e.g. <code class="text-xs">?email=ana@example.com</code></label>
                    </div>
                </div>
            </template>
        </aside>
    </div>

    {{-- Mobile palette --}}
    <div x-show="!preview" class="flex shrink-0 gap-2 overflow-x-auto border-t border-brand-line bg-white p-2 md:hidden">
        <template x-for="t in types" :key="t.t"><button @click="add(t.t)" class="shrink-0 rounded-md border border-brand-line px-3 py-1.5 text-xs font-medium" x-text="'+ '+t.n"></button></template>
    </div>
</div>

{{-- Publish dialog --}}
<div x-show="showLink" class="fixed inset-0 z-50 flex items-center justify-center bg-brand-navy/60 p-4" @keydown.escape.window="showLink=false">
    <div class="w-full max-w-md rounded-xl border-t-4 border-brand-blue bg-white p-6" @click.outside="showLink=false">
        <h2 class="text-lg font-semibold text-brand-navy">Your form is live</h2>
        <p class="mt-1 text-sm text-slate-500">Share this link, or embed it on your website.</p>
        <div class="mt-4 flex gap-2">
            <input readonly :value="publicUrl" class="min-w-0 flex-1 rounded-md border border-brand-line px-3 py-2 text-sm">
            <button class="btn-grad rounded-md px-3 py-2 text-sm font-medium text-white" @click="navigator.clipboard.writeText(publicUrl); copied=true" x-text="copied ? 'Copied' : 'Copy'"></button>
        </div>
        <p class="mt-4 text-xs text-slate-500">Embed code</p>
        <code class="mt-1 block break-all rounded-md bg-brand-sky p-2 text-xs" x-text="`<iframe src=&quot;${publicUrl}&quot; width=&quot;100%&quot; height=&quot;700&quot; frameborder=&quot;0&quot;></iframe>`"></code>
        <div class="mt-5 flex justify-end gap-2">
            <a :href="publicUrl" target="_blank" class="rounded-md border border-brand-line px-3 py-1.5 text-sm">Open form</a>
            <button class="rounded-md border border-brand-line px-3 py-1.5 text-sm" @click="showLink=false">Close</button>
        </div>
    </div>
</div>

<script>
function builder() {
    const D = window.FORM;
    const types = [
        {t:'text',n:'Text Input',i:'T'},{t:'textarea',n:'Textarea',i:'¶'},{t:'email',n:'Email',i:'@'},{t:'number',n:'Number',i:'#'},
        {t:'phone',n:'Phone',i:'☎'},{t:'date',n:'Date',i:'▦'},{t:'time',n:'Time',i:'◔'},{t:'file',n:'File Upload',i:'⇪'},
        {t:'select',n:'Dropdown',i:'▾'},{t:'radio',n:'Radio Button',i:'◉'},{t:'checkbox',n:'Checkbox',i:'☑'},{t:'rating',n:'Rating',i:'★'},
        {t:'scale',n:'Scale 0–10',i:'0-10'},{t:'url',n:'Website URL',i:'↗'},{t:'address',n:'Address',i:'⌂'},{t:'signature',n:'Signature',i:'✍'},
        {t:'heading',n:'Heading',i:'H'},{t:'paragraph',n:'Paragraph',i:'¶¶'}
    ];
    const uid = () => Math.random().toString(36).slice(2, 10);

    return {
        D, types,
        title: D.title, description: D.description || '', status: D.status, publicUrl: D.publicUrl,
        fields: D.fields.map(f => ({ ...f, id: f.id || uid(), required: !!f.required })),
        settings: Object.assign({ submit_text: 'Submit', success_message: 'Thanks! Your response has been recorded.', color: '#1e88ff', notify_enabled: false, notify_email: '', prefill: true }, Array.isArray(D.settings) ? {} : D.settings),
        selected: null, tab: 'General', preview: false, state: 'saved', publishing: false, showLink: false, copied: false,
        history: [], future: [], last: '', dragType: null, dragFrom: null, dragOver: null, _save: null, _track: null,

        init() {
            this.last = this.snap();
            ['fields', 'title', 'description', 'settings'].forEach(k => this.$watch(k, () => this.changed()));
        },
        get current() { return this.fields.find(f => f.id === this.selected) || null; },
        snap() { return JSON.stringify({ title: this.title, description: this.description, fields: this.fields, settings: this.settings }); },
        typeName(t) { return (types.find(x => x.t === t) || {}).n || t; },
        inputType(t) { return ({ phone: 'tel' })[t] || (['text','email','number','date','time','url'].includes(t) ? t : 'text'); },

        // ---- editing
        newField(t) {
            const name = this.typeName(t);
            const f = { id: uid(), type: t, label: name, required: false };
            if (['select','radio','checkbox'].includes(t)) f.options = ['Option 1', 'Option 2', 'Option 3'];
            if (['text','email','phone','url','number','textarea'].includes(t)) f.placeholder = '';
            if (t === 'email') f.label = 'Email address';
            return f;
        },
        add(t, at = null) {
            const f = this.newField(t);
            at === null ? this.fields.push(f) : this.fields.splice(at, 0, f);
            this.selected = f.id;
            this.$nextTick(() => document.querySelector('main')?.scrollTo({ top: 1e5, behavior: at === null ? 'smooth' : 'auto' }));
        },
        move(i, d) { const j = i + d; if (j < 0 || j >= this.fields.length) return; const [f] = this.fields.splice(i, 1); this.fields.splice(j, 0, f); },
        dup(i) { const c = JSON.parse(JSON.stringify(this.fields[i])); c.id = uid(); this.fields.splice(i + 1, 0, c); this.selected = c.id; },
        remove(i) { if (this.fields[i]?.id === this.selected) this.selected = null; this.fields.splice(i, 1); },
        drop(at) {
            if (this.dragType) this.add(this.dragType, at);
            else if (this.dragFrom !== null && this.dragFrom !== at) {
                const [f] = this.fields.splice(this.dragFrom, 1);
                this.fields.splice(at > this.dragFrom ? at - 1 : at, 0, f);
            }
            this.dragType = this.dragFrom = this.dragOver = null;
        },

        // ---- history + autosave
        changed() {
            this.state = 'unsaved';
            clearTimeout(this._save); this._save = setTimeout(() => this.save(), 800);
            clearTimeout(this._track);
            this._track = setTimeout(() => {
                const now = this.snap();
                if (now !== this.last) { this.history.push(this.last); if (this.history.length > 50) this.history.shift(); this.last = now; this.future = []; }
            }, 500);
        },
        restore(json) {
            const s = JSON.parse(json);
            this.last = json;
            this.title = s.title; this.description = s.description; this.fields = s.fields; this.settings = s.settings;
            if (!this.fields.some(f => f.id === this.selected)) this.selected = null;
        },
        undo() { if (!this.history.length) return; this.future.push(this.snap()); this.restore(this.history.pop()); },
        redo() { if (!this.future.length) return; this.history.push(this.snap()); this.restore(this.future.pop()); },

        async save() {
            this.state = 'saving';
            try {
                const r = await fetch(D.urls.update, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': D.csrf },
                    body: JSON.stringify({ title: this.title, description: this.description, fields: this.fields, settings: this.settings })
                });
                if (!r.ok) throw new Error(r.status);
                this.state = 'saved';
            } catch (e) { this.state = 'error'; }
        },
        async publish() {
            if (this.status === 'published') { this.showLink = true; return; }
            this.publishing = true;
            clearTimeout(this._save); await this.save();
            if (this.state === 'error') { this.publishing = false; return; }
            try {
                const r = await fetch(D.urls.publish, { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': D.csrf } });
                const j = await r.json();
                this.publicUrl = j.url; this.status = 'published'; this.showLink = true;
            } catch (e) { this.state = 'error'; }
            this.publishing = false;
        }
    };
}
</script>
</body>
</html>
