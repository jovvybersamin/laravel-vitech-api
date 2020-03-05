<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerCollection;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Get the list of paginated customers.
     *
     * @return CustomerCollection
     */
    public function index()
    {
        $pageSize = request()->get('pageSize');
        $sortField = request()->get('sortField') ;
        $sortOrder = request()->get('sortOrder') ;
        $searchQuery = request()->get('search_query');

        if (!empty($sortField)) {
            $sortOrder = empty($sortOrder) ? 'ascend' : $sortOrder;
            $sortOrder = $sortOrder === 'ascend' ? 'asc' : 'desc';
        }

        $userBuilder = Customer::orderBy($sortField, $sortOrder);

        if ($searchQuery) {
            $userBuilder = $userBuilder->where('name', 'like', '%' . $searchQuery . '%')
                ->orWhere('email', 'like', '%' . $searchQuery . '%');
        }

        $collections = $userBuilder->paginate($pageSize);

        return new CustomerCollection($collections);
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

        $customer = Customer::create([
            'name' => $validated['name'],
            'email' => $validated['email']
        ]);

        return new CustomerResource($customer);
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

        $customer->update([
            'name' => $validated['name'],
            'email' => $validated['email']
        ]);

        return response()->json('Successfully updated');
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

}
