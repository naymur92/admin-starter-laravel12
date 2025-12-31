@extends('layouts.app')

@section('title', 'User Profile | Show')

@push('styles')
@endpush

@push('scripts')
@endpush

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        {{-- <h1 class="h3 mb-2 text-gray-800">User Profile</h1> --}}

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h5 class="m-0 text-primary">My Profile</h5>
                        <div class="ms-auto">
                            <div class="btn-list">
                                <x-button.back href="{{ route('dashboard') }}"></x-button.back>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center justify-content-between">
                            <div class="col-12 col-xl-8">
                                <table class="show-table table border table-bordered">
                                    <tr>
                                        <th style="width: 20%">Name</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>

                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <x-badge-status :status="$user->status"></x-badge-status>
                                        </td>
                                    </tr>

                                </table>
                            </div>

                            <div class="col-12 col-xl-4 text-center">
                                @if (Auth::user()->profilePicture)
                                    <img class="img-thumbnail" src="{{ asset('/') }}{{ Auth::user()->profilePicture->path . '/' . Auth::user()->profilePicture->name }}"
                                        style="width: 20vw;">
                                @else
                                    <img class="img-thumbnail" src="{{ asset('/') }}uploads/users/user.png" style="width: 20vw;">
                                @endif
                                <br>
                                <button class="btn btn-outline-success mt-2" data-toggle="modal" data-target="#profilePictureChangeModal">Change Profile Picture</button>

                                {{-- profile picture change modal --}}
                                <div class="modal fade" id="profilePictureChangeModal" tabindex="-1" aria-labelledby="profilePictureChangeModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="profilePictureChangeModalLabel">Change Profile Picture
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <form id="profile_picture_change_form" action="{{ route('user-profile.change-profile-picture') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body text-center">
                                                    <image-uploader
                                                        initial-src="{{ Auth::user()->profilePicture ? asset('/') . Auth::user()->profilePicture->path . '/' . Auth::user()->profilePicture->name : asset('/') . 'uploads/users/user.png' }}"
                                                        name="profile_picture" input-id="_pp" size="15vw"></image-uploader>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <a href="{{ route('user-profile.login-history') }}" class="btn btn-outline-info mr-2 br-5 waves-effect waves-light">
                            <i class="fas fa-history"></i> My Login History
                        </a>

                        <x-button.edit href="{{ route('user-profile.edit') }}">Edit Profile</x-button.edit>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
