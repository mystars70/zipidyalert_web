<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Businesses, DB;
use App\Messages;
use App\ReplyMessages;

class MessagesController extends Controller
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
        return view('admin.messages.index', [
            'title' => 'Alert Manager'
        ]);
    }
    
    public function messageByBusiness($id) {
        return view('admin.messages.listMessage', [
            'title' => 'Messages Manager',
            'id' => $id
        ]);
    }
    
    public function ajaxMessageByBusiness(Request $request)
    {
        DB::enableQueryLog();
        // init data
        // $dataResponse = [
        //     'draw' => $request->get('draw'),
        //     "recordsFiltered" => 0,
        //     'recordsTotal' => 0,
        //     'data' => []
        // ];
        // if ($request->get('business_id') == 0) {
        //     return $dataResponse;
        // }
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
                    'users.firstname',
                    'users.lastname',
                    'users.email',
                    'users.user_id',
                    'businesses.name',
                    'businesses.business_id',
                    DB::raw('COALESCE (user_direct.total_direct, 0) AS total_direct'),
                    DB::raw('COALESCE (user_indirect.total_indirect, 0) AS total_indirect'),
                    DB::raw('COALESCE (msg_receive.receive, 0) AS receive'),
                    DB::raw('COALESCE (msg_reply.reply, 0) AS reply'),
                    DB::raw('CONCAT(COALESCE (user_direct.total_direct, 0),"/",COALESCE (user_indirect.total_indirect, 0)) AS direct_indirect'),
                ])
        ->join('users', 'users.user_id', '=', 'messages.sender_id')
        ->join('users_businesses', 'users.user_id', '=', 'users_businesses.user_id')
        ->join('businesses', 'businesses.business_id', '=', 'users_businesses.business_id')
        ->leftJoin(DB::raw('(
                SELECT
                    business_id,
                    count(*) AS total_direct
                FROM
                    users_businesses
                WHERE
                    user_type = 3
                GROUP BY
                    business_id
            ) AS user_direct'), 'user_direct.business_id', '=', 'businesses.business_id')
        ->leftJoin(DB::raw('(
                SELECT
                    business_id,
                    count(*) AS total_indirect
                FROM
                    users_businesses
                WHERE
                    user_type = 4
                GROUP BY
                    business_id
            ) AS user_indirect'), 'user_indirect.business_id', '=', 'businesses.business_id')
        ->leftJoin(DB::raw('(
                SELECT
                    business_id,
                    COALESCE (a.receive, 0) AS receive
                FROM
                    users_businesses
                INNER JOIN (
                    SELECT
                        user_id,
                        count(*) AS receive
                    FROM
                        user_receive_message
                    GROUP BY
                        user_id
                ) a ON a.user_id = users_businesses.user_id
                GROUP BY
                    users_businesses.business_id
            ) AS msg_receive'), 'msg_receive.business_id', '=', 'businesses.business_id')
        ->leftJoin(DB::raw('(
                SELECT
                    business_id,
                    COALESCE (a.reply, 0) AS reply
                FROM
                    users_businesses
                INNER JOIN (
                    SELECT
                        sender_id,
                        count(*) AS reply
                    FROM
                        reply_messages
                    GROUP BY
                        sender_id
                ) a ON a.sender_id = users_businesses.user_id
                GROUP BY
                    users_businesses.business_id
            ) AS msg_reply'), 'msg_reply.business_id', '=', 'businesses.business_id');
        // Check is keyword
        $searchKey = $request->get('search');
        if ($searchKey['value'] != '') {
            $objDB->Where(function ($sub) use ($searchKey) {
                $sub->where('users.firstname', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('users.lastname', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('users.email', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere('businesses.name', 'like', '%' . $searchKey['value'] . '%');
                $sub->orwhere(DB::raw("CONCAT(users.firstname, ' ',users.lastname)"),'like', '%'.$searchKey['value'].'%');
            });
        }
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
    
    public function messageDetail($id)
    {
        // init
        $detailMessage = [];
        /*---------------get detail message---------*/
        $objDBMesage = Messages::select([
            'messages.title',
            'messages.detail'
        ]);
        $objDBMesage->where('messages.message_id', '=', $id);
        $detailMessage = $objDBMesage->first();
        return view('admin.messages.messageDetail', [
            'title' => 'Messages Manager',
            'detailMessage' => $detailMessage,
            'message_id' => $id
        ]);
    }
    
    public function ajaxReplyList(Request $request)
    {
        DB::enableQueryLog();
        // init data
        $dataResponse = [
            'draw' => $request->get('draw'),
            "recordsFiltered" => 0,
            'recordsTotal' => 0,
            'data' => []
        ];
        if ($request->get('message_id') == 0) {
            return $dataResponse;
        }
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        $objDB = ReplyMessages::select([
            'reply_messages.title',
            'reply_messages.detail'
        ]);
        $objDB->join('messages', 'reply_messages.message_id', '=', 'messages.message_id');
        // add condition 
        $objDB->where('messages.message_id', '=', $request->get('message_id'));
        // Check is keyword
        $searchKey = $request->get('search');
        if ($searchKey['value'] != '') {
            $objDB->where('reply_messages.title', 'like', '%' . $searchKey['value'] . '%');
        }
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
}
