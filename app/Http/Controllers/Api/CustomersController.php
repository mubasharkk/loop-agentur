<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CustomerResource::collection(
            Customer::paginate()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return new CustomerResource(
            Customer::findOrFail($id)
        );
    }
}
