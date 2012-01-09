<?php /* if ($error = $this->error()) : ?>
<div class="alert-message error">
	<a href="#" class="close">×</a>
	<p><?php echo $error ?></p>
</div>
<?php endif */ ?>

<?php if ($error = $this->error()) : ?>
<span class="help-inline"><?php echo $error ?></span>
<?php endif ?>