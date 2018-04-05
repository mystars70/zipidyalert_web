<?php
use Illuminate\Pagination\LengthAwarePaginator;
use App\HelperDB;
$helpDb = new HelperDB();
$rootPage = url('user');
?>
@section('content')
        <?php if (!empty($listMessage) && count($listMessage) > 0) :?>
            <?php foreach ($listMessage as $item) :?>
                <?php
                $linkDetail = '#';
                if (Session::get('userType') != 3) {
                    $linkDetail = url('user') . '/message-detail/' . base64_encode($item->message_id . '-' . $item->business_id);
                }
                ?>
                <div class="item distance-api" data-lat="<?php echo $item->lat ?>" data-lon="<?php echo $item->lon; ?>">
                  <div class="media">
                  	<a class="pull-left iframe" href="<?php echo $linkDetail; ?>">
                    <?php if ($item->avatar && file_exists(env('DIR_UPLOAD_BUSINESS').$item->avatar)) :?>
                        <img class="img-rounded img-circle" src="<?php echo url(env('DIR_UPLOAD_BUSINESS').$item->avatar)?>">
                    <?php else :?>
                        <?php $helpDb->noImage($item->name); ?> 
                    <?php endif;?>
              		</a>
              		<div class="media-body">
              		    <div class="text-left">
                            <a class="iframe" href="<?php echo $linkDetail; ?>">
                                <span class="media-heading">{{ $item->name}}</span>
                            </a><br/>
                            <span class="date">{{ date(config('settings.date_format'), strtotime( $helpDb->printDate($item->created_at) ))}}</span>  
                        </div>
                        <div class="text-right">
                            <ul class="list-inline list-unstyled">
                            	<li><span>{{ $item->city_name.', '.(($item->state_code)? $item->state_code: '').' '.$item->zipcode}}</span>
                                    <br /><span class="count-miles"></span>  
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
                     $page = 1;
                     if (isset($_GET['page'])) {
                        $page = $_GET['page'];
                     }
                    ?>
                    <a class="jscroll-next" href="<?php echo $rootPage . '/home?page=' . ++$page ?>"></a>
              <?php endif?>
@endsection
