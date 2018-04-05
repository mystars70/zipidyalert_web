<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Businesses, DB;
use App\Messages;
use App\ReplyMessages;

class MailController extends Controller
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
        $path = env('DIR_MAIL_TEMPLATE');
        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        return view('admin.mail.index', [
            'title' => 'Mail Template Manager',
            'listFile' => $files
        ]);
    }

    public function edit($file)
    {
        $path = env('DIR_MAIL_TEMPLATE').$file;
        $fh = fopen($path,'r');
        $content = '';
        if (filesize($path) > 0) {
            $content = fread($fh,filesize($path));
        }
        fclose($fh);
        chmod($path, 0777);
        return view('admin.mail.edit', [
            'title' => 'Mail Template Manager',
            'content' => $content,
            'file' => $file
        ]);
    }

    public function view(Request $request)
    {
        $view = explode('.', $request->get('file'));
        $data = [
            'email' => '',
            'token' => '',
            'user_type' => 'User',
            'url_verify' => '#',
            'business_name' => 'Business Name'
        ];
        return response()->view('mail.'.$view[0], $data, 200)
            ->header('Content-Type', 'text');
    }

    public function save(Request $request)
    {
        $objDB = DB::table('email')->select('*')->Where('id',$request->get('id'))->first();
        if (!$objDB) {
            abort(404);
        }
        $dataUpdate = ['subject' => $request->get('subject')];
        DB::table('email')->where('id', $request->get('id'))->update($dataUpdate);
        $path = env('DIR_MAIL_TEMPLATE').$objDB->template;
        $fh = fopen($path, 'w');
        fwrite($fh, trim($request->get('content')));
        fclose($fh);
        return ['code' => 200];
    }

        public function ajaxGetListMail(Request $request)
    {
        DB::enableQueryLog();
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        $objDB = DB::table('email')->select('*');
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

    public function detail($id)
    {
        $objDB = DB::table('email')->select('*')->Where('id',$id)->first();
        if (!$objDB) {
            abort(404);
        }
        $path = env('DIR_MAIL_TEMPLATE').$objDB->template;
        if ($objDB->template && file_exists(env('DIR_MAIL_TEMPLATE').$objDB->template)) {
            $fh = fopen($path,'r');
            $content = '';
            if (filesize($path) > 0) {
                $content = fread($fh,filesize($path));
            }
            fclose($fh);
        } else {
            $content = '';
        }
        // print_r($content);exit;
        return view('admin.mail.edit', [
            'title' => 'Mail Template Manager',
            'content' => $content,
            'file' => '',
            'detailMail' => $objDB
        ]);
    }
}
