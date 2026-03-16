<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DepositWithdrawTutorial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositWithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $depositWithdrawTutorials = DepositWithdrawTutorial::latest()->get();
        ResponseData($depositWithdrawTutorials);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'title' => 'nullable',
            'youtube_link' => 'required|url',
        ]);
        DB::beginTransaction();
        try {
            $data = $request->all();

            $depositWithdrawTutorial = DepositWithdrawTutorial::updateOrCreate([
                'id' => $data['id'] ?? null
            ], $data);
            DB::commit();
            \ResponseData($depositWithdrawTutorial);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $depositWithdrawTutorial = DepositWithdrawTutorial::findOrFail($id);
        \ResponseData($depositWithdrawTutorial);
    }
}
