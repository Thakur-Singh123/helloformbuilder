@php
    $fieldKey = is_array($field) ? ($field['field_key'] ?? '') : ($field->field_key ?? '');
    $fieldName = is_array($field) ? ($field['field_name'] ?? '') : ($field->field_name ?? '');
    $fieldType = is_array($field) ? ($field['field_type'] ?? 'Input') : ($field->field_type ?? 'Input');
    $isRequired = is_array($field) ? !empty($field['is_required']) : !empty($field->is_required);
    $rawMetadata = is_array($field) ? ($field['metadata'] ?? '{}') : ($field->metadata ?? '{}');
    $metadata = is_string($rawMetadata) ? (json_decode($rawMetadata, true) ?: []) : (is_array($rawMetadata) ? $rawMetadata : []);
    $options = $metadata['options'] ?? [];
    $allowMultiple = !empty($metadata['allow_multiple']);
    $inputIdPrefix = 'field_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $fieldKey);
@endphp

<div class="form-group">
    <label class="form-label">
        @if($isRequired)
            <span class="required-star">*</span>
        @endif
        {{ $fieldName }}
    </label>

    @if($fieldType === 'Text Area')
        <textarea
            class="form-control"
            name="{{ $fieldKey }}"
            rows="4"
            @if($isRequired) required @endif
        ></textarea>

    @elseif($fieldType === 'Number')
        <input
            type="number"
            class="form-control"
            name="{{ $fieldKey }}"
            @if($isRequired) required @endif
        >

    @elseif($fieldType === 'Date')
        <input
            type="date"
            class="form-control"
            name="{{ $fieldKey }}"
            @if($isRequired) required @endif
        >

    @elseif($fieldType === 'Select')
        <select class="form-select" name="{{ $fieldKey }}" @if($isRequired) required @endif>
            <option value="">-- Select --</option>
            @foreach($options as $option)
                <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
        </select>

    @elseif($fieldType === 'Checkbox')
        @if(!empty($options))
            <div
                class="choice-group"
                data-choice-group="1"
                data-choice-type="{{ $allowMultiple ? 'multiple' : 'single' }}"
                @if($isRequired) data-required="1" @endif
            >
                @foreach($options as $option)
                    @php $optionId = $inputIdPrefix . '_' . $loop->index; @endphp
                    @if($allowMultiple)
                        <div class="form-check">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                name="{{ $fieldKey }}[]"
                                id="{{ $optionId }}"
                                value="{{ $option }}"
                            >
                            <label class="form-check-label" for="{{ $optionId }}">{{ $option }}</label>
                        </div>
                    @else
                        <div class="form-check">
                            <input
                                type="radio"
                                class="form-check-input"
                                name="{{ $fieldKey }}"
                                id="{{ $optionId }}"
                                value="{{ $option }}"
                                @if($isRequired && $loop->first) required @endif
                            >
                            <label class="form-check-label" for="{{ $optionId }}">{{ $option }}</label>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div class="form-check">
                <input
                    type="checkbox"
                    class="form-check-input"
                    name="{{ $fieldKey }}"
                    id="{{ $inputIdPrefix }}"
                    value="1"
                    @if($isRequired) required @endif
                >
                <label class="form-check-label" for="{{ $inputIdPrefix }}">Yes</label>
            </div>
        @endif

    @else
        <input
            type="text"
            class="form-control"
            name="{{ $fieldKey }}"
            @if($isRequired) required @endif
        >
    @endif
</div>
