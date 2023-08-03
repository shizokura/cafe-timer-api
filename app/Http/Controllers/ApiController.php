<?php

namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
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

    public function viewer_online()
    {
        $time = Carbon::now()->subSeconds(3)->format("Y-m-d H:i:s");
        $get_member = DB::table("tbl_member")->where("last_update",">=",$time)->get();

        foreach($get_member as $key => $gm)
        {
            $last_use_code = DB::table("tbl_code_record")->where("member_id",$gm->member_id)
                                                         ->join("tbl_code","tbl_code.code_id","=","tbl_code_record.code_id")
                                                         ->orderBy("date_claimed","DESC")
                                                         ->first();
            $get_last_use_code = DB::table("tbl_code_record")->where("member_id",$gm->member_id)
                                                         ->join("tbl_code","tbl_code.code_id","=","tbl_code_record.code_id")
                                                         ->orderBy("date_claimed","DESC")
                                                         ->take(3)
                                                         ->get();                                             
            if($last_use_code)
            {
                $start    = Carbon::parse($last_use_code->date_claimed);
                $end      = Carbon::now()->timezone('Asia/Manila');
                $mins     = $start->diffInMinutes($end);

                if($mins >= 5)
                {
                    $is_new = 2;
                }
                else
                {
                    $is_new = 1;
                }
                $ctr                             = 0;
                $str                             = "";
                $get_member[$key]->is_new        = $is_new;
                foreach($get_last_use_code as $glucode)
                {
                    if($ctr == 0)
                    {
                        $str = $str. $glucode->code_id;
                    }
                    else
                    {
                        $str = $str.",".$glucode->code_id;
                    }
                    
                    $ctr++;
                }
                $get_member[$key]->code_id       = $str;
                $get_member[$key]->date_last_use = Carbon::parse($last_use_code->date_claimed)->addHours(8);                  
            }
            else
            {
                $get_member[$key]->is_new        = 2;
                $get_member[$key]->code_id       = "NONE";
                $get_member[$key]->date_last_use = "NONE";
            }
        }

        $data["get_member"] = $get_member;
        $data["count"]      = count($get_member);


        $get_duplicate_code_viewed = DB::table("tbl_code_record")
                                ->select('tbl_code_record.code_id','pin_code','activation_code', DB::raw('count(*) as total'))
                                ->groupBy('tbl_code_record.code_id','pin_code','activation_code')
                                ->join("tbl_code","tbl_code.code_id","=","tbl_code_record.code_id")
                                ->havingRaw('count(*) > 1')
                                ->where("viewed",0)
                                ->get();

        $data["get_duplicate_code_viewed"] = $get_duplicate_code_viewed; 

        $get_duplicate_code = DB::table("tbl_code_record")
                                ->select('tbl_code_record.code_id','used_date','pin_code','activation_code', DB::raw('count(*) as total'))
                                ->groupBy('tbl_code_record.code_id','pin_code','activation_code','used_date')
                                ->join("tbl_code","tbl_code.code_id","=","tbl_code_record.code_id")
                                ->orderBy("used_date","DESC")
                                ->havingRaw('count(*) > 1')
                                ->get();

        $data["get_duplicate_code"] = $get_duplicate_code; 

        $get_duplicate_points = DB::table("tbl_claim_points")
                                ->select('tbl_claim_points.member_id','amount','member_un', DB::raw('count(*) as total'))
                                ->groupBy('tbl_claim_points.member_id','member_un','amount','amount_before','date_claimed')
                                ->join("tbl_member","tbl_member.member_id","=","tbl_claim_points.member_id")
                                ->havingRaw('count(*) > 1')
                                ->get();

        $data["get_duplicate_points"] = $get_duplicate_points; 
                           
        return view('view_online',$data);
    }

    public function check_unused_code(Request $request)
    {
        $code_id   = $request->code_id;
        $get_code    = DB::table("tbl_code")->where("code_id",$code_id)->leftJoin("tbl_member","tbl_member.member_id","=","tbl_code.used_by")->first();

        $data["get_code"] = $get_code;
        
        return view('check_unused_code',$data);
    }

    public function view_members_code(Request $request)
    {
        $member_id   = $request->member_id;
        $get_code    = DB::table("tbl_code_record")->where("member_id",$member_id)->join("tbl_code","tbl_code.code_id","=","tbl_code_record.code_id")->get();

        $data["get_code"] = $get_code;

        return view('view_members_code',$data);
    }

    public function view_duplicate_code(Request $request)
    {
        date_default_timezone_set('Asia/Manila');
        
        $code_id   = $request->code_id;
        $get_code    = DB::table("tbl_code_record")
                        ->where("tbl_code_record.code_id",$code_id)
                        ->join("tbl_member","tbl_member.member_id","=","tbl_code_record.member_id")
                        ->join("tbl_code","tbl_code.code_id","=","tbl_code_record.code_id")
                        ->get();

        $data["get_code"] = $get_code;

        $update["viewed"] = 1;
        DB::table("tbl_code_record")->where("code_id",$code_id)->update($update);

        return view('view_duplicate_code',$data);
    }

    public function view_member_points(Request $request)
    {
        $member_id     = $request->member_id;
        $get_points    = DB::table("tbl_claim_points")->where("member_id",$member_id)->get();

        $data["get_points"] = $get_points;

        return view('view_member_points',$data);
    }

    public function user_info(Request $request)
    {
        $member = DB::table("tbl_member")->where("member_un", $request->username)->where("member_pw", $request->password)->first();

        if ($member->remaining_minutes < 0)
        {
            return response()->json("no_time");
        }

        

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
        $code = DB::table("tbl_code")->where("code_id", $request->activation_code)->where("pin_code", $request->pin_code)->first();

        $member = DB::table("tbl_member")->where("member_un", $request->username)->where("member_pw", $request->password)->first();

        if (!$code)
        {
            return response()->json("error_code");    
        }

        if ($code->status != "unused")
        {
            return response()->json("error_used"); 
        }

        if (!$member)
        {
            return response()->json("error_member");
        }

        

        if ($member->remaining_minutes < 0)
        {
            $member->remaining_minutes = 0;
        }

        
        $insert_record["before_adding_time"]   = $member->remaining_minutes;

        DB::table("tbl_member")->where("member_un", $request->username)->update(
        [
            'remaining_minutes' => $member->remaining_minutes + $code->minutes,
            'points' => $member->points + ($code->minutes == 30 ? 6 : 12)
        ]);

        DB::table("tbl_code")->where("code_id", $code->code_id)->update(
        [
            'status' => 'used',
            'used_by' => $member->member_id,
            'used_date' => date("Y-m-d H:i:s")
        ]);


        $get_member_data = DB::table("tbl_member")->where("member_un", $request->username)->first();

        $insert_record["member_id"]            = $member->member_id;
        $insert_record["code_id"]              = $code->code_id;
        $insert_record["date_claimed"]         = date("Y-m-d H:i:s");
        $insert_record["after_adding_time"]    = $member->remaining_minutes + $code->minutes;

        DB::table("tbl_code_record")->insert($insert_record);

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

    public function claim_points(Request $request)
    {
        $points[50] = 30;
        $points[100] = 60;
        $points[200] = 180;
        $points[300] = 300;

        $minutes = $points[$request->points];
        $member = DB::table('tbl_member')->where('member_un', $request->username)->first();

        if ($member->points >= $request->points)
        {
            $insert_record["before_adding_time"]   = $member->remaining_minutes;

            DB::table('tbl_member')->where('member_un', $request->username)->update(
            [
                'points' => $member->points - $request->points,
                'remaining_minutes' => $member->remaining_minutes + $minutes 
            ]);

            $insert_record["member_id"]                     = $member->member_id;
            $insert_record["amount"]                        = $request->points;
            $insert_record["amount_before"]                 = $member->points;
            $insert_record["date_claimed"]                  = date("Y-m-d H:i:s");
            $insert_record["after_adding_time"]             = $member->remaining_minutes + $minutes;
            
            DB::table("tbl_claim_points")->insert($insert_record);

            return response()->json("success");
        }
        else
        {
            return response()->json("error");
        }
    }
}
