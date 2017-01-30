<?php 
	$lang = getLanguages();
?>

<div class="form-group">
	<div class="row">
		<label class="col-sm-4 control-label" for="card_num"><?php echo language('card_number', $lang); ?></label>
		<div class="col-sm-7">
			<input class="form-control input-sm validate <?php if(isset($checked) && $checked != '') echo 'required'; ?>" type="text" id="card_num" name="card_num" placeholder="<?php echo language('card_number', $lang); ?>">
		</div>
	</div>
</div>

<div class="form-group">
	<div class="row">
		<label class="col-sm-4 control-label" for="card_name"><?php echo language('card_name', $lang); ?></label>
		<div class="col-sm-7">
			<input class="form-control input-sm validate <?php if(isset($checked) && $checked != '') echo 'required'; ?>" type="text" id="card_name" name="card_name" placeholder="<?php echo language('card_name', $lang); ?>">
		</div>
	</div>
</div>

<div class="form-group">
	<div class="row">
		<label class="col-sm-4 control-label"><?php echo language('expired_date', $lang); ?></label>
		<div class="col-sm-2">
			<input class="form-control input-sm <?php if(isset($checked) && $checked != '') echo 'required'; ?>" type="text" name="expired_date[m]" placeholder="mm">
        </div>
		<div class="col-sm-1">/</div>
		<div class="col-sm-3">
			<input class="form-control input-sm <?php if(isset($checked) && $checked != '') echo 'required'; ?>" type="text" name="expired_date[y]" placeholder="yyyy">
		</div>
	</div>
</div>

<div class="form-group">
	<div class="row">
		<label class="col-sm-4 control-label" for="cvv_num"><?php echo language('cvv_num', $lang); ?></label>
		<div class="col-sm-3">
			<input class="form-control input-sm <?php if(isset($checked) && $checked != '') echo 'required'; ?>" type="text" id="cvv_num" name="cvv_num" placeholder="<?php echo language('cvv_num', $lang); ?>">
		</div> 
	</div>
</div>