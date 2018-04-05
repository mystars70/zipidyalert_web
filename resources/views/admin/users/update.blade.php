@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="page-title">
      <div class="title_left">
        
      </div>
    
      <div class="title_right">
        
      </div>
    </div>
    
    <div class="clearfix"></div>
    
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><?php echo $title; ?></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="back-link"><i class="fa fa-reply"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br />
              {{!! Form::model($model, array('action' => array('Admin\UsersController@save', $model->user_id), 'method' => 'PUT', 'class' => 'form-horizontal form-label-left')) !!}}

                    {{ Form::hidden('user_id', $model->user_id) }}
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">User Name</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      {{ Form::text('username', null, array('class' => 'form-control col-md-7 col-xs-12', 'disabled' => 'disabled')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Phone
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      {{ Form::text('phone', null, array('class' => 'form-control col-md-7 col-xs-12')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Address</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      {{ Form::text('address', null, array('class' => 'form-control col-md-7 col-xs-12')) }}
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Zip Code</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      {{ Form::text('zipcode', null, array('class' => 'form-control col-md-7 col-xs-12')) }}
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Status</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="">
                            <label>
                              {{ Form::checkbox('status', '1', $model->status, array('class' => 'js-switch')) }}
                            </label>
                          </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Password</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      {{ Form::password('password', array('class' => 'form-control')) }}
                    </div>
                  </div>
                  <div class="ln_solid"></div>
                  <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                      <button class="btn btn-primary back-link" type="button">Cancel</button>
                      <button type="submit" class="btn btn-success">Save</button>
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
<script type="text/javascript">
    $(document).ready(function() {
      $('#sidebar-menu').find('li.li-users').toggleClass('current-page');
    });
</script>
@endsection