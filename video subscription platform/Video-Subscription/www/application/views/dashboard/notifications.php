<div class="row">
    <div class="col-sm-12">
        <?php if(isset($msg)) echo $msg; ?>
        <div class="card-box">
            <h4 class="text-dark header-title m-t-0"><?php echo $this->lang->line('Recent Activity'); ?></h4>
            <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Check the last notifications.'); ?></p>
            <div class="table-responsive">
                <table class="table table-actions-bar m-b-0">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line('Type'); ?></th>
                            <th><?php echo $this->lang->line('Notification'); ?></th>
                            <th><?php echo $this->lang->line('Status'); ?></th>
                            <th><?php echo $this->lang->line('Time'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($getAllNotifications)) echo $getAllNotifications; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-right">
                    <?php if(isset($getPagination)) echo $getPagination; ?>
                </div>
        </div>
    </div>
</div>
