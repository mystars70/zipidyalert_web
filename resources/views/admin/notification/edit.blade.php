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
          {{ Form::open(['url' => 'admin/notification/update', 'method' => 'post', 'id' => 'notification-update', 'class' => 'form-horizontal']) }}
          <input name="id" type="hidden" value="<?php echo isset($detail->id) ? $detail->id: ''?>">
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Name
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input class="form-control" name="name" type="text" value="<?php echo isset($detail->name) ? $detail->name: ''?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Description
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea class="form-control" name="description"><?php echo isset($detail->description) ? $detail->description: ''?></textarea>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
              <button class="btn btn-primary back-link" type="button">Cancel</button>
              <button type="button" class="btn btn-success <?php echo ($page == 'add') ? 'btn-create' : 'btn-update'?>">Save</button>
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{!!url('public/admin/')!!}/js/notification.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $('#sidebar-menu').find('li.li-notification').toggleClass('current-page');
    });
</script>
@endsection
