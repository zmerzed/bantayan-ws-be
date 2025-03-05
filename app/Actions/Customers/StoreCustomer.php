<?php

namespace App\Actions\Customers;

use Carbon\Carbon;
use App\Models\Customer;
use App\Enums\AccountType;
use Illuminate\Http\Request;
use App\Enums\CustomerStatus;
use App\Enums\ApplicationType;
use Appetiser\Auth\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Requests\Customer\CustomerStoreRequest;

class StoreCustomer
{
    public function execute(CustomerStoreRequest|Request $request): CustomerResource
    {
        $customer = DB::transaction(function () use ($request) {
            $data = array_merge($request->validated(), [
                'account_number' => Customer::generateAccountNo(),
                'application_type' => ApplicationType::fromKey(strtoupper($request->application_type))->value,
                'account_type' => AccountType::fromKey(strtoupper($request->account_type))->value,
                'status' => CustomerStatus::PENDING,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            $customer = Customer::create($data);
            $customer->details()->create(['customer_id' => $customer->id]);
            return $customer;
        });

        return new CustomerResource($customer);
    }
}
