@extends('layouts.app')

@section('title', 'Permission Management')


@push('styles')
    <link href="{{ asset('/') }}assets/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <style>
        table tbody tr td {
            padding: 0px !important;
            vertical-align: middle !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('/') }}assets/vendor/datatables/jquery.dataTables.js"></script>
    <script src="{{ asset('/') }}assets/vendor/datatables/dataTables.bootstrap4.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('/') }}assets/js/demo/datatables-demo.js"></script>
@endpush


@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold text-primary">Permissions List</h5>
                        @can('permission-create')
                            <x-button.add-new onclick="window.openPermissionModal(); return false;">Create New Permission</x-button.add-new>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table data-page-length='25' class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                <thead class="text-white bg-primary">
                                    <tr>
                                        <th style="width: 70px;">SL No</th>
                                        <th>Permission Name</th>
                                        <th style="width: 150px;">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($permissions as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                @if ($item->id > 8)
                                                    @can('permission-delete')
                                                        <form action="{{ route('permissions.destroy', $item->id) }}" method="post"
                                                            onsubmit="swalConfirmationOnSubmit(event, 'Are you sure you want to delete this permission?')">
                                                            @csrf
                                                            @method('delete')
                                                            <x-icon.trash onclick="this.closest('form').requestSubmit()" title="Delete" />
                                                        </form>
                                                    @endcan
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vue Permission Create Modal -->
    <permission-create-modal :create-url="'{{ route('permissions.store') }}'"></permission-create-modal>
@endsection
