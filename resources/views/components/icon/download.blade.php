@props(['href' => null, 'title' => 'Download', 'type' => null, 'onclick' => null])
<a @if ($href) href="{{ $href }}" @endif @if ($type) type="{{ $type }}" @endif data-toggle="tooltip" data-placement="top"
    title="{{ $title }}" class="table-data-operation-icon mr-2" @if ($onclick) onclick="{{ $onclick }}" @endif>
    <span class="badge badge-success"><i class="fa-solid fa-download"></i></span>
</a>
