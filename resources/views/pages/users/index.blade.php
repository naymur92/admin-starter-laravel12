@extends('layouts.app')

@section('title', 'Auth Users')

@push('styles')
    <link href="{{ asset('/') }}assets/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
@endpush

@push('scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('/') }}assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('/') }}assets/js/demo/datatables-demo.js"></script>
@endpush

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        {{-- <h1 class="h3 mb-2 text-gray-800">User Management</h1> --}}

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 text-primary">Auth Users List</h5>

                <div class="ms-auto">
                    <div class="btn-list">
                        @can('user-create')
                            <x-button.add-new href="{{ route('users.create') }}">Add New User</x-button.add-new>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover text-nowrap text-center align-middle" id="dataTable" width="100%" cellspacing="0">

                                <colgroup>
                                    <col style="width: 10%;">
                                    <col style="width: 20%;">
                                    <col style="width: 25%;">
                                    <col style="width: 15%;">
                                    <col style="width: 15%;">
                                    <col style="width: 15%;">
                                </colgroup>

                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th class="no-sort">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td class="text-center" data-order="{{ $loop->iteration }}">{{ $loop->iteration }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>

                                            <td class="text-center">{{ $user->getTypeLabelAttribute() }}
                                            </td>
                                            <td class="text-center">
                                                <x-badge-is-active :isActive="$user->is_active"></x-badge-is-active>
                                            </td>
                                            <td>
                                                <div class="text-center d-flex justify-content-center align-items-center">

                                                    @can('user-list')
                                                        <x-icon.eye href="{{ route('users.show', $user->id) }}"></x-icon.eye>
                                                    @endcan

                                                    @if (auth()->user()->can('user-edit') && $user->id != 1 && $user->id != Auth::user()->id)
                                                        <x-icon.pen href="{{ route('users.edit', $user->id) }}"></x-icon.pen>

                                                        {{-- set inactive --}}
                                                        @if ($user->is_active == 1)
                                                            <form action="{{ route('users.change-status', $user->id) }}" method="POST"
                                                                onsubmit="swalConfirmationOnSubmit(event, 'Are you sure?');">
                                                                @csrf
                                                                @method('put')
                                                                <input type="text" value="0" name="is_active" hidden>

                                                                <input type="submit" class="hidden-submit-btn" hidden>
                                                                <x-icon.times type="button" title="Mark In-Active" onclick="this.closest('form').requestSubmit()"></x-icon.times>
                                                            </form>
                                                        @endif

                                                        {{-- set active --}}
                                                        @if ($user->is_active == 0)
                                                            <form action="{{ route('users.change-status', $user->id) }}" method="POST"
                                                                onsubmit="swalConfirmationOnSubmit(event, 'Are you sure?');">
                                                                @csrf
                                                                @method('put')
                                                                <input type="text" value="1" name="is_active" hidden>

                                                                <input type="submit" class="hidden-submit-btn" hidden>
                                                                <x-icon.check type="button" title="Mark Active" onclick="this.closest('form').requestSubmit()"></x-icon.check>
                                                            </form>
                                                        @endif
                                                    @endif

                                                </div>

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
@endsection
