<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Draw\DrawInterface;
use Google\Rpc\Context\AttributeContext\Response;
use Illuminate\Http\Request;

class DrawController extends Controller
{
    //
    private $drawRepo;
    public function __construct(DrawInterface $repo){
        $this->drawRepo=$repo;
    }

    public function index(Request $request){
        $data=$this->drawRepo->list($request);
        ResponseData($data);
    }

    public function create(Request $request){
        $data=$this->drawRepo->create($request);
        ResponseData($data);
    }

    public function detail($id){
        $data=$this->drawRepo->detail($id);
        ResponseData($data);
    }
}
