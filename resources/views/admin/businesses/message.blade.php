@extends('admin.layouts.master')
@section('content')
<div class="main-content">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Business Manager > Business's Messages</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <p class="font-13 m-b-30 text-right">
              <div class="form-group col-sm-2">
                <select class="form-control tab-selection">
                  <option value="1">Public</option>
                  <option value="2">Broadcast</option>
                </select>
              </div>
            </p>
            <ul id="myTab" class="nav nav-tabs bar_tabs hidden" role="tablist">
              <li role="presentation" class="active"><a href="#tab_content0" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" class="tab-public">Public</a>
              </li>
              <li role="presentation" class=""><a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab" aria-expanded="true" class="tab-broadcast">Broadcast</a>
              </li>
            </ul>
            <div id="myTabContent" class="tab-content">
              <div role="tabpanel" class="tab-pane fade active in" id="tab_content0" aria-labelledby="home-tab">
                <table id="messagesListPublic" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created On</th>
                        <th>Message Type</th>
                        <th>Received</th>
                    </tr>
                  </thead>
                </table>
              </div>
              <div role="tabpanel" class="tab-pane fade" id="tab_content1" aria-labelledby="home-tab">
                <table id="messagesListBroadcast" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Created On</th>
                        <th>Message Type</th>
                        <th>Received</th>
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
    $(document).ready(function() {
      getListBusinessMessagesPublic(); 
      getListBusinessMessagesBroadcast();
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