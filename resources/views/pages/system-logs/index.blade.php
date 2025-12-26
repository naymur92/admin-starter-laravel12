@extends('layouts.app')

@section('title', 'System Logs')

@push('scripts')
    <script>
        function confirmDelete(logName) {
            swalConfirmation(`Are you sure you want to delete the log file "${logName}"?`).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('deleteForm');
                    form.action = `/admin/system-logs/${logName}`;
                    form.submit();
                }
            });
        }
    </script>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h5 class="m-0 text-primary">System Log Files</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 40%">File Name</th>
                                <th style="width: 15%">Size</th>
                                <th style="width: 20%">Last Modified</th>
                                <th style="width: 25%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>
                                        <i class="fas fa-file-alt text-secondary"></i>
                                        {{ $log['name'] }}
                                    </td>
                                    <td>{{ $log['size'] }}</td>
                                    <td>{{ $log['modified_human'] }}</td>
                                    <td>
                                        <div class="text-center d-flex justify-content-center align-items-center">
                                            <x-icon.eye :href="route('system-logs.show', $log['name'])" />
                                            <x-icon.download :href="route('system-logs.download', $log['name'])" />
                                            @if (!in_array($log['name'], ['laravel.log']))
                                                <x-icon.trash onclick="confirmDelete('{{ $log['name'] }}')">Delete</x-icon.trash>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No log files found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden form for delete operation --}}
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
@endsection
