@props(['href' => null, 'title' => 'Edit', 'type' => null, 'onclick' => null])
<a @if ($href) href="{{ $href }}" @endif @if ($type) type="{{ $type }}" @endif data-toggle="tooltip" data-placement="top"
    title="{{ $title }}" class="table-data-operation-icon mr-2" @if ($onclick) onclick="{{ $onclick }}" @endif>
    <span class="badge badge-warning"><i class="fa-solid fa-pen-to-square"></i></span>
</a>
