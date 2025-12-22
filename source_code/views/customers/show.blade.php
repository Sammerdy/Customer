@extends('layouts.app')

@section('title', 'View Customer')

@section('content')
    <div class="container mt-4">
        
        <!-- Customer Details (TABLE STYLE) -->
        <h5 class="mb-2">Customer Details</h5>
        <table class="table table-bordered mb-4">
            <thead class="table-secondary text-center">
                <tr>
                    <th>CID</th>
                    <th>Name</th>
                    <th>Tel</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <tr>
                    <td>{{ $customer->cid }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->tel }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Appointments (SAME STYLE) -->
        <h5 class="mb-2">Appointments</h5>

        @if ($customer->appointments->count() > 0)
            <table class="table table-bordered table-hover text-center">
                <thead class="table-secondary">
                    <tr>
                        <th>ID</th>
                        <th>Service</th>
                        <th>Appointment Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customer->appointments as $appointment)
                        <tr>
                            <td>{{ $appointment->id }}</td>
                            <td>{{ $appointment->service->name ?? 'N/A' }}</td>
                            <td>{{ $appointment->appointment_time }}</td>
                            <td>
                                <span
                                    class="badge
                            @if ($appointment->status === 'confirmed') bg-success
                            @elseif($appointment->status === 'pending') bg-warning
                            @else bg-danger @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted">No appointments found.</p>
        @endif

        <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">
            Back to List
        </a>

    </div>
@endsection
