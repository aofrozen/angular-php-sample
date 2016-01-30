<?php if ($ngView != 'false') { ?>
    <?php echo $this->navigation->top(); ?>
    <div ui-view="top"></div>
<?php } else { ?>
    <div class="modal" tabindex="-1" role="dialog" ng-controller="messageController">
        <div class="dialog">
            Message here
        </div>
    </div>
<?php } ?>