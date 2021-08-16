<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return response()->json($products);

    }
    public function store(Request $request)
    {
         $rules = [
             'rating' => "required|numeric",
             "code" => "required|unique:product,code",
             "cat_id" => "required",
             "price" => "required|numeric|min:1",
             "sale_price" => "required|numeric",
             "quantity" => "required|numeric|min:1",
             "thumbnail" => "required",
         ];
         $message = [
             "rating.required" => "Cannot rating is null",
             "rating.numeric" => "Bắt buộc phải là số",
             "code.required" => "Bắt buộc nhập mã sản phẩm",
             "cat_id.required" => "Chọn danh mục",
             "price.required" => "Chưa có giá",
             "sale_price" => "Chưa có giá sale",
             "price.min" => "Giá phải lớn hơn 1",
             "price_sale.numeric" => "Có thể băng 0",
             "quantity.required" => "Chưa có số lượng",
             "quantity.min" => "Số lượng lớn hơn = 1",
             "thumbnail.required" => "Chưa có ảnh sản phẩm",
         ];
         $validator = Validator::make($request->all(), $rules , $message);
         if ($validator->fails()){
             return response()->json($validator);
         }else{
             $product = new Product();
             $product->rating = $request->input("rating");
             $product->code = $request->input("code");
             $product->cat_id = $request->input("cat_id");
             $product->price = $request->input("price");
             $product->sale_price = $request->input("sale_price");
             $product->quantity = $request->input("quantity");
             $product->thumbnail = $request->input("thumbnail");
             $product->save();
             return  response()->json($product);
         }


    }
    public function show(Request $request)
    {
        if ($request->get('id')) {
            $product = Product::find($request->get("id"));
            return response()->json($product);
        }
    }
    public function update(Request $request, $id)
    {

        $rules = [
            'rating' => "required|numeric",
            "code" => "required|unique:product,code,$id,id",
            "cat_id" => "required",
            "price" => "required|numeric|min:1",
            "sale_price" => "required|numeric",
            "quantity" => "required|numeric|min:1",
            "thumbnail" => "required",
        ];
        $message = [
            "rating.required" => "Cannot rating is null",
            "rating.numeric" => "Bắt buộc phải là số",
            "code.required" => "Bắt buộc nhập mã sản phẩm",
            "cat_id.required" => "Chọn danh mục",
            "price.required" => "Chưa có giá",
            "sale_price" => "Chưa có giá sale",
            "price.min" => "Giá phải lớn hơn 1",
            "price_sale.numeric" => "Có thể băng 0",
            "quantity.required" => "Chưa có số lượng",
            "quantity.min" => "Số lượng lớn hơn = 1",
            "thumbnail.required" => "Chưa có ảnh sản phẩm",
        ];
        $validator = Validator::make($request->all() , $rules , $message);
        if ($validator->fails()){
            return response()->json($validator);
        }
        else{
            $product = Product::find($id);
            $product->rating = $request->input("rating");
            $product->code = $request->input("code");
            $product->cat_id = $request->input("cat_id");
            $product->price = $request->input("price");
            $product->sale_price = $request->input("sale_price");
            $product->quantity = $request->input("quantity");
            $product->thumbnail = $request->input("thumbnail");
            $product->save();
            return  response()->json($product);
        }
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
