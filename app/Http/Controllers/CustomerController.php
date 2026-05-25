<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Customer::class);

        $query = Customer::query()
            ->with('user')
            ->orderBy('name');

        if ($request->user()->isUser()) {
            $query->where('user_id', $request->user()->id);
        }

        return view('customers.index', [
            'customers' => $query->paginate(10),
        ]);
    }

    public function create(Request $request): View
    {
        Gate::authorize('create', Customer::class);

        return view('customers.create', [
            'users' => $this->availableUsers($request),
        ]);
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $customer = Customer::create($request->validated());

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function show(Customer $customer): View
    {
        Gate::authorize('view', $customer);

        return view('customers.show', [
            'customer' => $customer->load('user'),
        ]);
    }

    public function edit(Request $request, Customer $customer): View
    {
        Gate::authorize('update', $customer);

        return view('customers.edit', [
            'customer' => $customer,
            'users' => $this->availableUsers($request),
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        Gate::authorize('delete', $customer);

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }

    private function availableUsers(Request $request)
    {
        if ($request->user()->isUser()) {
            return collect([$request->user()]);
        }

        return User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }
}
