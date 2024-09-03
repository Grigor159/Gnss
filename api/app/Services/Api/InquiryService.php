<?php

namespace App\Services\Api;

use App\Models\Inquiry;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Services\OpportunityService;
use App\Models\CustomerContactPerson;
use App\Repositories\Api\InquiryRepository;

class InquiryService
{
    /**
     * inquiryRepository
     *
     * @var mixed
     */
    private $inquiryRepository;

    /**
     * opportunityService
     *
     * @var mixed
     */
    private $opportunityService;

    /**
     * __construct
     *
     * @param  InquiryRepository $inquiryRepository
     * @param  OpportunityService $opportunityService
     * @return void
     */
    public function __construct(
        InquiryRepository $inquiryRepository,
        OpportunityService $opportunityService
    ) {
        $this->inquiryRepository = $inquiryRepository;
        $this->opportunityService = $opportunityService;
    }

    /**
     * create
     *
     * @param  mixed $data
     * @return void
     */
    public function create($data)
    {
        return $this->inquiryRepository->create($data);
    }

    /**
     * reject
     *
     * @param  mixed $id
     * @return void
     */
    public function reject($id)
    {
        return $this->inquiryRepository->reject($id);
    }

    /**
     * delete
     *
     * @param  mixed $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->inquiryRepository->delete($id);
    }

    /**
     * toOpportunity
     *
     * @param  mixed $data
     * @return mixed
     */
    public function toOpportunity($data)
    {
        $inquiry = Inquiry::find($data['id'])->toArray();

        $targetCustomer = CustomerContactPerson::where([
            ['email', '=', $inquiry['email']],
            ['phone', '=', $inquiry['phone']]
        ])->first();


        if ($targetCustomer) {
            $customerId = $targetCustomer['customer_id'];
        } else {
            DB::beginTransaction();
            try {
                $customer = Customer::create([
                    'name' => $inquiry['company'],
                ]);

                CustomerContactPerson::create([
                    'name' => $inquiry['fullName'],
                    'phone' => $inquiry['phone'],
                    'email' => $inquiry['email'],
                    'customer_id' => $customer->id,
                ]);
                $customerId = $customer->id;
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', $e->getMessage());
            }

        }

        $opportunity = [
            'product_id' => $inquiry['product_id'],
            'customer_id' => $customerId,
            'user_id' => $data['manager'],
            'count' => $inquiry['product_id'],
            'note' => $inquiry['note']
        ];
        $opportunity = $this->opportunityService->create($opportunity);


        // $this->delete($inquiry['id']);

        return $opportunity;
    }

}
