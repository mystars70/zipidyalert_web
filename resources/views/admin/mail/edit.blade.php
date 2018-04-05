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
              <h2>Edit Manager</h2>
              <div class="clearfix"></div>
            </div>
            {{ Form::open(['url' => 'admin/mail/save', 'method' => 'post', 'id' => 'saveForm']) }}
            <div class="x_content">
              <p class="font-13 m-b-30">
                Email Name: <?php echo $detailMail->name?>
            </p>
                <input type="hidden" name="id" value="{{$detailMail->id}}">
                <div class="form-group">
                  <label for="subject">Subject:</label>
                  <input class="form-control" type="text" name="subject" value="{{$detailMail->subject}}">
                </div>
                <textarea id="mail-content" style="width: 100%;height: 500px; resize: vertical;" name="content" rows="4" cols="50">
                {{trim($content)}}
                </textarea>
            </div>
            <div class="x_content">
              <div style="text-align: center;">
                  <a href="{{url('admin/mail')}}" class="btn btn-default">Cancel</a>
                  <button class="btn btn-primary mail-save">Save</button>
                  <!-- <button class="btn btn-default" onclick="view('{{$file}}')">View Source</button> -->
              </div>
            </div>
            {!! Form::close() !!}
        </div>
      </div>
    </div>
</div>
 <!-- popup view -->
            <div class="modal fade" id="view_mail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel" >View source</h4>
                        </div>
                        <div class="modal-body">

                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
@endsection
@section('script')

<script type="text/javascript" src="{!!url('public/admin/')!!}/js/mail.js"></script>
@endsection
@section('css')
<link href="{!!url('public/')!!}/plugins/summernote/summernote.css" rel="stylesheet">
@endsection