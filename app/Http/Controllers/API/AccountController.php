<?php

namespace App\Http\Controllers\API;

use App\Models\Account;
use App\Repositories\Account\AccountInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccountCreateRequest;

class AccountController extends Controller
{
    //
    private $accountRepo;

    public function __construct(AccountInterface $repo)
    {
        $this->accountRepo = $repo;
    }
    
    public function index(Request $request){
        $data= $this->accountRepo->list($request);
        ResponseData($data);
    }
    public function show(Account $account){
        $data= $this->accountRepo->detail($account);
        ResponseData($data);
    }

    public function store(AccountCreateRequest $request){
       $data= $this->accountRepo->updateOrCreate($request);
       ResponseData($data);

    }

    public function toggleIsActive(Request $request){
        $data= $this->accountRepo->toggleIsActive($request);
        ResponseData($data);
    }
}
