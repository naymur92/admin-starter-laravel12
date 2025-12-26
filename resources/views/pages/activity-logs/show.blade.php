@extends('layouts.app')

@section('title', 'Activity Log Details')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="m-0 text-primary">Activity Log Details</h5>

                <x-button.back href="{{ route('activity-logs.index') }}">Back</x-button.back>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">ID</th>
                                <td>{{ $activityLog->id }}</td>
                            </tr>
                            <tr>
                                <th>Log Name</th>
                                <td>{{ $activityLog->log_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ $activityLog->description }}</td>
                            </tr>
                            <tr>
                                <th>Event</th>
                                <td>
                                    @if ($activityLog->event)
                                        <span class="badge badge-info">{{ strtoupper($activityLog->event) }}</span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Causer</th>
                                <td>
                                    @if ($activityLog->causer)
                                        {{ $activityLog->causer->name ?? 'N/A' }}
                                        <small class="text-muted">({{ $activityLog->causer_type }})</small>
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Subject</th>
                                <td>
                                    @if ($activityLog->subject)
                                        {{ class_basename($activityLog->subject_type) }} #{{ $activityLog->subject_id }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">IP Address</th>
                                <td>{{ $activityLog->ip_address }}</td>
                            </tr>
                            <tr>
                                <th>User Agent</th>
                                <td><small>{{ $activityLog->user_agent ?? 'N/A' }}</small></td>
                            </tr>
                            <tr>
                                <th>Date/Time</th>
                                <td>{{ $activityLog->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Human Time</th>
                                <td>{{ $activityLog->created_at->diffForHumans() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if ($activityLog->properties)
                    <div class="mt-4">
                        <h6>Properties:</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <pre class="mb-0">{{ json_encode($activityLog->properties, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
