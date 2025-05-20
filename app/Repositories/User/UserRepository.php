<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserInterface
{
    public function list($request)
    {
        // $user=User::find(1);
        // if($user){
        //     $user->permissions()->delete();
        // }
        $perPage = $request->per_page ?? 20;
        return User::with(['permissions'])
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
    public function store($request)
    {
        // dd($request->all());
        $permissions = json_decode($request->permissions, true);
        if ($request->role == 'admin' && empty($permissions)) {
            // Handle the case where no permissions are provided
            return response()->json(['message' => 'No permisssions provided.'], 422);
        }
        $data = $request->all();
        // $permissions=$request->permissions;
        $data['role'] = $request->role;
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $user = User::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            if ($request->role == 'admin') {
                $user->permissions()->sync($permissions);
            }
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
    public function detail($user)
    {
        $user->load('permissions');
        return $user;
    }
}
