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
use App\HelperDB;
use App\Country;

class UsersController extends Controller
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
        return view('admin.users.index', [
            'title' => 'Users Manager'
        ]);
    }

    public function ajaxGetListUser(Request $request)
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
            // 'user_business_deny.users_user_id as deny',
            'users.email',
            'users.firstname',
            'users.lastname',
            'countries.country_name',
            'states.state_name',
            'users.city_name',
            'users.zipcode',
        ]);
        // $objDB->join('users_businesses', 'users.user_id', '=', 'users_businesses.user_id');
        // $objDB->leftJoin('user_business_deny', 'users.user_id', '=', 'user_business_deny.users_user_id');
        $objDB->leftJoin('states', 'users.state_id', '=', 'states.state_id');
        $objDB->join('countries', 'users.country_id', '=', 'countries.country_id');
        // Check is keyword
        $searchKey = $request->get('search');
        if ($searchKey['value'] != '') {
            $search = explode('|', $searchKey['value']);
            if ($search[0] != '') {
                $objDB->Where(function ($sub) use ($search) {
                    $sub->where('users.firstname', 'like', '%' . $search[0] . '%');
                    $sub->orwhere('users.lastname', 'like', '%' . $search[0] . '%');
                    $sub->orwhere('users.email', 'like', '%' . $search[0] . '%');
                    // $sub->orwhere('businesses.address', 'like', '%' . $search[0] . '%');
                    $sub->orwhere('users.city_name', 'like', '%' . $search[0] . '%');
                    $sub->orwhere('states.state_name', 'like', '%' . $search[0] . '%');
                    $sub->orwhere('users.zipcode', 'like', '%' . $search[0] . '%');
                    $sub->orwhere(DB::raw("CONCAT(users.city_name, ' ',states.state_name, ' ', users.zipcode)"),'like', '%'.$search[0].'%');
                    $sub->orwhere(DB::raw("CONCAT(users.firstname, ' ',users.lastname)"),'like', '%'.$search[0].'%');
                });
            }
            if ($search[1] != '') {
                $objDB->where('businesses.status', $search[1]);
            }
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

    public function ajaxGetListUserBusinesses(Request $request)
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
            'businesses.address',
            'businesses.phone',
            'businesses.city_name',
            'states.state_name',
            'countries.country_name',
            'businesses.zipcode',
            'businesses.email',
            'businesses.created_at',
            'users_businesses.user_type',
            'businesses.status',
        ]);
        $objDB->leftJoin('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id');
        $objDB->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
            ->join('countries', 'businesses.country_id', '=', 'countries.country_id');
        $objDB->groupBy('businesses.business_id')
        ->where(DB::raw('users_businesses.user_id'), $request->get('user_id'));
        // Check is keyword
        // $searchKey = $request->get('search');
        // if ($searchKey['value'] != '') {
        //     $objDB->Where(function ($sub) use ($searchKey) {
        //         $sub->where('businesses.name', 'like', '%' . $searchKey['value'] . '%');
        //         $sub->orwhere('businesses.city_name', 'like', '%' . $searchKey['value'] . '%');
        //         $sub->orwhere('states.state_name', 'like', '%' . $searchKey['value'] . '%');
        //         $sub->orwhere('businesses.zipcode', 'like', '%' . $searchKey['value'] . '%');
        //         $sub->orwhere(DB::raw("CONCAT(businesses.city_name, ' ',states.state_name, ' ', businesses.zipcode)"),'like', '%'.$searchKey['value'].'%');
        //     });
        // }
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
                $data['data'][$key]['created_at'] = date('h:i m-d-Y', strtotime($item['created_at']));
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

    public function ajaxChangeStatusUser(Request $request)
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
        $response['html'] = "<a href='#' onclick='changeStatusUser(this, " . $request->get('id') . ", $status)'>" . $response['html'] . "</i></a>";
        Users::where('user_id', $request->get('id'))
            ->update(['status' => $status]);
        return response()->json($response);

    }

    public function ajaxChangeStatusDeny(Request $request)
    {
        $status = '';
        $html = '';
        $response = [
            'code' => 100,
            'html' => $html
        ];
        if ($request->get('id') == '') {
            return response()->json($response);
        }

        // get business id
        $objDB = Users::select([
            'users_businesses.business_id'
        ]);
        $objDB->join('users_businesses', 'users.user_id', '=', 'users_businesses.user_id');
        $objDB->where('users.user_id', '=', $request->get('id'));
        $businessRecord = $objDB->first();
        if ($businessRecord == null) {
            return $response;
        }
        // check deny
        $objDB = Users::select([
            'users.user_id'
        ]);
        $objDB->join('users_businesses', 'users.user_id', '=', 'users_businesses.user_id');
        $objDB->leftJoin('user_business_deny', 'users.user_id', '=', 'user_business_deny.users_user_id');
        $objDB->where('user_business_deny.users_user_id', '=', $request->get('id'));
        $firstRecord = $objDB->first();

        if ($firstRecord === null) {
           $deny = 1;
           $response['code'] = 200;
           $response['html'] = '<i class="fa fa-close status-disable"></i>';
           // get business id

           // insert
            $userDeny = UserBusinessDeny::create([
                'users_user_id' => $request->get('id'),
                'business_id' => $businessRecord->business_id
            ]);
        } else {
            $deny = 0;
            $response['code'] = 200;
            $response['html'] = '<i class="fa fa-check status-enable"></i>';
            // remove deny
            $userDeny = UserBusinessDeny::where('users_user_id', '=', $request->get('id'));
            $userDeny->delete();
        }

        $response['html'] = "<a href='#' onclick='changeStatusDeny(this, " . $request->get('id') . ")'>" . $response['html'] . "</i></a>";
        return response()->json($response);

    }

    public function detail($id) {
        $helpDb = new HelperDB();
         /*---------------get detail---------*/
        $objDB = Users::select([
            'businesses.name',
            'users.user_id',
            'users.username',
            'users.phone',
            'users.address',
            'users.zipcode',
            'users.lat',
            'users.lon',
            'users.status',
            'users.created_at',
            'users.email',
            'users.firstname',
            'users.lastname',
            'states.state_name',
            'countries.country_name',
            'users.city_name',
            'users.avatar',
            'users.country_id',
        ]);
        $objDB->where('users.user_id', '=', $id);
        $objDB->join('users_businesses', 'users.user_id', '=', 'users_businesses.user_id');
        $objDB->join('businesses', 'users_businesses.business_id', '=', 'businesses.business_id')
        ->leftJoin('states', 'users.state_id', '=', 'states.state_id')
            ->join('countries', 'users.country_id', '=', 'countries.country_id');
        $detail = $objDB->first();

        if (empty($detail)) {
            abort(404);
        }
        $countryList = Country::get()->toArray();
        $dataCountry = [];
        if (!empty($countryList)) {
            foreach($countryList as $key => $item) {
                $dataCountry[ $item['country_id'] ] = $item['country_name'];
            }
        }
        // get state
        $stateList = DB::table('states')->select('*')->where('country_id', $detail->country_id)->get();
        $dataState = [];
        if (!empty($stateList)) {
            foreach($stateList as $key => $item) {
                $dataState[ $item->state_id ] = $item->state_name;
            }
        }
        //get city
        $dataCity = [];
        $cityList = $helpDb->getCityById($detail->city_id);
        if (!empty($cityList)) {
            $dataCity = $cityList;
        }
        return view('admin.users.detail', [
            'title' => 'User Detail',
            'data' => $detail,
            'user_id' => $id,
            'dataCountry' => $dataCountry,
            'dataState' => $dataState,
            'dataCity' => $dataCity,
        ]);
    }

    public function update($id)
    {
        $model = Users::find($id);

        if (empty($model)) {
            abort(404);
        }

        return view('admin.users.update', [
            'title' => 'Update User Info',
            'model' => $model
        ]);
    }

    /**
	 * Save the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function save($id, Request $request)
	{
        $input = $request->all();
        $user = Users::find($id);
        if (empty($user)) {
            return Redirect::to('admin');
        }

        if ($request->has('status')) {
            $user->status = 1;
        } else {
            $user->status = 0;
        }
		// store

		$user->phone = $input['phone'];
		$user->address = $input['address'];
		$user->zipcode = $input['zipcode'];
        if (isset($input['password']) && $input['password'] != '') {
            $user->password = md5($input['password']);
        }
		$user->save();
		// redirect
        $request->session()->flash('message', 'Successfully updated user!');
		return Redirect::to('admin/users/update/' . $id);
	}

    public function changeUser(Request $request) {
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
        //     DB::table('users')->where('user_id', $request->get('user_id'))->update($dataUpdate);
        //     $response['code'] = 200;
        // }
        if ($request->hasFile('avatar')) {
            $avatar = $request->get('user_id').'_avatar.'.$request->file('avatar')->getClientOriginalExtension();
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
                'zipcode' => $request->get('zipCode'),
                'country_id' => $request->get('country'),
                'city_id' => '',
                'city_name' => $request->get('city'),
                'state_id' => $request->get('state'),
                'status' => $request->get('status')
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
            // if ($request->get('password')) {
            //     $dataUpdate['password'] = Hash::make($request->get('password'));
            // }
            //update business
            DB::table('users')->where('user_id', $request->get('user_id'))->update($dataUpdate);
            $response['code'] = 200;
        }
        return $response;
    }
}
