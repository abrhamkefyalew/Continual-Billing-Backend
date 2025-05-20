<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\AdminRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AdminRequests\StoreAdminRoleRequest;
use App\Http\Requests\Api\V1\AdminRequests\UpdateAdminRoleRequest;

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
