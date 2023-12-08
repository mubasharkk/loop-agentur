<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductsController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProductResource::collection(
            Product::all()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return new ProductResource(
            Product::findOrFail($id)
        );
    }
}
