<form method="post" action="<?php echo current_url().'/'; ?>" role="form">
    <div class="row">
        <div class="col-sm-12">
            <?php if(isset($msg)) echo $msg; ?>
            <div class="card-box">
                <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Maintenance'); ?></b></h4>
                <p class="text-muted m-t-10 font-13"><?php echo $this->lang->line('Delete users who have never confirmed their account.'); ?></p>
                <form action="<?php echo current_url(); ?>" method="post">
                    <button type="submit" name="deleteAccounts" class="ladda-button btn btn-inverse waves-effect waves-light" data-style="expand-right">
                        <i class="fa fa-user-times m-r-5"></i>
                        <span class="ladda-label"><?php echo $this->lang->line('Execute'); ?></span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>
                </form>
                <p class="text-muted m-t-10 font-13"><?php echo $this->lang->line('Delete the oldest statistics from the database.'); ?></p>
                <form action="<?php echo current_url(); ?>" method="post">
                    <button type="submit" name="deleteStats" class="ladda-button btn btn-inverse waves-effect waves-light" data-style="expand-right">
                        <i class="fa fa-bar-chart-o m-r-5"></i>
                        <span class="ladda-label"><?php echo $this->lang->line('Execute'); ?></span>
                        <span class="ladda-spinner"></span>
                        <div class="ladda-progress" style="width: 0px;"></div>
                    </button>
                </form>
            </div> <!-- End card-box -->
        </div> <!-- End col -->
        <div class="col-sm-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Automated Tasks'); ?></b></h4>
                <p class="text-muted m-t-10 font-13"><?php echo $this->lang->line('Configure automated task. Add this command to your automated task (Cron task).'); ?></p>
                - <a href="#">php path/to/your/index.php cron index</a>
            </div> <!-- End card-box -->
            <div class="card-box">
                <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Tasks Activity'); ?></b></h4>
                <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Check the tasks activity.'); ?></p>
                <div class="table-responsive">
                    <table class="table table-actions-bar m-b-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo $this->lang->line('Notification'); ?></th>
                                <th><?php echo $this->lang->line('Status'); ?></th>
                                <th><?php echo $this->lang->line('Time'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($taskActivity)) echo $taskActivity; ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    <?php if(isset($getPagination)) echo $getPagination; ?>
                </div>
            </div>
        </div> <!-- End col -->
    </div> <!-- End row -->
</form>
