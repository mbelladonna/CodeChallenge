<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo $title_for_layout; ?>
        </title>
        <?php
        echo $this->Html->meta('icon');

        echo $this->Html->css('cake.generic');
        echo $this->Html->css('style.css');

        echo $this->Html->script('http://code.jquery.com/jquery.min.js');
        echo $this->Html->script('http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.0/jquery.validate.min.js');

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <h1><?php echo $this->Html->link('Code Challenge: Registration with Credit Card', '/'); ?></h1>
            </div>
            <div id="content">
                <?php echo $this->Session->flash(); ?>
                <?php echo $this->fetch('content'); ?>
            </div>
            <div id="footer">
                <?php echo $this->Text->autoLinkEmails('Mariano Belladonna | mariano.belladonna@bairesdev.com'); ?>
            </div>
        </div>
    </body>
</html>