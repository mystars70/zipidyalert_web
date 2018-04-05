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
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <p class="text-muted font-13 m-b-30">
              Select a message to show detail.</code>
            </p>
            <table id="messageList" class="table table-striped table-bordered">
              <thead>
                <tr>
                    <th>No</th>
                    <th>Message Title</th>
                    <th>Created By</th>
                    <th>Created At</th>
                    <th>Total Receive</th>
                    <th>Total Reply</th>
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
    var business_id = {!!$id!!};
    $(document).ready(function() {
      $('#sidebar-menu').find('li.li-messages').toggleClass('current-page');
        getListMessage();
    })
</script>
@endsection