<?php
use Illuminate\Pagination\LengthAwarePaginator;
use App\HelperDB;
$helpDb = new HelperDB();
$rootPage = url('user') . '/business/page/';
?>
@section('content')
        <?php if (!empty($messageList) && count($messageList) > 0) :?>
            <?php foreach ($messageList as $item) :?>

              <div class="item distance-api" data-lat="<?php echo $item->lat ?>" data-lon="<?php echo $item->lon; ?>">
                  <div class="media">
                    <a class="pull-left iframe" href="<?php echo url('user') . '/message-user-detail/' . base64_encode($item->message_id . '-' . $item->business_id. '-' . $item->user_id); ?>">
                    <?php if ($item->avatar && file_exists(env('DIR_UPLOAD_USER').$item->avatar)) :?>
                        <img class="img-rounded img-circle" src="<?php echo url(env('DIR_UPLOAD_USER').$item->avatar)?>">
                    <?php else :?>
                        <?php $helpDb->noImage($item->firstname); ?>   
                    <?php endif;?>
                  </a>
                  <div class="media-body">
                      <div class="text-left">
                            <a class="iframe" href="<?php echo url('user') . '/message-user-detail/' . base64_encode($item->message_id . '-' . $item->business_id. '-' . $item->user_id); ?>">
                                <span class="media-heading">{{ $item->firstname.' '.$item->lastname}}</span>
                            </a><br/>
                            <span class="date">{{ date(config('settings.date_format'), strtotime( $helpDb->printDate($item->created_at) ))}}</span>  
                        </div>
                        <div class="text-right">
                            <ul class="list-inline list-unstyled">
                              <li><span>{{ $item->city_name.', '.(($item->state_code)? $item->state_code: '').' '.$item->zipcode}}</span>
                                    <br /><span class="count-miles">&nbsp;</span>  
                                </li>
                            </ul>
                        </div>
                   </div>
                   <div class="desc">{{ $helpDb->cutText($item->detail) }}
                   <?php
                    if ($item->image != '') {
                        ?>
                        <p class="image-mesage-list"><img src="<?php echo url('public/upload/message/') . '/' . $item->image; ?>" /></p>
                        <?php
                        
                    }
                    ?>
                   </div>
                </div>
              </div>
                <?php endforeach;?>
                <?php 
                 $paging = 1;
                 if (isset($_GET['page'])) {
                    $paging = $_GET['page'];
                 }
                ?>
                <a class="jscroll-next" href="<?php echo $rootPage . $idBusiness . '?page=' . ++$paging ?>"></a>
        <?php endif;?>
@endsection
