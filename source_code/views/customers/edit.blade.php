@extends('layouts.app')
@section('title', 'Edit Customer')

@section('content')
    <form method="POST" action="{{ route('customers.update', $customer->cid) }}" class="row g-3 needs-validation">
        @csrf
        @method('PUT')
        <input type="hidden" name="page" value="{{ request()->get('page', 1) }}">

        {{-- Customer Info --}}
        <div class="col-md-4">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" id="name"
                value="{{ old('name', $customer->name) }}" placeholder="Enter Name" required>
        </div>

        <div class="col-md-4">
            <label for="tel" class="form-label">Tel</label>
            <input type="text" class="form-control" name="tel" id="tel"
                value="{{ old('tel', $customer->tel) }}" placeholder="Enter Tel" required>
        </div>

        {{-- Appointments --}}
        <div class="col-12 mt-3">
            <h5>Appointments</h5>

            @foreach ($customer->appointments as $appointment)
                <div class="row mb-2 appointment-row">
                    <input type="hidden" name="appointments[{{ $appointment->id }}][id]" value="{{ $appointment->id }}">
                    <input type="hidden" name="page" value="{{ request()->get('page', 1) }}">
                    <input type="hidden" name="appointments[{{ $appointment->id }}][_delete]" value="0"
                        class="delete-flag">
                    <div class="col-md-4">
                        <label>Service ID</label>
                        <input type="number" class="form-control" name="appointments[{{ $appointment->id }}][sid_fk]"
                            value="{{ old("appointments.{$appointment->id}.sid_fk", $appointment->sid_fk) }}">
                    </div>
                    <div class="col-md-4">
                        <label>Appointment Time</label>
                        <input type="datetime-local" class="form-control"
                            name="appointments[{{ $appointment->id }}][appointment_time]"
                            value="{{ old("appointments.{$appointment->id}.appointment_time", date('Y-m-d\TH:i', strtotime($appointment->appointment_time))) }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-remove-appointment">Remove</button>
                    </div>
                </div>
            @endforeach

            <button type="button" class="btn btn-sm btn-success mt-2" id="add-appointment">+ Add Appointment</button>
        </div>

        {{-- Submit --}}
        <div class="col-12 mt-3">
            <a href="{{ route('customers.index', ['page' => request()->get('page', 1)]) }}" class="btn btn-secondary me-2">
                Back to List
            </a>
            <button class="btn btn-primary" type="submit">UPDATE</button>
        </div>
    </form>

    {{-- JS for adding/removing appointments --}}
    <script>
        document.getElementById('add-appointment').addEventListener('click', function() {
            const container = document.querySelector('.col-12 h5').parentNode;
            const index = Date.now();
            const html = `
            <div class="row mb-2 appointment-row">
                <div class="col-md-4">
                    <label>Service ID</label>
                    <input type="number" class="form-control" name="appointments[new_${index}][sid_fk]">
                </div>
                <div class="col-md-4">
                    <label>Appointment Time</label>
                    <input type="datetime-local" class="form-control" name="appointments[new_${index}][appointment_time]">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove-appointment">Remove</button>
                </div>
            </div>
        `;
            container.insertAdjacentHTML('beforeend', html);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-appointment')) {
                const row = e.target.closest('.appointment-row');
                const deleteInput = row.querySelector('.delete-flag');
                if (deleteInput) {
                    deleteInput.value = 1; // mark for deletion
                }
                row.style.display = 'none'; // hide from user
            }
        });
    </script>
@endsection
