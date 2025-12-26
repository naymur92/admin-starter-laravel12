<a {{ $attributes->merge(['class' => 'btn btn-sm waves-effect waves-light br-5 btn-secondary']) }}>
    <i class="fas fa-arrow-left"></i> {{ strlen($slot) > 0 ? $slot : 'Back' }}
</a>
