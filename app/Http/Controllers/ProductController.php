<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductStore;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products);

    }

    public function store(ProductStore $request, Product $product)
    {
        $product->create($request->all());
    }

    public function show(Request $request)
    {
        if ($request->get('id')) {
            $product = Product::find($request->get("id"));
            return response()->json($product);
        }
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
    }
    public function delete($id)
    {
      $product = Product::find($id);
       if ($product) {
           $product->delete();
           return response()->json([
               'success' => "OK",
           ],200);
       } else {
           return response()->json([
               "errors" => "No data" ,

           ],404);
       }
    }

}
