@extends('layouts.app')
@section('title', 'Create Customer')

@section('content')
    <form method="POST" action="{{ route('customers.store') }}" class="row g-3 needs-validation">
        @csrf

        {{-- Pass the current page for back button --}}
        <input type="hidden" name="page" value="{{ request()->get('page', 1) }}">

        {{-- Customer Info --}}
        <div class="col-md-4">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}"
                placeholder="Enter Name" required>
        </div>

        <div class="col-md-4">
            <label for="tel" class="form-label">Tel</label>
            <input type="number" class="form-control" name="tel" id="tel" value="{{ old('tel') }}"
                placeholder="Enter Tel" required>
        </div>

        {{-- Appointments --}}
        <div class="col-12 mt-3">
            <h5>Appointments</h5>

            {{-- Container for dynamic appointments --}}
            <div id="appointments-container"></div>

            <button type="button" class="btn btn-sm btn-success mt-2" id="add-appointment">+ Add Appointment</button>
        </div>

        {{-- Submit --}}
        <div class="col-12 mt-3">
            <a href="{{ route('customers.index', ['page' => request()->get('page', 1)]) }}" class="btn btn-secondary me-2">
                Back to List
            </a>
            <button class="btn btn-primary" type="submit">
                Create
            </button>
        </div>
    </form>

    {{-- JS for adding/removing appointments --}}
    <script>
        const container = document.getElementById('appointments-container');
        const addBtn = document.getElementById('add-appointment');

        // Array of service IDs passed from controller
        const services = @json($services); // Example: [1,2,3,4]

        addBtn.addEventListener('click', function() {
            const index = Date.now(); // unique index for new appointments
            let options = '<option value="">-- Select Service ID --</option>';
            services.forEach(id => {
                options += `<option value="${id}">${id}</option>`;
            });

            const html = `
        <div class="row mb-2 appointment-row">
            <div class="col-md-4">
                <label>Service ID</label>
                <select class="form-control" name="appointments[new_${index}][sid_fk]" required>
                    ${options}
                </select>
            </div>

            <div class="col-md-4">
                <label>Appointment Time</label>
                <input type="datetime-local" class="form-control" name="appointments[new_${index}][appointment_time]" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-remove-appointment">Remove</button>
            </div>
        </div>`;
            container.insertAdjacentHTML('beforeend', html);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-appointment')) {
                e.target.closest('.appointment-row').remove();
            }
        });
    </script>
@endsection
