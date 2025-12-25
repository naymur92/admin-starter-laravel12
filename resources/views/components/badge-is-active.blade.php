@props(['isActive' => 1])
<span class="badge badge-pill {{ $isActive == 1 ? 'badge-success' : ($isActive == 0 ? 'badge-danger' : 'badge-secondary') }} ">
    {{ $isActive == 1 ? 'Active' : ($isActive == 0 ? 'Inactive' : 'Unknown') }}
</span>
