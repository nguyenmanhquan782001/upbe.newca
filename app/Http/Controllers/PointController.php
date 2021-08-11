<?php

namespace App\Http\Controllers;

use App\Models\Point;
use App\Models\User;

use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\True_;

class PointController extends Controller
{
    public function index()
    {
        $data = Point::all();
        foreach ($data as $item) {
            $point = $item->point;
            $name = $item->user->name;
        }
        // phần foreach làm thêm quan hệ để lấy được điểm của user đó.
        return response()->json($data);
    }
    public function store(Request $request)
    {
        if ($request->input('email')) {
            $email = $request->email;
        }
        $info = User::where('email', $email)->first();
        $user_id = $info->id;
        $check = Point::where("user_id", $user_id)->first();
        if ($check) {
            $check->point += (int)$request->point;
            $data = Point::where("user_id", $user_id)->update([
                'point' => $check->point
            ]);
        } else {
            $data = Point::create([
                'user_id' => $user_id,
                'point' => $request->input("point")
            ]);
        }
        return response()->json($data);
    }

    public function show($id)
    {
        $point = Point::find($id);
//        dd($point->user->name);
        return response()->json($point);

    }

    public function update(Request $request)
    {
        if ($request->input('email')) {
            $email = $request->email;
        }
        $info = User::where('email', $email)->first();
        $user_id = $info->id;
        $pointData = Point::where('user_id', $user_id)->update([
            'point' => $request->point,
        ]);
    }

    public function destroy(Request $request)
    {
        if ($request->input('email')) {
            $email = $request->email;
        }
        $info = User::where('email', $email)->first();
        $user_id = $info->id;
        Point::where('user_id', $user_id)->delete() ;

    }
}
