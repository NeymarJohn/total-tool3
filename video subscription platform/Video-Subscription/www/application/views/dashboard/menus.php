<div class="row" id="newMenu" <?php if(!is_null($idMenu)) echo 'style="display:none;"' ?>>
	<div class="col-sm-12">
        <div class="card-box">
            <form method="post" action="<?php echo current_url().'/'; ?>" role="form">
                <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('New menu'); ?></b></h4>
                <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Choose the name of your new menu'); ?></p>
                <div class="input-group m-t-10">
                    <input type="text" name="title" class="form-control" value="">
                    <span class="input-group-btn">
                        <button type="submit" class="btn waves-effect waves-light btn-inverse"><?php echo $this->lang->line('Submit'); ?></button>
                    </span>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if(!is_null($idMenu)) { ?>
    <div class="row">
        <div class="col-sm-12">
        	<div class="card-box">
    			<form class="form-inline" action="<?php echo current_url().'/'; ?>" method="post">
    				<div class="form-group m-r-10">
    					<label for="menus" class="font-normal p-r-10"><?php echo $this->lang->line('Select the menu you want to change'); ?></label>
    					<select class="form-control" style="height:34px;" name="menu">
                            <?php if(isset($getMenus)) echo $getMenus; ?>
                        </select>
    				</div>
                    <button type="submit" class="btn btn-inverse waves-effect waves-light btn-sm p-l-r-20"><?php echo $this->lang->line('Select'); ?></button>
    				<span class="p-l-10"><?php echo $this->lang->line('or'); ?> <a href="#" id="createMenu"><?php echo $this->lang->line('create a new menu'); ?></a></span>
    			</form>
        	</div>
    	</div>
    </div>
    <div class="row">
    	<div class="col-sm-8">
            <div class="card-box">
                <h4 class="m-t-0 header-title"><b><?php if(isset($getTileMenu)) echo $getTileMenu; ?></b></h4>
                <p class="text-muted m-b-30 font-13">
                    <?php echo $this->lang->line('Drag & drop hierarchical list for your menu.'); ?> <a href="<?php echo site_url('dashboard/menus/delete/'.$idMenu.'/'); ?>" id="deleteMenu" class="pull-right"><?php echo $this->lang->line('Delete this menu'); ?></a>
                </p>
                <div class="custom-dd dd" id="mainMenu">
                    <ol class="dd-list">
                        <?php if(!empty($getMenu)) {
                            echo $getMenu;
                        } else {
                            echo '<li class="dd-item"><div class="dd-empty"></div></li>';
                        } ?>
                    </ol>
                </div>
            </div>
        </div> <!-- End col -->
        <div class="col-sm-4">
            <div class="panel-group panel-group-joined" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed">
                                <?php echo $this->lang->line('Default Pages'); ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="custom-dd dd" id="defaultPages">
                                <ol class="dd-list">
                                    <?php if(isset($getDefaultPages)) echo $getDefaultPages; ?>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="collapsed">
                                <?php echo $this->lang->line('Categories'); ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                            <div class="custom-dd dd" id="categories">
                                <ol class="dd-list">
                                    <?php if(isset($getCategories)) echo $getCategories; ?>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed">
                                <?php echo $this->lang->line('Pages'); ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="custom-dd dd" id="pages">
                                <ol class="dd-list">
                                    <?php if(isset($getPages)) echo $getPages; ?>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour" class="collapsed">
                                <?php echo $this->lang->line('Post Categories'); ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseFour" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="custom-dd dd" id="postCategories">
                                <ol class="dd-list">
                                    <?php if(isset($getPostCategories)) echo $getPostCategories; ?>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End panel group -->
    	</div> <!-- End col -->
    </div> <!-- End row -->
<?php } ?>
<script>
window.onload = function() {
    $("a#createMenu").click(function(event) {
        event.preventDefault();
        $('div#newMenu').toggle();
	});
};
</script>
