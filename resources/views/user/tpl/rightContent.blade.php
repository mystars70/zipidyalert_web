<?php
use Illuminate\Pagination\LengthAwarePaginator;
use App\HelperDB;
$helpDb = new HelperDB();
?>
<?php if (!empty($listBusiness) && count($listBusiness) > 0) :?>
  <ul>
  <?php foreach($listBusiness as $item) : 
        $linkBusiness = url('user') . '/business/page/' . generateId(($item->business_id + 100)  . '-' . $item->business_id);?>
        <div class="item">
              <div class="media">
                <a class="pull-left biz_{{$item->business_id}}" href="<?php echo $linkBusiness; ?>">
                <?php if ($item->avatar && file_exists(env('DIR_UPLOAD_BUSINESS').$item->avatar)) :?>
                    <img class="img-rounded img-circle" src="<?php echo url(env('DIR_UPLOAD_BUSINESS').$item->avatar)?>">
                <?php else :?>
                    <?php $helpDb->noImage($item->name); ?>   
                <?php endif;?>
                </a>
                <div class="media-body">
                    <div class="text-left">
                        <a href="<?php echo $linkBusiness; ?>"><h4 class="media-heading"><?php echo $item->name; ?></h4></a>
                        <span><?php echo (($item->city_name) ? $item->city_name.', ': '').$item->state_code.' '.$item->zipcode?></span>
                    </div>
                    <div class="text-right">
                      <?php if ($item->join) :?>
                      <img class="places-join" src="<?php echo url('public/user/images/off-plus-icon.png')?>">
                      <?php else:?>
                      <img class="places-join join-places" onclick="joinPlaces('<?php echo generateId(($item->business_id + 100)  . '-' . $item->business_id)?>')" src="<?php echo url('public/user/images/plus-icon.png')?>">
                      <?php endif;?>
                    </div>
               </div>
               
            </div>
          </div>
  <?php endforeach;?>
  <div class="paging_partial">
    <?php echo $listBusiness->links();?>
  </div>
  </ul>
  <?php else :?>
  <div class="item">
      <div class="media-body">
          <div class="text-center">
              <span>Invite businesses in your community and everywhere around the world to register and keep us all safe where we live work and play!</span>
          </div>
      </div>
  </div>
  <?php endif;?>

              <!-- popup invite -->
            <div class="modal fade" id="invite_place" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="sharePlace">Invite other businesses to keep us all safe where we LIVE, WORK, & PLAY!</h4>
                        </div>
                        <div class="modal-body">
                            <div class="block-search">
                            {{ Form::open(['url' => 'user/business/add-invite', 'method' => 'post', 'id' => 'inviteForm']) }}
                            <!-- <input type="hidden" name="action" value="share"> -->
                                <div class="form-input-list">
                                    <div class="form-group">
                                        <input name="email[]" type="text" label="Email" class="input-user validate[custom[email]] placeholder-input ">
                                    </div>
                                    <div class="email-input-list">
                                    </div>
                                    <div class="add-email-input"><div><span>+</span></div>&nbsp;&nbsp;<span>Add more</span></div>
                                </div>
                            {!! Form::close() !!}
                            </div>
                        </div>
                        <div class="modal-footer">

                            <button type="button" class="btn btn-default btn-modal-close" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btn-invite_place">Invite</button>
                        </div>
                    </div>
                </div>
            </div>