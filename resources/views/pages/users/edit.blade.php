@extends('layouts.app')

@section('title', 'Auth Users | Edit')

@push('styles')
@endpush

@push('scripts')
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        {{-- <h1 class="h3 mb-2 text-gray-800">{{ $user->name }}</h1> --}}

        <div class="row justify-content-center">
            <div class="col-12 col-xl-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 text-primary">Auth User Edit</h5>

                        @can('user-list')
                            <x-button.back href="{{ route('users.index') }}"></x-button.back>
                        @endcan
                    </div>

                    <form action="{{ route('users.update', $user->id) }}" method="POST" onsubmit="swalConfirmationOnSubmit(event, 'Are you sure you want to update this auth user?');">
                        @method('put')
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12 col-lg-6 mb-3">
                                    <x-form.input name="name" label="Full Name" required="true" value="{{ old('name', $user->name) }}" placeholder="Abdur Rahman">
                                    </x-form.input>
                                </div>

                                <div class="form-group col-12 col-lg-6 mb-3">
                                    <x-form.input name="email" label="Email" type="email" required="true" value="{{ old('email', $user->email) }}" placeholder="abc@example.com">
                                    </x-form.input>
                                </div>

                                <div class="form-group col-12 col-lg-6 mb-3">
                                    <x-form.select name="user_type" label="Select User Type" required="true" placeholder="Select One" :options="$userTypes" value-by="key"
                                        :selected="old('user_type', $user->type)" />
                                </div>

                                <div class="form-group col-12 col-lg-6 mb-3">
                                    <x-form.select name="is_active" label="Select Status" required="true" placeholder="Select One" :options="$isActiveOptions" value-by="key" :selected="old('is_active', $user->is_active)" />
                                </div>

                                <div class="form-group col-12 col-lg-12 mb-3">
                                    <x-form.select name="roles" label="Select Role" :multiple="true" placeholder="Select One/Multiple" :options="$roles" value-by="value"
                                        :selected="old('roles', $userRoles)" />
                                </div>

                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <input type="submit" value="Update" class="btn btn-success">
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
