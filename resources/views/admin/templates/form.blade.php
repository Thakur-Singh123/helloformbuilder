@php
    $hasOld  = session()->hasOldInput();
    $initial = old('fields_json')
        ? (json_decode(old('fields_json'), true) ?: [])
        : ($template->fields ?? [
            ['type' => 'text',  'label' => 'Full name',     'required' => true],
            ['type' => 'email', 'label' => 'Email address', 'required' => true],
        ]);
    $isPopular = $hasOld ? old('is_popular') : ($template->is_popular ?? true);
    $isActive  = $hasOld ? old('is_active')  : ($template->is_active  ?? true);
@endphp
<style>.at-card:hover{transform:none!important}</style>
<form method="POST" action="{{ $action }}" id="templateForm">
    @csrf
    @if ($method !== 'POST') @method($method) @endif
    <input type="hidden" name="fields_json" id="fieldsJson">
    <div class="row g-3">
        {{-- Left: template settings --}}
        <div class="col-lg-4">
            <div class="card at-card"><div class="card-body">
                <h5 class="mb-3">Template details</h5>

                <label class="form-label fw-semibold">Title</label>
                <input name="title" value="{{ old('title', $template->title ?? '') }}" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Admission Form" required>
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror

                <label class="form-label fw-semibold mt-3">Description</label>
                <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror" placeholder="One line shown on the template card">{{ old('description', $template->description ?? '') }}</textarea>
                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <label class="form-label fw-semibold mt-3">Category</label>
                <select name="template_category_id" class="form-select @error('template_category_id') is-invalid @enderror">
                    <option value="">Select category</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('template_category_id', $template->template_category_id ?? '') == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('template_category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <label class="form-label small text-muted mt-2 mb-1">Or add a new category</label>
                <input name="new_category" value="{{ old('new_category') }}" class="form-control form-control-sm" placeholder="e.g. Admissions (leave empty to use the selected one)">

                <div class="form-check mt-3">
                    <input class="form-check-input" type="checkbox" name="is_popular" value="1" id="isPopular" @checked($isPopular)>
                    <label class="form-check-label" for="isPopular">Show in "Popular" (gallery home page)</label>
                </div>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" @checked($isActive)>
                    <label class="form-check-label" for="isActive">Active (visible to users)</label>
                </div>

                <button class="btn btn-primary w-100 mt-4">Save template</button>
            </div></div>
        </div>

        {{-- Right: field builder --}}
        <div class="col-lg-8">
            <div class="card at-card"><div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Fields <span class="badge bg-secondary" id="fieldCount">0</span></h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="addField"><i class="bi bi-plus-lg"></i> Add field</button>
                </div>
                @error('fields_json') <div class="alert alert-danger py-2 small">{{ $message }}</div> @enderror

                <div id="fieldsList"></div>
            </div></div>
        </div>
    </div>
</form>

<script>
(function () {
    var TYPES  = @json($fieldTypes);
    var LABELS = {text:'Text',email:'Email',phone:'Phone',url:'Website URL',number:'Number',textarea:'Long text',date:'Date',time:'Time',select:'Dropdown',radio:'Radio buttons',checkbox:'Checkboxes',file:'File upload',rating:'Rating',address:'Address',signature:'Signature',heading:'Heading',paragraph:'Paragraph',hidden:'Hidden',scale:'Scale 0-10'};
    var WITH_OPTIONS = ['select','radio','checkbox'];
    var WITH_PLACEHOLDER = ['text','email','phone','url','number','textarea'];
    var NO_REQUIRED = ['heading','paragraph','hidden'];

    var fields = @json($initial);
    var list = document.getElementById('fieldsList');
    var hidden = document.getElementById('fieldsJson');

    function esc(s) {
        return String(s == null ? '' : s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function sync() {
        hidden.value = JSON.stringify(fields.map(function (f) {
            return {type:f.type, label:f.label || '', required:!!f.required, placeholder:f.placeholder || '', help:f.help || '', options:f.options || []};
        }));
        document.getElementById('fieldCount').textContent = fields.length;
    }

    function render() {
        list.innerHTML = '';
        fields.forEach(function (f, i) {
            var hasOpts = WITH_OPTIONS.indexOf(f.type) > -1;
            var hasPh   = WITH_PLACEHOLDER.indexOf(f.type) > -1;
            var canReq  = NO_REQUIRED.indexOf(f.type) === -1;

            var typeOpts = TYPES.map(function (t) {
                return '<option value="' + t + '"' + (t === f.type ? ' selected' : '') + '>' + (LABELS[t] || t) + '</option>';
            }).join('');

            var card = document.createElement('div');
            card.className = 'border rounded p-3 mb-2 bg-light';
            card.setAttribute('data-i', i);
            card.innerHTML =
                '<div class="row g-2 align-items-center">' +
                    '<div class="col-md-3"><select class="form-select form-select-sm" data-k="type">' + typeOpts + '</select></div>' +
                    '<div class="col-md-5"><input class="form-control form-control-sm" data-k="label" placeholder="Field label" value="' + esc(f.label) + '"></div>' +
                    '<div class="col-md-2">' + (canReq ? '<div class="form-check"><input class="form-check-input" type="checkbox" data-k="required"' + (f.required ? ' checked' : '') + '> <label class="form-check-label small">Required</label></div>' : '') + '</div>' +
                    '<div class="col-md-2 text-end text-nowrap">' +
                        '<button type="button" class="btn btn-sm btn-outline-secondary" data-act="up"' + (i === 0 ? ' disabled' : '') + '>&uarr;</button> ' +
                        '<button type="button" class="btn btn-sm btn-outline-secondary" data-act="down"' + (i === fields.length - 1 ? ' disabled' : '') + '>&darr;</button> ' +
                        '<button type="button" class="btn btn-sm btn-outline-danger" data-act="del">&times;</button>' +
                    '</div>' +
                '</div>' +
                '<div class="row g-2 mt-1">' +
                    (hasPh ? '<div class="col-md-6"><input class="form-control form-control-sm" data-k="placeholder" placeholder="Placeholder (optional)" value="' + esc(f.placeholder) + '"></div>' : '') +
                    '<div class="col-md-' + (hasPh ? '6' : '12') + '"><input class="form-control form-control-sm" data-k="help" placeholder="' + (f.type === 'paragraph' ? 'Paragraph text' : 'Help text (optional)') + '" value="' + esc(f.help) + '"></div>' +
                '</div>' +
                (hasOpts ? '<div class="mt-2"><textarea class="form-control form-control-sm" rows="3" data-k="options" placeholder="One option per line">' + esc((f.options || []).join('\n')) + '</textarea></div>' : '');

            list.appendChild(card);
        });
        sync();
    }

    function onEdit(e) {
        var el = e.target, k = el.getAttribute('data-k');
        if (!k) return;
        var i = parseInt(el.closest('[data-i]').getAttribute('data-i'), 10);
        var f = fields[i];

        if (k === 'required') {
            f.required = el.checked;
        } else if (k === 'options') {
            f.options = el.value.split('\n');
        } else {
            f[k] = el.value;
        }

        if (k === 'type') {
            if (WITH_OPTIONS.indexOf(f.type) > -1 && (!f.options || !f.options.length)) {
                f.options = ['Option 1', 'Option 2', 'Option 3'];
            }
            render();
            return;
        }
        sync();
    }

    list.addEventListener('input', onEdit);
    list.addEventListener('change', function (e) { if (e.target.getAttribute('data-k') === 'type') onEdit(e); });

    list.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-act]');
        if (!btn) return;
        var i = parseInt(btn.closest('[data-i]').getAttribute('data-i'), 10);
        var act = btn.getAttribute('data-act');

        if (act === 'del')  fields.splice(i, 1);
        if (act === 'up'   && i > 0)                 fields.splice(i - 1, 0, fields.splice(i, 1)[0]);
        if (act === 'down' && i < fields.length - 1) fields.splice(i + 1, 0, fields.splice(i, 1)[0]);
        render();
    });

    document.getElementById('addField').addEventListener('click', function () {
        fields.push({type: 'text', label: '', required: false});
        render();
        var inputs = list.querySelectorAll('[data-k="label"]');
        if (inputs.length) inputs[inputs.length - 1].focus();
    });

    document.getElementById('templateForm').addEventListener('submit', sync);

    render();
})();
</script>