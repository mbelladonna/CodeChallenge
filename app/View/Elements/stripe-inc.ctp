<?php echo $this->Html->script('https://js.stripe.com/v2/', array('block' => 'script')); ?>
<script>
    Stripe.setPublishableKey('<?php echo Configure::read('Stripe.keys.public'); ?>');
</script>