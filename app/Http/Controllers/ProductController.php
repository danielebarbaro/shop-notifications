<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $products = Product::all();
        return view('products.index', ['products' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('products.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProductRequest  $request
     * @return RedirectResponse
     */
    public function store(ProductRequest $request)
    {
        try {
            $product = new Product();
            $product->fill($request->validated())->save();
        } catch (\Exception $exception) {
            abort($exception->getCode(), $exception->getMessage());
        }
        return redirect()->route('products.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Product  $product
     * @return View
     */
    public function edit(Product $product)
    {
        return view('products.edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProductRequest  $request
     * @param  Product  $product
     * @return RedirectResponse
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $product->fill($request->validated())->save();
        } catch (\Exception $exception) {
            abort($exception->getCode(), $exception->getMessage());
        }
        return redirect()->route('products.index');
    }
}
