<?php

namespace App\Http\Controllers;

use App\Models\PermissionRole;
use App\Http\Requests\StorePermissionRoleRequest;
use App\Http\Requests\UpdatePermissionRoleRequest;

class PermissionRoleController extends Controller
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
    public function store(StorePermissionRoleRequest $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(PermissionRole $permissionRole)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRoleRequest $request, PermissionRole $permissionRole)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PermissionRole $permissionRole)
    {
        //
    }
}
