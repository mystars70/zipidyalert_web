<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\HelperDB;
$helpDb = new HelperDB();
?>
@extends('user.layouts.profile')
@section('content')

<div class="col-lg-8 list-data-wrap">
    <h2 class="title-block">Businesses</h2>
    <div class="list-data">
        <?php if (!empty($listBusiness) && count($listBusiness) > 0) :?>
            <?php foreach ($listBusiness as $item) :?>
                <div class="item distance-api" data-lat="<?php echo $item->lat ?>" data-lon="<?php echo $item->lon; ?>">
                      <div class="media">
                        <a class="pull-left" href="{{url('user') . '/business/page/' . generateId(($item->business_id + 100)  . '-' . $item->business_id)}}">
                            <?php if ($item->avatar && file_exists(env('DIR_UPLOAD_BUSINESS').$item->avatar)) :?>
                                <img class="img-rounded img-circle" src="<?php echo url(env('DIR_UPLOAD_BUSINESS').$item->avatar)?>">
                            <?php else :?>
                                <?php $helpDb->noImage($item->name); ?> 
                            <?php endif;?>
                        </a>
                        <div class="media-body">
                            <div class="text-left">
                                <h4 class="media-heading"><a href="{{url('user') . '/business/page/' . generateId(($item->business_id + 100)  . '-' . $item->business_id)}}">{{$item->name}}</a></h4>
                                <span class="date"></span>  
                                <div class="counter-business">
                                    <span class="u1-business"><i class="glyphicon glyphicon-direct-users-active glyphicon-p"></i>{{$item->total_direct}}</span>
                                    <span class="u2-business"><i class="glyphicon glyphicon-usert glyphicon-p"></i>{{$item->total_indirect}}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <ul class="list-inline list-unstyled">
                                    <li><span>{{ $item->city_name.', '.(($item->state_code)? $item->state_code: '').' '.$item->zipcode}}</span>
                                    </li>
                                </ul>
                            </div>
                       </div>
                    </div>
                </div>
                <?php endforeach;?>
              <div class="paging_content">
                <?php echo $listBusiness->links() ;?>
              </div>
        <?php else:?>
            <div class="item">
              <div class="media-body">
                  <div class="text-center">
                        <p>Be safe where you LIVE WORK &amp; PLAY!</p>
                        <p>Alert messages from registered businesses in your community will be displayed here. </p>
                  </div>
              </div>
          </div>
        <?php endif;?>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
  $(document).ready(function(){
    <?php if (isset($ownerBusiness) && $ownerBusiness):?>
      addressMap('<?php echo $addressBusiness; ?>');
    <?php else:?>
      addressMap('<?php echo $address; ?>');
    <?php endif;?>
  });
</script>
@endsection