/**
 * Lead Matrix Form Embed Script
 * Universal embed script that works on any website (HTML, React, CMS, etc.)
 * 
 * Usage:
 * 1. Include this script: <script src="https://yourdomain.com/js/leadmatrix-embed.js"></script>
 * 2. Add embed element: <div data-leadmatrix-code="YOUR_SHORTCODE"></div>
 * 
 * Or use iframe:
 * <iframe src="https://yourdomain.com/api/shortcode/YOUR_SHORTCODE/html" width="100%" height="600" frameborder="0"></iframe>
 */

(function() {
    'use strict';

    // Configuration
    const API_BASE_URL = window.LEADMATRIX_API_URL || window.location.origin;
    
    /**
     * Default display settings (must match CustomForm::defaultDisplaySettings)
     */
    function defaultDisplaySettings() {
        return {
            heading: { tag: 'h2', color: '#333333', align: 'left', line_height: '1.5', italic: false, underline: false },
            title: { tag: 'h4', color: '#6c757d', align: 'left', line_height: '1.4', italic: false, underline: false },
            description: { tag: 'p', color: '#6c757d', align: 'left', line_height: '1.5', italic: false, underline: false },
        };
    }

    function mergeDisplaySettings(stored) {
        var defaults = defaultDisplaySettings();
        if (!stored || typeof stored !== 'object') {
            return defaults;
        }
        ['heading', 'title', 'description'].forEach(function (key) {
            if (stored[key] && typeof stored[key] === 'object') {
                defaults[key] = Object.assign({}, defaults[key], stored[key]);
            }
        });
        return defaults;
    }

    function appendStyledText(formWrapper, section, text, settings) {
        if (!text) {
            return;
        }

        var style = settings[section] || defaultDisplaySettings()[section];
        var allowedTags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'];
        var tag = allowedTags.indexOf(style.tag) !== -1 ? style.tag : 'p';
        var el = document.createElement(tag);
        el.textContent = text;
        el.style.color = style.color || '#333333';
        el.style.textAlign = style.align || 'left';
        el.style.lineHeight = style.line_height || '1.5';
        el.style.marginBottom = '1rem';
        if (style.italic) {
            el.style.fontStyle = 'italic';
        }
        if (style.underline) {
            el.style.textDecoration = 'underline';
        }
        if (section === 'description' && text.indexOf('\n') !== -1) {
            el.style.whiteSpace = 'pre-line';
        }
        formWrapper.appendChild(el);
    }

    /**
     * Render form field based on type
     */
    function renderField(field, container) {
        const fieldDiv = document.createElement('div');
        fieldDiv.className = 'lm-form-field';
        fieldDiv.style.marginBottom = '1rem';

        // Label
        const label = document.createElement('label');
        label.className = 'lm-form-label';
        label.style.display = 'block';
        label.style.fontWeight = '500';
        label.style.marginBottom = '0.5rem';
        label.style.color = '#495057';
        
        if (field.is_required) {
            const star = document.createElement('span');
            star.textContent = '* ';
            star.style.color = '#dc3545';
            label.appendChild(star);
        }
        
        label.appendChild(document.createTextNode(field.field_name));
        fieldDiv.appendChild(label);

        // Input field
        let input;
        if (field.field_type === 'Text Area') {
            input = document.createElement('textarea');
            input.rows = 4;
        } else if (field.field_type === 'Number') {
            input = document.createElement('input');
            input.type = 'number';
        } else if (field.field_type === 'Date') {
            input = document.createElement('input');
            input.type = 'date';
        } else if (field.field_type === 'Checkbox') {
            const checkboxDiv = document.createElement('div');
            input = document.createElement('input');
            input.type = 'checkbox';
            input.value = '1';
            const checkboxLabel = document.createElement('label');
            checkboxLabel.textContent = 'Yes';
            checkboxDiv.appendChild(input);
            checkboxDiv.appendChild(checkboxLabel);
            fieldDiv.appendChild(checkboxDiv);
            container.appendChild(fieldDiv);
            return;
        } else {
            input = document.createElement('input');
            input.type = 'text';
        }

        input.name = field.field_key;
        input.className = 'lm-form-input';
        input.style.width = '100%';
        input.style.padding = '0.5rem';
        input.style.border = '1px solid #ced4da';
        input.style.borderRadius = '0.25rem';
        input.style.fontSize = '1rem';
        
        if (field.is_required) {
            input.required = true;
        }

        fieldDiv.appendChild(input);
        container.appendChild(fieldDiv);
    }

    /**
     * Group fields by step (multi-step form support)
     */
    function groupFieldsByStep(fields) {
        const groups = {};
        (fields || []).forEach(function(field) {
            const step = field.step || 1;
            if (!groups[step]) groups[step] = [];
            groups[step].push(field);
        });
        const steps = Object.keys(groups).map(Number).sort(function(a, b) { return a - b; });
        return { steps: steps, byStep: groups };
    }

    /**
     * Render the form (single-step or multi-step)
     */
    function renderForm(formData, container) {
        container.innerHTML = '';

        const buttonColor = (formData.button_color && /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(formData.button_color))
            ? formData.button_color
            : '#0d6efd';

        const formWrapper = document.createElement('div');
        formWrapper.className = 'lm-form-wrapper';
        formWrapper.style.maxWidth = '600px';
        formWrapper.style.margin = '0 auto';
        formWrapper.style.padding = '1.5rem';
        formWrapper.style.backgroundColor = '#ffffff';
        formWrapper.style.borderRadius = '0.5rem';
        formWrapper.style.boxShadow = '0 2px 4px rgba(0,0,0,0.1)';

        const displaySettings = mergeDisplaySettings(formData.form.display_settings);

        appendStyledText(formWrapper, 'heading', formData.form.heading || formData.form.name, displaySettings);
        appendStyledText(formWrapper, 'title', formData.form.title, displaySettings);
        appendStyledText(formWrapper, 'description', formData.form.description, displaySettings);

        const alertContainer = document.createElement('div');
        alertContainer.id = 'lm-alert-container';
        formWrapper.appendChild(alertContainer);

        const grouped = groupFieldsByStep(formData.fields);
        const isMultiStep = grouped.steps.length > 1;

        const form = document.createElement('form');
        form.id = 'lm-form-' + Date.now();
        form.className = 'lm-form';

        if (isMultiStep) {
            // Step indicator
            const stepIndicator = document.createElement('div');
            stepIndicator.className = 'lm-step-indicator';
            stepIndicator.style.marginBottom = '1.5rem';
            stepIndicator.style.display = 'flex';
            stepIndicator.style.gap = '0.5rem';
            stepIndicator.style.flexWrap = 'wrap';
            grouped.steps.forEach(function(stepNum) {
                const pill = document.createElement('span');
                pill.className = 'lm-step-pill';
                pill.setAttribute('data-step', stepNum);
                pill.textContent = 'Step ' + stepNum;
                pill.style.padding = '0.35rem 0.75rem';
                pill.style.borderRadius = '9999px';
                pill.style.fontSize = '0.875rem';
                pill.style.fontWeight = '500';
                pill.style.backgroundColor = stepNum === 1 ? buttonColor : '#e9ecef';
                pill.style.color = stepNum === 1 ? '#fff' : '#6c757d';
                stepIndicator.appendChild(pill);
            });
            formWrapper.appendChild(stepIndicator);

            // Step panels (one per step)
            const stepsContainer = document.createElement('div');
            stepsContainer.className = 'lm-steps-container';
            grouped.steps.forEach(function(stepNum) {
                const panel = document.createElement('div');
                panel.className = 'lm-step-panel';
                panel.setAttribute('data-step', stepNum);
                panel.style.display = stepNum === 1 ? 'block' : 'none';
                grouped.byStep[stepNum].forEach(function(field) {
                    renderField(field, panel);
                });
                stepsContainer.appendChild(panel);
            });
            form.appendChild(stepsContainer);

            // Buttons: Previous, Next / Submit
            const btnRow = document.createElement('div');
            btnRow.style.display = 'flex';
            btnRow.style.gap = '0.75rem';
            btnRow.style.marginTop = '1.25rem';
            btnRow.style.justifyContent = 'space-between';

            const prevBtn = document.createElement('button');
            prevBtn.type = 'button';
            prevBtn.className = 'lm-btn-prev';
            prevBtn.textContent = 'Previous';
            prevBtn.style.padding = '0.75rem 1.25rem';
            prevBtn.style.backgroundColor = '#6c757d';
            prevBtn.style.color = '#fff';
            prevBtn.style.border = 'none';
            prevBtn.style.borderRadius = '0.375rem';
            prevBtn.style.fontWeight = '600';
            prevBtn.style.cursor = 'pointer';
            prevBtn.style.display = 'none';

            const nextBtn = document.createElement('button');
            nextBtn.type = 'button';
            nextBtn.className = 'lm-btn-next';
            nextBtn.textContent = 'Next';
            nextBtn.style.padding = '0.75rem 1.25rem';
            nextBtn.style.backgroundColor = buttonColor;
            nextBtn.style.color = '#fff';
            nextBtn.style.border = 'none';
            nextBtn.style.borderRadius = '0.375rem';
            nextBtn.style.fontWeight = '600';
            nextBtn.style.cursor = 'pointer';

            const submitBtn = document.createElement('button');
            submitBtn.type = 'submit';
            submitBtn.className = 'lm-form-submit';
            submitBtn.textContent = 'Submit';
            submitBtn.style.padding = '0.75rem 1.25rem';
            submitBtn.style.backgroundColor = buttonColor;
            submitBtn.style.color = '#fff';
            submitBtn.style.border = 'none';
            submitBtn.style.borderRadius = '0.375rem';
            submitBtn.style.fontWeight = '600';
            submitBtn.style.cursor = 'pointer';
            submitBtn.style.display = 'none';

            var currentStep = 1;
            function updateStepUI() {
                formWrapper.querySelectorAll('.lm-step-panel').forEach(function(panel) {
                    panel.style.display = parseInt(panel.getAttribute('data-step'), 10) === currentStep ? 'block' : 'none';
                });
                formWrapper.querySelectorAll('.lm-step-pill').forEach(function(pill) {
                    var s = parseInt(pill.getAttribute('data-step'), 10);
                    pill.style.backgroundColor = s === currentStep ? buttonColor : (s < currentStep ? buttonColor : '#e9ecef');
                    pill.style.color = s <= currentStep ? '#fff' : '#6c757d';
                });
                prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
                nextBtn.style.display = currentStep < grouped.steps.length ? 'inline-block' : 'none';
                submitBtn.style.display = currentStep === grouped.steps.length ? 'inline-block' : 'none';
            }

            prevBtn.addEventListener('click', function() {
                if (currentStep > 1) {
                    currentStep--;
                    updateStepUI();
                }
            });

            nextBtn.addEventListener('click', function() {
                var panel = formWrapper.querySelector('.lm-step-panel[data-step="' + currentStep + '"]');
                var inputs = panel.querySelectorAll('input, textarea, select');
                var valid = true;
                inputs.forEach(function(inp) {
                    if (inp.required && !inp.value.trim()) valid = false;
                });
                if (valid && currentStep < grouped.steps.length) {
                    currentStep++;
                    updateStepUI();
                } else if (!valid) {
                    alertContainer.innerHTML = '<div class="lm-alert" style="padding:0.75rem;margin-bottom:1rem;border-radius:0.375rem;background:#f8d7da;color:#842029;">Please fill in all required fields.</div>';
                }
            });

            btnRow.appendChild(prevBtn);
            btnRow.appendChild(nextBtn);
            btnRow.appendChild(submitBtn);
            form.appendChild(btnRow);
            updateStepUI();
        } else {
            formData.fields.forEach(function(field) {
                renderField(field, form);
            });
            const submitBtn = document.createElement('button');
            submitBtn.type = 'submit';
            submitBtn.className = 'lm-form-submit';
            submitBtn.textContent = 'Submit Form';
            submitBtn.style.width = '100%';
            submitBtn.style.padding = '0.75rem';
            submitBtn.style.backgroundColor = buttonColor;
            submitBtn.style.color = '#ffffff';
            submitBtn.style.border = 'none';
            submitBtn.style.borderRadius = '0.375rem';
            submitBtn.style.fontWeight = '600';
            submitBtn.style.cursor = 'pointer';
            submitBtn.style.marginTop = '1rem';
            submitBtn.addEventListener('mouseenter', function() { this.style.filter = 'brightness(0.92)'; });
            submitBtn.addEventListener('mouseleave', function() { this.style.filter = 'none'; });
            form.appendChild(submitBtn);
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var submitUrl = formData.submit_url || (API_BASE_URL + '/shortcode/' + formData.code + '/submit');
            handleFormSubmit(form, submitUrl, alertContainer);
        });

        formWrapper.appendChild(form);
        container.appendChild(formWrapper);
    }

    /**
     * Handle form submission
     */
    function handleFormSubmit(form, submitUrl, alertContainer) {
        // Clear previous alerts
        alertContainer.innerHTML = '';

        // Disable submit button
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';

        // Get form data
        const formData = new FormData(form);

        // Submit via fetch
        fetch(submitUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            credentials: 'same-origin'
        })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // If not JSON, try to get text
                return response.text().then(text => {
                    throw new Error('Server returned non-JSON response: ' + text);
                });
            }
        })
        .then(data => {
            if (data.success) {
                if (data.redirect_url) {
                    window.location.href = data.redirect_url;
                    return;
                }

                showAlert(alertContainer, data.message || 'Form submitted successfully!', 'success');
                form.reset();
            } else {
                showAlert(alertContainer, data.message || 'Error submitting form', 'error');
            }
        })
        .catch(error => {
            let errorMessage = 'An error occurred. Please try again.';
            if (error.message) {
                errorMessage = error.message;
            }
            showAlert(alertContainer, errorMessage, 'error');
            console.error('Form submission error:', error);
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    }

    /**
     * Show alert message
     */
    function showAlert(container, message, type) {
        const alert = document.createElement('div');
        alert.className = 'lm-alert';
        alert.textContent = message;
        alert.style.padding = '0.75rem 1rem';
        alert.style.marginBottom = '1rem';
        alert.style.borderRadius = '0.375rem';
        
        if (type === 'success') {
            alert.style.backgroundColor = '#d1e7dd';
            alert.style.color = '#0f5132';
            alert.style.border = '1px solid #badbcc';
        } else {
            alert.style.backgroundColor = '#f8d7da';
            alert.style.color = '#842029';
            alert.style.border = '1px solid #f5c2c7';
        }

        container.appendChild(alert);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.parentNode.removeChild(alert);
            }
        }, 5000);
    }

    /**
     * Load and render form
     */
    function loadForm(code, container) {
        // Show loading state
        container.innerHTML = '<div style="text-align: center; padding: 2rem;">Loading form...</div>';

        // Fetch form data
        fetch(API_BASE_URL + '/api/shortcode/' + code + '/data')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Add code to data for fallback URL
                    data.code = code;
                    renderForm(data, container);
                } else {
                    container.innerHTML = '<div style="color: #dc3545; padding: 1rem;">' + (data.message || 'Form not found') + '</div>';
                }
            })
            .catch(error => {
                container.innerHTML = '<div style="color: #dc3545; padding: 1rem;">Error loading form. Please check the shortcode.</div>';
                console.error('Error loading form:', error);
            });
    }

    /**
     * Initialize on DOM ready
     */
    function init() {
        // Find all elements with data-leadmatrix-code attribute
        const embedElements = document.querySelectorAll('[data-leadmatrix-code]');
        
        embedElements.forEach(function(element) {
            const code = element.getAttribute('data-leadmatrix-code');
            if (code) {
                loadForm(code, element);
            }
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Export for manual initialization
    window.LeadMatrixEmbed = {
        load: function(code, container) {
            if (typeof container === 'string') {
                container = document.querySelector(container);
            }
            if (container) {
                loadForm(code, container);
            }
        }
    };
})();

