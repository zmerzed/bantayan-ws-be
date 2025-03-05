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

class UpdateCustomer
{
    public function execute(CustomerUpdateRequest|Request $request, Customer $customer): CustomerResource
    {
        $customer = DB::transaction(function () use ($request, $customer) {
            $data = array_merge($request->validated(), [
                //'account_number' => Customer::generateAccountNo(),
                'application_type' => ApplicationType::fromKey(strtoupper($request->application_type))->value,
                'account_type' => AccountType::fromKey(strtoupper($request->account_type))->value,
                'status' => CustomerStatus::PENDING,
                //'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            $customer->update($data);
            return $customer;
        });

        return new CustomerResource($customer);
    }
}
