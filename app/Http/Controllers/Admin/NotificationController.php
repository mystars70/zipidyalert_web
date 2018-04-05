<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Businesses, DB;
use App\Messages;
use App\ReplyMessages;

class NotificationController extends Controller
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
        return view('admin.notification.index', [
            'title' => 'Legal Notification'
        ]);
    }
    
    public function ajaxListNotification(Request $request)
    {
        DB::enableQueryLog();
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        $objDB = DB::table('notification')->select('*');
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
        $objDB = DB::table('notification')->select('*')->where('id','=',$id);
        $detail = $objDB->first();
        if (empty($detail)) {
            abort(404);
        }
        return view('admin.notification.edit', [
            'title' => 'Legal Notification > Update',
            'detail' => $detail,
            'page' => 'edit'
        ]);
    }

    public function addPage()
    {
        return view('admin.notification.edit', [
            'title' => 'Legal Notification > Create',
            'detail' => [],
            'page' => 'add'
        ]);
    }

    public function update(Request $request)
    {
        $response = [
            'code' => 300
        ];
        $objDB = DB::table('notification')->select('*')->where('id', $request->get('id'));
        $detail = $objDB->first();
        if ($detail) {
            $dataUpdate = [
                'name' => $request->get('name'),
                'description' => $request->get('description'),
            ];
            DB::table('notification')->where('id', $request->get('id'))->update($dataUpdate);
            $response['code'] = 200;
        }
        return $response;
    }

    public function create(Request $request)
    {
        $dataInsert = [
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        DB::table('notification')->insert($dataInsert);
        $response['code'] = 200;
        return $response;
    }

    public function delete(Request $request)
    {
        $response = [
            'code' => 300
        ];
        $select = $request->get('select');
        if (!$select) {
            return $response;
        }
        $dataDelete = array_keys($select);
        DB::table('notification') ->whereIn('id', $dataDelete)->delete();
        $response['code'] = 200;
        return $response;
    }
}
