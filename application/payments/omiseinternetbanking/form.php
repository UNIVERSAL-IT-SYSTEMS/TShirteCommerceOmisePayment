<?php 
	$lang = getLanguages();
?>

<div class="form-group">
	<div class="row">
		<div class="col-sm-1">
			<input class="<?php if(isset($checked) && $checked != '') echo 'required'; ?>" type="radio" id="bay_bank" name="internet_banking" value="bay">
		</div>
		<label class="col-sm-6 control-label" for="bay_bank"><img src="<?php echo site_url('assets/images/banks/bay.gif');?>"/> <?php echo language('bay_bank', $lang); ?></label>
	</div>
</div>
<div class="form-group">
	<div class="row">
		<div class="col-sm-1">
			<input class="<?php if(isset($checked) && $checked != '') echo 'required'; ?>" type="radio" id="bbl_bank" name="internet_banking" value="bbl">
		</div>
		<label class="col-sm-6 control-label" for="bbl_bank"><img src="<?php echo site_url('assets/images/banks/bbl.gif');?>"/> <?php echo language('bbl_bank', $lang); ?></label>
	</div>
</div>
<div class="form-group">
	<div class="row">
		<div class="col-sm-1">
			<input class="<?php if(isset($checked) && $checked != '') echo 'required'; ?>" type="radio" id="ktb_bank" name="internet_banking" value="ktb">
		</div>
		<label class="col-sm-6 control-label" for="ktb_bank"><img src="<?php echo site_url('assets/images/banks/ktb.gif');?>"/> <?php echo language('ktb_bank', $lang); ?></label>
	</div>
</div>
<div class="form-group">
	<div class="row">
		<div class="col-sm-1">
			<input class="<?php if(isset($checked) && $checked != '') echo 'required'; ?>" type="radio" id="scb_bank" name="internet_banking" value="scb">
		</div>
		<label class="col-sm-6 control-label" for="scb_bank"><img src="<?php echo site_url('assets/images/banks/scb.gif');?>"/> <?php echo language('scb_bank', $lang); ?></label>
	</div>
</div>