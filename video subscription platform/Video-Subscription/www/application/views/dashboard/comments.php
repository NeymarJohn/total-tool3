<section>
    <div class="row head-links">
        <div class="col-sm-12">
            <a href="<?php echo site_url('dashboard/comments/'); ?>"><?php echo $this->lang->line('All'); ?></a> <small>(<?php if(isset($totalComments)) echo $totalComments; ?>) |</small>
            <a href="<?php echo site_url('dashboard/comments/pending/'); ?>"><?php echo $this->lang->line('Pending'); ?></a> <small>(<?php if(isset($totalPending)) echo $totalPending; ?>) |</small>
            <a href="<?php echo site_url('dashboard/comments/spam/'); ?>"><?php echo $this->lang->line('Spam'); ?></a> <small>(<?php if(isset($totalSpam)) echo $totalSpam; ?>)</small>
        </div>
    </div>
    <div class="card-box">
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box">
                    <a href="<?php echo site_url('dashboard/comments/add/'); ?>" class="btn btn-sm btn-primary waves-effect waves-light pull-right"><?php echo $this->lang->line('newComment'); ?></a>
                    <h4 class="m-t-0 p-b-20 header-title"><b><?php if(isset($title2)) echo $title2; ?></b></h4>
                    <table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0" data-page-size="7">
                        <thead>
                            <tr>
                                <th data-toggle="true">#</th>
                                <th><?php echo $this->lang->line('Author'); ?></th>
                                <th data-hide="phone, tablet"><?php echo $this->lang->line('Title'); ?></th>
                                <th data-hide="phone"><?php echo $this->lang->line('Date'); ?></th>
                                <th data-hide="all"><?php echo $this->lang->line('Comment'); ?></th>
                                <th data-hide="all"><?php echo $this->lang->line('Status'); ?></th>
                                <th data-hide="all"><?php echo $this->lang->line('IP'); ?></th>
                            </tr>
                        </thead>
                        <?php if (isset($filterView)) { ?>
                            <div class="form-inline m-b-20">
                                <div class="row">
                                    <div class="col-sm-6 text-xs-center">
                                        <div class="form-group">
                                            <label class="control-label m-r-5"><?php echo $this->lang->line('Status'); ?></label>
                                            <select id="demo-foo-filter-status" class="form-control input-sm">
                                                <option value=""><?php echo $this->lang->line('Show all'); ?></option>
                                                <option value="pending"><?php echo $this->lang->line('Pending'); ?></option>
                                                <option value="approved"><?php echo $this->lang->line('Approved'); ?></option>
                                                <option value="spam"><?php echo $this->lang->line('Spam'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-xs-center text-right">
                                        <div class="form-group">
                                            <input id="demo-foo-search" type="text" placeholder="<?php echo $this->lang->line('Search'); ?>" class="form-control input-sm" autocomplete="on">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <tbody>
                            <?php if(isset($getComments)) echo $getComments; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">
                                    <div class="text-right">
                                        <ul class="pagination pagination-split m-t-30 m-b-0"></ul>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
