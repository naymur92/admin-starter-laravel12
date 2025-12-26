@extends('layouts.app')

@section('title', 'Login History Details')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 text-primary">Login History Details</h5>

                <x-button.back href="{{ route('login-history.index') }}">Back</x-button.back>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">ID</th>
                                <td>{{ $loginHistory->id }}</td>
                            </tr>
                            <tr>
                                <th>User</th>
                                <td>{{ $loginHistory->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $loginHistory->user->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($loginHistory->is_successful)
                                        <span class="badge badge-success">Successful</span>
                                    @else
                                        <span class="badge badge-danger">Failed</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Login Method</th>
                                <td><span class="badge badge-info">{{ $loginHistory->method_label }}</span></td>
                            </tr>
                            <tr>
                                <th>IP Address</th>
                                <td>{{ $loginHistory->ip_address }}</td>
                            </tr>
                            <tr>
                                <th>Location</th>
                                <td>{{ $loginHistory->location ?? 'Unknown' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Device</th>
                                <td>{{ $loginHistory->device ?? 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <th>Browser</th>
                                <td>{{ $loginHistory->browser ?? 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <th>Platform</th>
                                <td>{{ $loginHistory->platform ?? 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <th>Login At</th>
                                <td>{{ $loginHistory->login_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Logout At</th>
                                <td>{{ $loginHistory->logout_at ? $loginHistory->logout_at->format('Y-m-d H:i:s') : 'Still Active' }}</td>
                            </tr>
                            <tr>
                                <th>Session Duration</th>
                                <td>
                                    @if ($loginHistory->logout_at)
                                        {{ $loginHistory->login_at->diffForHumans($loginHistory->logout_at, true) }}
                                    @else
                                        {{ $loginHistory->login_at->diffForHumans() }}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if ($loginHistory->user_agent)
                    <div class="mt-4">
                        <h6>User Agent:</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <small>{{ $loginHistory->user_agent }}</small>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
