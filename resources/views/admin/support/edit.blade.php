@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>{{$title}}</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="support-status text-right">
              <div class="row">
                <div class="col-md-12">
                  <label>Status:</label>
                  <?php echo ($detail->email == 0) ? 'New' : 'Replied'?>
                </div>
              </div>
            </div>
            <div class="support-detail">
              <div class="row">
                <div class="col-md-6">
                  <label>Email:</label>
                  <?php echo $detail->email?>
                </div>
                <div class="col-md-3">
                  <?php echo $detail->firstname.' '.$detail->lastname?>
                </div>
                <div class="col-md-3">
                  <?php echo date('h:i m-d-Y', strtotime($detail->created_at))?>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="row message-warp">
                <div class="col-md-4">
                  <label>Message:</label>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="message-content">
                    <?php echo $detail->message?>
                  </div>
                </div>
              </div>
            </div>
            <div class="reply-wrap">
              <div class="row">
                <div class="col-md-4">
                  <label><i>Reply:</i></label>
                </div>
              </div>
              {{ Form::open(['url' => 'admin/support/send', 'method' => 'post', 'id' => 'support-send', 'class' => 'form-horizontal']) }}
              <input name="id" type="hidden" value="<?php echo isset($detail->id) ? $detail->id: ''?>">
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <textarea class="form-control" name="message"><?php echo isset($detail->reply_message) ? $detail->reply_message: ''?></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 text-left">
                  <div class="btn-group">
                    <i>Customer Support Team</i>
                  </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                  <div class="btn-group">
                    <button class="btn btn-primary back-link hidden" type="button">Cancel</button>
                    <button type="button" class="btn btn-success btn-send">Send</button>
                  </div>
                </div>
              </div>
              {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{!!url('public/admin/')!!}/js/support.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $('#sidebar-menu').find('li.li-support').toggleClass('current-page');
    });
</script>
@endsection
