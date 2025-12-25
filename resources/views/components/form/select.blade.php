@props([
    'name' => '',
    'id' => null,
    'label' => '',
    'required' => false,
    'multiple' => false,
    'placeholder' => 'Select One',
    'options' => [],
    'selected' => [],
    // valueBy: 'auto' | 'key' | 'value' — controls what goes into the <option value="...">
    'valueBy' => 'auto',
])

@php
    $inputId = $id ?? "_$name";
    $inputName = $multiple ? $name . '[]' : $name;
    $selectedValues = is_array($selected) ? $selected : (!empty($selected) ? [$selected] : []);
@endphp

@if ($label)
    <label for="{{ $inputId }}">
        <strong>{{ $label }}</strong>
        @if ($required)
            <span class="text-danger">&nbsp;<i class="fas fa-xs fa-asterisk"></i></span>
        @endif
    </label>
@endif

<select name="{{ $inputName }}" id="{{ $inputId }}" class="form-control select2 @error($name) is-invalid @enderror" @if ($multiple) multiple @endif
    {{ $attributes }}>
    @if (!$multiple)
        <option value="" disabled @if (empty($selectedValues)) selected @endif>{{ $placeholder }}</option>
    @endif

    @foreach ($options as $value => $text)
        @php
            $optionValue = $valueBy === 'key' ? $value : ($valueBy === 'value' ? $text : (is_numeric($value) ? $text : $value));
        @endphp
        <option value="{{ $optionValue }}" @if (in_array($optionValue, $selectedValues)) selected @endif>
            {{ $text }}
        </option>
    @endforeach
</select>

@error($name)
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
@enderror

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#{{ $inputId }}').select2({
                width: '100%',
                placeholder: '{{ $placeholder }}',
                @if ($multiple)
                    allowClear: true,
                    closeOnSelect: false
                @endif
            });
        });
    </script>
@endpush
