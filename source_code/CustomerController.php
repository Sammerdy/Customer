<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Service;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('cid')->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function show(int $id)
    {
        $customer = Customer::with('appointments.service')->findOrFail($id);
        // Force reload appointments.service to get latest sid_fk updates
        $customer->appointments->each(function ($appointment) {
            $appointment->load('service');
        });
        return view('customers.show', compact('customer'));
    }

    public function create(Request $request)
    {
        // Pass all service IDs for create dropdown
        $services = Service::pluck('sid');
        return view('customers.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'tel' => 'required',
            'appointments.*.sid_fk' => 'required|exists:tbl_service,sid',
            'appointments.*.appointment_time' => 'required|date',
        ]);

        // Create customer
        $customer = Customer::create($request->only(['name', 'tel']));

        // Create appointments
        $appointments = $request->input('appointments', []);
        foreach ($appointments as $data) {
            $customer->appointments()->create([
                'sid_fk' => $data['sid_fk'],
                'appointment_time' => $data['appointment_time'],
                'status' => 'pending',
            ]);
        }

        // Determine the page where this customer appears
        $perPage = 10;
        $position = Customer::orderBy('cid')->pluck('cid')->search($customer->cid); // zero-based index
        $page = intval(floor($position / $perPage)) + 1;

        return redirect()->route('customers.index', ['page' => $page])
            ->with('success', 'Customer and appointments created successfully!');
    }

    public function edit(Request $request, $id)
    {
        $customer = Customer::with('appointments')->findOrFail($id);
        $services = Service::pluck('sid');
        $page = $request->get('page', 1);
        return view('customers.edit', compact('customer', 'services', 'page'));
    }

    public function update(Request $request, int $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'tel' => 'required',
            'appointments.*.sid_fk' => 'nullable|integer',
            'appointments.*.appointment_time' => 'nullable|date',
        ]);

        // Update customer info
        $customer->update($request->only(['name', 'tel']));
        $customer->load('appointments.service');
        $appointmentsData = $request->input('appointments', []);

        foreach ($appointmentsData as $key => $data) {
            if (isset($data['id'])) {
                $appointment = $customer->appointments()->find($data['id']);
                if ($appointment) {
                    if (isset($data['_delete']) && $data['_delete'] == 1) {
                        $appointment->delete();
                    } else {
                        $appointment->update([
                            'sid_fk' => $data['sid_fk'],
                            'appointment_time' => $data['appointment_time'],
                        ]);
                    }
                }
            } else {
                // New appointment
                if (!empty($data['sid_fk']) && !empty($data['appointment_time'])) {
                    $customer->appointments()->create([
                        'sid_fk' => $data['sid_fk'],
                        'appointment_time' => $data['appointment_time'],
                        'status' => 'pending',
                    ]);
                }
            }
        }

        // Get current page from form
        $page = $request->input('page', 1);

        return redirect()->route('customers.index', ['page' => $page])
            ->with('success', 'Customer and appointments updated successfully!');
    }

    public function destroy(Request $request, int $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        $perPage = 10; // pagination size
        $page = $request->input('page', 1);

        // Total customers after deletion
        $totalCustomers = Customer::count();

        // Last page available
        $lastPage = ceil($totalCustomers / $perPage);

        // If the current page is now empty, go to the last page
        if ($page > $lastPage) {
            $page = $lastPage > 0 ? $lastPage : 1;
        }

        return redirect()->route('customers.index', ['page' => $page])
            ->with('success', 'Customer deleted successfully!');
    }
}
