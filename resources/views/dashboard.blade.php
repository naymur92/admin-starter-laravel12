@extends('layouts.app')

@section('title', 'Dashboard')

@push('scripts')
    <!-- Page level plugins -->
    {{-- <script src="{{ asset('/') }}assets/vendor/chart.js/Chart.min.js"></script> --}}

    <!-- Page level custom scripts -->
    {{-- <script src="{{ asset('/') }}assets/js/demo/chart-area-demo.js"></script> --}}
    {{-- <script src="{{ asset('/') }}assets/js/demo/chart-pie-demo.js"></script> --}}
@endpush

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
        </div>

        <!-- Content Row -->
        <div class="row">

        </div>

    </div>
@endsection
