<?php
namespace App\Repositories\Ads;
interface AdsInterface {

    public function list($request);

    public function detail($id);

    public function updateOrCreate($request);



}