@extends('layouts.app')
@section('title', 'Customer List')

@if (session('success'))
    <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<script>
    // Auto dismiss alert after 1 seconds (1000 milliseconds)
    setTimeout(function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            // Bootstrap 5 fade out and remove
            alert.classList.remove('show');
            alert.classList.add('hide');
            setTimeout(() => alert.remove(), 500); // remove from DOM after fade out
        }
    }, 1500);
</script>


@section('content')
    <a href="{{ route('customers.create', ['page' => request()->get('page', 1)]) }}" class="btn btn-primary mb-3 mt-3">
        CREATE CUSTOMER
    </a>

    <table class="table table-hover align-middle">
        <table class="table table-bordered mb-4">
            <thead class="table-primary text-center">
                <tr>
                    <th>CID</th>
                    <th>Name</th>
                    <th>Tel</th>
                    <th>Appointments</th>
                    <th width="220">Action</th>
                </tr>
            </thead>

            <tbody class="table-light">
                @foreach ($customers as $customer)
                    <tr>
                        <td class="text-center">{{ $customer->cid }}</td>
                        <td class="text-center">{{ $customer->name }}</td>
                        <td class="text-center">{{ $customer->tel }}</td>

                        {{-- Appointment count --}}
                        <td class="text-center">
                            <span class="badge bg-info">
                                {{ $customer->appointments->count() }}
                            </span>
                        </td>

                        {{-- Action buttons --}}
                        <td class="text-center">
                            <a href="{{ route('customers.show', $customer->cid) }}" class="btn btn-sm btn-info">
                                View
                            </a>

                            <a href="{{ route('customers.edit', ['customer' => $customer->cid, 'page' => request()->get('page', 1)]) }}"
                                class="btn btn-sm btn-warning">
                                Edit
                            </a>

                            <form action="{{ route('customers.destroy', $customer->cid) }}" method="POST"
                                class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="page" value="{{ request()->get('page', 1) }}">
                                <button type="button" class="btn btn-danger btn-delete">Delete</button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $customers->links('pagination::bootstrap-5') }}
    @endsection
