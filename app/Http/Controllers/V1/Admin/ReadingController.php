<?php

namespace App\Http\Controllers\V1\Admin;

use App\Models\Reading;
use App\Models\Barangay;
use App\Models\Customer;
use App\Models\Sequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Http\Resources\ReadingResource;
use App\Actions\Customers\StoreCustomer;
use App\Actions\Readings\ReaderReadings;
use App\Actions\Customers\UpdateCustomer;
use App\Actions\Readings\GenerateReadings;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Requests\Customer\CustomerStoreRequest;
use App\Http\Requests\Customer\CustomerUpdateRequest;

class ReadingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (request()->has('generate') && request()->has('day') && request()->has('barangay_id')) {
            $readings = (new GenerateReadings())
                ->execute(
                    Barangay::find(request()->barangay_id),
                    (int) request()->day
                );
        } else {
            $readings = (new ReaderReadings())->get();
        }

        return ReadingResource::collection($readings);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerStoreRequest $request)
    {
    
        //$customer = Customer::create($request->validated());
        return (new StoreCustomer)->execute($request);
    }


    /**
     * Show
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        return (new UpdateCustomer)->execute($request, $customer);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return response()->noContent();
    }


    /**
     * Generate Readings
     *
     * @return \Illuminate\Http\Response
     */
    public function generate(Request $request)
    {
        $readings = (new GenerateReadings())
            ->execute(
                Barangay::find($request->barangay_id),
                (int) $request->day
            );
        
        return ReadingResource::collection($readings);
    }

    /**
     * Generate Account No.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function generateAccountNo(Request $request)
    {
        return response()->json([
            'data' => [
                'account_number' => Customer::generateAccountNo()
            ],
        ]);
    }
}
