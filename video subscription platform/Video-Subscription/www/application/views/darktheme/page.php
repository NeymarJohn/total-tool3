<section id="page">
    <?php if ($customPage !== '1') { ?>
        <div class="row" id="head-page">
            <div class="col-sm-12">
                <div class="container">
                    <div class="page-default-title">
                        <h1 class="m-t-0"><b><?php if(isset($title_page)) echo $title_page; ?></b></h1>
                        <?php if(isset($sub_page)) { ?>
                            <span><?php echo $this->lang->line('in'); ?> <a href="<?php echo site_url('page/'.$urlSubPage.'/'); ?>"><?php echo $titleSubPage; ?></a></span>
                        <?php } ?>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    <?php } ?>
	<div class="row">
		<div class="col-sm-12">
            <div id="content" class="page-content container-mobile">
                <?php if(isset($content)) echo $content; ?>
            </div>
		</div> <!-- end row -->
	</div> <!-- end container -->
</section>
