<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller as Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use DB;
use App\HelperDB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
         if (!Auth::check()) {
            //return redirect()->route('user', []);
            //return redirect()->route('login');

 
        }
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user.home');
    }
    
    public function ajaxUpload(Request $request) {
        // upload immage
        $validator = Validator::make($request->all(), [
            'qqfile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->passes() &&  isset($request->all()['qqfile'])) {
            $input = $request->all();
            $helpDb = new HelperDB();
            $urlData = $helpDb->convertId($input['idBusiness']);
            $input['image'] = Auth::user()->user_id.'_'.$input['action'].'.'.$request->qqfile->getClientOriginalExtension();
            if ($input['page'] == 'profile') {
                $img = url(env('DIR_UPLOAD_USER').$input['image']);
                $request->qqfile->move(env('DIR_UPLOAD_USER'), $input['image']);
            } else {
                $img = url(env('DIR_UPLOAD_BUSINESS').$input['image']);
                $request->qqfile->move(env('DIR_UPLOAD_BUSINESS'), $input['image']);
            }
            
            // update database profile
            if ($input['page'] == 'profile') {
                if ($input['action'] == 'cover') {
                    DB::table('users')
                    ->where('user_id', Auth::user()['user_id'])
                    ->update(['cover' => $input['image']]);
                }
                
                if ($input['action'] == 'avatar') {
                    DB::table('users')
                    ->where('user_id', Auth::user()['user_id'])
                    ->update(['avatar' => $input['image']]);
                }
            }
            
            // update datebase business
            if ($input['page'] == 'business') {
                if (count($urlData) ==  2) {
                    if ($input['action'] == 'cover') {
                        DB::table('businesses')
                        ->where('business_id', $urlData[1])
                        ->update(['cover' => $input['image']]);
                    }
                    
                    if ($input['action'] == 'avatar') {
                        DB::table('businesses')
                        ->where('business_id', $urlData[1])
                        ->update(['avatar' => $input['image']]);
                    }
                }
            }
            
            return response()->json([
                'image' => $img,
                'code' => 200
            ], 200);
        } else {
            return response()->json([
                'image' => $img,
                'code' => 300
            ], 200);
        }
    }
    
    public function ajaxAddToken(Request $request) {
        if ($request->get('token') != '') {
            $user = DB::table('user_settings')->where('user_id', '=', Auth::user()['user_id'])->first();
            if (!empty($user)) {
               DB::table('user_settings')
                ->where('user_id', '=', Auth::user()['user_id'])
                ->update(['fcm_id_web' => $request->get('token')]);
            } else {
                DB::table('user_settings')
                ->where('user_id', '=', Auth::user()['user_id'])
                ->insert([
                    'fcm_id_web' => $request->get('token'),
                    'user_id' => Auth::user()['user_id']
                ]);
            }
        }
        
        return response()->json([
            'code' => 200
        ], 200);
    }
    
    public function ajaxAddLocal(Request $request) {
        $helpDb = new HelperDB();
        if ($request->get('lat') != '' && $request->get('lon') != '') {
            DB::table('users')
                ->where('user_id', '=', Auth::user()['user_id'])
                ->update([
                    'lat' => $request->get('lat'),
                    'lon' => $request->get('lon')
                ]);
            /*
            $businessR = $helpDb->getBusiness(Auth::user()->user_id);
            if (!empty($businessR)) {
                DB::table('businesses')
                    ->where('business_id', '=', $businessR->business_id)
                    ->update([
                        'lat' => $request->get('lat'),
                        'lon' => $request->get('lon')
                    ]);
            }
            */
            
        }
        
        return response()->json([
            'code' => 200,
            'uid' => Auth::user()['user_id']
        ], 200);
    }
    
}
