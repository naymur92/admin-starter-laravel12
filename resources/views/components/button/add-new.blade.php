@props(['onclick' => null])
<a {{ $attributes->merge(['class' => 'btn btn-primary waves-effect waves-light br-5']) }} @if ($onclick) onclick="{{ $onclick }}" @endif>
    <i class="fas fa-plus-circle me-1"></i> {{ strlen($slot) > 0 ? $slot : 'Add New' }}</a>
