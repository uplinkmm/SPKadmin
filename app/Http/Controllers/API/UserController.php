<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Repositories\User\UserInterface;

class UserController extends Controller
{
    //
    private $userRepo;
    public function __construct(UserInterface $repo){
        $this->userRepo=$repo;
    }

    public function index(Request $request){
        $data=$this->userRepo->list($request);
        ResponseData($data);
    }

    public function store(UserRequest $request){
        $data=$this->userRepo->store($request);
        ResponseData($data);
    }

    public function show(User $user){
        $data=$this->userRepo->detail($user);
        ResponseData($data);
    }

    public function getPermission(){
        $permissions=Permission::orderBy('id','asc')->get();
        ResponseData($permissions);
    }
}
