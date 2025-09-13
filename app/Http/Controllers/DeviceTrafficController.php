<?php

namespace App\Http\Controllers;

use App\Models\DeviceTraffic;
use App\Http\Requests\StoreDeviceTrafficRequest;
use App\Http\Requests\UpdateDeviceTrafficRequest;

class DeviceTrafficController extends Controller
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
    public function store(StoreDeviceTrafficRequest $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(DeviceTraffic $deviceTraffic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeviceTrafficRequest $request, DeviceTraffic $deviceTraffic)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeviceTraffic $deviceTraffic)
    {
        //
    }
}
