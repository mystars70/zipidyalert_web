<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Businesses, DB;
use App\Users;
use App\HelperDB;
use Illuminate\Support\Facades\Hash;
use App\Country;

class BusinessesController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.businesses.index', [
            'title' => 'Businesses Manager'
        ]);
    }

    public function userBusiness($id)
    {
        $objDB = Businesses::select([
            'businesses.business_id',
            'businesses.name',
            'businesses.status',
            'businesses.address',
            'businesses.phone',
            'businesses.city_name',
            'states.state_name',
            'countries.country_name',
            'businesses.zipcode',
            'businesses.email',
            'businesses.created_at',
            DB::raw('count(messages.message_id) as total'),
            DB::raw('CONCAT(COALESCE (total_direct,0),"/",COALESCE (total_indirect,0)) as direct_indirect'),
            DB::raw('COALESCE (total_direct,0) as total_direct'),
            DB::raw('COALESCE (total_indirect,0) as total_indirect'),
        ]);
        $objDB->leftJoin(DB::raw('(select business_id, count(*) as total_direct from users_businesses where  user_type=3 group by business_id) as user_direct'), 'businesses.business_id', '=', 'user_direct.business_id');
        $objDB->leftJoin(DB::raw('(select business_id, count(*) as total_indirect from users_businesses where user_type=4 group by business_id) as user_indirect'), 'businesses.business_id', '=', 'user_indirect.business_id');
        $objDB->leftJoin('messages', 'businesses.business_id', '=', 'messages.business_id');
        $objDB->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
            ->join('countries', 'businesses.country_id', '=', 'countries.country_id');
        $objDB->groupBy('businesses.business_id')
            ->where(DB::raw('businesses.business_id'), $id);
        
        // $objDB = Businesses::select(['businesses.*','states.state_name','countries.country_name']);
        // $objDB->leftJoin('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id');
        // $objDB->join('users', 'users.user_id', '=', 'users_businesses.user_id');
        // $objDB->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
        //     ->join('countries', 'businesses.country_id', '=', 'countries.country_id')
        $detailBusiness = $objDB->first();
        if (empty($detailBusiness)) {
            abort(404);
        }
        return view('admin.businesses.listUser', [
            'title' => 'Businesses Manager',
            'business_id' => $id,
            'detailBusiness' => $detailBusiness,
        ]);
    }
    
    public function ajaxListBusiness(Request $request)
    {
        DB::enableQueryLog();
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        $objDB = Businesses::select([
            'businesses.business_id',
            'businesses.name',
            'businesses.status',
            'businesses.address',
            'businesses.phone',
            'businesses.city_name',
            'states.state_name',
            'countries.country_name',
            'businesses.zipcode',
            'businesses.email',
            'businesses.created_at',
            'businesses.city_id',
            DB::raw('count(messages.message_id) as total'),
            DB::raw('CONCAT(COALESCE (total_direct,0),"/",COALESCE (total_indirect,0)) as direct_indirect'),
            DB::raw('COALESCE (total_direct,0) as total_direct'),
            DB::raw('COALESCE (total_indirect,0) as total_indirect'),
        ]);
        $objDB->leftJoin(DB::raw('(select business_id, count(*) as total_direct from users_businesses where  user_type=3 group by business_id) as user_direct'), 'businesses.business_id', '=', 'user_direct.business_id');
        $objDB->leftJoin(DB::raw('(select business_id, count(*) as total_indirect from users_businesses where user_type=4 group by business_id) as user_indirect'), 'businesses.business_id', '=', 'user_indirect.business_id');
        $objDB->leftJoin('messages', 'businesses.business_id', '=', 'messages.business_id');
        $objDB->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
            ->join('countries', 'businesses.country_id', '=', 'countries.country_id');
        $objDB->groupBy('businesses.business_id');
        // Check is keyword
        $searchKey = $request->get('search');
        if ($searchKey['value'] != '') {
            $search = explode('|', $searchKey['value']);
            if ($search[0] != '') {
                $objDB->Where(function ($sub) use ($search) {
                    $sub->where('businesses.name', 'like', '%' . $search[0] . '%');
                    $sub->orwhere('businesses.email', 'like', '%' . $search[0] . '%');
                    $sub->orwhere('businesses.address', 'like', '%' . $search[0] . '%');
                    $sub->orwhere('businesses.city_name', 'like', '%' . $search[0] . '%');
                    $sub->orwhere('states.state_name', 'like', '%' . $search[0] . '%');
                    $sub->orwhere('businesses.zipcode', 'like', '%' . $search[0] . '%');
                    $sub->orwhere(DB::raw("CONCAT(businesses.city_name, ' ',states.state_name, ' ', businesses.zipcode)"),'like', '%'.$search[0].'%');
                });
            }
            if ($search[1] != '') {
                $objDB->where('businesses.status', $search[1]);
            }
        }
        // $objDB->where('users_businesses.user_id', '=', DB::raw('messages.sender_id'));
        // sort
        $sort = $request->get('order');
        $columns = $request->get('columns');
        
        if (isset($sort[0])) {
            $objDB->orderBy($columns[$sort[0]['column']]['data'], $sort[0]['dir']);
        }
        $data = $objDB->paginate($request->get('length'))->toArray();
        // add id
        if (!empty($data['data'])) {
            foreach ($data['data'] as $key => $item) {
                $data['data'][$key]['no'] = $request->get('start') + $key + 1;
                $data['data'][$key]['created_at'] = date('h:i a m-d-Y', strtotime($item['created_at']));
                if (date('Ymd', strtotime($item['created_at'])) == date('Ymd', time())) {
                    $data['data'][$key]['new'] = 1;
                } else {
                    $data['data'][$key]['new'] = 0;
                }
            }
        }
        //dd(DB::getQueryLog());
        return response()->json([
            'draw' => $request->get('draw'),
            "recordsFiltered" => $data['total'],
            'recordsTotal' => $data['total'],
            'data' => $data['data']
        ]);
    }
    
    public function ajaxChangeStatus(Request $request) 
    {
        $status = '';
        $html = '';
        $response = [
            'code' => 100,
            'html' => $html
        ];
        if ($request->get('status') == '' && $request->get('id') == '') {
            return response()->json($response);
        }
        
        if ($request->get('status') == 1) {
            $status = 0;
            $response['code'] = 200;
            $response['html'] = '<i class="fa fa-close status-disable"></i>';
        } else {
            $status = 1;
            $response['code'] = 200;
            $response['html'] = '<i class="fa fa-check status-enable"></i>';
        }
        $response['html'] = "<a href='#' onclick='changeStatusB(this, " . $request->get('id') . ", $status)'>" . $response['html'] . "</i></a>";
        Businesses::where('business_id', $request->get('id'))
            ->update(['status' => $status]);
        return response()->json($response);
        
    }
    
    public function detail($id) {
        $helpDb = new HelperDB();
         /*---------------get detail---------*/
        $objDB = Businesses::select(['businesses.*','users.user_id',DB::raw('users.email as email_user'),'users.firstname','users.lastname','countries.country_name']);
        $objDB->leftJoin('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id');
        $objDB->join('users', 'users.user_id', '=', 'users_businesses.user_id');
        $objDB->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
            ->join('countries', 'businesses.country_id', '=', 'countries.country_id')
            ->where(DB::raw('businesses.business_id'), $id)
            ->where(DB::raw('users_businesses.user_type'), 1);
            // print_r($objDB->toSql());exit;
        $detailBusiness = $objDB->first();
        
        if (empty($detailBusiness)) {
            abort(404);
        }
        // DB::connection()->setFetchMode(\PDO::FETCH_ASSOC);
        $query = DB::table('industries')->select('*')->get();
        $query = json_decode(json_encode((array) $query), true);
        $listIndustries = [];
        if ($query) {
            $listIndustries = array_column($query , 'name', 'industry_id');
            array_unshift($listIndustries, "Select Industry");
        }
        $countryList = Country::get()->toArray();
        $dataCountry = [];
        if (!empty($countryList)) {
            foreach($countryList as $key => $item) {
                $dataCountry[ $item['country_id'] ] = $item['country_name'];
            }
        }
        // get state
        $stateList = DB::table('states')->select('*')->where('country_id', $detailBusiness->country_id)->get();
        $dataState = [];
        if (!empty($stateList)) {
            foreach($stateList as $key => $item) {
                $dataState[ $item->state_id ] = $item->state_name;
            }
        }
        //get city
        $dataCity = [];
        $cityList = $helpDb->getCityById($detailBusiness->city_id);
        if (!empty($cityList)) {
            $dataCity = $cityList;
        }
        // var_dump($detailBusiness);exit;
        return view('admin.businesses.detail', [
            'title' => 'Business Detail',
            'detailBusiness' => $detailBusiness,
            'business_id' => $id,
            'owner_id' => $detailBusiness->user_id,
            'listIndustries' => $listIndustries,
            'dataCountry' => $dataCountry,
            'dataState' => $dataState,
            'dataCity' => $dataCity,
        ]);
    }

    public function messages($id) {
        return view('admin.businesses.message', [
            'title' => 'Business Detail',
            'business_id' => $id,
        ]);
    }

    public function detailUser($businessId,$id) {
         /*---------------get detail---------*/
        $objDB = Businesses::select(['businesses.*','states.state_name','countries.country_name',DB::raw('CONCAT(COALESCE (total_direct,0),"/",COALESCE (total_indirect,0)) as direct_indirect')]);
                $objDB->leftJoin(DB::raw('(select business_id, count(*) as total_direct from users_businesses where  user_type=3 group by business_id) as user_direct'), 'businesses.business_id', '=', 'user_direct.business_id');
        $objDB->leftJoin(DB::raw('(select business_id, count(*) as total_indirect from users_businesses where user_type=4 group by business_id) as user_indirect'), 'businesses.business_id', '=', 'user_indirect.business_id');
        $objDB->leftJoin('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id');
        $objDB->join('users', 'users.user_id', '=', 'users_businesses.user_id');
        $objDB->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
            ->join('countries', 'businesses.country_id', '=', 'countries.country_id')
            ->where(DB::raw('businesses.business_id'), $businessId);
        $query = DB::table('users')
            ->join('users_businesses', 'users.user_id', '=', 'users_businesses.user_id')
            ->leftJoin('states', 'users.state_id', '=', 'states.state_id')
            ->join('countries', 'users.country_id', '=', 'countries.country_id')
            ->leftJoin(DB::raw('(select sender_id, count(*) as total_msg from messages where messages.business_id='.$businessId.' GROUP BY sender_id) as b'), 'users.user_id', '=', 'b.sender_id')
            ->leftJoin(DB::raw('(
                    SELECT
                        user_id,
                        count(*) AS receive
                    FROM
                        user_receive_message
                    INNER JOIN 
                        messages
                    ON user_receive_message.message_id = messages.message_id
                    WHERE
                        messages.business_id='.$businessId.'
                    GROUP BY
                        user_id
            ) AS msg_receive'), 'msg_receive.user_id', '=', 'users.user_id')
            ->select(['users.*',DB::raw('COALESCE (total_msg,0) as total_msg'),DB::raw('COALESCE (receive,0) as receive'),'states.state_name', 'countries.country_name', 'users_businesses.created_at as created_on', 'users_businesses.user_type', 'users_businesses.status'])
            ->where([
                ['users.user_id', $id],
                ['users_businesses.business_id', $businessId]
            ]);
            // print_r($query->toSql());exit;
            // var_dump( $query->first());exit;
        $detailUser = $query->first();
        $detailBusiness = $objDB->first();
        
        if (empty($detailUser) || empty($detailBusiness)) {
            abort(404);
        }
        return view('admin.businesses.detailUser', [
            'title' => 'Business Detail',
            'detailUser' => $detailUser,
            'detailBusiness' => $detailBusiness,
            'business_id' => $businessId,
        ]);
    }

    public function detailMessage($business_id,$id) {
         /*---------------get detail---------*/
        $objDB = DB::table('messages')->select([
                    'messages.message_id',
                    'messages.created_at',
                    'messages.detail',
                    'messages.message_type',
                    'users.firstname',
                    'users.lastname',
                    'users.email',
                    'users.user_id',
                    'users.avatar',
                    DB::raw('COALESCE (msg_receive.receive, 0) AS receive'),
                    DB::raw('COALESCE (msg_reply.reply, 0) AS reply'),
                ])
        ->join('users', 'users.user_id', '=', 'messages.sender_id')
        ->leftJoin(DB::raw('(
                    SELECT
                        message_id,
                        count(*) AS receive
                    FROM
                        user_receive_message
                    GROUP BY
                        message_id
            ) AS msg_receive'), 'msg_receive.message_id', '=', 'messages.message_id')
        ->leftJoin(DB::raw('(
                    SELECT
                        message_id,
                        count(*) AS reply
                    FROM
                        reply_messages
                    GROUP BY
                        message_id
            ) AS msg_reply'), 'msg_reply.message_id', '=', 'messages.message_id')
        ->where([
                ['messages.message_id', $id],
            ]);
        $detailMessage = $objDB->first();
        
        if (empty($detailMessage)) {
            abort(404);
        }
        return view('admin.businesses.detailMessage', [
            'title' => 'Business Detail',
            'detailMessage' => $detailMessage,
            'message_id' => $id,
            'business_id' => $business_id,
        ]);
    }

    /**
     * Get list business message
     */
    public function ajaxListBusinessMessage(Request $request)
    {
        DB::enableQueryLog();
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        $objDB = DB::table('messages')->select([
                    'messages.message_id',
                    'messages.created_at',
                    'messages.detail',
                    'messages.message_type',
                    'users.firstname',
                    'users.lastname',
                    'users.email',
                    'users.user_id',
                    DB::raw('COALESCE (msg_receive.receive, 0) AS receive'),
                ])
        ->join('users', 'users.user_id', '=', 'messages.sender_id')
        // ->join('users_businesses', 'users.user_id', '=', 'users_businesses.user_id')
        ->leftJoin(DB::raw('(
                    SELECT
                        message_id,
                        count(*) AS receive
                    FROM
                        user_receive_message
                    GROUP BY
                        message_id
            ) AS msg_receive'), 'msg_receive.message_id', '=', 'messages.message_id')
        ->where([
                ['messages.business_id', $request->get('business_id')],
                ['messages.message_type', $request->get('type')],
            ]);
        // Check is keyword
        $searchKey = $request->get('search');
        if ($searchKey['value'] != '') {
            $objDB->Where(function ($sub) use ($searchKey) {
                $sub->where('users.email', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('users.firstname', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('users.lastname', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('users.city_name', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('countries.country_name', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere(DB::raw("CONCAT(users.firstname, ' ',users.lastname)"),'like', '%'.$searchKey['value'].'%');
            });
        }
        // $objDB->where('businesses.business_id', $request->get('business_id'));
        // $objDB->where('users_businesses.user_type', $request->get('type'));
        // sort
        $sort = $request->get('order');
        $columns = $request->get('columns');
        
        if (isset($sort[0])) {
            $objDB->orderBy($columns[$sort[0]['column']]['data'], $sort[0]['dir']);
        }
        $data = $objDB->paginate($request->get('length'))->toArray();
        // add id
        if (!empty($data['data'])) {
            $data['data'] = json_decode(json_encode($data['data']),true);
            foreach ($data['data'] as $key => $item) {
                $data['data'][$key]['no'] = $request->get('start') + $key + 1;
                $data['data'][$key]['created_at'] = date('h:i m-d-Y', strtotime($item['created_at']));
            }
        }
        //dd(DB::getQueryLog());
        return response()->json([
            'draw' => $request->get('draw'),
            "recordsFiltered" => $data['total'],
            'recordsTotal' => $data['total'],
            'data' => $data['data']
        ]);
    }
    
    /**
     * Get list user message
     */
    public function ajaxListUserMessage(Request $request)
    {
        DB::enableQueryLog();
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        $objDB = DB::table('messages')->select([
                    'messages.created_at',
                    'messages.detail',
                    'messages.message_type',
                    'users.firstname',
                    'users.lastname',
                    'users.email',
                    'users.user_id',
                    DB::raw('COALESCE (msg_receive.receive, 0) AS receive'),
                ])
        ->join('users', 'users.user_id', '=', 'messages.sender_id')
        ->join('users_businesses', 'users.user_id', '=', 'users_businesses.user_id')
        ->leftJoin(DB::raw('(
                    SELECT
                        message_id,
                        count(*) AS receive
                    FROM
                        user_receive_message
                    GROUP BY
                        message_id
            ) AS msg_receive'), 'msg_receive.message_id', '=', 'messages.message_id')
        ->where([
                ['users.user_id', $request->get('user_id')],
                ['messages.business_id', $request->get('business_id')]
            ]);
        // Check is keyword
        $searchKey = $request->get('search');
        if ($searchKey['value'] != '') {
            $objDB->Where(function ($sub) use ($searchKey) {
                $sub->where('users.email', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('users.firstname', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('users.lastname', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('users.city_name', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('countries.country_name', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere(DB::raw("CONCAT(users.firstname, ' ',users.lastname)"),'like', '%'.$searchKey['value'].'%');
            });
        }
        // $objDB->where('businesses.business_id', $request->get('business_id'));
        // $objDB->where('users_businesses.user_type', $request->get('type'));
        // sort
        $sort = $request->get('order');
        $columns = $request->get('columns');
        
        if (isset($sort[0])) {
            $objDB->orderBy($columns[$sort[0]['column']]['data'], $sort[0]['dir']);
        }
        $data = $objDB->paginate($request->get('length'))->toArray();
        // add id
        if (!empty($data['data'])) {
            $data['data'] = json_decode(json_encode($data['data']),true);
            foreach ($data['data'] as $key => $item) {
                $data['data'][$key]['no'] = $request->get('start') + $key + 1;
                $data['data'][$key]['created_at'] = date('h:i m-d-Y', strtotime($item['created_at']));
            }
        }
        //dd(DB::getQueryLog());
        return response()->json([
            'draw' => $request->get('draw'),
            "recordsFiltered" => $data['total'],
            'recordsTotal' => $data['total'],
            'data' => $data['data']
        ]);
    }

    /**
     * Get list user normal
     */
    public function ajaxListUserReceive(Request $request)
    {
        DB::enableQueryLog();
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        $objDB = Users::select([
            'users.user_id',
            'users.username',
            'users.phone',
            'users.address',
            'users.status',
            'users.created_at',
            'users.email',
            'users.firstname',
            'users.lastname',
            'countries.country_name',
            'states.state_name',
            'users.city_name',
            'users.zipcode',
        ]);
        $objDB->leftJoin('user_receive_message', 'users.user_id', '=', 'user_receive_message.user_id');
        $objDB->leftJoin('states', 'users.state_id', '=', 'states.state_id');
        $objDB->join('countries', 'users.country_id', '=', 'countries.country_id');
        $objDB->where('user_receive_message.message_id', $request->get('message_id'));
        // sort
        $sort = $request->get('order');
        $columns = $request->get('columns');
        
        if (isset($sort[0])) {
            $objDB->orderBy($columns[$sort[0]['column']]['data'], $sort[0]['dir']);
        }
        $data = $objDB->paginate($request->get('length'))->toArray();
        // add id
        if (!empty($data['data'])) {
            foreach ($data['data'] as $key => $item) {
                $data['data'][$key]['no'] = $request->get('start') + $key + 1;
                $data['data'][$key]['created_at'] = date('h:i a m-d-Y', strtotime($item['created_at']));
            }
        }
        //dd(DB::getQueryLog());
        return response()->json([
            'draw' => $request->get('draw'),
            "recordsFiltered" => $data['total'],
            'recordsTotal' => $data['total'],
            'data' => $data['data']
        ]);
    }

    /**
     * Get list user normal
     */
    public function ajaxListUserReply(Request $request)
    {
        DB::enableQueryLog();
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        $objDB = Users::select([
            'users.user_id',
            'users.username',
            'users.phone',
            'users.address',
            'users.status',
            'users.email',
            'users.firstname',
            'users.lastname',
            'countries.country_name',
            'states.state_name',
            'users.city_name',
            'users.zipcode',
            'users.avatar',
            'reply_messages.detail',
            'reply_messages.image',
            'reply_messages.created_at',
        ]);
        $objDB->leftJoin('reply_messages', 'users.user_id', '=', 'reply_messages.sender_id');
        $objDB->leftJoin('states', 'users.state_id', '=', 'states.state_id');
        $objDB->join('countries', 'users.country_id', '=', 'countries.country_id');
        $objDB->where('reply_messages.message_id', $request->get('message_id'));
        $objDB->orderBy('reply_messages.created_at', 'desc');
        // sort
        $sort = $request->get('order');
        $columns = $request->get('columns');
        
        if (isset($sort[0])) {
            $objDB->orderBy($columns[$sort[0]['column']]['data'], $sort[0]['dir']);
        }
        $data = $objDB->paginate($request->get('length'))->toArray();
        // add id
        if (!empty($data['data'])) {
            foreach ($data['data'] as $key => $item) {
                $data['data'][$key]['no'] = $request->get('start') + $key + 1;
                if ($data['data'][$key]['avatar'] && file_exists(env('DIR_UPLOAD_USER').$data['data'][$key]['avatar'])) {
                    $data['data'][$key]['avatar'] = url(env('DIR_UPLOAD_USER').$data['data'][$key]['avatar']);
                } else {
                    $data['data'][$key]['avatar'] = url('public/admin/').'/images/user-64.png';
                }
                if ($data['data'][$key]['image'] && file_exists(env('DIR_UPLOAD_MESSAGE').$data['data'][$key]['image'])) {
                    $data['data'][$key]['image'] = url(env('DIR_UPLOAD_MESSAGE').$data['data'][$key]['image']);
                } else {
                    $data['data'][$key]['image'] = '';
                }
                $data['data'][$key]['created_at'] = date('h:i a m-d-Y', strtotime($item['created_at']));
            }
        }
        //dd(DB::getQueryLog());
        return response()->json([
            'draw' => $request->get('draw'),
            "recordsFiltered" => $data['total'],
            'recordsTotal' => $data['total'],
            'data' => $data['data']
        ]);
    }

    /**
     * Get list user normal
     */
    public function ajaxListUser(Request $request)
    {
        DB::enableQueryLog();
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        $objDB = Businesses::select([
            'users.user_id',
            'users.username',
            'users.phone',
            'users.address',
            'users.zipcode',
            'users.lat',
            'users.lon',
            'users_businesses.status',
            'users.city_name',
            'countries.country_name',
            'users.email',
            'users.firstname',
            'users.lastname',
            'users_businesses.created_at',
            'states.state_name',
            'users_businesses.user_type',
            'businesses.business_id'
        ]);
        
        $objDB->join('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id');
        $objDB->join('users', 'users_businesses.user_id', '=', 'users.user_id');
        $objDB->leftJoin('states', 'users.state_id', '=', 'states.state_id');
        $objDB->join('countries', 'users.country_id', '=', 'countries.country_id');
        // Check is keyword
        $searchKey = $request->get('search');
        if ($searchKey['value'] != '') {
            $objDB->Where(function ($sub) use ($searchKey) {
                $sub->where('users.email', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('users.firstname', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('users.lastname', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('users.city_name', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('countries.country_name', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere(DB::raw("CONCAT(users.firstname, ' ',users.lastname)"),'like', '%'.$searchKey['value'].'%');
            });
        }
        $objDB->where('businesses.business_id', $request->get('business_id'));
        $objDB->where('users_businesses.user_type', $request->get('type'));
        // sort
        $sort = $request->get('order');
        $columns = $request->get('columns');
        
        if (isset($sort[0])) {
            $objDB->orderBy($columns[$sort[0]['column']]['data'], $sort[0]['dir']);
        }
        $data = $objDB->paginate($request->get('length'))->toArray();
        // add id
        if (!empty($data['data'])) {
            foreach ($data['data'] as $key => $item) {
                $data['data'][$key]['no'] = $request->get('start') + $key + 1;
                $data['data'][$key]['created_at'] = date('h:i a m-d-Y', strtotime($item['created_at']));
            }
        }
        //dd(DB::getQueryLog());
        return response()->json([
            'draw' => $request->get('draw'),
            "recordsFiltered" => $data['total'],
            'recordsTotal' => $data['total'],
            'data' => $data['data']
        ]);
    }
    
    /**
     * Get list user deny
     */
    public function ajaxListDenyUser(Request $request)
    {
        DB::enableQueryLog();
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        $objDB = Businesses::select([
            'users.user_id',
            'users.username',
            'users.phone',
            'users.address',
            'users.zipcode',
            'users.lat',
            'users.lon',
            'users.status'
        ]);
        
        $objDB->join('user_business_deny', 'businesses.business_id', '=', 'user_business_deny.business_id');
        $objDB->join('users', 'user_business_deny.users_user_id', '=', 'users.user_id');

        // Check is keyword
        $searchKey = $request->get('search');
        if ($searchKey['value'] != '') {
            $objDB->where('users.username', 'like', '%' . $searchKey['value'] . '%');
        }
        $objDB->where('businesses.business_id', $request->get('business_id'));
        // sort
        $sort = $request->get('order');
        $columns = $request->get('columns');
        
        if (isset($sort[0])) {
            $objDB->orderBy($columns[$sort[0]['column']]['data'], $sort[0]['dir']);
        }
        $data = $objDB->paginate($request->get('length'))->toArray();
        // add id
        if (!empty($data['data'])) {
            foreach ($data['data'] as $key => $item) {
                $data['data'][$key]['no'] = $request->get('start') + $key + 1;
            }
        }
        //dd(DB::getQueryLog());
        return response()->json([
            'draw' => $request->get('draw'),
            "recordsFiltered" => $data['total'],
            'recordsTotal' => $data['total'],
            'data' => $data['data']
        ]);
    }

    public function changeInfo(Request $request) {
        $helpDb = new HelperDB();
        $response = [
            'code' => 300
        ];
        if ($request->get('email')) {
            $dataUpdate = [
                'email' => $request->get('email'),
                'username' => md5($request->get('email'))
            ];
            $response['email'] = $request->get('email');
        } elseif ($request->get('password')) {
            $dataUpdate = [
                'password' => Hash::make($request->get('password')),
            ];
        }
        //update business
        DB::table('users')->where('user_id', $request->get('owner_id'))->update($dataUpdate);
        $response['code'] = 200;
        return $response;
    }

    public function changeBusiness(Request $request) {
        $helpDb = new HelperDB();
        $response = [
            'code' => 300
        ];
        $dataUpdate = [];
        // if ($request->all()) {
        //     if ($request->get('status') != null) {
        //         $dataUpdate ['status'] = $request->get('status');
        //         $response['status'] = $request->get('status');
        //     }
        //     if ($request->get('industry') != null) {
        //         $dataUpdate['industry_id'] = $request->get('industry');
        //     }
        //     if ($request->hasFile('avatar')) {
        //         $avatar = $request->get('user_id').'_avatar.'.$request->file('avatar')->getClientOriginalExtension();
        //         $request->file('avatar')->move(env('DIR_UPLOAD_BUSINESS'), $avatar);
        //         $response['img'] = url(env('DIR_UPLOAD_BUSINESS').$avatar);
        //         $dataUpdate ['avatar'] = $avatar;
        //     }
        //     // var_dump($dataUpdate);exit;
        //     //update business
        //     DB::table('businesses')->where('business_id', $request->get('businessId'))->update($dataUpdate);
        //     $response['code'] = 200;
        // }

        $detail_business = DB::table('businesses')->select('*')->where('business_id', $request->get('businessId'))->first();
        if ($request->hasFile('avatar')) {
            $avatar = $request->get('user_id').'_avatar.'.$request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(env('DIR_UPLOAD_BUSINESS'), $avatar);
            $response['img'] = url(env('DIR_UPLOAD_BUSINESS').$avatar);
            // if (file_exists(env('DIR_UPLOAD_BUSINESS').$detail_business->avatar)) {
            //     unlink(env('DIR_UPLOAD_BUSINESS').$detail_business->avatar);
            // }
            // $avatar = Auth::user()->user_id.'_avatar.'.$request->file('avatar')->getClientOriginalExtension();
            // $request->file('avatar')->move(env('DIR_UPLOAD_BUSINESS'), $avatar);
            // $response['img'] = url(env('DIR_UPLOAD_BUSINESS').$avatar);
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
                'state_id' => $request->get('state'),
                'status' => $request->get('status'),
                'industry_id' => $request->get('industry_id')
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
            $response['status'] = $request->get('status');
            $response['biz_id'] = $request->get('businessId');
        }
        return $response;
    }
}
