<?php

namespace Database\Seeders;

use App\Models\Barangay;
use App\Models\Sequence;
use App\Enums\AccountType;
use Illuminate\Http\Request;
use App\Enums\CustomerStatus;
use App\Enums\ApplicationType;
use Illuminate\Database\Seeder;
use App\Models\OldCustInformation;
use Illuminate\Support\Facades\Schema;
use App\Actions\Customers\StoreCustomer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OldCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ini_set('memory_limit', '1000M');
        Schema::disableForeignKeyConstraints();

        \DB::table('customers')->truncate();
        \DB::table('customer_details')->truncate();
        /*
            #original: array:39 [
            "custId" => 20170001.0
            "custLName" => "GILTENDEZ"
            "custFName" => "CARIDAD"
            "custMI" => ""
            "custAddress" => "ACACIA KABANGBANG"
            "custBarangay" => "KABANGBANG"
            "custMeterNo" => "160874663"
            "custMeterBrand" => ""
            "custAcctType" => "R"
            "workPhone" => ""
            "route" => "1"
            "readingdate" => 5
            "sequence" => 1
            "billNote" => null
            "lastReading" => 302.1
            "previousReading" => 301.2
            "lastUsage" => 0.9
            "lastDateRead" => "2025-02-05"
            "lastPayment" => 236.6
            "lastPaidDate" => "2025-01-27 00:00:00"
            "lastCheckNo" => null
            "reconnectFee" => null
            "adjusments" => null
            "paymentAgreement" => null
            "salesTax" => 0.0
            "previousCharges" => 220.0
            "paidThisMonth" => 236.6
            "balance" => null
            "nextDueDate" => 20
            "seqSubs" => null
            "amountdue" => 220.0
            "days30" => 0.0
            "days60" => 0.0
            "days90over" => 0.0
            "totalaging" => 0.0
            "meterreader" => "PETER ANTHONY"
            "watersystemcode" => "KAMPINGGANON"
            "dcflag" => 1
            "ncode" => 1
        ]

         $data = [
            'account_number' => Customer::generateAccountNo(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'mi' => $this->faker->randomLetter(),
            'address' => 'Kandugyap',
            'brgy' => $brgy,
            'phone_number' => $this->faker->numerify('09#########'),
            'work_phone_number' => $this->faker->numerify('09#########'),
            'status' => CustomerStatus::ACTIVE,
            'account_type' => AccountType::RESIDENCE,
            'application_type' => ApplicationType::NEW,
            'created_by' => 1,
            'updated_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        */
        $oldCustomers = OldCustInformation::query()->get();
            
        foreach($oldCustomers as $oldCustomer) {
            //dd($customer);
            $barangay = Barangay::query()->where('name', $oldCustomer->custBarangay)->first();
            if ($barangay) 
            {
                switch ($oldCustomer->custAcctType) {
                    case 'R': 
                        $accountType = AccountType::RESIDENCE;
                        break;
                    case 'C': 
                        $accountType = AccountType::COMMERCIAL;
                        break;
                    case 'A': 
                        $accountType = AccountType::APARTMENT;
                        break;
                    case 'M': 
                        $accountType = AccountType::MARKET_STALL;
                        break;
                    default:
                        $accountType = AccountType::RESIDENCE;
                        break;
                }
                
                $request = Request::create('/fake-url', 'POST', [
                    'account_number' => $oldCustomer->custId,
                    'first_name' => $oldCustomer->custFName,
                    'last_name' => $oldCustomer->custLName,
                    'mi' => $oldCustomer->custMI,
                    'address' => $oldCustomer->custAddress,
                    'barangay_id' => $barangay->id,
                    'sequence' => $oldCustomer->sequence,
                    'account_type' => $accountType,
                    'application_type' => ApplicationType::NEW,
                    'status' => CustomerStatus::ACTIVE,
                    'is_cron' => true,

                    'meter_no' => $oldCustomer->custMeterNo,
                    'reading_day' => $oldCustomer->readingdate,
                    'due_day' => $oldCustomer->nextDueDate
                ]);
    
                $customer = (new StoreCustomer())->execute($request);
            }
            //exit;
        }

        Schema::enableForeignKeyConstraints();
    }
}
