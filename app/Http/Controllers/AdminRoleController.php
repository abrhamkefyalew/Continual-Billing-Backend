<?php

namespace App\Http\Controllers;

use App\Models\AdminRole;
use App\Http\Requests\StoreAdminRoleRequest;
use App\Http\Requests\UpdateAdminRoleRequest;

class AdminRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRoleRequest $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(AdminRole $adminRole)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRoleRequest $request, AdminRole $adminRole)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminRole $adminRole)
    {
        //
    }
}
