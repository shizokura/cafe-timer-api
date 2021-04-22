<?php

namespace App\Http\Controllers;
use DB;

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
}
