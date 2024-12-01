<?php

namespace App\Repositories\User;

interface UserInterface
{
    public function list($request);
    public function store($request);
    public function detail($user);

}