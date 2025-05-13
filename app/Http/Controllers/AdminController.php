<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $admin = Admin::all();
        return $admin;

        //
        // $this->authorize('viewAny', Admin::class);

        // $admin = Admin::whereNotNull('id')->with('media', 'roles');
        
        // if ($request->has('name')){
        //     FilteringService::filterByAllNames($request, $admin);
        // }
        // $adminData = $admin->paginate(FilteringService::getPaginate($request));

        // return AdminResource::collection($adminData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request, Admin $admin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        //
    }
}
