<?php

namespace App\Http\Controllers\User;

#use App\Http\Controllers\Controller as Controller;
use App\Http\Requests;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Users as UsersModel;
use App\HelperDB;

use DB;

class LoginController extends Controller
{
    public function index()
    {
        Auth::logout();
        $helperDb = new HelperDB();
        // Getting all post data
        $data = Input::all();
        // Applying validation rules.
        $rules = array(
    		'email' => 'required|email',
    		'password' => 'required',
	     );
  
        $validator = Validator::make($data, $rules);
        
        if ($validator->fails()){
          // If validation falis redirect back to login.
          return response()->json([
                'message' => 'Looks like these are not your correct details. Please try again.',
                'code' => 300
            ], 200);
        }
        else {
          $userdata = array(
    		    'email' => Input::get('email'),
    		    'password' => Hash::make(Input::get('password'))
    		  ); 
          // doing login.
          $mUser = UsersModel::select( DB::raw('IFNULL(bus.user_type, 0) as type, users.*,IFNULL(bus.business_id, 0) as business_id') )
                    ->leftJoin(DB::raw('(select * from users_businesses where user_type = 1) as bus'), function ($join){
                        $join->on('bus.user_id', '=', 'users.user_id');
                    })
                    ->where('users.status', '=', 1)
                    ->where('email', Input::get('email'))
                    // ->where('bus.user_type', '=', 1);
                    ->get()->first();
          if ($mUser != null) {
            $business = DB::table('businesses')->select('*')->where('business_id', $mUser->business_id)->first();
            if ($business && !in_array($business->status, [0, 1])) {
                return response()->json([
                    'message' => "Your business in't active. Please active it by email confirm or contact to support.",
                    'code' => 300
                ], 200);
            }
            if (Hash::check(Input::get('password'), $mUser->password) || (Input::get('verify_user') && Input::get('password') == $mUser->password)) {
                $userAddress = '';
                // if ($mUser->address != '') {
                //     $userAddress[] = $mUser->address;
                // }
                
                // get city
                if ($mUser->city_id != '' && is_numeric($mUser->city_id)) {
                    $cityDb = $helperDb->getCityById($mUser->city_id);
                    if (!empty($cityDb)) {
                        $userAddress = $cityDb->city_name.', ';
                    }
                } else if($mUser->city_name != '') {
                    $userAddress = $mUser->city_name.', ';
                }
                // get State
                if ($mUser->state_id != '' && is_numeric($mUser->state_id)) {
                    $stateDb = $helperDb->getStateById($mUser->state_id);
                    if (!empty($stateDb)) {
                        $userAddress .= $stateDb->state_code;
                    } else {
                        $userAddress .= '';
                    }
                }
                //get zipcode
                $userAddress .= ' '.$mUser->zipcode;
                // get country
                if ($mUser->country_id != '' && is_numeric($mUser->country_id)) {
                    $countryDb = $helperDb->getCountryById($mUser->country_id);
                    if (!empty($countryDb)) {
                        $userAddress .= ' '.$countryDb->country_name;
                    }
                }
                unset($mUser->password);
                unset($mUser->created_at);
                unset($mUser->updated_at);
                \Session::put('addressFull', $userAddress);
                \Session::put('userType', $mUser->type);
                // total user with city
                // $totalUser = $helperDb->countUserWithCity($mUser->city_id);
                // \Session::put('totalUser', $totalUser);
                
                // total business with city
                // $totalBusiness = $helperDb->countBusinessWithCity($mUser->city_id);
                // \Session::put('totalBusiness', $totalBusiness);
                
                Auth::login($mUser, true);
            }
          } else {
            return response()->json([
                'message' => " Your account isn't active. Please active it by email confirm or contact to support.",
                'code' => 300
            ], 200);
          }
          if (Auth::check()) {
            return response()->json([
                'message' => 'Login success!',
                'code' => 200
            ], 200);
          } 
          else {
            // if any error send back with message.
            return response()->json([
                'message' => 'Invalid Login or password.',
                'code' => 300
            ], 200);
          }
        }
  }
  
  public function logout() {
    Auth::logout();
    return redirect('user');
  }
    
}
