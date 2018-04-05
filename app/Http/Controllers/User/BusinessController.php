<?php

namespace App\Http\Controllers\User;

#use App\Http\Controllers\Controller as Controller;
use App\Http\Requests;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Country;
use App\Cities;
use App\HelperDB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Jobs\SendReminderEmail;
use DB;
use App\SendMail;

class BusinessController extends Controller
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
    
    public function register(Request $request) {
        $user = Auth::user();
        if ($user) {
            $helpDb = new HelperDB();
            // $listMessage = $helpDb->getListMessageById(Auth::user()->user_id, true);
            // if($request->ajax()) {
            //     return view('user.profile.indexAjax', [
            //         'title' => 'Profile user::Zipidy',
            //         'listMessage' => $listMessage,
            //         'page' => 'profile',
            //         'address' => '',
            //     ])->renderSections()['content'];
            // }
            //check owner business
            $business = $helpDb->getBusiness(Auth::user()->user_id);
            // $addressBusiness = '';
            if ($business) {
                // if (!$request->get('menu_action')) {
                    return redirect()->route('business.page', ['idBusiness' => generateId(($business->business_id + 100)  . '-' . $business->business_id)]);
                // }
                // $businessDetail = $helpDb->getBusinessById($business->business_id);
                // if ($businessDetail) {
                //     $addressBusiness = (($businessDetail->address) ? $businessDetail->address.', ' : '').(($businessDetail->city_name) ? $businessDetail->city_name.', ' : '').$businessDetail->state_name.' '.$businessDetail->zipcode;
                // }
            } else {
                return redirect()->route('profile.index');
            }
            // var_dump($business);exit;
            // var_dump($addressBusiness);exit;
            // return view('user.profile.index', [
            //     'title' => 'Profile user::Zipidy',
            //     'listMessage' => $listMessage,
            //     'page' => 'profile',
            //     'address' => \Session::get('addressFull'),
            //     'ownerBusiness' => $business,
            //     'addressBusiness' => $addressBusiness
            // ]);
        }
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
        return view('user.business.registerBusiness', [
            'title' => 'Register user::Zipidy',
            'dataCountry' => $dataCountry,
            'dataState' => $dataState,
            'action' => 'business'
        ]);
    }
    
    public function getState(Request $request)
    {
        $countryId = $request->get('cid');
        $dataState = [];
        if ($countryId != '' && is_numeric($countryId)) {
            // get state
            $stateList = DB::table('states')->select('*')->where('country_id', $countryId)->get();
            if (!empty($stateList)) {
                foreach($stateList as $key => $item) {
                    $dataState[ $item->state_id ] = $item->state_name;
                }
            }
        }
        return response()->json([
            'dataState' => $dataState,
            'code' => 200
        ], 200);
    }
    
    public function stepAddress(Request $request) {
        // validate action
        if ($request->get('_method') != 'PUT') {
            return Redirect::to('user');
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
        return view('user.business.step2Address', [
            'title' => 'Step address::Zipidy',
            'request' => $request->all(),
            'dataState' => $dataState
        ]);
    }
    
    public function stepCreator(Request $request) {
        if ($request->get('_method') != 'PUT') {
            return Redirect::to('user');
        }
        // validate parameter
        //$this->validateParameter('stepPassWord', $request);
        
        return view('user.business.stepCreator', [
            'title' => 'Step Creator::Zipidy',
            'request' => $request->all()
        ]);
    }
    
    public function stepPassWord(Request $request) {
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
        return view('user.business.step3ConfirmPass', [
            'title' => 'Step Password::Zipidy',
            'request' => $request->all(),
            'dataCountry' => $dataCountry,
            'dataState' => $dataState
        ]);
    }
    
    public function stepAddCard(Request $request) {
        if ($request->get('_method') != 'PUT') {
            return Redirect::to('user');
        }
        // validate parameter
        $this->validateParameter('stepAddCard', $request);
        
        // get list country
        $countryList = Country::get()->toArray();
        $dataCountry = [];
        if (!empty($countryList)) {
            foreach($countryList as $key => $item) {
                $dataCountry[ $item['country_id'] ] = $item['country_name'];
            }
        }
        
        return view('user.business.step4AddCard', [
            'title' => 'Step add card::Zipidy',
            'dataCountry' => $dataCountry,
            'request' => $request->all()
        ]);
    }
    
    public function stepSuccessRegisBusiness(Request $request) {
        if ($request->get('_method') != 'PUT') {
            return Redirect::to('user');
        }
        
        // check city name
        $dataSearch = [
            'city_name' => $request->get('city'),
            'country_code' => '',
            'state_code' => ''
        ];
        

        
        // validate parameter
        $this->validateParameter('stepSuccess', $request);
        // check duplicate user
        $user = DB::table('users')
                    ->where('email', '=', $request->get('email'))
                    ->orWhere('phone','=', $request->get('email'))
                    ->first();
        if (!empty($user)) {
            return Redirect::to('user');
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
        
        $cities = new Cities();
        $rCheck = $cities->checkCity($dataSearch);
        // insert data
        $dataInsertB = [
            'name' => $request->get('businessName'),
            'address' => $request->get('address'),
            'email' => $request->get('email'),
            'city_id' => $request->get('city'),
            'state_id' => $request->get('state'),
            'zipcode' => $request->get('zipCode'),
            'country_id' => $request->get('country'),
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
            'lat' => $request->get('lat'),
            'lon' => $request->get('lon'), 
            'status'  => 1,
        ];
        
        $dataInsertU = [
            'firstname' => $request->get('firstName'),
            'lastname' => $request->get('lastName'),
            'email' => $request->get('emailCreator'),
            'username' => md5($request->get('emailCreator')),
            'password' => Hash::make($request->get('password')),
            'token_key' => $request->get('_token'),
            'phone' => '',
            'address' => $request->get('address'),
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
            $dataInsertB['city_id'] = $rCheck->city_id;
            $dataInsertU['city_id'] = $rCheck->city_id;
        } else {
            $dataInsertB['city_name'] = $request->get('city');
            $dataInsertU['city_name'] = $request->get('city');
        }
        //--- create business user
        $idBusiness = DB::table('businesses')->insertGetId($dataInsertB);
        //--- create user
        if (is_numeric($idBusiness)) {
            $userId = DB::table('users')->insertGetId($dataInsertU);
            //--- set type of user
            DB::table('users_businesses')->insert(
            [
            
                'user_id' => $userId,
                'business_id' => $idBusiness,
                'user_type' => 1,
                'status' => 1
            ]);
            //---set usser setting
            $dataInsertS = [
                'user_id' => $userId,
                'radius' => env('CONFIG_USER_RADIUS', 5),
                'notification' => 1,
                'notification_time' => date('Y-m-d H:i:s'),
                'fcm_id' => ''
            ];
            DB::table('user_settings')->insertGetId($dataInsertS);
            
            // save invoce
            if (!$request->get('saveInfo')) {
                DB::table('invoices')->insert(
                [
                
                    'user_id' => $userId,
                    'business_id' => $idBusiness,
                    'start_date' => '',
                    'end_date' => $request->get('cardDate'),
                    'amount' => '',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } 
            
            
        }
        return Redirect::to('user/register/success');
    }
    
    public function stepShowSuccess(Request $request) {
        return view('user.business.step5Success', [
            'title' => 'Success Register::Zipidy'
        ]);
    }
    
    public function validateParameter($request) {
        $redirect = false;
        
        if (!filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            $redirect = true;
        }
        if ($request->get('businessName') == '' || $request->get('country') == '') {
            $redirect = true;
        }
        if ($request->get('password') == '') {
            $redirect = true;
        }
        /*
        switch (true) {
            case ($step == 'stepAddress' || $step == 'stepCreator' || $step == 'stepPassWord' || $step == 'stepAddCard' || $step == 'stepSuccess'):
                if (!filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
                    $redirect = true;
                }
                if ($request->get('businessName') == '' || $request->get('country') == '') {
                    $redirect = true;
                }
            case($step == 'stepCreator' || $step == 'stepPassWord' || $step == 'stepAddCard' || $step == 'stepSuccess'):
                if (!filter_var($request->get('emailCreator'), FILTER_VALIDATE_EMAIL)) {
                    $redirect = true;
                }
                if ($request->get('firstName') == '' || $request->get('lastName') == '') {
                    $redirect = true;
                }
            case ($step == 'stepPassWord' || $step == 'stepAddCard' || $step == 'stepSuccess'):
                if (!filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
                    $redirect = true;
                }
                if ($request->get('businessName') == '' || $request->get('country') == '') {
                    $redirect = true;
                }
            case($step == 'stepAddCard' || $step == 'stepSuccess'):
                if ($request->get('password') == '') {
                    $redirect = true;
                }
            case($step == 'stepSuccess'):
                if (!$request->get('saveInfo')) {
                    if ($request->get('cardNumber') == '' || 
                        $request->get('cardDate') == '' ||
                        $request->get('cardCvv') == '' ||
                        $request->get('cardCountry') == '' ||
                        $request->get('cardNumber') == '' ||
                        $request->get('carZip') == ''
                    ) {
                        $redirect = true;
                    }
                }
                
        }
        */
        if ($redirect) {
            return Redirect::to('user');
        }
    }
    
    public function checkBusinessAcc(Request $request)
    {
        $return = [
            'code' => 300,
            'message' => 'Email is exits!!'
        ];
        
        if ($request->get('email') == '') {
            return $return;
        }
        $user = DB::table('users')
                    ->where('email', '=', $request->get('email'))
                    ->orWhere('phone','=', $request->get('email'))
                    ->first();
        if ($user === null) {
            $return = [
                'code' => 200,
                'message' => 'Not exit!!'
            ];
           return $return;
        } else {
            return $return;
        }
    }
    
    /*----------new register---------------*/
    
    public function businessRegister(Request $request) {
        $response = [
            'code' => 300
        ];
        
        // check city name
        $dataSearch = [
            'city_name' => $request->get('city')
        ];
        
        // validate parameter
        $resultValdaite = $this->validateParameter($request);
        if ($resultValdaite) {
            return $response;
        }
        
        // check duplicate user
        $user = DB::table('users')
                    ->where('email', '=', $request->get('email'))
                    ->orWhere('phone','=', $request->get('email'))
                    ->first();
        if (!empty($user)) {
            $response['message'] = 'User exits!';
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
        $cities = new Cities();
        $rCheck = $cities->checkCity($dataSearch);
        // insert data
        $dataInsertB = [
            'name' => $request->get('businessName'),
            'address' => $request->get('address'),
            'email' => $request->get('email'),
            'city_name' => $request->get('city'),
            'state_id' => $request->get('state'),
            'zipcode' => $request->get('zipCode'),
            'country_id' => $request->get('country'),
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
            'lat' => $request->get('lat'),
            'lon' => $request->get('lon'), 
            'status'  => -1,
        ];
        
        $dataInsertU = [
            'firstname' => $request->get('firstName'),
            'lastname' => $request->get('lastName'),
            'email' => $request->get('email'),
            'username' => md5($request->get('email')),
            'password' => Hash::make($request->get('password')),
            'token_key' => $request->get('_token'),
            'secret_key' => $request->get('_token'),
            'phone' => '',
            'address' => $request->get('address'),
            'state_id' => $request->get('state'),
            'zipcode' => $request->get('zipCode'),
            'country_id' => $request->get('country'),
            'lat' => $request->get('lat'),
            'lon' => $request->get('lon'), 
            'created_at'  => date('Y-m-d H:i:s'),
            'updated_at'  => date('Y-m-d H:i:s'),
            'status'  => 0,
            'city_name' => $request->get('city')
        ];
        if (!empty($rCheck)) {
            $dataInsertB['city_id'] = $rCheck->city_id;
            $dataInsertU['city_id'] = $rCheck->city_id;
        }
        //--- create business user
        $idBusiness = DB::table('businesses')->insertGetId($dataInsertB);
        //--- create user
        if (is_numeric($idBusiness)) {
            $userId = DB::table('users')->insertGetId($dataInsertU);
            //--- set type of user
            DB::table('users_businesses')->insert(
            [
            
                'user_id' => $userId,
                'business_id' => $idBusiness,
                'user_type' => 1,
                'status' => 1
            ]);
            //---set usser setting
            $dataInsertS = [
                'user_id' => $userId,
                'radius' => env('CONFIG_USER_RADIUS', 5),
                'notification' => 1,
                'notification_time' => date('Y-m-d H:i:s'),
                'fcm_id' => ''
            ];
            DB::table('user_settings')->insertGetId($dataInsertS);
            
            // save invoce
            if (!$request->get('saveInfo')) {
                DB::table('invoices')->insert(
                [
                
                    'user_id' => $userId,
                    'business_id' => $idBusiness,
                    'start_date' => '',
                    'end_date' => $request->get('cardDate'),
                    'amount' => '',
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            } 
            $url_verify = url('/user/business/verify',['biz_id' => base64_encode($idBusiness), 'user_id' => base64_encode($userId)]);
            $value = config('settings.mailType');
            if ($value) {
                $sendMail = new SendMail($value['BusinessRegister'], $request->get('email'), ['url_verify' => $url_verify]);
                $sendMail->send();
            }
        }
        
        $response['code'] = 200;
        return $response;
    }

    public function verify($biz_id, $user_id) {
        $business = DB::table('businesses')->select('*')->where('business_id', base64_decode($biz_id))->first();
        $user = DB::table('users')->select('*')->where('user_id', base64_decode($user_id))->first();
        $msg = 'Your request has been occur error. Please try again!';
        $flag = false;
        if ($business && $user && $business->status == -1 && $user->status == 0) {
            DB::table('businesses')->where('business_id', base64_decode($biz_id))->update(['status' => 0]);
            DB::table('users')->where('user_id', base64_decode($user_id))->update(['status' => 1]);
            $msg = 'Verify Success!!';
            $flag = true;
        }
        return view('user.business.verify', [
            'title' => 'Register user::Zipidy',
            'msg' => $msg,
            'flag' => $flag,
            'user' => $user
        ]);
    }

    public function getListUser(Request $request){
        $helpDb = new HelperDB();
        $biz_id = $request->get('biz_id');
        $type = $request->get('type');
        $search = trim($request->get('search'));
        $business_id = $helpDb->convertUrlUser($biz_id)[1];
        if ($type != 4) {
            $user_type = [$request->get('type'), 1];
        } else {
            $user_type = [$request->get('type')];
        }
        $query = DB::table('users')
            ->join('users_businesses', 'users.user_id', '=', 'users_businesses.user_id')
            ->select(DB::raw("users.user_id,CONCAT(users.firstname, ' ',users.lastname) as user_name, users.avatar, users_businesses.status, users_businesses.created_at, users_businesses.user_type"))
            ->where([
                ['users_businesses.business_id', $business_id],
                // ['users_businesses.user_type', $type]
            ])->whereIn('users_businesses.user_type', $user_type);
        $user_invited = DB::table('users_invited')->select(DB::raw("'' as user_id, users_invited.email, '' as avatar, users_invited.status, users_invited.created_at,  '' as user_type"))->where([
                ['users_invited.business_id', $business_id],
                ['users_invited.user_type', $type]
            ]);
        if ($search) {
            $query->Where(function ($sub) use ($search) {
                $sub->where('users.firstname','like', '%'.$search.'%');
                $sub->orwhere('users.lastname','like', '%'.$search.'%');
                $sub->orwhere(DB::raw("CONCAT(users.firstname, ' ',users.lastname)"),'like', '%'.$search.'%');
            });
            $user_invited->Where(function ($sub) use ($search) {
                $sub->where('users_invited.email','like', '%'.$search.'%');
            });
        }
        // print_r($query->union($user_invited)->toSql());exit;    
        $users = $query->union($user_invited)->get();

        return response()->view('user.business.listUser', ['users' => $users], 200)
            ->header('Content-Type', 'text');
    }

    public function updateUser(Request $request)
    {
        $helpDb = new HelperDB();
        $biz_id = $helpDb->convertUrlUser($request->get('biz_id'))[1];
        $user_id = $request->get('user_id');
        $status = $request->get('status');
        $users = DB::table('users_businesses')
            ->where([
                ['users_businesses.business_id', $biz_id],
                ['users_businesses.user_id', $user_id]
            ])
            ->get();
        if ($users) {
            $usersUpdate = DB::table('users_businesses')
            ->where([
                ['users_businesses.business_id', $biz_id],
                ['users_businesses.user_id', $user_id]
            ])
            ->update(['users_businesses.status' => $status]);
            $return = [
                'code' => 200,
                'message' => 'Success!!'
            ];
        } else {
            $return = [
                'code' => 404,
                'message' => 'Not Exits!!'
            ];
        }
        return $return;
    }

    public function addUser(Request $request) {
        $helpDb = new HelperDB();
        $biz_id = $helpDb->convertUrlUser($request->get('biz_id'))[1];
        $email = $request->get('email');
        $type = $request->get('type');
        //check email
        $query = DB::table('users')->select('user_id', 'email')->get();
        $data = json_decode(json_encode($query), true);
        $exitsEmail = array_column($data, 'email');
        $user = array_column($data, 'user_id', 'email');
        $empty = false;
        foreach ($email as $key => $value) {
            if ($value) {
                $empty = true;
                if (in_array($value, $exitsEmail)) {
                    $checkUserBiz = DB::table('users_businesses')->select('*')->where(['user_id' => $user[$value],'business_id' => $biz_id])->get();
                    if (!$checkUserBiz) {
                        DB::table('users_businesses')->insert(
                            ['user_id' => $user[$value],'business_id' => $biz_id, 'user_type' => $type, 'status' => 2]
                        );
                    } else {
                        DB::table('users_businesses')->where([['user_id', $user[$value]],['business_id', $biz_id]])->update(['user_type' => $type]);
                    }
                } else {
                    $url_verify = url('user/invitation',['biz_id' => base64_encode($biz_id), 'email' => base64_encode($value), 'type' => base64_encode($type)]);
                    $user_type = '';
                    $setting = config('settings.mailType');
                    switch ($type) {
                        case 2:
                            $user_type = 'Manager User';
                            $mail_id = $setting['AddAlertManager'];
                            break;
                        case 3:
                            $user_type = 'Direct User';
                            $mail_id = $setting['AddDirectUser'];
                            break;
                        case 4:
                            $user_type = 'Indirect User';
                            $mail_id = $setting['AddDirectUser'];
                            break;
                    }
                    $businessDetail = $helpDb->getBusinessById($biz_id);
                    $action = $request->get('action');
                    if (isset($action) && $action == 'share') {
                        $mail_id = $setting['ShareOnFacebook'];
                    }
                    $sendMail = new SendMail($mail_id, $value, ['url_verify' => $url_verify, 'user_type' => $user_type, 'business_name' => $businessDetail->name]);
                    $sendMail->send();
                    // $mail = ['html' => 'mail.addUser'];
                    // $subject = env('SUBJECT_ADD_USER');
                    //get detail business
                    // Mail::send($mail, array('url_verify' => $url_verify, 'user_type' => $user_type, 'business_name' => $businessDetail->name), function($message) use ($value, $subject) {
                    //         $message->to($value, 'Visitor')->subject($subject);
                    // });
                    //check user invited
                    $invited = DB::table('users_invited')->select('*')->where(['email' => $value,'business_id' => $biz_id])->get();
                    if (!$invited) {
                        DB::table('users_invited')->insert(
                            ['email' => $value,'business_id' => $biz_id, 'user_type' => $type, 'status' => 2]
                        );
                    } else {
                        DB::table('users_invited')->where(['email' => $value,'business_id' => $biz_id])->delete();
                        DB::table('users_invited')->insert(
                            ['email' => $value,'business_id' => $biz_id, 'user_type' => $type, 'status' => 2]
                        );
                    }
                    // dispatch(new SendReminderEmail($biz_id, $value, $type));
                }
            }
        }
        if (!$empty) {
            return [
                'code' => 400,
                'message' => 'Empty Email'
            ];
        }
        return [
            'code' => 200,
            'message' => 'Success!!'
        ];
    }

    public function addInvite(Request $request) {
        $helpDb = new HelperDB();
        $email = $request->get('email');
        //check email
        $query = DB::table('users')->select('user_id', 'email')->get();
        $data = json_decode(json_encode($query), true);
        $exitsEmail = array_column($data, 'email');
        $user = array_column($data, 'user_id', 'email');
        $empty = false;
        $exist = false;
        $setting = config('settings.mailType');
        foreach ($email as $key => $value) {
            if ($value) {
                $empty = true;
                if (!in_array($value, $exitsEmail)) {
                    $url_verify = url('user/invitation-business',[ 'email' => base64_encode($value)]);
                    $sendMail = new SendMail($setting['InviteBusiness'], $value, ['url_verify' => $url_verify]);
                    $sendMail->send();
                } else {
                    $exist = true;
                }
            }
        }
        if (!$empty) {
            return [
                'code' => 400,
                'message' => 'Empty Email'
            ];
        }

        if ($exist) {
            return [
                'code' => 400,
                'message' => 'Email address have been exist.'
            ];
        }
        return [
            'code' => 200,
            'message' => 'Your invite have been sent!!'
        ];
    }

    public function invitationUser($biz_id, $email, $type) {
        // var_dump(base64_decode($biz_id), base64_decode($type), base64_decode($email));exit;
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
            'invitation' => true,
            'biz_id' => base64_decode($biz_id),
            'type' => base64_decode($type),
            'email' => base64_decode($email),
            'action' => 'user'
        ]);
    }

    public function invitationBusiness($email) {
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
        return view('user.business.registerBusiness', [
            'title' => 'Register user::Zipidy',
            'dataCountry' => $dataCountry,
            'dataState' => $dataState,
            'action' => 'business',
            'email' => base64_decode($email),
            'invitation' => true,
        ]);
        // return view('user.user.userRegister', [
        //     'title' => 'Register user::Zipidy',
        //     'dataCountry' => $dataCountry,
        //     'dataState' => $dataState,
        //     'invitation' => true,
        //     'biz_id' => base64_decode($biz_id),
        //     'type' => base64_decode($type),
        //     'email' => base64_decode($email),
        //     'action' => 'user'
        // ]);
    }

    public function updatePage($idBusiness) {
        $helpDb = new HelperDB();
        $urlData = $helpDb->convertUrlUser($idBusiness);
        //get data business
        $business = $helpDb->getBusinessById($urlData[1]);
        if (!$business) {
            abort(404);
        }
        // var_dump($business);exit;
        // get list country
        $countryList = Country::get()->toArray();
        $dataCountry = [];
        if (!empty($countryList)) {
            foreach($countryList as $key => $item) {
                $dataCountry[ $item['country_id'] ] = $item['country_name'];
            }
        }
        // get state
        $stateList = DB::table('states')->select('*')->where('country_id', $business->country_id)->get();
        $dataState = [];
        if (!empty($stateList)) {
            foreach($stateList as $key => $item) {
                $dataState[ $item->state_id ] = $item->state_name;
            }
        }
        //get city
        $dataCity = [];
        $cityList = $helpDb->getCityById($business->city_id);
        if (!empty($cityList)) {
            $dataCity = $cityList;
        }
        // var_dump($dataCity);exit;
        return view('user.business.updateBusiness', [
            'title' => 'Update Business::Zipidy',
            'page' => 'business/update',
            'dataCountry' => $dataCountry,
            'dataState' => $dataState,
            'dataCity' => $dataCity,
            'dataBusiness' => $business,
            'idBusiness' => $idBusiness,
        ]);
    }

    public function updateBusiness(Request $request) {
        $helpDb = new HelperDB();
        $response = [
            'code' => 300
        ];
        $detail_business = DB::table('businesses')->select('*')->where('business_id', $request->get('businessId'))->first();
        if ($request->hasFile('avatar')) {
            if (file_exists(env('DIR_UPLOAD_BUSINESS').$detail_business->avatar)) {
                unlink(env('DIR_UPLOAD_BUSINESS').$detail_business->avatar);
            }
            $avatar = Auth::user()->user_id.'_avatar.'.$request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(env('DIR_UPLOAD_BUSINESS'), $avatar);
            $response['img'] = url(env('DIR_UPLOAD_BUSINESS').$avatar);
        }
        $flag = true;
        //check city
        //check city
        $checkCity = DB::table('cities')->select('*')->where('city_name', $request->get('city'))->first();
        //check country
        $countryItem = DB::table('countries')
                        ->where('country_id', '=', $request->get('country'))
                        ->first();
        if (!$countryItem) {
            $flag = false;
            $response['msg'] = 'Country not exits!';
        }
        //check state
        $stateItem = [];
        if ($request->get('state')) {
            $stateItem = DB::table('states')
                            ->where('state_id', '=', $request->get('state'))
                            ->where('country_id', '=', $request->get('country'))
                            ->first();
        }
        // var_dump($request->get('state'));exit;
        if ($flag) {
            $dataUpdate = [
                'name' => $request->get('businessName'),
                'address' => $request->get('address'),
                'email' => $request->get('email'),
                'zipcode' => $request->get('zipCode'),
                'country_id' => $request->get('country'),
                'city_id' => '',
                'city_name' => $request->get('city'),
                'state_id' => $request->get('state')
            ];
            if ($checkCity) {
                $dataUpdate['city_id'] = $checkCity->city_id;
                $dataUpdate['city_name'] = $request->get('city');
            }
            if ($stateItem) {
                $dataUpdate['state_id'] = $request->get('state');
            }

            if (isset($avatar) && $avatar) {
                $dataUpdate['avatar'] = $avatar;
            }
            //update business
            DB::table('businesses')->where('business_id', $request->get('businessId'))->update($dataUpdate);
            $response['code'] = 200;
            $response['biz_id'] = $request->get('businessId');
        }
        return $response;
    }

    public function addIndirect(Request $request) {
        $user = Auth::user();
        $helpDb = new HelperDB();
        $urlData = $helpDb->convertUrlUser($request->get('biz_id'));
        $data = [
            'user_id' => $user->user_id,
            'business_id' => $urlData[1],
            'user_type' => 4,
            'status' => 1
        ];
        DB::table('users_businesses')->insert($data);
        $response['code'] = 200;
        return $response;
    }

    public function searchBusiness(Request $request){
        $helpDb = new HelperDB();
        $search = $request->get('search');
        $query = DB::table('businesses')->select('businesses.*', 'states.state_code', DB::raw('COALESCE(direct.total_direct, 0) as total_direct'), DB::raw('COALESCE(indirect.total_indirect, 0) as total_indirect'),'a.join')
            ->leftJoin(DB::raw('(select business_id,count(*) as total_direct from users_businesses where user_type in (1, 2, 3) and status=1 GROUP BY business_id ) as direct'), 'businesses.business_id', '=', 'direct.business_id')
            ->leftJoin(DB::raw('(select business_id,count(*) as total_indirect from users_businesses where user_type=4 and status=1 GROUP BY business_id ) as indirect'), 'businesses.business_id', '=', 'indirect.business_id')
            ->leftJoin(DB::raw("(select 'true' as `join`,business_id from users_businesses where user_id=".Auth::user()->user_id.") as a"), 'businesses.business_id', '=', 'a.business_id')
            ->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
            ->whereIn(DB::raw('businesses.status'), [0, 1]);
        if ($search) {
            $query->Where(function ($sub) use ($search) {
                $sub->where('businesses.name','like', '%'.$search.'%');
                $sub->orwhere('businesses.city_name','like', '%'.$search.'%');
                $sub->orwhere('businesses.zipcode','like', $search);
            });
        }
        // print_r($query->toSql());exit;
        $business = $query->paginate(env('PAGING_MAX'));
        return response()->view('user.business.search', ['business' => $business], 200)
            ->header('Content-Type', 'text');
    }

    public function changeStatus(Request $request){
        $user = $request->get('user');
        $helpDb = new HelperDB();
        if ($user) {
            $usersUpdate = DB::table('users_businesses')
            ->where([
                ['users_businesses.business_id', $request->get('business_id')],
            ])->whereIn('users_businesses.user_id', array_keys($user))
            ->update(['users_businesses.status' => $request->get('status')]);
            $return = [
                'code' => 200,
                'message' => 'Success!!'
            ];
        } else {
            $return = [
                'code' => 404,
                'message' => 'Not Exits!!'
            ];
        }
        return $return;
    }
}
