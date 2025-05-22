<?php

namespace App\Http\Controllers;

use App\Models\EnterpriseUser;
use App\Http\Requests\StoreEnterpriseUserRequest;
use App\Http\Requests\UpdateEnterpriseUserRequest;

class EnterpriseUserController extends Controller
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
    public function store(StoreEnterpriseUserRequest $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(EnterpriseUser $enterpriseUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEnterpriseUserRequest $request, EnterpriseUser $enterpriseUser)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EnterpriseUser $enterpriseUser)
    {
        //
    }
}
