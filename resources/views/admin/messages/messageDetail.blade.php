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
            <h2>Message detail</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <br />
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left">
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="title-message">Title Message</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input type="text" id="title-message" class="form-control col-md-7 col-xs-12" value="{!! isset($detailMessage['title']) ? $detailMessage['title'] : null !!}">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="content">Content</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <textarea id="content" class="form-control" name="message" rows="5">{!! isset($detailMessage['detail']) ? $detailMessage['detail'] : null !!}</textarea>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Reply Messages List</h2>
            <ul class="nav navbar-right panel_toolbox">
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <p class="text-muted font-13 m-b-30">
            </p>
            <table id="replyList" class="table table-striped table-bordered">
              <thead>
                <tr>
                    <th>No</th>
                    <th>Reply Title</th>
                    <th>Reply Detail</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    var message_id = {!!$message_id!!};
    $(document).ready(function() {
        $('#sidebar-menu').find('li.li-messages').toggleClass('current-page');
        getListReply();
    })
</script>
@endsection