<?php

namespace App\Http\Controllers\User;

#use App\Http\Controllers\Controller as Controller;
use App\Http\Requests;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Country;
use App\Cities;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Mail;
use DB;
use Cache;
use App\SendMail;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }
    
    public function stepInfo() {
        // get list country
        $countryList = Country::get()->toArray();
        $dataCountry = [];
        if (!empty($countryList)) {
            foreach($countryList as $key => $item) {
                $dataCountry[ $item['country_id'] ] = $item['country_name'];
            }
        }
        return view('user.user.stepInfo', [
            'title' => 'Free user register::Zipidy',
            'dataCountry' => $dataCountry
        ]);
    }
    
    public function stepAddress(Request $request) {
        // validate action
        if ($request->get('_method') != 'PUT') {
            return Redirect::to('user/free/register');
        }
        // validate parameter
        $this->validateParameter('stepAddress', $request);
        // get state
        $stateList = DB::table('states')->select('*')->where('country_id', $request->get('country'))->get();
        $dataState = [];
        if (!empty($stateList)) {
            foreach($stateList as $key => $item) {
                $dataState[ $item->state_id ] = $item->state_name;
            }
        }
        return view('user.user.stepAddress', [
            'title' => 'Free user step address::Zipidy',
            'request' => $request->all(),
            'dataState' => $dataState
        ]);
    }
    
    
    
    public function stepPassword(Request $request) {
        if ($request->get('_method') != 'PUT') {
            return Redirect::to('user/free/register');
        }
        // validate parameter
        $this->validateParameter('stepPassWord', $request);
        
        // get list country
        $countryList = Country::get()->toArray();
        $dataCountry = [];
        if (!empty($countryList)) {
            foreach($countryList as $key => $item) {
                $dataCountry[ $item['country_id'] ] = $item['country_name'];
            }
        }
        
        if ($request->get('_method') != 'PUT') {
            return Redirect::to('user');
        }
        
        // get state
        $stateList = DB::table('states')->select('*')->where('country_id', $request->get('country'))->get();
        $dataState = [];
        if (!empty($stateList)) {
            foreach($stateList as $key => $item) {
                $dataState[ $item->state_id ] = $item->state_name;
            }
        }
        return view('user.user.stepConfirmPass', [
            'title' => 'Free user step password::Zipidy',
            'request' => $request->all(),
            'dataCountry' => $dataCountry,
            'dataState' => $dataState
        ]);
    }
    
    public function stepSave(Request $request) {
        if ($request->get('_method') != 'PUT') {
            return Redirect::to('user');
        }
        // validate parameter
        $this->validateParameter('stepSuccess', $request);
        // check duplicate user
        $user = DB::table('users')
                    ->where('email', '=', $request->get('email'))
                    ->orWhere('phone','=', $request->get('email'))
                    ->first();
        if (!empty($user)) {
            return Redirect::to('user/free/register');
        }
        
        //-----get country by id
        $countryItem = DB::table('countries')
                        ->where('country_id', '=', $request->get('country'))
                        ->first();
        if (!empty($countryItem)) {
            $dataSearch['country_code'] = $countryItem->country_code;
        }
        //---get state by id
        if ($request->get('state') != '') {
            $stateItem = DB::table('states')
                            ->where('state_id', '=', $request->get('state'))
                            ->first();
            if (!empty($stateItem)) {
                $dataSearch['state_code'] = $stateItem->state_code;
            }
        }
        if ($request->get('city') != ''){
            $dataSearch['city_name'] = $request->get('city');
        }

        $cities = new Cities();
        $rCheck = $cities->checkCity($dataSearch);
        
        // insert data
        //--- create user
        $dataInsertU = [
            'firstname' => $request->get('firstName'),
            'lastname' => $request->get('lastName'),
            'email' => $request->get('email'),
            'username' => md5($request->get('email')),
            'password' => Hash::make($request->get('password')),
            'token_key' => $request->get('_token'),
            'phone' => '',
            'state_id' => $request->get('state'),
            'zipcode' => $request->get('zipCode'),
            'country_id' => $request->get('country'),
            'lat' => $request->get('lat'),
            'lon' => $request->get('lon'), 
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
            'status'  => 1,
        ];
        if (!empty($rCheck)) {
            $dataInsertU['city_id'] = $rCheck->city_id;
        } else {
            $dataInsertU['city_name'] = $request->get('city');
        }
        if (!empty($rCheck)) {
            $dataInsertU['city_id'] = $rCheck->city_id;
        } else {
            $dataInsertU['city_name'] = $request->get('city');
        }
        $userId = DB::table('users')->insertGetId($dataInsertU);
        //---set usser setting
        $dataInsertS = [
            'user_id' => $userId,
            'radius' => env('CONFIG_USER_RADIUS', 5),
            'notification' => 1,
            'notification_time' => date('Y-m-d H:i:s'),
            'fcm_id' => ''
        ];
        DB::table('user_settings')->insertGetId($dataInsertS);
        
        return Redirect::to('user/free/register/success');
    }
    
    public function stepShowSuccess(Request $request) {
        return view('user.user.stepSuccess', [
            'title' => 'Success Register::Zipidy'
        ]);
    }
    
    public function validateParameter($request) {
        $errorValidate = false;
        
        if (!filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            $errorValidate = true;
        }
        if ($request->get('firstName') == '' || $request->get('lastName') == '' || $request->get('country') == '') {
            $errorValidate = true;
        }
        if ($request->get('city') == '' || $request->get('zipCode') == '') {
            $errorValidate = true;
        }
        return $errorValidate;
    }
    
    public function searchCity(Request $request) {
        $country = [];
        if ($request->get('term') != '') {
            $tmp = DB::table('cities')
                    -> select([
                        'cities.city_id as id',
                        'cities.city_name as value',
                        'cities.city_name as label'
                    ])
                    ->where('city_name', 'like', '%' . $request->get('term') . '%');
            if ($request->get('state_code')) {
                $stateItem = DB::table('states')
                            ->where('state_id', '=', $request->get('state_code'))
                            ->first();
                $tmp->where('state_code', $stateItem->state_code);
            } else {
                $countryItem = DB::table('countries')
                            ->where('country_id', '=', $request->get('cid'))
                            ->first();
                $tmp->where('country_code', $countryItem->country_code);
            }
            // print_r($tmp->toSql());exit;
            $country = $tmp->get();
        }
        
        return $request->get('callback') . '(' . json_encode($country) . ')';
    }
    
    /*--------new function-----*/
    public function register() {
        // get list country
        $countryList = Country::get()->toArray();
        $dataCountry = [];
        if (!empty($countryList)) {
            foreach($countryList as $key => $item) {
                $dataCountry[ $item['country_id'] ] = $item['country_name'];
            }
        }
        // get state
        $stateList = DB::table('states')->select('*')->where('country_id', 230)->get();
        $dataState = [];
        if (!empty($stateList)) {
            foreach($stateList as $key => $item) {
                $dataState[ $item->state_id ] = $item->state_name;
            }
        }
        return view('user.user.userRegister', [
            'title' => 'Register user::Zipidy',
            'dataCountry' => $dataCountry,
            'dataState' => $dataState,
            'action' => 'user'
        ]);
    }
    
    public function ajaxFreeRegister(Request $request)
    {
        // init response
        $response = [
            'code' => 300
        ];
        // validate parameter
        $validateData = $this->validateParameter($request);
        if ($validateData) {
            return $response;
        }
        // check duplicate user
        $user = DB::table('users')
                    ->where('email', '=', $request->get('email'))
                    ->first();
        if (!empty($user)) {
            $response['message'] = 'Duplicate user';
            return $response;
        }
        
        //-----get country by id
        $countryItem = DB::table('countries')
                        ->where('country_id', '=', $request->get('country'))
                        ->first();
        if (!empty($countryItem)) {
            $dataSearch['country_code'] = $countryItem->country_code;
        }
        //---get state by id
        if ($request->get('state') != '') {
            $stateItem = DB::table('states')
                            ->where('state_id', '=', $request->get('state'))
                            ->first();
            if (!empty($stateItem)) {
                $dataSearch['state_code'] = $stateItem->state_code;
            }
        }
        if ($request->get('city') != ''){
            $dataSearch['city_name'] = $request->get('city');
        }

        $cities = new Cities();
        $rCheck = $cities->checkCity($dataSearch);
        
        // insert data
        //--- create user
        $dataInsertU = [
            'firstname' => $request->get('firstName'),
            'lastname' => $request->get('lastName'),
            'email' => $request->get('email'),
            'username' => md5($request->get('email')),
            'password' => Hash::make($request->get('password')),
            'token_key' => $request->get('_token'),
            'secret_key' => $request->get('_token'),
            'phone' => '',
            'state_id' => $request->get('state'),
            'zipcode' => $request->get('zipCode'),
            'country_id' => $request->get('country'),
            'lat' => $request->get('lat'),
            'lon' => $request->get('lon'),
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
            'status'  => 1,
            'city_name' => $request->get('city')
        ];
        if (!empty($rCheck)) {
            $dataInsertU['city_id'] = $rCheck->city_id;
        }
        $userId = DB::table('users')->insertGetId($dataInsertU);
        //---set usser setting
        $dataInsertS = [
            'user_id' => $userId,
            'radius' => env('CONFIG_USER_RADIUS', 5),
            'notification' => 1,
            'notification_time' => date('Y-m-d H:i:s'),
            'fcm_id' => ''
        ];
        DB::table('user_settings')->insertGetId($dataInsertS);
        // insert users_businesses for user invitation
        //check user invited
        $query = DB::table('users_invited')->select('*')->where(['email' => $request->get('email')]);
        $invited = $query->get();
        if ($invited) {
            if ($request->get('biz_id') && $request->get('type')) {
                DB::table('users_businesses')->insert(
                    ['user_id' => $userId,'business_id' => $request->get('biz_id'), 'user_type' => $request->get('type'), 'status' => 1]
                );
                DB::table('users_invited')->where(['email' => $request->get('email'),'business_id' => $request->get('biz_id')])->delete();
            }
            $invited = $query->get();
            if ($invited) {
                foreach ($invited as $key => $value) {
                    DB::table('users_businesses')->insert(
                        ['user_id' => $userId,'business_id' => $value->business_id, 'user_type' => $value->user_type, 'status' => 2]
                    );
                }
                //remove user invited
                DB::table('users_invited')->where(['email' => $request->get('email')])->delete();
            }
        }
        // suceess register
        $response['message'] = 'Success register user';
        $response['code'] = 200;
        return $response;
    }

    public function forgotPassword(Request $request) {
        $email = $request->get('email');
        $response['message'] = "Don't have that email in system. Please sign in and access system.";
        $response['code'] = 300;
        $check_email = DB::table('users')->select('*')->where(['email' => $email])->first();
        if (!$check_email) {
            return $response;
        }
        // $minutes = 60;
        $token = $check_email->secret_key;
        // Cache::put($email, $token,$minutes);
        $value = config('settings.mailType');
        if ($value) {
            $sendMail = new SendMail($value['ForgotPassword'], $email, ['email' => $email,'token' => $token]);
            $sendMail->send();
            // Mail::send(['html' => 'mail.changePassword'], ['email' => $email,'token' => $token], function($message) use ($email) {
            //     $message->to($email, 'Forgot Password')->subject(env('SUBJECT_FORGOT_PASSWORD'));
            // });
            $response['message'] = 'Please check your email to reset your password.';
            $response['code'] = 200;
        } else {
            $response['message'] = "Error! Please try again!";
            $response['code'] = 300;
        }
        return $response;
    }
    
    public function changePassword($email, $token = '')
    {
        $flag = false;
        $check_email = DB::table('users')->select('*')->where(['email' => $email])->first();
        if (isset($check_email) && $check_email->secret_key == $token) {
            $flag = true;
        }
        return view('user.user.changePassword', [
            'title' => 'Profile user::Zipidy',
            'email' => $email,
            'flag' => $flag,
            'token' => $token
        ]);
    }

    public function updatePassword(Request $request)
    {
        $response['message'] = 'error';
        $response['code'] = 300;
        $email = $request->get('email');
        $password = $request->get('password');
        $token = $request->get('token');
        $check_email = DB::table('users')->select('*')->where(['email' => $email])->first();
        if (isset($check_email) && $check_email->secret_key == $token) {
            DB::table('users')->where('email', $email)->update(['password' => Hash::make($password), 'secret_key' => $request->get('_token')]);
            $response['message'] = 'Success';
            $response['code'] = 200;
        }
        return $response;
    }
}
