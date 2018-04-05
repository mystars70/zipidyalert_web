@extends('user.layouts.detailMessage')
<?php
use App\HelperDB;
$helpDb = new HelperDB();
?>
@section('content')

<?php
$messageContent = '';
$messageDate = '';
$businessName = '';
$businessAddress = '';
$imageMessage = '';
$imageAvatar = '';
if (!empty($mesage)) {
    $mesageContent = $mesage->detail;
    $messageDate =date(config('settings.date_format'), strtotime( $helpDb->printDate($mesage->created_at) ));
    $businessName = $mesage->name;
    $businessAddress = $mesage->city_name.', '.(($mesage->state_code)? $mesage->state_code: '').' '.$mesage->zipcode;
    if ($mesage->image != '') {
        $imageMessage = $mesage->image;
    }
    if ($mesage->avatar != '') {
        $imageAvatar = $mesage->avatar;
    }
    ?>
    @section('script')
        var messageId = '{{$messageId}}';
    @endsection
    <?php
}
?>



<div id='detail_message_wrap'>
    <div class="popup-content">
        <div class="rows detail-top-header">
            <div class="col-lg-6">
                <div class="user-wrap">
                <?php if ($mesage->avatar && file_exists(env('DIR_UPLOAD_BUSINESS').$mesage->avatar)) :?>
                    <img src="<?php echo url(env('DIR_UPLOAD_BUSINESS').$mesage->avatar)?>">
                <?php else :?>
                    <?php $helpDb->noImage($businessName); ?> 
                <?php endif;?>
                    
                    <div class="user-info">
                        <span><?php echo $businessName; ?></span>
                        <span class="date"><?php echo $messageDate; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="address-info">                    
                </div>
            </div>
        </div>
        <div id="wrapScroll">
            <div class="content-message">
                
                <div class="message">
                    <?php echo $mesageContent; ?>
                </div>
                <?php if ($mesage->image && file_exists(env('DIR_UPLOAD_MESSAGE').$mesage->image)) :?>
                    <p class="image">
                        <img src="<?php echo url(env('DIR_UPLOAD_MESSAGE').$mesage->image)?>">
                    </p>
                <?php endif;?>
            </div>
            <div class="replay-list">
                <ul>
            <?php if (!empty($replyMessage)): ?>
            
                    <?php
                        foreach ($replyMessage as $item) {
                            ?>
                            <li>
                                <div class="avatar">
                                    <?php if ($item->avatar && file_exists(env('DIR_UPLOAD_USER').$item->avatar)) :?>
                                        <img src="<?php echo url(env('DIR_UPLOAD_USER').$item->avatar)?>">
                                    <?php else :?>
                                        <?php $helpDb->noImage($item->firstname); ?> 
                                    <?php endif;?>
                                </div>
                                <div class="replay-detail">
                                
                                    <div class="block-replay-1">
                                        <span class="user-name"><?php echo $item->firstname.' '.$item->lastname; ?></span>
                                        <span class="date"><?php echo date(config('settings.date_format'), strtotime( $helpDb->printDate($item->created_at) ));  ?> </span>
                                    </div>
                                    <?php
                                    $html = '';
                                    if ($item->image != '') {
                                        $html = '<img src="' . url(env('DIR_UPLOAD_MESSAGE')) . '/' . $item->image . '" \>';
                                    }
                                    ?>
                                    <div class="image-replay-view">
                                        <?php echo  $html; ?>
                                    </div>
                                    <div class="replay-detail-text">
                                        <?php echo $item->detail;  ?>
                                    </div>
                                </div>
                            </li>
                            <?php
                        }
                    ?>
               
            <?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="image-replay-wrap">
            <div class="replay-img">
                <span class="remove-image hide" aria-hidden="true">&times;</span>
            </div>
        </div>
        
        <div class="replay-messgess-send">
            <img class="replay-select-img" src="<?php echo url('public/user/'); ?>/images/icon-select-img.png" />
            <img class="replay-send-message-icon" src="<?php echo url('public/user/'); ?>/images/icon-send-message.png" />
            {{ Form::open(['url' => 'user/ajax-replay-message', 'method' => 'post', 'id' => 'replayMesssage', 'enctype' => 'multipart/form-data']) }}
                <input type="text" placeholder="Reply here" class="message-replay-text" name="message"/>
                <input class="hide" accept=".jpeg,.png,.jpg,.gif" type="file" name="image" id="image-replay-file">
            </form>
        </div>
    </div>
</div>










@endsection
@section('scriptRun')
<script type="text/javascript">
    var nice = false;
    $(document).ready(function() {
        $('#wrapScroll').height($(parent.document).find('#cboxLoadedContent iframe').height() - 80);
        nice = $('#wrapScroll').niceScroll();
        console.log("$('#wrapScroll').height()", $(parent.document).find('#cboxLoadedContent iframe').height());
        
        setTimeout(function(){
            console.log(1111111, $(parent.document));
            $(parent.document).find('#cboxLoadedContent iframe').attr('scrolling', 'no');
            //$('#detail_message_wrap').niceScroll();
            console.log('niceScroll');
            //$(parent.document).find('#cboxLoadedContent iframe').niceScroll();
            var heightPopup = $('#detail_message_wrap').height();
            console.log(heightPopup + 50, 'op');
            //window.parent.clickPopup.colorbox.resize({height: heightPopup + 70})
            
            
            setTimeout(function(){
                //$(parent.document).find('#cboxLoadedContent iframe').attr('scrolling', 'no');
            }, 1000);
            //$(parent.document).find('.replayMessageWrap #cboxLoadedContent').niceScroll();
            //$('#detail_message_wrap').niceScroll();
            console.log($('#detail_message_wrap').height());
        }, 10000)
        
    })
</script>
    
@endsection
