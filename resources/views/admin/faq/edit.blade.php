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
          {{ Form::open(['url' => 'admin/faq/update', 'method' => 'post', 'id' => 'faq-update', 'class' => 'form-horizontal']) }}
          <input name="id" type="hidden" value="<?php echo isset($detail->id) ? $detail->id: ''?>">
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Category
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <input class="form-control" name="category" type="text" value="<?php echo isset($detail->category) ? $detail->category: ''?>">
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Questions
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea class="form-control" name="questions"><?php echo isset($detail->questions) ? $detail->questions: ''?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Answers
            </label>
            <div class="col-md-6 col-sm-6 col-xs-12">
              <textarea  class="form-control" name="answers"><?php echo isset($detail->answers) ? $detail->answers: ''?></textarea>
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
<script type="text/javascript" src="{!!url('public/admin/')!!}/js/faq.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
      $('#sidebar-menu').find('li.li-faq').toggleClass('current-page');
    });
</script>
@endsection
