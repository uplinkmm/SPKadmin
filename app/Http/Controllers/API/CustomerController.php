<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerRequest;
use App\Http\Requests\Admin\CustomerVerifyRequest;
use App\Repositories\Customer\CustomerInterface;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private $customerRepo;
    public function __construct(CustomerInterface $repo)
    {
        $this->customerRepo=$repo;
    }
    
    public function store(Request $request){
        $data=$this->customerRepo->store($request);
        ResponseData($data);
    }
    public function getCustomerList(Request $request){
        $data=$this->customerRepo->getCustomerList($request);
        ResponseData($data);
    }

    public function getCustomerLimitationList(Request $request){
        $data=$this->customerRepo->getCustomerLimitationList($request);
        ResponseData($data);
    }

    public function updateCustomerBetLimit(Request $request){
        $data=$this->customerRepo->updateCustomerBetLimit($request);
        ResponseData($data);
    }

    public function verifyCustomer(CustomerVerifyRequest $request){
        $data=$this->customerRepo->verifyCustomer($request);
        ResponseData($data);
    }
}
