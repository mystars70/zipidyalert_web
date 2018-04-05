@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Business Manager > Business's Messages > Message Detail</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="row businesses-detail">
                <div class="col-md-6">
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Email:
                    </div>
                    <div class="col-md-8">
                      <?php echo $detailMessage->email; ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Name:
                    </div>
                    <div class="col-md-8">
                      <?php echo $detailMessage->firstname.' '.$detailMessage->lastname; ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Created On:
                    </div>
                    <div class="col-md-8">
                      <?php echo date('h:i m-d-Y', strtotime($detailMessage->created_at)); ?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Message Type:
                    </div>
                    <div class="col-md-8">
                      <?php if ($detailMessage->message_type == 2) :?>
                        Public
                      <?php elseif ($detailMessage->message_type == 1) :?>
                        Broadcast
                      <?php endif;?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Total Received:
                    </div>
                    <div class="col-md-8">
                      <?php echo $detailMessage->receive;?>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4 detail-label">
                      Total Replied:
                    </div>
                    <div class="col-md-8">
                      <?php echo $detailMessage->reply;?>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class='avatar-box'>
                  <?php if ($detailMessage->avatar && file_exists(env('DIR_UPLOAD_USER').$detailMessage->avatar)) :?>
                    <img src="<?php echo url(env('DIR_UPLOAD_USER').$detailMessage->avatar)?>" />
                  <?php else:?>
                    <img src="{!!url('public/admin/')!!}/images/user-64.png" />
                  <?php endif;?>
                  </div>
                </div>

          </div>
        </div>

          <div class="x_content">
            <p class="font-13 m-b-30 text-center location-tabs">
              <a class="message-tab-reply active" href="javascript:void(0)">Replied</a>&nbsp;/
              <a class="message-tab-receive" href="javascript:void(0)">Received</a>
            </p>
            <ul id="myTab" class="nav nav-tabs bar_tabs hidden" role="tablist">
              <li role="presentation" class="active"><a href="#tab_content0" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" class="tab-reply">Replied</a>
              </li>
              <li role="presentation" class=""><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" class="tab-receive">Received</a>
              </li>
            </ul>
            <div id="myTabContent" class="tab-content">
              <div role="tabpanel" class="tab-pane fade active in" id="tab_content0" aria-labelledby="home-tab">
                <table id="replyList" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                        <th>Reply</th>
                        <!-- <th>Created</th> -->
                    </tr>
                  </thead>
                </table>
              </div>
              <div role="tabpanel" class="tab-pane fade" id="tab_content1" aria-labelledby="home-tab">
                <table id="receiveList" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                        <th>No</th>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Created</th>
                        <th>Status</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
    
      
    
    
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{!!url('public/admin/')!!}/js/businesses.js"></script>
<script type="text/javascript">
    var business_id = {!!$business_id!!};
    var message_id = {!!$message_id!!};
    var user_id = {!!$detailMessage->user_id!!};
    $(document).ready(function() {
      getListUserReceive();
      getListUserReply();
    });
</script>
@endsection
@section('nav-business')
<ul class="nav nav-business">
  <li><a href="{{url('/admin/businesses/detail').'/'.$business_id}}">Detail</a></li>
  <li class="disable"><a href="javascript:void(0)">Billing</a></li>
  <li><a href="{!!url('/admin/businesses/user-business').'/'.$business_id!!}">User</a></li>
  <li class="current-page"><a href="{!!url('/admin/businesses/messages').'/'.$business_id!!}">Business's Messages</a></li>
</ul>
@endsection