<?php

namespace App\Http\Controllers\User;
use Illuminate\Support\Facades\Auth;
#use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectRespons;
use App\HelperDB;
use Validator;
use App\FCM\Push;
use App\FCM\Firebase;
use App\Country;
use App\Cities;
use Illuminate\Support\Facades\Hash;
use DB;

class ProfileController extends Controller
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
    public function index(Request $request)
    {
        // $lat = 10.8104432;
        // $lon = 106.71555629999999;
        // $distance = 5;
        
        // $query = "SELECT *,(((acos(sin((".$lat."*pi()/180)) * 
        //     sin((`Latitude`*pi()/180))+cos((".$lat."*pi()/180)) * 
        //     cos((`Latitude`*pi()/180)) * cos(((".$lon."- `Longitude`)* 
        //     pi()/180))))*180/pi())*60*1.1515
        // ) as distance 
        // FROM `MyTable` 
        // HAVING distance >= ".$distance;

        // echo "<pre>";
        // print_r($query);
        // echo "</pre>";
        // exit;
        // exit;
        $helpDb = new HelperDB();
            $listMessage = $helpDb->getListMessageById(Auth::user()->user_id, true);
            if($request->ajax()) {
                return view('user.profile.indexAjax', [
                    'title' => 'Profile user::Zipidy',
                    'listMessage' => $listMessage,
                    'page' => 'profile',
                    'address' => '',
                ])->renderSections()['content'];
            }
            // //check owner business
            // $user = Auth::user();
            // $business = $helpDb->getBusiness(Auth::user()->user_id);
            // $addressBusiness = '';
            // if ($business) {
            //     $businessDetail = $helpDb->getBusinessById($business->business_id);
            //     if ($businessDetail) {
            //         $addressBusiness = (($businessDetail->address) ? $businessDetail->address.', ' : '').(($businessDetail->city_name) ? $businessDetail->city_name.', ' : '').$businessDetail->state_name.' '.$businessDetail->zipcode;
            //     }
            // }
            return view('user.profile.index', [
                'title' => 'Profile user::Zipidy',
                'listMessage' => $listMessage,
                'page' => 'profile',
                'address' => \Session::get('addressFull'),
                // 'ownerBusiness' => $business,
                // 'addressBusiness' => $addressBusiness
            ]);
    }
    
    public function workPlace(Request $request)
    {
        //\Config::get('settings.userType')
        $listWorkPlace = DB::table('businesses')
                ->select([
                'businesses.name',
                'businesses.business_id',
                'businesses.address',
                'users_businesses.user_type',
                'states.state_code',
                'businesses.city_name',
                'businesses.avatar',
                'businesses.zipcode',
                'businesses.lat',
                'businesses.lon',
                DB::raw('COALESCE(direct.total_direct, 0) as total_direct'),
                DB::raw('COALESCE(indirect.total_indirect, 0) as total_indirect')
                ])
                ->join('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id')
                ->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
                ->leftJoin(DB::raw('(select business_id,count(*) as total_direct from users_businesses where user_type in (1, 2, 3) and status=1 GROUP BY business_id ) as direct'), 'businesses.business_id', '=', 'direct.business_id')
                ->leftJoin(DB::raw('(select business_id,count(*) as total_indirect from users_businesses where user_type=4 and status=1 GROUP BY business_id ) as indirect'), 'businesses.business_id', '=', 'indirect.business_id')
                // ->where(DB::raw('businesses.status'), 1)
                ->whereIn(DB::raw('businesses.status'), [0, 1])
                ->where(DB::raw('users_businesses.user_id'), Auth::user()->user_id)
                ->where(DB::raw('users_businesses.status'), 1)
                ->paginate(env('PAGING_MAX'));
        if($request->ajax()) {
            return view('user.profile.businessList', [
                'title' => 'Work place::Zipidy',
                'listBusiness' => $listWorkPlace,
                'page' => 'profile',
                'address' => ''
            ])->renderSections()['content'];
        }
        //check owner business
        $helpDb = new HelperDB();
        $business = $helpDb->getBusiness(Auth::user()->user_id);
        $addressBusiness = [];
        if ($business) {
            $businessDetail = $helpDb->getBusinessById($business->business_id);
            if ($businessDetail) {
                $addressBusiness = (($businessDetail->address) ? $businessDetail->address.', ' : '').(($businessDetail->city_name) ? $businessDetail->city_name.', ' : '').$businessDetail->state_name.' '.$businessDetail->zipcode;
            }
        }
        return view('user.profile.businessList', [
            'title' => 'Work place::Zipidy',
            'listBusiness' => $listWorkPlace,
            'page' => 'profile',
            'address' => \Session::get('addressFull'),
            'ownerBusiness' => $business,
            'addressBusiness' => $addressBusiness
        ]);
    }
    
    public function messageDetail($idMessage)
    {
        $helpDb = new HelperDB();
        
        
        $urlData = $helpDb->convertUrlUser($idMessage);
        
        $type = 0;
        $user_type = $helpDb->checkPermissWithBusiness(Auth::user()['user_id'], $urlData[1]);
        if (!empty($user_type)) {
            $type = $user_type->user_type;
        }
        
        //DB::enableQueryLog();
        $mesage = DB::table('messages')
                ->select([
                'messages.title',
                'messages.detail',
                'messages.business_id',
                'messages.created_at',
                'businesses.name',
                'businesses.address',
                'messages.image',
                'businesses.avatar',
                'businesses.city_name',
                'states.state_code',
                'countries.country_name',
                'businesses.zipcode',
                'businesses.lat',
                'businesses.lon'
                ])
                ->join('user_receive_message', 'messages.message_id', '=', 'user_receive_message.message_id')
                ->join('businesses', 'messages.business_id', '=', 'businesses.business_id')
                ->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
                ->join('countries', 'businesses.country_id', '=', 'countries.country_id')
                ->where(DB::raw('messages.business_id'), $urlData[1])
                ->where(DB::raw('user_receive_message.user_id'), Auth::user()->user_id)
                ->where(DB::raw('messages.message_id'), $urlData[0])
                ->first();
        //dd(DB::getQueryLog());
        
        return view('user.profile.message', [
            'title' => 'Detail message::Zipidy',
            'mesage' => $mesage,
            'replyMessage' => $helpDb->getListReplay($urlData[0], $type),
            'messageId' => $idMessage,
            'page' => 'profile'
        ]);        
    }

    public function messageUserDetail($idMessage)
    {
        $helpDb = new HelperDB();
        $urlData = $helpDb->convertUrlMsg($idMessage);
        
        $type = 0;
        $user_type = $helpDb->checkPermissWithBusiness(Auth::user()['user_id'], $urlData[1]);
        if (!empty($user_type)) {
            $type = $user_type->user_type;
        }
        
        // var_dump($urlData);exit;
        //DB::enableQueryLog();
        $mesage = DB::table('messages')
                ->select([
                'messages.title',
                'messages.detail',
                'messages.business_id',
                'messages.created_at',
                'businesses.name',
                'businesses.address',
                'messages.image',
                'businesses.avatar',
                'users.firstname',
                'users.lastname',
                'users.avatar',
                'users.user_id',
                'businesses.city_name',
                'states.state_code',
                'countries.country_name',
                'businesses.zipcode',
                'businesses.lat',
                'businesses.lon'
                ])
                ->join('businesses', 'messages.business_id', '=', 'businesses.business_id')
                ->join('users', 'users.user_id', '=', 'messages.sender_id')
                ->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
                ->join('countries', 'businesses.country_id', '=', 'countries.country_id')
                ->where(DB::raw('messages.business_id'), $urlData[1])
                ->where(DB::raw('messages.sender_id'), $urlData[2])
                ->where(DB::raw('messages.message_id'), $urlData[0])
                ->first();
                // var_dump($mesage);exit;
        //dd(DB::getQueryLog());
        
        return view('user.profile.messageUser', [
            'title' => 'Detail message::Zipidy',
            'mesage' => $mesage,
            'replyMessage' => $helpDb->getListReplay($urlData[0], $type),
            'messageId' => base64_encode($urlData[0] . '-' . $urlData[1]),
            'page' => 'profile'
        ]);        
    }
    
    public function getMessageByBusinessId($idBusiness)
    {
        $helpDb = new HelperDB();
        $urlData = $helpDb->convertUrlUser($idBusiness);
        $messageList = $helpDb->getListMessageByBusinessId($urlData[0], $urlData[1]);
        $businessInfo = $helpDb->getBusinessById($urlData[1]);
        return view('user.profile.businessById', [
            'title' => 'Detail business::Zipidy',
            'messageList' => $messageList,
            'page' => 'business',
            'urlData' => $urlData,
            'page' => 'profile'
        ]); 
    }
    
    public function search(Request $request)
    {
        $s  = $request->get('s');
        $listBusiness = [];
        if ($s != '') {
            $listBusiness = DB::table('businesses')
                    ->select([
                    'businesses.name',
                    'businesses.business_id',
                    'businesses.address',
                    'users_businesses.user_type'
                    ])
                    ->join('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id')
                    // ->where(DB::raw('businesses.status'), 1)
                    ->whereIn(DB::raw('businesses.status'), [0, 1])
                    ->where('businesses.name', 'like', "%$s%")
                    ->get();
        }
        return view('user.profile.businessList', [
            'title' => 'Business list::Zipidy',
            'listBusiness' => $listBusiness,
            'page' => 'profile'
        ]);
    }
    
    public function pageBusiness($idBusiness, Request $request) {
        $helpDb = new HelperDB();
        $urlData = $helpDb->convertUrlUser($idBusiness);
        // check business exit
        $businessDetail = $helpDb->getBusinessById($urlData[1]);

        if (empty($businessDetail)) {
            abort(404);
        }
        $user_type = $helpDb->checkPermissWithBusiness(Auth::user()['user_id'], $urlData[1]);
        $user_exists = true;
        $getAll = true;
        $messageList = [];
        if (!$user_type) {
            $user_exists = false;
            // $getAll = false;
            // $messageList = $helpDb->getListBusinessMessage($urlData[1], 0);
            $messageList = $helpDb->getListBusinessMessage($urlData[1], false, true);
        } else {
            //show broadcast message
            if ($user_type->user_type == 4) {
                // $getAll = false;
                $messageList = $helpDb->getListBusinessMessage($urlData[1], false, true);
            } elseif ($user_type->user_type == 3) {
                // $getAll = false;
                $statusWithBusiness = $helpDb->checkStatusWithBusiness(Auth::user()['user_id'], $urlData[1]);
                if ($statusWithBusiness->status == 1) {
                    $messageList = $helpDb->getListBusinessMessage($urlData[1], true, true);
                } else {
                    $messageList = $helpDb->getListBusinessMessage($urlData[1], false, true);
                }
                
            }else {
                // show all 
                $messageList = $helpDb->getListBusinessMessage($urlData[1], true, true);
            }
        }
        if($request->ajax()) {
                return view('user.profile.businessDetailAjax', [
            'title' => 'Detail business::Zipidy',
            'messageList' => $messageList,
            'page' => 'business',
            'urlData' => $urlData,
            'idBusiness' => $idBusiness,
            'businessDetail' => $businessDetail,
            'permis' => $helpDb->checkPermissUserWithBusiness(Auth::user()['user_id'], $urlData[1]),
            'user_exists' => $user_exists,
            'user_type' => $user_type,
            'address' => ''
        ])->renderSections()['content'];
            }
        return view('user.profile.businessDetail', [
            'title' => 'Detail business::Zipidy',
            'messageList' => $messageList,
            'page' => 'business',
            'urlData' => $urlData,
            'idBusiness' => $idBusiness,
            'businessDetail' => $businessDetail,
            'permis' => $helpDb->checkPermissUserWithBusiness(Auth::user()['user_id'], $urlData[1]),
            'user_exists' => $user_exists,
            'user_type' => $user_type,
            'address' => (($businessDetail->address) ? $businessDetail->address.', ' : '').(($businessDetail->city_name) ? $businessDetail->city_name.', ' : '').$businessDetail->state_name.' '.$businessDetail->zipcode.' '.$businessDetail->country_name
        ]); 
    }
    
    public function ajaxFollow(Request $request) {
        $helpDb = new HelperDB();
        $status = $request->get('follow');
        $idBusiness = $request->get('idBusiness');
        $idUser = Auth::user()['user_id'];
        $urlData = $helpDb->convertUrlUser($idBusiness);
        
        // validate ajax
        if (count($urlData) != 2 && $status == '' && $idUser == '') {
            return response()->json([
                'message' => 'Not follow!',
                'code' => 300
            ], 200);
        }

        if ($status == 'true') {
            DB::table('users_businesses')->insert(
                [
                    'user_id' => $idUser,
                    'business_id' => $urlData[1],
                    'user_type' => 4,
                    'status' => 1
                ]
            );
        } else {
            DB::table('users_businesses')
                ->where('user_id', $idUser)
                ->where('business_id', $urlData[1])
                ->delete();

        }
        
        return response()->json([
            'message' => 'Success follow!',
            'code' => 200
        ], 200);
    }
    
    
    public function addMessage($idBusiness) {
        $helpDb = new HelperDB();
        $urlData = $helpDb->convertUrlUser($idBusiness);
        
        $businessDetail = $helpDb->getBusinessById($urlData[1]);
        if (empty($businessDetail)) {
            abort(404);
        }
        
        return view('user.profile.addMessage', [
            'title' => 'Add message::Zipidy',
            'idBusiness' => $idBusiness,
            'page' => 'business',
            'urlData' => $urlData,
            'userType' => $helpDb->getTypeUserBuBusiness($urlData[1]),
            'businessDetail' => $businessDetail
            
        ]); 
    }
    
    public function ajaxSendMessage(Request $request) {
        $helpDb = new HelperDB();
        $idBusiness = $request->get('idBusiness');
        $idUser = Auth::user()['user_id'];
        $urlData = $helpDb->convertUrlUser($idBusiness);
        
        // validate ajax
        if (count($urlData) != 2 && $idUser == '') {
            return response()->json([
                'message' => 'Not access!',
                'code' => 300
            ], 200);
        }
        $user_type = $helpDb->checkPermissWithBusiness(Auth::user()['user_id'], $urlData[1]);
        // init data
      	if (isset($user_type) && in_array($user_type->user_type, [1,2])) {
            $type = $request->get('option-message');
        } else {
            $type = 3;
        }
        $data = [
                'title' => '',
                'detail' => $request->get('message'),
                'sender_id' => $idUser,
                'message_type' => $type,
                'business_id' => $urlData[1],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s', time()),
                'updated_at' => date('Y-m-d H:i:s', time()),
                'image' => ''
            ];
        // upload immage
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($validator->passes() &&  isset($request->all()['image'])) {
            $input = $request->all();
            $input['image'] = time().'.'.$request->image->getClientOriginalExtension();
            $request->image->move(env('DIR_UPLOAD_MESSAGE'), $input['image']);
            $data['image'] = $input['image'];
        }
        
        // insert message
        $idMessage = DB::table('messages')->insertGetId($data);
        // get list business
        $businessDetail = $helpDb->getBusinessById($urlData[1]);
        
        $listUser = '';
        if (!empty($businessDetail)) {
            //$listUser = $helpDb->getUserBroadcast($businessDetail);
            if ($type == 1) {
                $listUser1 = $helpDb->getUserBroadcast($businessDetail);
                $listUser2 = $helpDb->getUserBroadcastFree($businessDetail);
                $listUser = array_merge($listUser1, $listUser2);
            }
            if ($type == 2) {
                $listUser = $helpDb->getUserPublic($businessDetail);
            }
            if ($type == 3) {
                $listUser = $helpDb->getUserPrivate($businessDetail);
            }
        }
        
        // send message
        $res = array();
        /*
        $res['data']['title'] = $data['title'];
        $res['data']['message'] = $data['detail'];
        
        $res['data']['timestamp'] = date('Y-m-d H:i:s');
        */
        $imgPath = '';
        if ($data['image'] != '') {
            $imgPath = url(env('DIR_UPLOAD_MESSAGE')) . '/' . $data['image'];
        }
        $res['data'] = [
            "image" => $imgPath,
            "click_action" => url('user/business/page/' . $idBusiness),
            "payload" => [
                    "team" => $businessDetail->name,
                    "score" => "5.6",
                    "message" => [
                        "title" => $businessDetail->name,
                        "message_id" => $idMessage,
                        "content" => $data['detail'],
                        "reply_id" => 0,
                        "type" => "message",
                        'senderId' => Auth::user()['user_id']
                    ]
            ],
            "timestamp" => date('Y-m-d H:i:s')
        ];
        
        $firebase = new Firebase();
        $noSend = [];
        if (!empty($listUser)) {
            foreach ($listUser as $item) {
                $record = DB::table('user_receive_message')
                    ->where('user_id', '=', $item->user_id)
                    ->where('message_id', '=', $idMessage)
                    ->first();
                if (empty($record)) {
                    if ($item->fcm_id != '') {
                        $response = $firebase->send($item->fcm_id, $res);
                    }
                    if ($item->fcm_id_web != '') {
                        $response = $firebase->send($item->fcm_id_web, $res);
                    }
                    if ($item->fcm_id_ios != '') {
                        $response = $firebase->send($item->fcm_id_ios, $res);
                    }
                    
                    // save data send message
                    $dataInsertS = [
                        'user_id' => $item->user_id,
                        'message_id' => $idMessage,
                        'created_at'  => date('Y-m-d H:i:s')
                    ];
                    DB::table('user_receive_message')->insertGetId($dataInsertS);
                } else {
                    $noSend[] = [
                        'user_id' => $item->user_id,
                        'message_id' => $idMessage,
                    ];
                }
            }
        }
        
        return response()->json([
            'message' => 'Success send!',
            'code' => 200,
            'noSend' => $noSend
        ], 200);
    }
    
    public function ajaxReplayMessage(Request $request) {
        $helpDb = new HelperDB();
        $messageId = $request->get('messageId');
        $idUser = Auth::user()['user_id'];
        $urlData = $helpDb->convertUrlUser($messageId);
        $dataReponse = [];
        
        // validate ajax
        if (count($urlData) != 2 && $idUser == '') {
            return response()->json([
                'message' => 'Not access!',
                'code' => 300
            ], 200);
        }
        // init data
        $data = [
                'message_id' => $urlData[0],
                'detail' => $request->get('message'),
                'sender_id' => $idUser,
                'created_at' => date('Y-m-d H:i:s', time()),
                'image' => ''
            ];
        $dataReponse['date'] = $helpDb->printDate(date('Y-m-d H:i:s', time()) );
        $dataReponse['message'] = $request->get('message');
        $dataReponse['userName'] = Auth::user()->firstname.' '.Auth::user()->lastname;
        $dataReponse['avatar'] = '';
        if (Auth::user()['avatar'] && file_exists(env('DIR_UPLOAD_USER').Auth::user()['avatar'])) {
            $dataReponse['avatar'] = url(env('DIR_UPLOAD_USER').Auth::user()['avatar']);
        }
        $dataReponse['no_image'] = '';
        if($dataReponse['avatar'] == '') {
            $dataReponse['no_image'] = $helpDb->noImage(Auth::user()->firstname, false);
        }
        
        
        // upload immage
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->passes() &&  isset($request->all()['image'])) {
            $input = $request->all();
            $input['image'] = time().'.'.$request->image->getClientOriginalExtension();
            $request->image->move( env('DIR_UPLOAD_MESSAGE'), $input['image']);
            $data['image'] = $input['image'];
            $dataReponse['image'] = url(env('DIR_UPLOAD_MESSAGE')) . '/' . $input['image'];
        }
        // insert message
        DB::table('reply_messages')->insert($data);
        
        /*----------------------send message-----*/
        // get owner message
        $messageDetail = $helpDb->getMessageDetail($urlData[0]);
        $userDetail = $helpDb->getUserById($messageDetail->sender_id);
        $businessDetail = $helpDb->getBusinessById($urlData[1]);
        $idBusiness = generateId(($businessDetail->business_id + 100)  . '-' . $businessDetail->business_id);
        if (!empty($messageDetail)) {
			
            $res['data'] = [
                "image" => $data['image'],
                "click_action" => url('user/business/page/' . $idBusiness),
                "payload" => [
                        "team" => $businessDetail->name,
                        "score" => "5.6",
                        "message" => [
                            "title" => $businessDetail->name,
                            "message_id" => $urlData[0],
                            "content" => $data['detail'],
                            "reply_id" => 0,
                            "type" => "message",
                            'senderId' => Auth::user()['user_id']
                        ]
                ],
                "timestamp" => date('Y-m-d H:i:s')
            ];

            
            $firebase = new Firebase();
            $userToken = $userDetail->fcm_id_web;
            
            if ($userDetail->fcm_id != '') {
                $response = $firebase->send($userDetail->fcm_id, $res);
            }
            if ($userDetail->fcm_id_web != '') {
                $response = $firebase->send($userDetail->fcm_id_web, $res);
            }
            if ($userDetail->fcm_id_ios != '') {
                $response = $firebase->send($userDetail->fcm_id_ios, $res);
            }
        }
        
        return response()->json([
            'message' => 'Success send!',
            'code' => 200,
            'dataMessage' => $dataReponse
        ], 200);
    }

    public function updatePage() {
        $user = Auth::user();
        $helpDb = new HelperDB();
        // get list country
        $countryList = Country::get()->toArray();
        $dataCountry = [];
        if (!empty($countryList)) {
            foreach($countryList as $key => $item) {
                $dataCountry[ $item['country_id'] ] = $item['country_name'];
            }
        }
        // get state
        $stateList = DB::table('states')->select('*')->where('country_id', $user->country_id)->get();
        $dataState = [];
        if (!empty($stateList)) {
            foreach($stateList as $key => $item) {
                $dataState[ $item->state_id ] = $item->state_name;
            }
        }
        //get city
        $dataCity = [];
        $cityList = $helpDb->getCityById($user->city_id);
        if (!empty($cityList)) {
            $dataCity = $cityList;
        }
        return view('user.profile.updateProfile', [
            'title' => 'Update Profile::Zipidy',
            'page' => 'profile/update',
            'dataCountry' => $dataCountry,
            'dataState' => $dataState,
            'dataCity' => $dataCity,
            'dataProfile' => $user
        ]);
    }

    public function updateProfile(Request $request) {
        $helpDb = new HelperDB();
        $response = [
            'code' => 300
        ];
        if ($request->hasFile('avatar')) {
            $avatar = Auth::user()->user_id.'_avatar.'.$request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(env('DIR_UPLOAD_USER'), $avatar);
            $response['img'] = url(env('DIR_UPLOAD_USER').$avatar);
        }
        $flag = true;
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
        if ($flag) {
            $dataUpdate = [
                'firstname' => $request->get('firstName'),
                'lastname' => $request->get('lastName'),
                'email' => $request->get('email'),
                'username' => md5($request->get('email')),
                // 'token_key' => $request->get('_token'),
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
            $userAddress = $request->get('city');
            if ($stateItem) {
                $userAddress .= ', '.$stateItem->state_code;
                $dataUpdate['state_id'] = $request->get('state');
            } else {
                $userAddress .= ',';
            }
            $userAddress .= ' '.$request->get('zipCode');
            $userAddress .= ' '.$countryItem->country_name;
            if (isset($avatar) && $avatar) {
                $dataUpdate['avatar'] = $avatar;
            }
            if ($request->get('password')) {
                $dataUpdate['password'] = Hash::make($request->get('password'));
            }
            //update business
            DB::table('users')->where('user_id', $request->get('userId'))->update($dataUpdate);
            // var_dump(Auth::user());exit;
            // Auth::user()->update($dataUpdate);
            \Session::put('addressFull', $userAddress);
            $response['code'] = 200;

        }
        return $response;
    }

    public function checkUserAcc(Request $request)
    {
        $return = [
            'code' => 300,
            'message' => 'Email is exits!!'
        ];
        
        if ($request->get('email') == '') {
            return $return;
        }
        if (Auth::user()->email != trim($request->get('email'))) {
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
        } else {
            $return = [
                'code' => 200,
                'message' => 'Not exit!!'
            ];
           return $return;
        }
    }

    public function pageNotification(Request $request)
    {
        $helpDb = new HelperDB();
        $listNotification= $helpDb->getNotification(Auth::user()->user_id, true);
        // var_dump($listNotification);exit;
        if($request->ajax()) {
            return view('user.profile.notificationList', [
                'title' => 'Profile user::Zipidy',
                'listNotification' => $listNotification,
                'page' => 'notification'
            ])->renderSections()['content'];
        }
        return view('user.profile.notificationList', [
            'title' => 'Profile user::Zipidy',
            'listNotification' => $listNotification,
            'page' => 'notification'
        ]);
    }

    public function updateNotification(Request $request) {
        $helpDb = new HelperDB();
        $response = [
            'code' => 300
        ];
        $user = Auth::user();
        $check = DB::table('users_businesses')->where('user_id', $user->user_id)->where('business_id', $request->get('biz_id'))->get();
        if ($check) {
            if ($request->get('flag')) {
                $data = [
                    'status' => 1
                ];
                DB::table('users_businesses')->where('user_id', $user->user_id)->where('business_id', $request->get('biz_id'))->update($data); 
                $response['msg'] = 'Congratulations! Your\'s accept have been success!';
            } else {
                DB::table('users_businesses')->where('user_id', $user->user_id)->where('business_id', $request->get('biz_id'))->delete();
                $response['msg'] = 'You have been denied this notification!';
            }
            $response['count'] = $helpDb->getCountNotification(Auth::user()->user_id);
            $response['code'] = 200;
        }              
        return $response;
    }
}
