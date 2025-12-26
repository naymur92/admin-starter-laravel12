@props(['icon' => 'fas fa-download'])

<a {{ $attributes->merge(['class' => 'btn btn-success btn-sm']) }}>
    <i class="{{ $icon }}"></i> {{ strlen($slot) > 0 ? $slot : 'Download' }}
</a>
