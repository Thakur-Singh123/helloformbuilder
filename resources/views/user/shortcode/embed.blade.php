{{--
    Embed view for shortcodes
    This view is used when parsing shortcodes in content using the parse_shortcodes() helper function
--}}

<div class="shortcode-form-embed" style="margin: 1rem 0;">
    <form class="shortcode-form" data-shortcode="{{ $shortcode->code }}">
        @csrf

        {!! $form->renderDisplayTextHtml('heading', $form->heading ?? $form->name) !!}
        {!! $form->renderDisplayTextHtml('title', $form->title) !!}
        {!! $form->renderDisplayTextHtml('description', $form->description) !!}

        @foreach($form->fields->sortBy('order') as $field)
            @include('user.shortcode.partials.form-field', ['field' => $field])
        @endforeach

        <button type="submit" style="background-color: #0d6efd; color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 0.5rem; cursor: pointer; font-weight: 600;">
            Submit Form
        </button>
    </form>

    <div class="shortcode-form-message" style="margin-top: 1rem; display: none;"></div>
</div>

<script>
(function() {
    const form = document.querySelector('.shortcode-form[data-shortcode="{{ $shortcode->code }}"]');
    if (!form) {
        return;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const messageDiv = form.parentElement.querySelector('.shortcode-form-message');
        messageDiv.style.display = 'block';
        messageDiv.innerHTML = '<p style="color: #0d6efd;">Submitting...</p>';

        fetch('{{ route("shortcode.submit", $shortcode->code) }}', {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                    return;
                }
                messageDiv.innerHTML = '<p style="color: #0f5132; background-color: #d1e7dd; padding: 0.75rem; border-radius: 0.25rem;">' + data.message + '</p>';
                form.reset();
            } else {
                messageDiv.innerHTML = '<p style="color: #842029; background-color: #f8d7da; padding: 0.75rem; border-radius: 0.25rem;">' + (data.message || 'Something went wrong.') + '</p>';
            }
        })
        .catch(function() {
            messageDiv.innerHTML = '<p style="color: #842029; background-color: #f8d7da; padding: 0.75rem; border-radius: 0.25rem;">An error occurred. Please try again.</p>';
        });
    });
})();
</script>
