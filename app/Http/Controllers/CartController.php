<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function store(Request $request)
    {

        if ($request->get("email")) {

            $email = $request->get("email");
            $info = User::where("email", $email)->first();
            $user_id = $info->id;
            $product_id = $request->input("product_id");
            $product_quantity = $request->input("quantity");
            $check = Cart::where("user_id", $user_id)->first();
            $checkProduct = DB::table("cart_item")
                ->where("product_id", '=', $product_id)
                ->where("cart_id", '=', $check->id)->first();

            if ($check && $checkProduct) {
                $check_Id = $check->id;
                $data = [];
                $data['product_id'] = $request->product_id;
                $data['quantity'] = $request->quantity;
                DB::table("cart_item")->where("cart_id", $check_Id)->update($data);
                return response()->json([
                    'success' => " cập nhật Số lượng thành công",
                    'data' => $data
                ], 200);
            }
            if ($check) {
                $cart = Cart::find($check->id);
                $data = [];
                $data['cart_id'] = $cart->id;
                $data['product_id'] = $request->product_id;
                $data['quantity'] = $request->quantity;
                DB::table("cart_item")->where("cart_id", $cart->id)->insert($data);
                return response()->json([
                    'success' => "Cập nhật giỏ hàng và thêm sản phẩm mới vào giỏ hàng thành công",
                ], 200);
            } else {
                $cart = new Cart();
                $cart->user_id = $user_id;
                $cart->save();
                $cart_id = $cart->id;
                $cart->cartItem()->attach($cart_id, ['product_id' => $product_id, 'quantity' => $product_quantity]);
                return response()->json($cart);
            }
        } else {
            return response()->json([
                'errors' => "Không tìm thấy user này"
            ], 404);
        }
    }

    public function showCart(Request $request)
    {
        $email = $request->get("email");
        $info = User::where("email", $email)->first();
        $user_id = $info->id;
        $checkCart = Cart::where("user_id", $user_id)->first();
        echo "$request->email";
        $data =  $checkCart->cartI ;
        $data->load("infoProduct") ;

        return response()->json($data);
    }
}
