<div style="text-align:center; width: 100%;">
<div style="width:600px; margin:0 auto;">
<h1>Zipidy Alert</h1>
<h4>You have a invitation-&gt; to be a {{$user_type}}</h4>
<img src="{{ $message-&gt;embed(public_path() . '/user/images/mail_banner.png') }}" alt="">
<div>
<a style="text-decoration: none; color:#FFF; padding: 20px 60px 20px 60px;background-color: #52b266;" href="{{$url_verify}}">Accept Invite</a>
</div>
</div>
</div>