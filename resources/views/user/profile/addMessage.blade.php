@extends('user.layouts.profile')
@section('content')
<p>Be safe where you LIVE WORK &amp; PLAY!</p>
<p>Alert messages from registered businesses in your community will be displayed here. </p>
<div class="item2">
    {{ Form::open(['url' => 'user/ajax-send-message', 'method' => 'post', 'id' => 'sendMesssage', 'enctype' => 'multipart/form-data']) }}
        <?php if ($userType->user_type == 1 || $userType->user_type == 2): ?>
        <div class="option-message">
            <div><input type="radio" name="option-message" value="1" checked="" /> Publish Message </div>
            <div><input type="radio" name="option-message" value="2" /> Broadcast Message </div>
        </div>
        <?php else: ?>
            <input type="hidden" name="option-message" value="3"/>
        <?php endif ?>
        <div class="form-message">
            <div class="input">
                <label>Title:</label> <input class="validate[required]" type="text" name="title" placeholder="Title message"  />
            </div>
            <div class="input">
                <textarea class="validate[required]" placeholder="Content message" name="message"></textarea>
            </div>
            <div class="input">
                <label>Select image to upload</label><input class="validate[funcCall[validateMIME]]" type="file" name="image" id="image">
            </div>
            
            <input type="submit" name="btn-send-message" id="btn-send-message" value="Send" >
        </div>
    </form>
      
    
</div>
@endsection