<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    public function store(Request $request)
    {
        if ($request->get('email')) {
            $email = $request->email;
            $infoUser = User::where("email", $email)->first();
            $product_id = $request->product_id;
            $userId = $infoUser->id;
            $checkInput = Cart::where("user_id", $userId)->first();
            $cartStatus = Cart::where('user_id', $userId)->where('status', 0)->first();
            if ($cartStatus) {
                $count = $cartStatus->count();
            }
            $checkProduct = DB::table("cart_item")
                ->where("product_id", '=', $product_id)
                ->where("cart_id", '=', @$checkInput->id)->first();
            if (!$checkInput) {
                $cart = new Cart();
                $cart->user_id = $userId;
                $cart->status = 0;
                $cart->save();
                $cartId = $cart->id;
                $cart->cartItem()->attach($cartId, [
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity
                ]);
                return response()->json([
                    "message" => "Thêm sản phẩm vào giỏ hàng thành công",
                    "data" => $cart
                ], 200);
            }
            if (@$count == 0 && $request->input("product_id")) {
                $cart = new Cart();
                $cart->user_id = $userId;
                $cart->status = 0;
                $cart->save();
                $cartId = $cart->id;
                $cart->cartItem()->attach($cartId, [
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity
                ]);
                return response()->json([
                    "message" => "Thêm sản phẩm vào giỏ hàng thành công",
                    "data" => $cart
                ], 200);
            }
            if (@$cartStatus && $checkProduct) {
                $data = [];
                $data['quantity'] = $request->quantity;
                DB::table('cart_item')
                    ->where("cart_id", '=', $cartStatus->id)
                    ->update($data);
                return response()->json([
                    'message' => "Cập nhật số lượng thành công"
                ]);
            }
            if (@$cartStatus && !$checkProduct) {
                $cart = Cart::find($cartStatus->id);
                $data = [];
                $data['cart_id'] = $cart->id;
                $data['product_id'] = $request->product_id;
                $data['quantity'] = $request->quantity;
                DB::table("cart_item")->where("cart_id", $cart->id)->insert($data);
                return response()->json([
                    'success' => "Cập nhật giỏ hàng và thêm sản phẩm mới vào giỏ hàng thành công",
                ], 200);
            }
        } else {
            return response()->json(['message' => "Chưa get email"]);
        }
    }


    public function showCart(Request $request)
    {

    }
}
