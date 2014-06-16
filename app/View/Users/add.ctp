<?php
    echo $this->element('stripe-inc');
    echo $this->Html->script('add-user.js', array('block' => 'script'));
?>

<?php if (!empty($stripe_api_error)) : ?>
<div class="stripe-api-error"><?php echo $stripe_api_error; ?></div>
<?php endif; ?>

<div class="users form">
    <?php echo $this->Form->create('User', array('novalidate' => true)); ?>
        <fieldset>
            <legend><?php echo __('User data'); ?></legend>
            <?php 
            echo $this->Form->input('first_name');
            echo $this->Form->input('last_name');
            echo $this->Form->input('email');
            echo $this->Form->input('password');
            ?>
        </fieldset>
        <fieldset>
            <legend><?php echo __('Credit Card data'); ?></legend>
            <?php echo $this->Form->input('cc.card_number', array(
                'div' => array('class' => 'required'),
                'data-stripe' => 'number'
            ));?>
            <div class="input date required">
                <label>Expiration Date</label>
                <div><?php echo $this->Form->month('cc.expiration_date', array('data-stripe' => 'exp_month')); ?></div>
                <div><?php echo $this->Form->year('cc.expiration_date', 2000, 2025, array('data-stripe' => 'exp_year')); ?></div>
            </div>
            <?php
            echo $this->Form->input('cc.cvv2_cvc2', array(
                'div' => array('class' => 'required'),
                'label' => 'CVV2/CVC2',
                'data-stripe' => 'cvc'
            ));
            ?>
        </fieldset>
        <div class="error hidden" id="stripe-error"></div>
    <?php echo $this->Form->end(__('Submit')); ?>
</div>