<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\CustomerRequest;
use App\Models\Company;
use App\Models\Customer;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(Request $request): Response
    {
        $search = (string) $request->query('search', '');

        return Inertia::render('Features/Customers/pages/index', [
            'filters' => ['search' => $search],
            'customers' => Customer::query()
                ->with('company:id,name')
                ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search): void {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                }))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Features/Customers/pages/form', [
            'companies' => Company::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(CustomerRequest $request, ActivityLogger $logger): RedirectResponse
    {
        $customer = Customer::query()->create($request->validated() + ['owner_id' => $request->user()?->id]);
        $logger->log('customer.created', $customer, [], $request);

        return to_route('customers.index')->with('success', 'Customer created.');
    }
}
