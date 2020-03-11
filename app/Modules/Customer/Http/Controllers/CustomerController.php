<?php

namespace App\Modules\Customer\Http\Controllers;

use App\Modules\Customer\CustomerDataService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Customer\Customer;
use App\Modules\Customer\Http\Requests\CustomerRequest;
use App\Modules\Customer\Http\Resources\CustomerCollection;
use App\Modules\Customer\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    /**
     * @var CustomerDataService
     */
    private $customerDataService;

    public function __construct(CustomerDataService $customerDataService)
    {
        $this->middleware('auth:api');

        $this->customerDataService = $customerDataService;
    }

    /**
     * Get the list of paginated customers.
     *
     * @return CustomerCollection
     */
    public function index()
    {
        return new CustomerCollection(
            $this->customerDataService->paginatedList([
                'pageSize' => request()->get('pageSize'),
                'sortField' => request()->get('sortField'),
                'sortOrder' => request()->get('sortOrder'),
                'searchQuery' => request()->get('search_query'),
                'created_at_range' => request()->get('created_at_range'),
        ]));
    }

    /**
     * Show a single customer resource from the database.
     *
     * @return CustomerResource
     */
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return new CustomerResource($customer);
    }

    /**
     * Get all the customers useful for selects.
     *
     * @return CustomerCollection
     */
    public function all()
    {
        $collections = Customer::orderBy('name','asc')->get();
        return new CustomerCollection($collections);
    }

    /**
     * Store the customer resource to the database.
     *
     * @param CustomerRequest $request
     * @return CustomerResource
     */
    public function store(CustomerRequest $request)
    {
        $validated = $request->validated();
        return new CustomerResource(
            $this->customerDataService->store($validated)
        );
    }

    /**
     * Update the customer resource to the database.
     *
     * @param CustomerRequest $request
     * @param Customer $customer
     * @return JsonResponse
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        $validated = $request->validated();
        $this->customerDataService->update($customer, $validated);
        return response()->json('Successfully updated');
    }

    /**
     * Delete the customers using the specified ids.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $this->customerDataService->destroy($request->get('ids'));
        return response()->json('Customers are successfully deleted.');
    }
}
