<a {{ $attributes->merge(['class' => 'btn waves-effect waves-light br-5 btn-secondary']) }}>
    <i class="fas fa-angle-left"></i> {{ strlen($slot) > 0 ? $slot : 'Back' }}
</a>
