<?php
namespace App\Modules\Customer;

use App\Modules\Customer\Customer;
use Carbon\Carbon;

class CustomerDataService
{

    /**
     * @var Customer
     */
    private $customer;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }


    /***
     * GET: Get the paginated list of customers.
     *
     * @param $request
     * @return mixed
     */
    public function paginatedList($request)
    {
        $pageSize = $request['pageSize'];
        $sortField = $request['sortField'] ;
        $sortOrder = $request['sortOrder'] ;
        $searchQuery = $request['searchQuery'];
        $createdAtRange = $request['created_at_range'];


        if (!empty($sortField)) {
            $sortOrder = empty($sortOrder) ? 'ascend' : $sortOrder;
            $sortOrder = $sortOrder === 'ascend' ? 'asc' : 'desc';
        }

        $queryBuilder = Customer::orderBy($sortField, $sortOrder);

        if(isset($createdAtRange)) {
            $createdAtRange[0] = str_replace('"',"", $createdAtRange[0]);
            $startDate = Carbon::parse($createdAtRange[0]);
            $createdAtRange[1] = str_replace('"',"", $createdAtRange[1]);
            $endDate = Carbon::parse($createdAtRange[1]);

            $queryBuilder = $queryBuilder->whereBetween('created_at',[$startDate,$endDate]);
        }

        //dd($queryBuilder->toSql());

        if ($searchQuery) {
            $queryBuilder = $queryBuilder->where('name', 'like', '%' . $searchQuery . '%')
                ->orWhere('email', 'like', '%' . $searchQuery . '%');
        }

        return $queryBuilder->paginate($pageSize);
    }

    /**
     * POST: Store a new customer.
     *
     * @param array $attributes
     * @return mixed
     */
    public function store(array $attributes)
    {
        $customer = Customer::create([
            'name' => $attributes['name'],
            'email' => $attributes['email']
        ]);

        return $customer;
    }

    /**
     * POST: Update an existing customer.
     *
     * @param \App\Modules\Customer\Customer $customer
     * @param $attributes
     */
    public function update(Customer $customer, $attributes)
    {
        $customer->update([
            'name' => $attributes['name'],
            'email' => $attributes['email']
        ]);
    }


    /**
     * DELETE: Delete all customers using the specified ids.
     *
     * @param array $ids
     */
    public function destroy(array $ids)
    {
        Customer::destroy($ids);

    }


}
