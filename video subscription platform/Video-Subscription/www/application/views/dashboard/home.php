<div class="row">
    <div class="col-sm-12">
        <?php if (!$this->input->cookie('notification') && $this->config->item('hidePromo') !== '1' && !$this->config->item('demo')) { ?>
            <div class="alert alert-warning alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="fa fa-lightbulb-o"></i>
                <div class="p-l-20"><a href="https://video-streaming.coffeetheme.com/" target="_blank">Discover our new Video Streaming Platform</a> !</div>
            </div>
        <?php } ?>
        <div class="card-box widget-inline">
            <div class="row">
                <div class="col-lg-3 col-sm-6">
                    <div class="widget-inline-box text-center">
                        <h3><i class="text-primary md md-add-shopping-cart"></i> <b data-plugin="counterup"><?php if (isset($nbIncomesCount)) echo $nbIncomesCount; ?></b></h3>
                        <h4 class="text-muted"><?php echo $this->lang->line('Lifetime total sales'); ?></h4>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="widget-inline-box text-center">
                        <h3><i class="text-custom md md-attach-money"></i> <b data-plugin="counterup"><?php if (isset($nbIncomesPrice)) echo $nbIncomesPrice; ?></b></h3>
                        <h4 class="text-muted"><?php echo $this->lang->line('Income amounts'); ?></h4>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="widget-inline-box text-center">
                        <h3><i class="text-pink md md-account-child"></i> <b data-plugin="counterup"><?php if (isset($nbMembers)) echo $nbMembers; ?></b></h3>
                        <h4 class="text-muted"><?php echo $this->lang->line('Total members'); ?></h4>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="widget-inline-box text-center b-0">
                        <h3><i class="text-purple md md-visibility"></i> <b data-plugin="counterup"><?php if (isset($nbPlayed)) echo $nbPlayed; ?></b></h3>
                        <h4 class="text-muted"><?php echo $this->lang->line('Total played videos'); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-4">
        <div class="card-box widget-box-1 bg-white">
            <i class="fa fa-info-circle text-muted pull-right inform" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $this->lang->line('Total active subscriptions'); ?>"></i>
            <h4 class="text-dark"><?php echo $this->lang->line('Active subscriptions'); ?></h4>
            <h2 class="text-dark text-center"><span data-plugin="counterup"><?php if (isset($nbActiveSubscriptionsCount)) echo $nbActiveSubscriptionsCount; ?></span></h2>
            <p class="text-muted"><?php echo $this->lang->line('Recurring payments:'); ?> <span class="pull-right">$ <?php if (isset($nbActiveSubscriptionsPrice)) echo $nbActiveSubscriptionsPrice; ?></span></p>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-4">
        <div class="card-box widget-box-1 bg-white">
            <i class="fa fa-info-circle text-muted pull-right inform" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $this->lang->line('Sales of the day'); ?>"></i>
            <h4 class="text-dark"><?php echo $this->lang->line('Daily Sales'); ?></h4>
            <h2 class="text-dark text-center"><span data-plugin="counterup"><?php if (isset($nbDayIncomesCount)) echo $nbDayIncomesCount; ?></span></h2>
            <p class="text-muted"><?php echo $this->lang->line('Total:'); ?> <span class="pull-right">$ <?php if (isset($nbDayIncomesPrice)) echo $nbDayIncomesPrice; ?></span></p>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-4">
        <div class="card-box widget-box-1 bg-white">
            <i class="fa fa-info-circle text-muted pull-right inform" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="<?php echo $this->lang->line('Sales of the month'); ?>"></i>
            <h4 class="text-dark"><?php echo $this->lang->line('Monthly sales'); ?></h4>
            <h2 class="text-dark text-center"><span data-plugin="counterup"><?php if (isset($nbMonthIncomesCount)) echo $nbMonthIncomesCount; ?></span></h2>
            <p class="text-muted"><?php echo $this->lang->line('Total:'); ?> <span class="pull-right">$ <?php if (isset($nbMonthIncomesPrice)) echo $nbMonthIncomesPrice; ?></span></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="card-box">
            <h4 class="text-dark header-title m-t-0"><?php echo $this->lang->line('Latest sales'); ?></h4>
            <div class="text-center">
                <ul class="list-inline chart-detail-list">
                    <li>
                        <h5><i class="fa fa-circle m-r-5" style="color: #36404a;"></i><?php echo $this->lang->line('Subscriptions'); ?></h5>
                    </li>
                    <li>
                        <h5><i class="fa fa-circle m-r-5" style="color: #5d9cec;"></i><?php echo $this->lang->line('Payments'); ?></h5>
                    </li>
                </ul>
            </div>
            <div id="morris-area-with-dotted" style="height: 300px;" data-played="<?php if (isset($statsSubscriptions)) echo $statsSubscriptions; ?>" data-last="<?php if (isset($statsPayments)) echo $statsPayments; ?>"></div>
        </div>
    </div> <!-- end col -->
    <div class="col-sm-6">
        <div class="card-box">
            <h4 class="text-dark header-title m-t-0"><?php echo $this->lang->line('Monthly sales'); ?></h4>
            <div class="text-center">
                <ul class="list-inline chart-detail-list">
                    <li>
                        <h5><i class="fa fa-circle m-r-5" style="color: #36404a;"></i><?php echo $this->lang->line('Subscriptions'); ?></h5>
                    </li>
                    <li>
                        <h5><i class="fa fa-circle m-r-5" style="color: #5d9cec;"></i><?php echo $this->lang->line('Payments'); ?></h5>
                    </li>
                </ul>
            </div>
            <div id="morris-bar-stacked" style="height: 300px;" data-month="01,02,03,04,05,06,07,08,09,10,11,12" data-subs="<?php if (isset($statsMonthSubscriptions)) echo $statsMonthSubscriptions; ?>" data-paymt="<?php if (isset($statsMonthPayments)) echo $statsMonthPayments; ?>"></div>
        </div>
    </div> <!-- col -->
</div>
<div class="row">
    <div class="col-lg-4">
        <div class="card-box">
            <div class="bar-widget">
                <div class="table-box">
                    <div class="table-detail">
                        <h4 class="m-t-0 m-b-5"><i class="text-inverse md md-account-child p-r-5"></i><?php if (isset($nbMembers)) echo $nbMembers; ?></h4>
                        <p class="text-muted m-b-0 m-t-0"><?php echo $this->lang->line('Total Subscribers'); ?></p>
                    </div>
                    <div class="table-detail text-right">
                        <span data-plugin="peity-bar" data-colors="#34d3eb,#ebeff2" data-width="120" data-height="45"><?php if (isset($statsMembers)) echo $statsMembers; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
    <div class="col-lg-4">
        <div class="card-box">
            <div class="bar-widget">
                <div class="table-box">
                    <div class="table-detail">
                        <h4 class="m-t-0 m-b-5"><i class="text-inverse md md-forum p-r-5"></i><?php if (isset($nbComments)) echo $nbComments; ?></h4>
                        <p class="text-muted m-b-0 m-t-0"><?php echo $this->lang->line('totalComments'); ?></p>
                    </div>
                    <div class="table-detail text-right">
                        <span data-plugin="peity-bar" data-colors="#34d3eb,#ebeff2" data-width="120" data-height="45"><?php if (isset($statsComments)) echo $statsComments; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
    <div class="col-lg-4">
        <div class="card-box">
            <div class="bar-widget">
                <div class="table-box">
                    <div class="table-detail">
                        <h4 class="m-t-0 m-b-5"><i class="text-inverse md md-visibility p-r-5"></i><?php if (isset($nbPlayed)) echo $nbPlayed; ?></h4>
                        <p class="text-muted m-b-0 m-t-0"><?php echo $this->lang->line('totalPlayedVideos'); ?></p>
                    </div>
                    <div class="table-detail text-right">
                        <span data-plugin="peity-bar" data-colors="#34d3eb,#ebeff2" data-width="120" data-height="45"><?php if (isset($statsPlayed)) echo $statsPlayed; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end col -->
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="card-box">
            <h4 class="text-dark header-title m-t-0"><?php echo $this->lang->line('Members country'); ?></h4>
            <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Location of members (this year).'); ?></p>
            <div class="table-responsive">
                <table class="table table-actions-bar m-b-0">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line('Flag'); ?></th>
                            <th><?php echo $this->lang->line('Country'); ?></th>
                            <th><?php echo $this->lang->line('Number'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($locationMembers)) echo $locationMembers; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card-box">
            <h4 class="text-dark header-title m-t-0"><?php echo $this->lang->line('Members country'); ?></h4>
            <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Location of members (total).'); ?></p>
            <div class="table-responsive">
                <table class="table table-actions-bar m-b-0">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line('Flag'); ?></th>
                            <th><?php echo $this->lang->line('Country'); ?></th>
                            <th><?php echo $this->lang->line('Number'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($totalLocationMembers)) echo $totalLocationMembers; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <a href="<?php echo site_url('dashboard/payments/'); ?>" class="pull-right btn btn-default btn-sm waves-effect waves-light"><?php echo $this->lang->line('viewAll'); ?></a>
            <h4 class="text-dark header-title m-t-0"><?php echo $this->lang->line('Invoices'); ?></h4>
            <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Check latest invoices created'); ?></p>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo $this->lang->line('Type'); ?></th>
                            <th><?php echo $this->lang->line('Subscription ID'); ?></th>
                            <th><?php echo $this->lang->line('Date created'); ?></th>
                            <th><?php echo $this->lang->line('Date end'); ?></th>
                            <th><?php echo $this->lang->line('Status'); ?></th>
                            <th><?php echo $this->lang->line('Price'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($getInvoices)) echo $getInvoices; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="card-box">
            <h4 class="text-dark m-t-0 header-title"><?php echo $this->lang->line('Activity'); ?></h4>
            <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Check latest website activity'); ?></p>
            <div id="line-chart" class="line-chart" data-notes="<?php if (isset($getNotesActivity)) echo $getNotesActivity; ?>" data-favorites="<?php if (isset($getFavsActivity)) echo $getFavsActivity; ?>" data-comments="<?php if (isset($getComsActivity)) echo $getComsActivity; ?>">
                <svg style="height:400px;width:100%"></svg>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card-box">
            <div class="clearfix">
                <a href="<?php echo site_url('dashboard/comments/'); ?>" class="pull-right btn btn-default btn-sm waves-effect waves-light"><?php echo $this->lang->line('viewAll'); ?></a>
                <h4 class="text-dark header-title m-t-0"><?php echo $this->lang->line('latestComments'); ?></h4>
                <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Check the latest comments received'); ?></p>
            </div>
            <div class="table-responsive">
                <table class="table table-actions-bar m-b-0">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line('Comments'); ?></th>
                            <th><?php echo $this->lang->line('Videos'); ?></th>
                            <th style="min-width: 80px;"><?php echo $this->lang->line('manage'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($getLastcomments)) echo $getLastcomments; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col -->
    <div class="col-lg-6">
        <div class="card-box">
            <div class="clearfix">
                <a href="<?php echo site_url('dashboard/videos/'); ?>" class="pull-right btn btn-default btn-sm waves-effect waves-light"><?php echo $this->lang->line('viewAll'); ?></a>
                <h4 class="text-dark header-title m-t-0"><?php echo $this->lang->line('latestVideosAdd'); ?></h4>
                <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Check the latest videos added'); ?></p>
            </div>
            <div class="table-responsive">
                <table class="table table-actions-bar m-b-0">
                    <thead>
                        <tr>
                            <th><?php echo $this->lang->line('Videos'); ?></th>
                            <th><?php echo $this->lang->line('Categories'); ?></th>
                            <th style="min-width: 80px;"><?php echo $this->lang->line('manage'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($getLastVideosAdded)) echo $getLastVideosAdded; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col -->
</div>

<script>
    window.onload = function() {
        $('.close').click(function(event) {
            event.preventDefault();
            document.cookie = "notification=false";
        });
    };
</script>