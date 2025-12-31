@extends('layouts.app')

@section('title', 'Auth Users | Show')

@push('styles')
@endpush

@push('scripts')
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        {{-- <h1 class="h3 mb-2 text-gray-800">Show User</h1> --}}

        <div class="row justify-content-center">
            <div class="col-12 col-xl-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 text-primary">Auth User Profile</h5>

                        @can('user-list')
                            <x-button.back href="{{ route('users.index') }}"></x-button.back>
                        @endcan
                    </div>

                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-12 col-xl-8">
                                <table class="show-table table border table-bordered">
                                    <tr>
                                        <th style="width: 20%;">Name</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>

                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <x-badge-is-active :isActive="$user->is_active"></x-badge-is-active>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Type</th>
                                        <td>
                                            {{ $user->getTypeLabelAttribute() }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Roles</th>
                                        <td>
                                            @if (!empty($user->getRoleNames()))
                                                @foreach ($user->getRoleNames() as $v)
                                                    <label class="badge badge-success">{{ $v }}</label>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Permissions</th>
                                        <td>
                                            @if (count($user->getAllPermissions()) > 0)
                                                @foreach ($user->getAllPermissions() as $v)
                                                    <label class="badge badge-primary">{{ $v->name }}</label>
                                                @endforeach
                                            @else
                                                <label class="badge badge-danger">No Permission</label>
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Created By</th>
                                        <td>
                                            {{ $user->createdBy->name ?? '' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Updated By</th>
                                        <td>
                                            {{ $user->updatedBy->name ?? '' }}
                                        </td>
                                    </tr>

                                </table>
                            </div>

                            <div class="col-12 col-md-4 text-center">
                                @if ($user->profilePicture)
                                    <img class="img-thumbnail" src="{{ asset('/') }}{{ $user->profilePicture->path . '/' . $user->profilePicture->name }}" style="width: 20vw;">
                                @else
                                    <img class="img-thumbnail" src="{{ asset('/') }}uploads/users/user.png" style="width: 20vw;">
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">

                        @if (auth()->user()->can('user-edit') && $user->id != Auth::user()->id && $user->id != 1)
                            <x-button.edit href="{{ route('users.edit', $user->id) }}"></x-button.edit>

                            @if ($user->status == 1)
                                <form action="{{ route('users.change-status', $user->id) }}" method="POST" onsubmit="swalConfirmationOnSubmit(event, 'Are you sure?');">
                                    @csrf
                                    @method('put')
                                    <input type="text" value="2" name="status" hidden>

                                    <button type="submit" class="btn btn-outline-danger"><i class="fas fa-times"></i> Mark Inactive</button>
                                </form>
                            @endif

                            {{-- set active --}}
                            @if ($user->status == 2)
                                <form action="{{ route('users.change-status', $user->id) }}" method="POST" onsubmit="swalConfirmationOnSubmit(event, 'Are you sure?');">
                                    @csrf
                                    @method('put')
                                    <input type="text" value="1" name="status" hidden>

                                    <button type="submit" class="btn btn-outline-success"><i class="fas fa-check"></i> Mark Active</button>
                                </form>
                            @endif

                            {{-- password reset --}}
                            <a href="{{ route('users.change-password', $user->id) }}" class="btn btn-outline-info br-5 waves-effect waves-light">
                                <i class="fa-solid fa-eraser"></i> Reset Password
                            </a>

                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
