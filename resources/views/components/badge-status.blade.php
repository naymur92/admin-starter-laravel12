@props(['status' => 1])
<span class="badge badge-pill {{ $status == 1 ? 'badge-success' : 'badge-danger' }} ">
    {{ ($status == 1 ? 'Active' : $status == 0) ? 'Inactive' : 'Unknown' }}
</span>
