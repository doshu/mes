<?php echo $this->Session->flash('flash', array('element' => 'flash_info')); ?>
<?php echo $this->Session->flash('auth', array('element' => 'flash_error')); ?>
<?php echo $this->Session->flash('error', array('element' => 'flash_error')); ?>
<?php echo $this->Session->flash('info', array('element' => 'flash_info')); ?>
<script>
    setTimeout(function() {
        $('.alert.alert-dismissable').slideUp().remove();
    }, 7000)
</script>
