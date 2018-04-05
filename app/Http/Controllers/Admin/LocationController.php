<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\Paginator;
use App\Users, DB;
use App\Businesses;
use App\UserBusinessDeny;
use Illuminate\Support\Facades\Redirect;


class LocationController extends Controller
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
        return view('admin.location.index', [
            'title' => 'Location Manager'
        ]);
    }
    
    public function ajaxGetListLocation(Request $request)
    {
        DB::enableQueryLog();
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        // sort
        $query1 = DB::table('businesses')->select([
                    'city_name',
                    'state_id',
                    'zipcode',
                    'country_id',
                    DB::raw('min(created_at) AS created_at'),
                    DB::raw('count(*) AS total_business'),
                    DB::raw("'' AS total_indirect")
                ])->groupBy(DB::raw('city_name,state_id,zipcode,country_id'));
        $query2 = DB::table('users')->select([
                    'city_name',
                    'state_id',
                    'zipcode',
                    'country_id',
                    DB::raw('min(created_at) AS created_at'),
                    DB::raw("'' AS total_business"),
                    DB::raw('count(*) AS total_indirect')
                ])->join(DB::raw('(
                SELECT
                    user_id
                FROM
                    users_businesses
                WHERE
                    user_type = 4
                GROUP BY
                    user_id
            ) a'), 'a.user_id', '=', 'users.user_id')
        ->groupBy(DB::raw('city_name,state_id,zipcode,country_id'));
        $objDB = DB::table(DB::raw("({$query2->union($query1)->toSql()}) as a"))->select([
                    'a.city_name',
                    'a.state_id',
                    'states.state_name',
                    'a.zipcode',
                    'a.country_id',
                    'countries.country_name',
                    'a.created_at',
                    DB::raw('sum(total_indirect) AS total_indirect'),
                    DB::raw("sum(total_business) AS total_business")
                ])
        ->leftJoin('states', 'a.state_id', '=', 'states.state_id')
            ->join('countries', 'a.country_id', '=', 'countries.country_id')
            ->groupBy(DB::raw('a.city_name,a.state_id,a.zipcode,a.country_id'))
                    ->mergeBindings($query2);
                    // print_r($objDB->toSql());exit;
        // Check is keyword
        $search = $request->get('search');
        if ($search['value'] != '') {
            if ($search['value'] != '') {
                $objDB->Where(function ($sub) use ($search) {
                    $sub->where('country_name', 'like', '%' . $search['value'] . '%');
                    $sub->orwhere('city_name', 'like', '%' . $search['value'] . '%');
                    $sub->orwhere('state_name', 'like', '%' . $search['value'] . '%');
                    $sub->orwhere('zipcode', 'like', '%' . $search['value'] . '%');
                    $sub->orwhere(DB::raw("CONCAT(city_name, ' ',state_name, ' ', zipcode, ' ', country_name)"),'like', '%'.$search['value'].'%');
                });
            }
        }
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

    public function detail($city_name,$state_id,$zipcode,$country_id)
    {
        $query1 = DB::table('businesses')->select([
                    'city_name',
                    'state_id',
                    'zipcode',
                    'country_id',
                    DB::raw('min(created_at) AS created_at'),
                    DB::raw('count(*) AS total_business'),
                    DB::raw("'' AS total_indirect")
                ])->groupBy(DB::raw('city_name,state_id,zipcode,country_id'));
        $query2 = DB::table('users')->select([
                    'city_name',
                    'state_id',
                    'zipcode',
                    'country_id',
                    DB::raw('min(created_at) AS created_at'),
                    DB::raw("'' AS total_business"),
                    DB::raw('count(*) AS total_indirect')
                ])->join(DB::raw('(
                SELECT
                    user_id
                FROM
                    users_businesses
                WHERE
                    user_type = 4
                GROUP BY
                    user_id
            ) a'), 'a.user_id', '=', 'users.user_id')
        ->groupBy(DB::raw('city_name,state_id,zipcode,country_id'));
        $objDB = DB::table(DB::raw("({$query2->union($query1)->toSql()}) as a"))->select([
                    'a.city_name',
                    'a.state_id',
                    'states.state_name',
                    'a.zipcode',
                    'a.country_id',
                    'countries.country_name',
                    'a.created_at',
                    DB::raw('sum(total_indirect) AS total_indirect'),
                    DB::raw("sum(total_business) AS total_business")
                ])
        ->leftJoin('states', 'a.state_id', '=', 'states.state_id')
            ->join('countries', 'a.country_id', '=', 'countries.country_id')
        ->where(DB::raw('a.city_name'), $city_name)
        ->where(DB::raw('a.state_id'), $state_id)
        ->where(DB::raw('a.zipcode'), $zipcode)
        ->where(DB::raw('a.country_id'), $country_id)
        ->groupBy(DB::raw('a.city_name,a.state_id,a.zipcode,a.country_id'))
                    ->mergeBindings($query2);
        $data = $objDB->first();
        if (!$data) {
            abort(404);
        }
        return view('admin.location.detail', [
            'title' => 'User Detail',
            'city_name' => $city_name,
            'state_id' => $state_id,
            'zipcode' => $zipcode,
            'country_id' => $country_id,
            'detail' => $data
        ]);
    }

    public function ajaxGetListBusinesses(Request $request)
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
            'countries.country_name',
            'states.state_name',
            'businesses.*'
        ]);
        $objDB->leftJoin('states', 'businesses.state_id', '=', 'states.state_id');
        $objDB->join('countries', 'businesses.country_id', '=', 'countries.country_id');
        $objDB->where('businesses.city_name', $request->get('city_name'));
        $objDB->where('businesses.state_id', $request->get('state_id'));
        $objDB->where('businesses.zipcode', $request->get('zipcode'));
        $objDB->where('businesses.country_id', $request->get('country_id'));
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

    public function ajaxGetListIndirect(Request $request)
    {
        DB::enableQueryLog();
        // force current page to 5
        $currentPage = ceil(($request->get('start') +$request->get('length')) / $request->get('length'));
        // force current page to 5
        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });
        // Build sql query
        $objDB = DB::table('users')->select([
                    'users.*'
                ])->join(DB::raw('(
                SELECT
                    user_id
                FROM
                    users_businesses
                WHERE
                    user_type = 4
                GROUP BY
                    user_id
            ) a'), 'a.user_id', '=', 'users.user_id');
        $objDB->leftJoin('states', 'users.state_id', '=', 'states.state_id');
        $objDB->join('countries', 'users.country_id', '=', 'countries.country_id');
        $objDB->where('users.city_name', $request->get('city_name'));
        $objDB->where('users.state_id', $request->get('state_id'));
        $objDB->where('users.zipcode', $request->get('zipcode'));
        $objDB->where('users.country_id', $request->get('country_id'));
        // sort
        $sort = $request->get('order');
        $columns = $request->get('columns');
        
        if (isset($sort[0])) {
            $objDB->orderBy($columns[$sort[0]['column']]['data'], $sort[0]['dir']);
        }
        $data = $objDB->paginate($request->get('length'))->toArray();
        // var_dump($data);exit;
        // add id
        if (!empty($data['data'])) {
            $data['data'] = json_decode(json_encode($data['data']),true);
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
}
