@extends('user.layouts.profile')
@section('content')
<p>Be safe where you LIVE WORK &amp; PLAY!</p>
<p>Alert messages from registered businesses in your community will be displayed here. </p>
<div class="item2">
    <ul>
    <?php 
    if (!empty($listMessage)) {
        foreach ($listMessage as $item) {
            ?>
            <li><img src="{!!url('public/user/')!!}/images/imguser.jpg" class="freeuser"><a href="<?php echo url('user') . '/message-detail/' . base64_encode($item->message_id . '-' . $item->business_id); ?>">{{ $item->name}}</a>
                <div class="add"><a href="#"> {{ date(config('settings.date_format'), strtotime($item->created_at)) }}</a></div>
                <div>{{ $item->address}}<br>
                    <div class="message">
                        {{$item->detail}}
                    </div>
                </div>
            </li>
            <?php
        }
    }
    ?>
      
    </ul>
</div>
@endsection