<?php

namespace App\Http\Controllers;

use App\Models\Directive;
use App\Http\Requests\StoreDirectiveRequest;
use App\Http\Requests\UpdateDirectiveRequest;

class DirectiveController extends Controller
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
    public function store(StoreDirectiveRequest $request)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Display the specified resource.
     */
    public function show(Directive $directive)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDirectiveRequest $request, Directive $directive)
    {
        //
        // $var = DB::transaction(function () {
            
        // });

        // return $var;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Directive $directive)
    {
        //
    }
}
