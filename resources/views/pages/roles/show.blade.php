@extends('layouts.app')

@section('title', 'Show Role' . ' - ' . $role->name)

@push('styles')
@endpush

@push('scripts')
    <script>
        window.rolesData = {
            permissions: @json($permissions)
        };
    </script>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Show Role</h1>

        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 font-weight-bold text-primary">{{ $role->name }}</h5>
                        @can('role-list')
                            <x-button.back href="{{ route('roles.index') }}"></x-button.back>
                        @endcan
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th style="width: 20%;">Role Name</th>
                                <td>{{ $role->name }}</td>
                            </tr>
                            <tr>
                                <th>Permissions</th>
                                <td>
                                    @if ($role->permissions->count() > 0)
                                        @foreach ($role->permissions as $p)
                                            <label class="badge badge-primary">{{ $p->name }}</label>
                                        @endforeach
                                    @else
                                        <label class="badge badge-danger">No Permission</label>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        @can('role-edit')
                            <x-button.edit onclick="window.openEditRoleModal({{ $role->id }}); return false;" title="Edit" />
                        @endcan

                        @if ($role->name != 'Super Admin')
                            @can('role-delete')
                                <form action="{{ route('roles.destroy', $role->id) }}" method="post"
                                    onsubmit="swalConfirmationOnSubmit(event, 'Are you sure you want to delete this role?')">
                                    @csrf
                                    @method('delete')

                                    <x-button.delete onclick="this.closest('form').requestSubmit()" title="Delete" />
                                </form>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Edit Role Modal (Vue Component) -->
    <role-edit-modal :permissions='@json($permissions)' :update-url="'{{ route('roles.update', 0) }}'"></role-edit-modal>
@endsection
