
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title><?php echo isset($title) ? $title : 'Zipidy Admin' ?></title>

    <!-- Bootstrap -->
    <link href="{!!url('public/admin/')!!}/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{!!url('public/admin/')!!}/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="{!!url('public/admin/')!!}/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="{!!url('public/admin/')!!}/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="{!!url('public/admin/')!!}/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="{!!url('public/admin/')!!}/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="{!!url('public/admin/')!!}/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="{!!url('public/admin/')!!}/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="{!!url('public/admin/')!!}/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="{!!url('public/admin/')!!}/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{!!url('public/admin/')!!}/css/jquery-confirm.css" type="text/css"/>
    <link href="{!!url('public/user/')!!}/plugin/select2/css/select2.css" rel="stylesheet" />
    <link href="{!!url('public/user/')!!}/css/jquery-ui.min.css" rel="stylesheet">
    @yield('css')
    <!-- Custom Theme Style -->
    <link href="{!!url('public/admin/')!!}/css/validationEngine.jquery.css" rel="stylesheet">
    <link href="{!!url('public/admin/')!!}/css/custom.css" rel="stylesheet">
    <link href="{!!url('public/admin/')!!}/css/admin.css" rel="stylesheet">
    <script type="text/javascript">
        var baseUrl = '{!!url("/")!!}';
    </script>
    
    
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            @include('admin.layouts.sidebar')
          </div>
        </div>
        <!-- top navigation -->
        @include('admin.layouts.topNav')
        <!-- /top navigation -->

        <!-- page content -->
        <div class="right_col" role="main">
          @yield('content')
        </div>
        <!-- /page content -->

        <!-- footer content -->
        @include('admin.layouts.footer')
        <!-- /footer content -->
      </div>
    </div>
    <!-- jQuery -->
    <script src="{!!url('public/admin/')!!}/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="{!!url('public/admin/')!!}/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="{!!url('public/admin/')!!}/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="{!!url('public/admin/')!!}/vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="{!!url('public/admin/')!!}/vendors/iCheck/icheck.min.js"></script>
    <!-- Switchery -->
    <script src="{!!url('public/admin/')!!}/vendors/switchery/dist/switchery.min.js" type="text/javascript"></script>
    <!-- Datatables -->
    <script src="{!!url('public/admin/')!!}/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/jszip/dist/jszip.min.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="{!!url('public/admin/')!!}/vendors/pdfmake/build/vfs_fonts.js"></script>
    <script src="{!!url('public/')!!}/plugins/summernote/summernote.js"></script>
    <script src="{!!url('public/admin/')!!}/js/jquery-confirm.js"></script>
    <script src="{!!url('public/admin/')!!}/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
    <script src="{!!url('public/admin/')!!}/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
    <script src="{!!url('public/user/')!!}/plugin/select2/js/select2.min.js"></script>
    <script src="{!!url('public/user/')!!}/js/jquery-ui.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="{!!url('public/admin/')!!}/js/custom.js"></script>
    @yield('script')
  </body>
</html>