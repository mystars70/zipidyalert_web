<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;

class HelperDB extends Model
{
    /**
     * Count business usser folow country
     *
     * @param  int  $id
     * @return int
     */
    public function countBusinessWithCountry($id)
    {
        //DB::enableQueryLog();
        $total = 0;
        if (is_numeric($id)) {
            $result = DB::table('businesses')
                ->select(DB::raw('count(business_id) as total'))
                ->where('status', 1)
                ->where('country_id', $id)
                ->first();
            //dd(DB::getQueryLog());
            if (!empty($result)) {
                $total = $result->total;
            }
        }
        return $total;
        
    }
    
    /**
     * Count business usser folow city
     *
     * @param  int  $id
     * @return int
     */
    public function countBusinessWithCity($id)
    {
        //DB::enableQueryLog();
        $total = 0;
        if (is_numeric($id)) {
            $result = DB::table('businesses')
                ->select(DB::raw('count(businesses.business_id) as total'))
                ->join('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id')
                // ->where(DB::raw('businesses.status'), 1)
                ->whereIn(DB::raw('businesses.status'), [0, 1])
                ->where(DB::raw('users_businesses.user_type'), 1)
                ->where('businesses.city_id', $id)
                ->first();
            //dd(DB::getQueryLog());
            if (!empty($result)) {
                $total = $result->total;
            }
        }
        return $total;

    }

    /**
     * Get business usser folow country
     *
     * @param  int  $id
     * @return int
     */
    public function getBusinessWithCountry($id)
    {
        //DB::enableQueryLog();
        $result = DB::table('businesses')
            ->select('*')
            ->where('status', 1)
            ->where('country_id', $id)
            ->get();
        //dd(DB::getQueryLog());
        return $result;
    }

    /**
     * Count user folow country
     *
     * @param  int  $id
     * @return int
     */
    public function countUserWithCountry($id)
    {
        //DB::enableQueryLog();
        $total = 0;
        if (is_numeric($id)) {
            $result = DB::table('users')
                ->select(DB::raw('count(user_id) as total'))
                ->where('status', 1)
                ->where('country_id', $id)
                ->first();
            //dd(DB::getQueryLog());
            if (!empty($result)) {
                $total = $result->total;
            }
        }
        return $total;
    }

    /**
     * Count user folow country
     *
     * @param  int  $id
     * @return int
     */
    public function countUserWithCity($id)
    {
        //DB::enableQueryLog();
        $total = 0;
        if (is_numeric($id)) {
            $result = DB::table('users')
                ->select(DB::raw('count(user_id) as total'))
                ->where('status', 1)
                ->where('city_id', $id)
                ->first();
            //dd(DB::getQueryLog());
            if (!empty($result)) {
                $total = $result->total;
            }
        }
        return $total;

    }

    public function countBusinessAccWithCountry($id) {
        //DB::enableQueryLog();
        $total = 0;
        if (is_numeric($id)) {
            $result = DB::table('businesses')
                ->select(DB::raw('count(user_id) as total'))
                ->join('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id')
                // ->where(DB::raw('businesses.status'), 1)
                ->whereIn(DB::raw('businesses.status'), [0, 1])
                ->where(DB::raw('users_businesses.user_type'), 1)
                ->where('country_id', $id)
                ->first();
            //dd(DB::getQueryLog());
            if (!empty($result)) {
                $total = $result->total;
            }
        }
        return $total;
    }

    /**
     * Count usser of business
     *
     * @param  int  $id Business id
     * @param  int  $type
     * @return int
     */
    public function countUserOfBusinessID($id, $type)
    {
        $total = 0;
        $typeIn = [];
        if ($type == 3) {
            $typeIn = [1, 2, 3];
        } else if ($type == 2) {
            $typeIn = [1, $type];
        } else {
            $typeIn = [$type];
        }

        if (is_numeric($id)) {
            $result = DB::table('users')
                ->select(DB::raw('count(users.user_id) as total'))
                ->join('users_businesses', 'users.user_id', '=', 'users_businesses.business_id')
                ->join('businesses', 'users_businesses.business_id', '=', 'businesses.business_id')
                ->where('users.status', 1)
                ->where('businesses.business_id', $id)
                ->whereIn('users_businesses.user_type', $typeIn)
                ->where('users_businesses.status', 1)
                ->first();
            if (!empty($result)) {
                $total = $result->total;
            }
        }
        return $total;

    }

    public function getBusinessAccWithCountry($id) {
        //DB::enableQueryLog();
        $result = [];
        if (is_numeric($id)) {
            $result = DB::table('businesses')
                ->select('*')
                ->join('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id')
                // ->where(DB::raw('businesses.status'), 1)
                ->whereIn(DB::raw('businesses.status'), [0, 1])
                ->where(DB::raw('users_businesses.user_type'), 1)
                ->where('country_id', $id)
                ->get();
            //dd(DB::getQueryLog());
        }
        return $result;
    }

    public function getBusinessAccWithCity($id, $paging = false) {
        //DB::enableQueryLog();
        $result = [];
        if (is_numeric($id)) {
            $result = DB::table('businesses')
                ->select(['businesses.*','states.*','countries.*','a.join'])
                ->join('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id')
                ->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
                ->join('countries', 'businesses.country_id', '=', 'countries.country_id')
                ->leftJoin(DB::raw("(select 'true' as `join`,business_id from users_businesses where user_id=".Auth::user()->user_id.") as a"), 'businesses.business_id', '=', 'a.business_id')
                // ->where(DB::raw('businesses.status'), 1)
                ->whereIn(DB::raw('businesses.status'), [0, 1])
                ->where(DB::raw('users_businesses.user_type'), 1)
                ->where('city_id', $id);
                if ($paging) {
                    return $result->paginate(env('PAGING_MAX'));
                } else {
                    return $result->get();
                }
                // ->get();
            //dd(DB::getQueryLog());
        }
        return $result;
    }

    public function getListMessageById($id, $paging = false) {
        $result = [];
        //DB::enableQueryLog();
        if (is_numeric($id)) {
            $result = DB::table('businesses')
                ->select([
                    DB::raw('businesses.business_id'),
                    DB::raw('businesses.name'),
                    DB::raw('messages.detail'),
                    DB::raw('messages.image'),
                    DB::raw('user_receive_message.created_at'),
                    DB::raw('businesses.address'),
                    DB::raw('user_receive_message.message_id'),
                    DB::raw('user_receive_message.user_id'),
                    DB::raw('messages.title'),
                    DB::raw('businesses.city_name'),
                    DB::raw('states.state_code'),
                    DB::raw('countries.country_name'),
                    DB::raw('businesses.avatar'),
                    DB::raw('businesses.zipcode'),
					DB::raw('businesses.lat'),
					DB::raw('businesses.lon')
                ])
                ->join('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id')
                ->rightJoin('messages', 'users_businesses.business_id', '=', 'messages.business_id')
                ->rightJoin('user_receive_message', 'messages.message_id', '=', 'user_receive_message.message_id')
                ->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
                ->join('countries', 'businesses.country_id', '=', 'countries.country_id')
                ->where(DB::raw('users_businesses.user_type'), 1)
                ->where(DB::raw('user_receive_message.user_id'), $id)
                ->orderBy('user_receive_message.created_at', 'desc')
                ->limit(20);
                // var_dump($result->paginate(env('PAGING_MAX')));exit;
                if ($paging) {
                    return $result->paginate(5);
                } else {
                    return $result->get();
                }
            //dd(DB::getQueryLog());
        }
        return $result;
    }

    public function getListMessageByBusinessId($userId, $businessId) {
        $result = [];
        //DB::enableQueryLog();
        if (is_numeric($businessId)) {
            $result = DB::table('messages')
                ->select([
                    DB::raw('businesses.business_id'),
                    DB::raw('businesses.name'),
                    DB::raw('messages.detail'),
                    DB::raw('user_receive_message.created_at'),
                    DB::raw('businesses.address'),
                    DB::raw('user_receive_message.message_id'),
                    DB::raw('user_receive_message.user_id'),
                    DB::raw('messages.title'),
					DB::raw('businesses.lat'),
					DB::raw('businesses.lon'),
                ])
                ->join('businesses', 'messages.business_id', '=', 'businesses.business_id')
                ->join('user_receive_message', 'messages.message_id', '=', 'user_receive_message.message_id')
                ->where(DB::raw('businesses.business_id'), $businessId)
                ->where(DB::raw('user_receive_message.user_id'), $userId)
                ->get();


            //dd(DB::getQueryLog());
        }
        return $result;
    }

     public function getListBusinessMessage($businessId, $getAll, $paging = false) {
        $result = [];

        DB::enableQueryLog();
        if (is_numeric($businessId)) {


            // $sql = "

            // select *
            //     from
            //     ((SELECT
            //     businesses.business_id,
            //     businesses.name,
            //     messages.detail,
            //     businesses.address,
            //     messages.title,
            //     businesses.city_name,
            //     states.state_code,
            //     countries.country_name,
            //     users.firstname,
            //     users.lastname,
            //     users.avatar,
            //     users.user_id,
            //     businesses.zipcode,
            //     messages.message_id,
            //     user_receive_message.created_at
            //     from `messages`
            //     inner join `businesses` on `messages`.`business_id` = `businesses`.`business_id`
            //     inner join `user_receive_message` on `messages`.`message_id` = `user_receive_message`.`message_id`
            //     inner join users on users.user_id = messages.sender_id
            //     left join `states` on `businesses`.`state_id` = `states`.`state_id`
            //     inner join `countries` on `businesses`.`country_id` = `countries`.`country_id`
            //     WHERE
            //     businesses.business_id =  " .$businessId." AND
            //     user_receive_message.user_id =  '".Auth::user()->user_id."' limit 20)


            //     UNION


            //     (SELECT
            //     businesses.business_id,
            //     businesses.name,
            //     messages.detail,
            //     businesses.address,
            //     messages.title,
            //     businesses.city_name,
            //     states.state_code,
            //     countries.country_name,
            //     users.firstname,
            //     users.lastname,
            //     users.avatar,
            //     users.user_id,
            //     businesses.zipcode,
            //     messages.message_id,
            //     messages.created_at
            //     FROM
            //     messages
            //     Inner Join businesses ON messages.business_id = businesses.business_id
            //     Left Join states ON businesses.state_id = states.state_id
            //     Inner Join countries ON businesses.country_id = countries.country_id
            //     inner join users on users.user_id = messages.sender_id
            //     WHERE
            //     businesses.business_id =  " .$businessId.")) a
            //     ORDER BY
            //     created_at desc

            // ";
            // print_r($sql);exit;
            // $result = DB::select(DB::raw($sql));



                $query1 = DB::table('messages')->select([
                    'businesses.business_id',
                    'businesses.name',
                    'messages.detail',
                    'businesses.address',
                    'messages.title',
                    'businesses.city_name',
                    'states.state_code',
                    'countries.country_name',
                    'users.firstname',
                    'users.lastname',
                    'users.avatar',
                    'users.user_id',
                    'businesses.zipcode',
                    'messages.message_id',
                    'messages.image',
                    'messages.created_at',
					'businesses.lat',
					'businesses.lon'
                ])
                ->join('businesses', 'messages.business_id', '=', 'businesses.business_id')
                ->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
                ->join('countries', 'businesses.country_id', '=', 'countries.country_id')
                ->join('users', 'messages.sender_id', '=', 'users.user_id')
                ->where(DB::raw('businesses.business_id'), $businessId);

                $query2 = DB::table('messages')->select([
                'businesses.business_id',
                'businesses.name',
                'messages.detail',
                'businesses.address',
                'messages.title',
                'businesses.city_name',
                'states.state_code',
                'countries.country_name',
                'users.firstname',
                'users.lastname',
                'users.avatar',
                'users.user_id',
                'businesses.zipcode',
                'messages.message_id',
                'messages.image',
                'messages.created_at',
				'businesses.lat',
				'businesses.lon'
                ])
                ->join('businesses', 'messages.business_id', '=', 'businesses.business_id')
                ->join('user_receive_message', 'messages.message_id', '=', 'user_receive_message.message_id')
                ->join('users', 'messages.sender_id', '=', 'users.user_id')
                ->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
                ->join('countries', 'businesses.country_id', '=', 'countries.country_id')
                ->where(DB::raw('businesses.business_id'), $businessId)
                ->where(DB::raw('user_receive_message.user_id'), Auth::user()->user_id);
                if (!$getAll) {
                    $query1->where(DB::raw('messages.message_type'), 1);
                    $query2->where(DB::raw('messages.message_type'), 1);
                }
                // $query2->union($query1);
                $result = DB::table(DB::raw("({$query2->union($query1)->toSql()}) as a"))
                    ->mergeBindings($query2)
                    ->orderBy('created_at', 'desc') ;
                    // echo $businessId;
                    // echo Auth::user()->user_id;
                    // print_r(DB::raw("({$query2->union($query1)->toSql()}) as a"));exit;
                if ($paging) {
                    return $result->paginate(5);
                } else {
                    return $result->get();
                }
                // if (!$getAll) {
                //     $query->where(DB::raw('user_receive_message.user_id'), Auth::user()->user_id);
                // } else {
                //     $query->groupBy('user_receive_message.message_id');
                // }

                // if ($type > 0) {
                //     $query->where(DB::raw('messages.message_type'), $type);
                // }

                // print_r($query->toSql());exit;
                //$result = $query->get();
                // var_dump($businessId);exit;

            //dd(DB::getQueryLog());

        }
        return $result;
    }

    public function getCityList()
    {
        $data = DB::table('cities')->get()->toArray();
        return $data;
    }

    public function getCityById($id)
    {
        $data = DB::table('cities')->where('city_id', $id)->first();
        return $data;
    }

    public function getCountryList()
    {
        $data = DB::table('countries')->get()->toArray();
        return $data;
    }

    public function getCountryById($id)
    {
        $data = DB::table('countries')->where('country_id', $id)->first();
        return $data;
    }

    public function getStateById($id)
    {
        $data = DB::table('states')->where('state_id', $id)->first();
        return $data;
    }

    public function convertUrlUser ($url) {
        $dataUrl = explode('-', base64_decode($url));
        if (count($dataUrl) == 2 && is_numeric($dataUrl[0])) {
            return $dataUrl;
        } else {
            abort(404);
        }
    }

    public function convertId ($url) {
        $dataUrl = explode('-', base64_decode($url));
        if (count($dataUrl) == 2 && is_numeric($dataUrl[0])) {
            return $dataUrl;
        } else {
            return [];
        }
    }

    public function convertUrlMsg ($url) {
        $dataUrl = explode('-', base64_decode($url));
        if (count($dataUrl) == 3 && is_numeric($dataUrl[0])) {
            return $dataUrl;
        } else {
            return [];
        }
    }

    public function getBusiness($id) {

        $result = [];
        //DB::enableQueryLog();
        if (is_numeric($id)) {
            $result = DB::table('businesses')
                ->select([
                    'businesses.name',
                    'businesses.business_id',
                    'businesses.lat',
                    'businesses.lon'
                ])
                ->join('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id')
                ->where(DB::raw('users_businesses.user_type'), 1)
                ->where(DB::raw('users_businesses.user_id'), $id)
                ->first();
            //dd(DB::getQueryLog());
        }
        return $result;
    }

    public function checkPermissWithBusiness($userId, $businessId) {
        $result = [];
        //DB::enableQueryLog();
        if (is_numeric($userId)) {
            $result = DB::table('users')
                ->select([
                    'users_businesses.user_type'
                ])
                ->join('users_businesses', 'users.user_id', '=', 'users_businesses.user_id')
                ->where(DB::raw('users.user_id'), $userId)
                ->where(DB::raw('users_businesses.business_id'), $businessId)
                ->first();
            //dd(DB::getQueryLog());
        }
        return $result;
    }

    public function checkStatusWithBusiness($userId, $businessId) {
        $result = [];
        //DB::enableQueryLog();
        if (is_numeric($userId)) {
            $result = DB::table('users')
                ->select([
                    'users_businesses.status'
                ])
                ->join('users_businesses', 'users.user_id', '=', 'users_businesses.user_id')
                ->where(DB::raw('users.user_id'), $userId)
                ->where(DB::raw('users_businesses.business_id'), $businessId)
                ->first();
            //dd(DB::getQueryLog());
        }
        return $result;
    }

    public function getBusinessById($businessId) {
        $result = [];
        //DB::enableQueryLog();
        if (is_numeric($businessId)) {
            $result = DB::table('businesses')
                ->select('*')
                ->leftJoin('states', 'businesses.state_id', '=', 'states.state_id')
                ->join('countries', 'businesses.country_id', '=', 'countries.country_id')
                ->whereIn(DB::raw('businesses.status'), [0, 1])
                ->where(DB::raw('businesses.business_id'), $businessId)
                ->first();
            //dd(DB::getQueryLog());
        }
        return $result;
    }

    public function getTypeUserBuBusiness($businessId) {
        $result = [];
        //DB::enableQueryLog();
        if (is_numeric($businessId)) {
            $result = DB::table('users_businesses')
                ->select('*')
                ->where(DB::raw('users_businesses.business_id'), $businessId)
                ->first();
            //dd(DB::getQueryLog());
        }
        return $result;
    }

    public function getListReplay($messageId, $type) {
        $result = [];
        $idUser = Auth::user()['user_id'];
        //DB::enableQueryLog();
        if (is_numeric($messageId)) {
            $sqlObj = DB::table('reply_messages')
                ->select([
                    'reply_messages.title',
                    'reply_messages.detail',
                    'reply_messages.image',
                    'reply_messages.created_at',
                    'users.firstname',
                    'users.lastname',
                    'users.avatar',
                ])
                ->where(DB::raw('reply_messages.message_id'), $messageId)
                ->leftJoin('messages', 'messages.message_id', '=', 'reply_messages.message_id')
                ->join('users', 'users.user_id', '=', 'reply_messages.sender_id');


            if ($type == 0) {
                $sqlObj->where('reply_messages.sender_id', $idUser);
            }

            $result = $sqlObj->get();
            //dd(DB::getQueryLog());
        }

        return $result;
    }

    public function checkPermissUserWithBusiness($userId, $businessId) {
        $result = [];
        //DB::enableQueryLog();
        if ($userId != '' && $businessId != '') {
            $result = DB::table('businesses')
                ->select('*')
                ->where('businesses.business_id', $businessId)
                ->where('users_businesses.user_id', $userId)
                ->where('users_businesses.user_type', 1)
                ->join('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id')
                ->get();
            //dd(DB::getQueryLog());
        }
        if (count($result) == 1) {
            return true;
        } else {
            return false;
        }
        return $result;
    }

    public function getUserBroadcast($mBusiness)
    {
        //DB::enableQueryLog();
        // get all user's business deny
        $mUserDeny = DB::table(DB::raw('users as u1'))
                        ->select('u1.user_id')
                        ->join('user_business_deny as ubd', function ($join) {
                            $join->on('u1.user_id', '=', 'ubd.users_user_id');
                        })
                        ->where('ubd.business_id', '=', $mBusiness->business_id)
                        ->get();
        $idsDeny = [];
        foreach ($mUserDeny as $k => $v) {
            $idsDeny[] = $v->user_id;
        }

        $select = 'u.*, us.*';
        $objData = DB::table(DB::raw('users as u1'))
            ->select(DB::raw($select))
            ->from(DB::raw("users as u"))
            ->leftJoin('user_settings as us', function ($join) {
                $join->on('us.user_id', '=', 'u.user_id');
            })
            ->leftJoin('users_businesses', 'u.user_id', '=', 'users_businesses.user_id')
            ->where('users_businesses.business_id', '=', $mBusiness->business_id)
            ->where('users_businesses.status', '=', 1)
            ->where('u.status', '=', 1)
            ->whereIn('users_businesses.user_type', [1,2,3,4]);
        $objData->where('u.user_id', '!=', Auth::user()->user_id);
        if (!empty($idsDeny)) {
            $objData->whereNotIn('u.user_id', $idsDeny);

        }

        // get user
        $result = $objData->get();

        //dd(DB::getQueryLog());
        return $result;
    }


    public function getUserBroadcastFree($mBusiness)
    {
        DB::enableQueryLog();
        // get all user's business deny
        $mUserDeny = DB::table(DB::raw('users as u1'))
                        ->select('u1.user_id')
                        ->join('user_business_deny as ubd', function ($join) {
                            $join->on('u1.user_id', '=', 'ubd.users_user_id');
                        })
                        ->where('ubd.business_id', '=', $mBusiness->business_id)
                        ->get();
        $idsDeny = [];
        foreach ($mUserDeny as $k => $v) {
            $idsDeny[] = $v->user_id;
        }

        $select = 'IFNULL(bus.user_type, 0) as type, u.*, us.radius, (0.621371192 * 6371 * acos (
                  cos ( radians(' . $mBusiness->lat . ') )
                  * cos( radians( u.lat ) )
                  * cos( radians( u.lon ) - radians(' . $mBusiness->lon . ') )
                  + sin ( radians(' . $mBusiness->lat . ') )
                  * sin( radians( u.lat ) )
                )
              ) AS distance, us.*';
        $objData = DB::table(DB::raw('users as u1'))
            ->select(DB::raw($select))
            ->from(DB::raw("users as u"))
            ->leftJoin('user_settings as us', function ($join) {
                $join->on('us.user_id', '=', 'u.user_id');
            })
            //->leftJoin('users_businesses', 'u.user_id', '=', 'users_businesses.user_id')

            ->leftJoin('users_businesses as bus', function ($join) use($mBusiness) {
                $join->on('bus.user_id', '=', 'u.user_id');
                $join->on('bus.business_id', '=', DB::raw($mBusiness->business_id));
            })

            // ->havingRaw('distance <= us.radius')
            ->having('distance', '<=', $mBusiness->radius)
            ->where('u.status', '=', 1);
            //->where('bus.status', '=', 1);
        $objData->where('u.user_id', '!=', Auth::user()->user_id);
        if (!empty($idsDeny)) {
            $objData->whereNotIn('u.user_id', $idsDeny);

        }

        // get user 5 km
        $userIn5Km = $objData->get();
        $listUser = [];
        if (!empty($userIn5Km)) {
            foreach($userIn5Km as $item) {
                if ($item->type == 0) {
                    $listUser[] = $item;
                }
            }
        }

        //$objData->get();
        //dd(DB::getQueryLog());
        return $listUser;
    }
    
    public function getUserPrivate($mBusiness) {
        //DB::enableQueryLog();
        // get all user's business deny
        $mUserDeny = DB::table(DB::raw('users as u1'))
                        ->select('u1.user_id')
                        ->join('user_business_deny as ubd', function ($join) {
                            $join->on('u1.user_id', '=', 'ubd.users_user_id');
                        })
                        ->where('ubd.business_id', '=', $mBusiness->business_id)
                        //->where('ubd.status', '=', 1)
                        ->get();
        $idsDeny = [];
        foreach ($mUserDeny as $k => $v) {
            $idsDeny[] = $v->user_id;
        }

        $select = 'u.*, us.*';
        $objData = DB::table(DB::raw('users as u1'))
            ->select(DB::raw($select))
            ->from(DB::raw("users as u"))
            ->leftJoin('user_settings as us', function ($join) {
                $join->on('us.user_id', '=', 'u.user_id');
            })
            ->join('users_businesses', 'users_businesses.user_id', '=', 'u.user_id')
            ->whereIn('users_businesses.user_type', [1,2]);
        $objData->where('u.user_id', '!=', Auth::user()->user_id);
        $objData->where('u.status', '=', 1);
        $objData->where('users_businesses.status', '=', 1);
        if (!empty($idsDeny)) {
            $objData->whereNotIn('u.user_id', $idsDeny);
        }
        $objData->groupBy('u.user_id');

        //$objData->get();
        //dd(DB::getQueryLog());
        return $objData->get();
    }

    public function getUserPublic($mBusiness)
    {
        //DB::enableQueryLog();
        // get all user's business deny
        $mUserDeny = DB::table(DB::raw('users as u1'))
                        ->select('u1.user_id')
                        ->join('user_business_deny as ubd', function ($join) {
                            $join->on('u1.user_id', '=', 'ubd.users_user_id');
                        })
                        ->where('ubd.business_id', '=', $mBusiness->business_id)
                        //->where('ubd.status', '=', 1)
                        ->get();
        $idsDeny = [];
        foreach ($mUserDeny as $k => $v) {
            $idsDeny[] = $v->user_id;
        }

        $select = 'u.*, us.*';
        $objData = DB::table(DB::raw('users as u1'))
            ->select(DB::raw($select))
            ->from(DB::raw("users as u"))
            ->leftJoin('user_settings as us', function ($join) {
                $join->on('us.user_id', '=', 'u.user_id');
            })
            ->join('users_businesses', 'users_businesses.user_id', '=', 'u.user_id')
            ->whereIn('users_businesses.user_type', [2,3]);
        $objData->where('u.user_id', '!=', Auth::user()->user_id);
        $objData->where('u.status', '=', 1);
        $objData->where('users_businesses.status', '=', 1);
        if (!empty($idsDeny)) {
            $objData->whereNotIn('u.user_id', $idsDeny);
        }
        $objData->groupBy('u.user_id');

        //$objData->get();
        //dd(DB::getQueryLog());
        return $objData->get();
    }

    public function getMessageDetail($id) {
        $message = DB::table('messages')
            ->select('*')
            ->join('user_receive_message', 'messages.message_id', '=', 'user_receive_message.message_id')
            ->where(DB::raw('messages.message_id'), $id)
            ->first();
        return $message;
    }

    public function getUserById($userId) {
        $result = [];
        //DB::enableQueryLog();
        if (is_numeric($userId)) {
            $result = DB::table('users')
                ->select([
                    'users.*',
                    'user_settings.fcm_id',
                    'user_settings.fcm_id_web',
                    'user_settings.fcm_id_ios'
                ])
                ->join('user_settings', 'users.user_id', '=', 'user_settings.user_id')
                ->where(DB::raw('users.user_id'), $userId)
                ->where(DB::raw('users.status'), 1)
                ->first();
            //dd(DB::getQueryLog());
        }
        return $result;
    }

    public function getNotification($userId, $paging = false) {
        $result = [];
        //DB::enableQueryLog();
        if (is_numeric($userId)) {
            $result = DB::table('businesses')
                ->select('*')
                ->join('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id')
                // ->where(DB::raw('businesses.status'), 1)
                ->whereIn(DB::raw('businesses.status'), [0, 1])
                ->where(DB::raw('users_businesses.status'), 2)
                ->where('users_businesses.user_id', $userId);
                if ($paging) {
                    return $result->paginate(env('PAGING_MAX'));
                } else {
                    return $result->get();
                }
            //dd(DB::getQueryLog());
        }
        return $result;
    }

    public function getCountNotification($userId) {
        $result = [];
        //DB::enableQueryLog();

        $result = DB::table('businesses')
            ->select('*')
            ->join('users_businesses', 'businesses.business_id', '=', 'users_businesses.business_id')
            // ->where(DB::raw('businesses.status'), 1)
            ->whereIn(DB::raw('businesses.status'), [0, 1])
            ->where(DB::raw('users_businesses.status'), 2)
            ->where('users_businesses.user_id', $userId)->get();
        //dd(DB::getQueryLog());

        return count($result);
    }

    public function noImage($text, $print = true) {
        $chart = '';
        if ($text == '') {
            $chart = 'A';
        } else {
            $chart = strtoupper($text[0]);
        }

        // color
        $color = '';
        if (ord($chart) >= 65 && ord($chart) <= 75) {
            $color = 'color-number-1';
        } else if (ord($chart) > 75) {
            $color = 'color-number-2';
        } else {
            $color = 'color-number-3';
        }
        if ($print) {
            echo "<span class='no-image $color'>$chart</span>";
        } else {
            return "<span class='no-image $color'>$chart</span>";
        }
        
    }

    public function getTimeZoneOffset() {
        $offset = 'UTC';
        if(isset($_COOKIE['time_zone_offset'])) {
            $offset = $_COOKIE['time_zone_offset'];
        }
        return $offset;
    }

    public function printDate($date) {
        $helpDb = new HelperDB();
        $dt = new \DateTime($date, new \DateTimeZone('UTC'));
        $dt->setTimezone(new \DateTimeZone($helpDb->getTimeZoneOffset()));
        return date_format($dt, 'Y-m-d H:i:s');
    }
    
    function cutText($text, $n=230) 
    { 
        // string is shorter than n, return as is
        if (strlen($text) <= $n) {
            return $text;
        }
        $text= substr($text, 0, $n);
        if ($text[$n-1] == ' ') {
            return trim($text)."...";
        }
        $x  = explode(" ", $text);
        $sz = sizeof($x);
        if ($sz <= 1)   {
            return $text."...";}
        $x[$sz-1] = '';
        return trim(implode(" ", $x))."...";
    }
}
