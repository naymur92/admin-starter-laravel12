@extends('layouts.app')

@section('title', 'Role Management')

@push('styles')
    <link href="{{ asset('/') }}assets/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('/') }}assets/vendor/datatables/jquery.dataTables.js"></script>
    <script src="{{ asset('/') }}assets/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="{{ asset('/') }}assets/js/demo/datatables-demo.js"></script>
    <script>
        window.rolesData = {
            permissions: @json($permissions)
        };
    </script>
@endpush


@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold text-primary">Roles List</h5>
                        @can('role-create')
                            <x-button.add-new onclick="window.openCreateRoleModal(); return false;">Create New Role</x-button.add-new>
                        @endcan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-center" id="dataTable" width="100%">
                                <thead class="text-white bg-primary">
                                    <tr>
                                        <th style="width: 70px;">SL No</th>
                                        <th>Name</th>
                                        <th style="width: 150px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($roles as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="font-weight-500">{{ $item->name }}</td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    @can('role-list')
                                                        <x-icon.eye href="{{ route('roles.show', $item->id) }}" title="View" />
                                                    @endcan
                                                    @can('role-edit')
                                                        <x-icon.pen onclick="window.openEditRoleModal({{ $item->id }}); return false;" data-toggle="tooltip" data-placement="top"
                                                            title="Edit"></x-icon.pen>
                                                    @endcan
                                                    @if ($item->name != 'Super Admin')
                                                        @can('role-delete')
                                                            <form action="{{ route('roles.destroy', $item->id) }}" method="post"
                                                                onsubmit="swalConfirmationOnSubmit(event, 'Are you sure you want to delete this role?')">
                                                                @csrf
                                                                @method('delete')
                                                                <x-icon.trash onclick="this.closest('form').requestSubmit()" title="Delete" />
                                                            </form>
                                                        @endcan
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="py-4 text-muted">No roles found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Role Modal (Vue Component) -->
    <role-create-modal :permissions='@json($permissions)' :create-url="'{{ route('roles.store') }}'"></role-create-modal>

    <!-- Edit Role Modal (Vue Component) -->
    <role-edit-modal :permissions='@json($permissions)' :update-url="'{{ route('roles.update', 0) }}'"></role-edit-modal>
@endsection
