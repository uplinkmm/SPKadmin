<?php

namespace App\Repositories\Draw;

use Illuminate\Http\Request;

interface DrawInterface
{
    public function list($request);
    public function create($request);

}
