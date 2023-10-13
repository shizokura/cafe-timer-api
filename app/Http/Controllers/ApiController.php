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
        $time = Carbon::now()->subSeconds(5)->format("Y-m-d H:i:s");
        $get_member = DB::table("tbl_member")->where("last_update",">=",$time)->get();

        foreach($get_member as $key => $gm)
        {
            $last_use_code = DB::table("tbl_code_record")->where("member_id",$gm->member_id)
                                                         ->join("tbl_code","tbl_code.code_id","=","tbl_code_record.code_id")
                                                         ->orderBy("date_claimed","DESC")
                                                         ->first();

            // $get_last_use_code = DB::table("tbl_code_record")->where("member_id",$gm->member_id)
            //                                              ->join("tbl_code","tbl_code.code_id","=","tbl_code_record.code_id")
            //                                              ->orderBy("date_claimed","DESC")
            //                                              ->take(3)
            //                                              ->get();  

            $get_last_use_code = [];  

            $last_use_code_receipt = DB::table("tbl_code_record_receipt")->where("member_id",$gm->member_id)
                                                                            ->join("tbl_code_receipt","tbl_code_receipt.id","=","tbl_code_record_receipt.code_id")
                                                                            ->orderBy("date_claimed","DESC")
                                                                            ->first();                                          
            if($last_use_code || $last_use_code_receipt)
            {
                if($last_use_code && $last_use_code_receipt)
                {
                    if($last_use_code->date_claimed >= $last_use_code_receipt->date_claimed)
                    {
                        $date_to_use    = $last_use_code->date_claimed;
                        $code_to_show   = $last_use_code->code_id;
                    }
                    else
                    {
                        $date_to_use    = $last_use_code_receipt->date_claimed;
                        $code_to_show   = $last_use_code_receipt->first_code;
                    }
                }
                else if($last_use_code)
                {
                    $date_to_use    = $last_use_code->date_claimed;
                    $code_to_show   = $last_use_code->code_id;
                }
                else
                {
                    $date_to_use    = $last_use_code_receipt->date_claimed;
                    $code_to_show   = $last_use_code_receipt->first_code;
                }
                $start    = Carbon::parse($date_to_use);
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

                $str                             = $code_to_show;


                $get_member[$key]->is_new        = $is_new;
                // foreach($get_last_use_code as $glucode)
                // {
                //     if($ctr == 0)
                //     {
                //         $str = $str. $glucode->code_id;
                //     }
                //     else
                //     {
                //         $str = $str.",".$glucode->code_id;
                //     }
                    
                //     $ctr++;
                // }
                $get_member[$key]->code_id       = $str;
                $get_member[$key]->date_last_use = Carbon::parse($date_to_use)->addHours(8);                  
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
        $get_points    = DB::table("tbl_claim_points")->orderBy("date_claimed","DESC")->where("member_id",$member_id)->get();

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

        if($member)
        {
            DB::table("tbl_member")->where("member_un", $request->username)->where("member_pw", $request->password)->update(
            [
                // 'expected_next_points' => 0,
                'enp_date_checker' => null,
                'is_multiple_user' => "false"
            ]);   
        }

        

        return response()->json($member);
    }

    public function update_time(Request $request)
    {
        $get_member              = DB::table("tbl_member")->where("member_un", $request->username)->where("member_pw", $request->password)->first();
        $remaining_minutes       = $get_member->remaining_minutes;
        $current_date_now        = date("Y-m-d H:i:s");
        
        $update_timer_amount     = 0.0166666667;

        $expected_points         = null;
        $proceed_to_checker      = 0;
        $enp_date_checker        = null;
        $is_mulitple_check       = false;
        $is_multiple_user        = "false";
        $is_timer_stopping       = 0;
        if($get_member->last_update)
        {
            $timeFirst  = strtotime($current_date_now);
            $timeSecond = strtotime($get_member->last_update);
            $differenceInSeconds = $timeFirst - $timeSecond;

            if($get_member->enp_date_checker && $get_member->expected_next_points != 0)
            {
                $enp_strtotime          = strtotime($get_member->enp_date_checker);
                $enpdifferenceInSeconds = $timeFirst - $enp_strtotime;

                if($enpdifferenceInSeconds >= 10)
                {
                    $is_mulitple_check = true;

                    $expected_points_check = ($remaining_minutes + ($update_timer_amount * 3) ) - $get_member->expected_next_points;

                    $expected_points    = $remaining_minutes - ($update_timer_amount * 10);
                    $enp_date_checker   = $current_date_now;
                    $proceed_to_checker = 1;  

                    if($expected_points_check < 0)
                    {
                        $is_multiple_user = "true"; 
                    }
                    else
                    {
                        $is_multiple_user = "false";
                    }
                }
            }
            else
            {
                $expected_points    = $remaining_minutes - ($update_timer_amount * 10);
                $enp_date_checker   = $current_date_now;
                $proceed_to_checker = 1;
            }


            
            if($differenceInSeconds >= 8 && $differenceInSeconds <= 20)
            {
                $update_timer_amount = $update_timer_amount * $differenceInSeconds;
                $is_timer_stopping = 1;
            }
        }

        DB::table("tbl_member")->where("member_un", $request->username)->where("member_pw", $request->password)->update(
        [
            'remaining_minutes' => $remaining_minutes - $update_timer_amount,
            'last_update' => date("Y-m-d H:i:s")
        ]);


        if($is_timer_stopping != 1)
        {
            if($proceed_to_checker == 1 && $expected_points)
            {
                DB::table("tbl_member")->where("member_un", $request->username)->where("member_pw", $request->password)->update(
                [
                    'expected_next_points' => $expected_points,
                    'enp_date_checker' => $enp_date_checker,
                    'is_multiple_user' => $is_multiple_user
                ]);    
            }
        }

    
        return response()->json($remaining_minutes - $update_timer_amount);
    }

    
    public function topup_preview(Request $request)
    {
        $quantity           = $request->quantity;
        $data["quantity"]   = $quantity;
        $data["wait"]       = $request->wait == 1 ? true : false;
        $data["add_time"]   = 60 * $quantity;
        
        return view('generate_code_receipt',$data);
    }

    public function topup_receipt($request)
    {
        $first_code  = $request->activation_code;
        $second_code = $request->pin_code;

        $code = DB::table("tbl_code_receipt")->where("first_code", $first_code)->where("second_code", $second_code)->first();
    
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

        $add_minutes = $code->minutes;
        $add_points  = ($add_minutes / 60) * 12;

        $insert_record["before_adding_time"]   = $member->remaining_minutes;

        DB::table("tbl_member")->where("member_un", $request->username)->update(
        [
            'remaining_minutes' => $member->remaining_minutes + $add_minutes,
            'points' => $member->points + $add_points
        ]);

        DB::table("tbl_code_receipt")->where("id", $code->id)->update(
        [
            'status' => 'used',
            'used_by' => $member->member_id,
            'used_date' => date("Y-m-d H:i:s")
        ]);


        $get_member_data = DB::table("tbl_member")->where("member_un", $request->username)->first();

        $insert_record["member_id"]            = $member->member_id;
        $insert_record["code_id"]              = $code->id;
        $insert_record["date_claimed"]         = date("Y-m-d H:i:s");
        $insert_record["after_adding_time"]    = $member->remaining_minutes + $code->minutes;

        DB::table("tbl_code_record_receipt")->insert($insert_record);

        return response()->json("success");
    }

    public function topup(Request $request)
    {
        $activation_code = $request->activation_code;
        if($activation_code[0] == "W")
        {
            return $this->topup_receipt($request);
        }
        else
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
