@extends('layouts.app')

@section('title', 'User Profile | Edit')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        {{-- <h1 class="h3 mb-2 text-gray-800">Edit User Profile</h1> --}}

        <div class="row justify-content-center">
            <div class="col-12 col-xl-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 text-primary">User Profile Edit</h5>
                        <div class="ms-auto">
                            <div class="btn-list">
                                <x-button.back href="{{ route('user-profile.show') }}"></x-button.back>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('user-profile.update') }}" method="POST" onsubmit="swalConfirmationOnSubmit(event, 'Are you sure?');">
                        @csrf
                        @method('put')

                        <div class="card-body">
                            <div class="row">

                                {{-- name --}}
                                <div class="form-group col-12 col-lg-6 mb-3">
                                    <x-form.input name="name" label="Name" required value="{{ old('name', $user->name) }}" placeholder="Name" />
                                </div>

                                {{-- email/ user id --}}
                                <div class="form-group col-12 col-lg-6 mb-3">
                                    <x-form.input type="email" name="email" label="E-mail" required value="{{ old('email', $user->email) }}" placeholder="abc@example.com" />
                                </div>

                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <input class="btn btn-primary" type="submit" value="Update Profile">
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
