<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Businesses, DB;
use App\Messages;
use App\ReplyMessages;

class FaqController extends Controller
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
        return view('admin.faq.index', [
            'title' => 'FAQ Manager'
        ]);
    }
    
    public function ajaxListFaq(Request $request)
    {
        DB::enableQueryLog();
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        $objDB = DB::table('faq')->select('*');
        // sort
        $sort = $request->get('order');
        $columns = $request->get('columns');
        
        if (isset($sort[0])) {
            $objDB->orderBy($columns[$sort[0]['column']]['data'], $sort[0]['dir']);
        }
        $data = $objDB->paginate($request->get('length'))->toArray();
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
        $objDB = DB::table('faq')->select('*')->where('id','=',$id);
        $detail = $objDB->first();
        if (empty($detail)) {
            abort(404);
        }
        return view('admin.faq.edit', [
            'title' => 'FAQ Manager > Update',
            'detail' => $detail,
            'page' => 'edit'
        ]);
    }

    public function addPage()
    {
        return view('admin.faq.edit', [
            'title' => 'FAQ Manager > Create',
            'detail' => [],
            'page' => 'add'
        ]);
    }

    public function update(Request $request)
    {
        $response = [
            'code' => 300
        ];
        $objDB = DB::table('faq')->select('*')->where('id', $request->get('id'));
        $detail = $objDB->first();
        if ($detail) {
            $dataUpdate = [
                'category' => $request->get('category'),
                'questions' => $request->get('questions'),
                'answers' => $request->get('answers')
            ];
            DB::table('faq')->where('id', $request->get('id'))->update($dataUpdate);
            $response['code'] = 200;
        }
        return $response;
    }

    public function create(Request $request)
    {
        $dataInsert = [
            'category' => $request->get('category'),
            'questions' => $request->get('questions'),
            'answers' => $request->get('answers')
        ];
        DB::table('faq')->insert($dataInsert);
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
        DB::table('faq') ->whereIn('id', $dataDelete)->delete();
        $response['code'] = 200;
        return $response;
    }
}
