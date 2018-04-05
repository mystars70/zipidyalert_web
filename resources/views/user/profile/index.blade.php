<?php
use Illuminate\Pagination\LengthAwarePaginator;
use App\HelperDB;
$helpDb = new HelperDB();
$rootPage = url('user');
?>
@extends('user.layouts.profile')
@section('content')
<div class="col-lg-8 list-data-wrap">
    <div class="title-block title-block-wrap title-block-message">
        <h2 class="title-block">Messages</h2>
    </div>
    <div class="list-data autoscroll">
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
              <?php else:?>
              <div class="item">
                <div class="media-body">
                    <div class="text-center">
                          <p>Be safe where you LIVE WORK &amp; PLAY!</p>
                          <p>Alert messages from registered businesses in your community will be displayed here. </p>
                    </div>
                </div>
              </div>
              <?php endif?>
    </div>
</div>


<a class='popupSendMessage' href="#send_message_content" style="display: none;">1111111111111</a>
<div style="display: none;">
    <div id='send_message_content'>
        <div class="map-wrap">
            
            {{ Form::open(['url' => 'user/ajax-create-message', 'method' => 'post', 'id' => 'createMesssage', 'enctype' => 'multipart/form-data']) }}
            <div class="popup-content">
                <h2 class="title-popup">Create Message</h2>
                <div class="business-send-info-wrap">
                <?php if (Auth::user()['avatar'] && file_exists(env('DIR_UPLOAD_USER').Auth::user()['avatar'])) :?>
                    <img class="img-rounded img-circle" src="<?php echo url(env('DIR_UPLOAD_USER').Auth::user()['avatar'])?>">
                    <span class="business"><?php echo Auth::user()['firstname'].' '.Auth::user()['lastname']; ?></span>
                <?php else :?>
                    <?php $helpDb->noImage(Auth::user()['firstname']); ?>         
                    <span class="business no-image-text"><?php echo Auth::user()['firstname'].' '.Auth::user()['lastname']; ?></span>           
                <?php endif;?>
                    
                    
                </div>
                <div class="message-send-input-wrap">
                    <input id="messge-content-send" type="text" name="message" placeholder="What’s news you want to share?" />
                </div>
                <div class="image-image-create">
                    <span class="remove-image hide" aria-hidden="true">&times;</span>
                </div>
                <div class="middle-fix"></div>
                <div class="footer-add-user">
                    <div class="group-upload">
                        <img src="{!!url('public/user/')!!}/images/icon-select-img.png" />
                        <span>Upload photo</span>
                    </div>
                    <input class="hide" accept=".jpeg,.png,.jpg,.gif" type="file" name="image" id="image-create">
                    <div class="group-button">
                        <input data-type=1 type="button" value="Broadcast to all users" id="send-broadcast" class="btn-white send-message-btn" />
                        <input data-type=2 type="button" value="Publish to direct users" id="send-public" class="btn-blue send-message-btn" />
                    </div>
                </div>
            </div>
            </form>
         </div>
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript">
  $(document).ready(function(){
      addressMap('<?php echo $address; ?>');
    
    <?php if (Session::get('userType') == 3):?>
      $('.iframe').click(function(){
        return false;
      })
    <?php endif;?>
  });
</script>
@endsection
