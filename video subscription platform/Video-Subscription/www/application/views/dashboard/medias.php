<?php
if(isset($msg)) echo $msg;
if(isset($error)) echo alert($error, 'danger');
?>
<?php if ($this->uri->segment(3) === 'edit') { ?>
    <div class="card-box">
        <div class="row">
            <div class="col-sm-12">

            </div>
        </div>
    </div> <!-- End card-box -->
<?php } else { ?>
    <div class="row" id="newMenu" style="display:none">
    	<div class="col-sm-12">
            <div class="card-box">
                <form method="post" action="<?php echo current_url().'/'; ?>" role="form" enctype="multipart/form-data" accept-charset="utf-8">
                    <div class="form-group m-b-20">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('New image'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Upload an image in the default image folder'); ?> (.gif, .jpg, .jpeg, .png).</p>
                        <input type="file" name="userImage" class="filestyle" data-buttontext="Select file" data-buttonname="btn-inverse" data-placeholder="">
                    </div>
                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-inverse waves-effect waves-light" type="submit" name="submit" value="1"><?php echo $this->lang->line('submit'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-box">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="m-t-0 header-title inline-block"><b><?php echo $this->lang->line('Images'); ?></b></h4>
                <a href="#" id="addImage" class="pull-right font-14"><?php echo $this->lang->line('Add new image'); ?></a>
            </div>
            <?php echo (!empty($getImages)) ? $getImages : $this->lang->line('noData'); ?>
        </div>
        <div class="row">
            <div class="col-sm-12 text-right">
                <?php if(isset($pagination)) echo $pagination; ?>
            </div>
        </div>
    </div> <!-- End card-box -->
    <div class="card-box">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Videos'); ?></b></h4>
            </div>
            <div class="col-sm-12">
                <div class="card-box table-responsive">
                    <table id="datatable-fixed-header" class="table table-striped table-bordered dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="text-center"><?php echo $this->lang->line('file'); ?></th>
                                <th class="text-center"><?php echo $this->lang->line('video'); ?></th>
                                <th class="text-center"><?php echo $this->lang->line('options'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(isset($getFiles)) echo $getFiles; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- End row -->
    </div> <!-- End card-box -->
<?php } ?>

<script type="text/javascript">
    window.onload = function() {
        $('#addImage').click(function(event) {
            event.preventDefault();
            $('div#newMenu').toggle();
        });
    };
</script>
