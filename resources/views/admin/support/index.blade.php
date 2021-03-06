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
            <table id="supportList" class="table table-striped table-bordered">
              <thead>
                <tr>
                    <th>No</th>
                    <th>From</th>
                    <th>Email</th>
                    <th>Comment</th>
                    <th>Received On</th>
                    <th>Status</th>
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
<script type="text/javascript" src="{!!url('public/admin/')!!}/js/support.js"></script>
<script type="text/javascript">
    var csrf_token = '{!!csrf_token()!!}';
    $(document).ready(function() {
        getListSupport();
    })
</script>
@endsection