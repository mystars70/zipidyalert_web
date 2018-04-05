<div class="navbar nav_title" style="border: 0;">
  <a href="{!!url('/admin')!!}" class="site_title"><i class="fa fa-paw"></i> <span>Zipidy Admin</span></a>
</div>

<div class="clearfix"></div>
<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
  <div class="menu_section">
    <ul class="nav side-menu">
      <!-- <li><a href="{!!url('/admin')!!}"><i class="fa fa-home"></i> Home <span class="fa fa-chevron-down"></span></a></li> -->
      <li class="li-businesses"><a href="{!!url('/admin/businesses')!!}"><i class="fa fa-university"></i> Businesses Manager<span class="fa fa-chevron-down"></span></a>
      @yield('nav-business')
<!--       <ul class="nav">
        <li><a href="{!!url('/admin/businesses')!!}">Detail</a>
        <li><a href="{!!url('/admin/businesses')!!}">Billing</a>
        <li><a href="{!!url('/admin/businesses')!!}">user</a>
      </ul> -->
      </li>
      <li class="li-users"><a href="{!!url('/admin/users')!!}"><i class="fa fa-users"></i> Users Manager<span class="fa fa-chevron-down"></span></a></li>
      <li class="li-location"><a href="{!!url('/admin/location')!!}"><i class="fa fa-location-arrow"></i> Location Manager<span class="fa fa-chevron-down"></span></a></li>
      <li class="li-messages"><a href="{!!url('/admin/messages')!!}"><i class="fa fa-comment"></i> Alert Manager<span class="fa fa-chevron-down"></span></a></li>
      <li class="li-mail"><a href="{!!url('/admin/mail')!!}"><i class="fa fa-envelope-o"></i> Mail Template Manager<span class="fa fa-chevron-down"></span></a></li>
      <li class="li-support"><a href="{!!url('/admin/support')!!}"><i class="fa fa-medkit"></i> Support Manager<span class="fa fa-chevron-down"></span></a></li>
      <li class="li-faq"><a href="{!!url('/admin/faq')!!}"><i class="fa fa-question-circle"></i> FAQ Manager<span class="fa fa-chevron-down"></span></a></li>
      <li class="li-notification"><a href="{!!url('/admin/notification')!!}"><i class="fa fa-bell"></i> Legal Notification<span class="fa fa-chevron-down"></span></a></li>
    </ul>
  </div>

</div>
<!-- /sidebar menu -->
<!-- /menu footer buttons -->
<!-- <div class="sidebar-footer hidden-small">
  <a data-toggle="tooltip" data-placement="top" title="Settings">
    <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
  </a>
  <a data-toggle="tooltip" data-placement="top" title="FullScreen">
    <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
  </a>
  <a data-toggle="tooltip" data-placement="top" title="Lock">
    <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
  </a>
  <a data-toggle="tooltip" data-placement="top" title="Logout" href="login.html">
    <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
  </a>
</div> -->
<!-- /menu footer buttons -->