<?php
use Illuminate\Pagination\LengthAwarePaginator;
use App\HelperDB;
$helpDb = new HelperDB();
?>
@extends('user.layouts.profile')
@section('content')
<div class="col-lg-8 list-data-wrap">
    <h2 class="title-block">Notifications</h2>
    <div class="list-data">
        <?php  if (!empty($listNotification)) :?>
            <?php foreach ($listNotification as $item) :?>
                <div class="item item-{{$item->business_id}}">
                      <div class="media">
                        <a class="pull-left" href="javascript:void(0)">
                            <?php if ($item->avatar && file_exists(env('DIR_UPLOAD_BUSINESS').$item->avatar)) :?>
                                <img class="img-rounded img-circle" src="<?php echo url(env('DIR_UPLOAD_BUSINESS').$item->avatar)?>">
                            <?php else :?>
                                <?php $helpDb->noImage($item->name); ?>  
                            <?php endif;?>
                        </a>
                        <div class="media-body">
                            <div class="text-left">
                                <h4 class="media-heading"><a href="javascript:void(0)">{{$item->name}}</a></h4>
                                <?php 
                                    $type = '';
                                    switch($item->user_type) {
                                        case 2:
                                            $type = 'Manager';
                                            break;
                                        case 3:
                                            $type = 'Direct User';
                                            break;
                                        case 4:
                                            $type = 'Indirect User';
                                            break;
                                        }?>
                                        <span>Send you an invitation to be a {{$type}}</span>
                            </div>
                            <div class="text-right">
                            <div class="btn-group-notification">
                                <div onclick="notification({{$item->business_id}},0)"><i class="glyphicon glyphicon-uncheck glyphicon-p"></i>Deny</div>
                                <div onclick="notification({{$item->business_id}},1)"><i class="glyphicon glyphicon-checked glyphicon-p"></i>Accept</div>
                            </div>
                                
                            </div>
                       </div>
                    </div>
                </div>
                <?php endforeach;?>
              <div class="paging_content">
                <?php echo $listNotification->links() ;?>
              </div>
            <?php endif;?>
    </div>
</div>
@endsection