{{--
|--------------------------------------------------------------------------
| Form Input Component (Anonymous Blade Component)
|--------------------------------------------------------------------------
|
| Purpose:
|   Reusable Bootstrap-based input field with validation, label, and
|   attribute merging support.
|
| Supported Field Types:
|   - text, email, password, number, tel, url, search, date, datetime-local,
|     time, month, week, color, hidden
|
| Usage:
|   <x-form.input name="email" />
|
| Props:
|   - name (string)        : Field name (required)
|   - label (string|null)  : Label text (optional)
|   - required (bool)      : Shows required (*) indicator
|   - type (string)        : Input type (default: text)
|   - Additional attributes can be passed and will be merged
|
|--------------------------------------------------------------------------
--}}

@props(['name', 'label' => null, 'required' => false, 'type' => 'text'])

@if ($label)
    <label for="{{ $attributes->get('id', "_$name") }}">
        <strong>{{ $label }}</strong>

        @if ($required)
            <span class="text-danger">&nbsp;<i class="fas fa-xs fa-asterisk"></i></span>
        @endif
    </label>
@endif

<input
    {{ $attributes->merge([
        'type' => $type,
        'name' => $name,
        'id' => $attributes->get('id', "_$name"),
        'value' => $type === 'password' ? null : old($name),
        'class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : ''),
    ]) }}>

@error($name)
    <span class="invalid-feedback" role="alert">
        {{ $message }}
    </span>
@enderror
