<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\CustomerRequest;
use App\Services\CustomerService;

class CustomerController extends Controller
{
    /**
     * customerService
     *
     * @var mixed
     */
    private $customerService;

    /**
     * __construct
     *
     * @param  CustomerService $customerService
     * @return void
     */
    public function __construct(
        CustomerService $customerService
    ) {
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::paginate('10');
        return view('dashboard.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        $data = $request->validated();

        $this->customerService->create($data);

        return redirect()->route('customers.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        return view('dashboard.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('dashboard.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $data = $request->validated();

        $this->customerService->update($customer, $data);

        return redirect()->route('customers.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * deletePerson
     *
     * @param  mixed $id
     * @return void
     */
    public function deletePerson(string $id)
    {
        try {
            $this->customerService->deletePerson($id);
            return [
                'status' => 200,
                'message' => 'Person Deleted'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
    }
}
