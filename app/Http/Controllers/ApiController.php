<?php

namespace App\Http\Controllers;
use DB;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function user_info(Request $request)
    {
        $member = DB::table("tbl_member")->where("member_un", $request->username)->where("member_pw", $request->password)->first();

        return response()->json($member);
    }

    public function update_time(Request $request)
    {
        $remaining_minutes = DB::table("tbl_member")->where("member_un", $request->username)->where("member_pw", $request->password)->value('remaining_minutes');
        DB::table("tbl_member")->where("member_un", $request->username)->where("member_pw", $request->password)->update(
        [
            'remaining_minutes' => $remaining_minutes - 0.0166666667,
            'last_update' => date("Y-m-d H:i:s")
        ]);

        return response()->json($remaining_minutes - 0.0166666667);
    }

    public function topup(Request $request)
    {
        $code = DB::table("tbl_code")->where("code_id", $request->activation_code)->where("pin_code", $request->pin_code)->where("status", "unused")->first();

        $member = DB::table("tbl_member")->where("member_un", $request->username)->first();

        if (!$code)
        {
            return response()->json("error_code");    
        }

        if (!$member)
        {
            return response()->json("error_member");
        }

        if ($member->remaining_minutes < 0)
        {
            $member->remaining_minutes = 0;
        }

        DB::table("tbl_member")->where("member_un", $request->username)->update(
        [
            'remaining_minutes' => $member->remaining_minutes + $code->minutes
        ]);

        DB::table("tbl_code")->where("code_id", $code->code_id)->update(
        [
            'status' => 'used',
            'used_by' => $member->member_id,
            'used_date' => date("Y-m-d H:i:s")
        ]);

        return response()->json("success");
    }

    public function register(Request $request)
    {
        $exist = DB::table("tbl_member")->where("member_un", $request->username)->first();

        if (!$exist)
        {
            DB::table("tbl_member")->insert(
            [
                'member_un' => $request->username,
                'member_pw' => $request->password
            ]);

            return response()->json("success");
        }
        else
        {
            return response()->json("error");
        }
    }
}
