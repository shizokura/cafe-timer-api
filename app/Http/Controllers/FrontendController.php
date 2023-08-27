<?php

namespace App\Http\Controllers;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FrontendController extends Controller
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

    public function index(Request $request)
    {
        return view('index');
    }

    public function codes(Request $request)
    {
        $data['codes'] = DB::table('tbl_code')->where('status', 'unused')->where('archive', 0)->get();
        return view('codes', $data);
    }

    public function generate_codes(Request $request)
    {
        $digits = 4;
        
        for ($x = 1; $x <= $request->quantity; $x++) 
        {
            DB::table('tbl_code')->insert([
                'pin_code' => rand(pow(10, $digits-1), pow(10, $digits)-1),
                'activation_code' => '',
                'price' => $request->price,
                'minutes' => $request->minutes
            ]);
        }

        return redirect('/');
    }

    public function generate_codes_receipt(Request $request)
    {
        $requested_quantity = $request->quantity;
        $digits  = 4;
        $price   = 10;
        $minutes = 60;

        if($requested_quantity >= 1)
        {
            $code_id = DB::table('tbl_code_receipt')->insertGetId([
                'first_code' => "W".rand(pow(10, $digits-1), pow(10, $digits)-1),
                'second_code' => rand(pow(10, $digits-1), pow(10, $digits)-1),
                'price' => $price * $requested_quantity,
                'minutes' => $minutes * $requested_quantity,
                'date_generated' => date("Y-m-d H:i:s")
            ]);
    
            $id = $code_id;
            $data['code'] = DB::table('tbl_code_receipt')->where('id', $id)->first();
            $data['date_format'] = Carbon::parse($data['code']->date_generated)->addHours(8)->format('F j, Y, g:i a');
            $data['price'] = number_format($data['code']->price,2);
            $data['time'] = $data['code']->minutes / 60;
      
            return $data;
        }

    }

    public function view_generate_code_receipt(Request $request)
    {

        $data['code'] = DB::table('tbl_code_receipt')->where('id', $request->id)->first();

        return view('view_generated_receipt', $data);
    }
}
