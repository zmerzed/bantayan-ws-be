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
            //dd($request->all());
            $authId = $request->is_cron ? 1 : auth()->user()->id;
            $data = array_merge($request->is_cron ? $request->all() : $request->validated(), [
                'account_number' => $request->account_number ? $request->account_number : Customer::generateAccountNo(),
                'application_type' => ApplicationType::fromKey(strtoupper($request->application_type))->value,
                'account_type' => AccountType::fromKey(strtoupper($request->account_type))->value,
                'status' => $request->status ? $request->status : CustomerStatus::PENDING,
                'sequence' => $request->sequence,
                'barangay_id' => $request->barangay_id,
                'created_by' => $authId,
                'updated_by' => $authId 
            ]);

            if ($request->is_cron) {
                unset($data['is_cron']);
            }

            $customer = Customer::create($data);
            $customer->details()->create(['customer_id' => $customer->id]);
            //dd($customer);
            return $customer;
        });


        return new CustomerResource($customer);
    }
}
