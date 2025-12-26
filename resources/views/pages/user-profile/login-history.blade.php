@extends('layouts.app')

@section('title', 'My Login History')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 text-primary">My Login History</h5>
                {{-- <a href="{{ route('user-profile.show') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back to Profile
                </a> --}}
                <x-button.back href="{{ route('user-profile.show') }}">Back to Profile</x-button.back>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 15%">IP Address</th>
                                <th style="width: 15%">Device</th>
                                <th style="width: 15%">Browser</th>
                                <th style="width: 10%">Platform</th>
                                <th style="width: 10%">Status</th>
                                <th style="width: 15%">Login Time</th>
                                <th style="width: 15%">Logout Time</th>
                                <th style="width: 5%">Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logins as $login)
                                <tr>
                                    <td>{{ $login->ip_address }}</td>
                                    <td>{{ $login->device ?? 'Unknown' }}</td>
                                    <td>{{ $login->browser ?? 'Unknown' }}</td>
                                    <td>{{ $login->platform ?? 'Unknown' }}</td>
                                    <td>
                                        @if ($login->is_successful)
                                            <span class="badge badge-success">Success</span>
                                        @else
                                            <span class="badge badge-danger">Failed</span>
                                        @endif
                                    </td>
                                    <td>{{ $login->login_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        @if ($login->logout_at)
                                            {{ $login->logout_at->format('Y-m-d H:i:s') }}
                                        @else
                                            <span class="badge badge-info">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($login->logout_at)
                                            {{ $login->login_at->diffForHumans($login->logout_at, true) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No login history found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $logins->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
