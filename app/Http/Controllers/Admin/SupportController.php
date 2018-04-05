<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Businesses, DB;
use App\Messages;
use App\ReplyMessages;
use App\SendMail;

class SupportController extends Controller
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
        return view('admin.support.index', [
            'title' => 'Support Manager'
        ]);
    }
    
    public function ajaxListSupport(Request $request)
    {
        DB::enableQueryLog();
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        $objDB = DB::table('support')->select([
            'support.*',
            'users.firstname',
            'users.lastname',
            'users.email',
        ])->join('users', 'users.user_id', '=', 'support.sender_id');
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
    
    public function editPage($id)
    {
        $objDB = DB::table('support')->select([
            'support.*',
            'users.firstname',
            'users.lastname',
            'users.email',
        ])->join('users', 'users.user_id', '=', 'support.sender_id')->where('id','=',$id);
        $detail = $objDB->first();
        if (empty($detail)) {
            abort(404);
        }
        return view('admin.support.edit', [
            'title' => 'Support Manager',
            'detail' => $detail,
            'page' => 'edit'
        ]);
    }

    public function sendSupport(Request $request)
    {
        $response = [
            'code' => 300
        ];
        $objDB = DB::table('support')->select([
            'support.*',
            'users.firstname',
            'users.lastname',
            'users.email',
        ])->join('users', 'users.user_id', '=', 'support.sender_id')->where('id','=',$request->get('id'));
        $detail = $objDB->first();
        if ($detail) {
            $detail = json_decode(json_encode($detail),true);
            $value = config('settings.mailType');
            $email = $detail['email'];
            $email = 'hoanglinh611611@gmail.com';
            $detail['name'] = $detail['firstname'].' '.$detail['lastname'];
            $detail['reply_message'] = nl2br($request->get('message'));
            $sendMail = new SendMail($value['CustomerSupport'], $email, ['support' => $detail]);
            $sendMail->send();
            $dataUpdate = [
                'reply_message' => $request->get('message'),
                'status' => 1
            ];
            DB::table('support')->where('id', $request->get('id'))->update($dataUpdate);
            $response['code'] = 200;
        }
        return $response;
    }
}
