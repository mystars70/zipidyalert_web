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
            <div class="row">
              <div class="col-sm-11 text-right location-tabs">
                <a href="{!!url('/admin/faq/add')!!}">Add</a>&nbsp;-
                <a class="action-edit" href="javascript:void(0)">Edit</a>&nbsp;-
                <a class="action-delete" href="javascript:void(0)">Delete</a>
              </div>
            </div>
            {{ Form::open(['url' => 'admin/faq/delete', 'method' => 'post', 'id' => 'faq-delete', 'class' => '']) }}
            <table id="faqList" class="table table-striped table-bordered">
              <thead>
                <tr>
                    <th><div class="ckb-input"><input type="checkbox" class="ckb-selectAll"/></div></th>
                    <th>Category</th>
                    <th>Questions</th>
                    <th>Answers</th>
                </tr>
              </thead>
              {!! Form::close() !!}
            </table>
          </div>
        </div>
      </div>
    </div>
</div>

@endsection
@section('script')
<script type="text/javascript" src="{!!url('public/admin/')!!}/js/faq.js"></script>
<script type="text/javascript">
    var csrf_token = '{!!csrf_token()!!}';
    $(document).ready(function() {
        getListFaq();
    })
</script>
@endsection