<?php only_admin_access(); ?>

<style>

</style>

<?php
    $before =  get_option('before', $params['id']);
    $after =  get_option('after', $params['id']);
    $title =  get_option('title', $params['id']);
?>
<div class="module-live-edit-settings">
 
<div class="mw-ui-box mw-ui-box-content">
<label class="mw-ui-label">Upload 2 images</label>
<div class="mw-ui-field-holder">
  <span class="mw-ui-btn" id="before"><span class="mw-icon-upload"></span>onmouse_over</span>
  <span class="mw-ui-btn" id="after"><span class="mw-icon-upload"></span>onmouse_leave</span>
    <div><span>title</span><input type="text" class="mw-ui-field mw-ui-filed-big mw_option_field w100" placeholder="Enter Text" name="title" id="title" value="<?php echo $title;?>" /></div>
</div>
</div>



<input type="hidden" class="mw_option_field" name="before" id="beforeval" value="<?php print $before; ?>" />
<input type="hidden" class="mw_option_field" name="after" id="afterval" value="<?php print $after; ?>" />

</div>

<script>

$(document).ready(function(){
    var before = mw.uploader({
          filetypes:"images,videos",
          multiple:false,
          element:"#before"
    });
    $(before).bind('FileUploaded', function(a,b){
        mw.$("#beforeval").val(b.src).trigger('change');
    });
    var after = mw.uploader({
          filetypes:"images,videos",
          multiple:false,
          element:"#after"
    });
    $(after).bind('FileUploaded', function(a,b){
        mw.$("#afterval").val(b.src).trigger('change');
    });
});
</script>