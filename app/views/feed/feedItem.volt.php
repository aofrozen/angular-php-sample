<?php if ($ngView != 'false') { ?>
    <?php echo $this->navigation->top(); ?>
    <?php echo $this->getContent(); ?>
    <div ui-view="top"></div>
<?php } else { ?>
Test
<?php } ?>