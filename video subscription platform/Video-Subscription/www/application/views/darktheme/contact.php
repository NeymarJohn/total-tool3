<section id="page">
    <div class="row" id="head-page">
        <div class="col-sm-12">
            <div class="container">
                <div class="page-default-title">
                    <h1 class="m-t-0"><b><?php echo $this->lang->line('Contact Us') ?></b></h1>
                </div>
            </div>
        </div> <!-- end col -->
    </div>
	<div class="row">
		<div class="col-sm-12">
            <div id="content" class="page-content">
                <div class="container">
                    <div class="col-sm-8">
                        <h2><?php echo $this->lang->line('Frequently Asked Questions') ?></h2>
                        <p><?php echo $this->lang->line('Please check the <a href="'.site_url('page/faqs/').'">Frequently Asked Questions</a> before you submit support form:') ?></p>
                        <?php if(isset($msg)) echo $msg; ?>
                        <form class="form-horizontal" action="<?php echo current_url(); ?>" method="post">
                            <div class="well2">
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo $this->lang->line('Name') ?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo $this->lang->line('Email') ?></label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control" name="email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo $this->lang->line('Issue Type') ?></label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="issue[]" required>
                                            <option value="Subscription or Payment Issue"><?php echo $this->lang->line('Subscription or Payment Issue') ?></option>
                                            <option value="Video Problem"><?php echo $this->lang->line('Video Problem') ?></option>
                                            <option value="Account Login/Verification Problem"><?php echo $this->lang->line('Account Login/Verification Problem') ?></option>
                                            <option value="General Issue/Problem" selected><?php echo $this->lang->line('General Issue/Problem') ?></option>
                                            <option value="Copyright Issue"><?php echo $this->lang->line('Copyright Issue') ?></option>
                                            <option value="Suggestion"><?php echo $this->lang->line('Suggestion') ?></option>
    									</select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo $this->lang->line('Subject') ?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="subject" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo $this->lang->line('Message') ?></label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" rows="6" name="message" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo $this->lang->line('Are you human?') ?></label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="secureUserCode" required>
                                        <input type="hidden" name="secureCode" value="<?php echo $secureCode; ?>">
                                        <small><?php echo $this->lang->line('Please type ').$secureCode.$this->lang->line(' into this field.'); ?></small>
                                    </div>
                                </div>
                                <div class="form-group m-b-0">
                                    <div class="col-sm-offset-3 col-sm-12">
                                        <button class="btn btn-red waves-effect waves-light" type="submit" name="submit" value="1"><?php echo $this->lang->line('submit'); ?></button>
                                    </div>
                				</div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel panel-color panel-inverse">
							<div class="panel-heading">
								<h3 class="panel-title font-15"><?php echo $this->config->item('contactBox1Title'); ?></h3>
							</div>
							<div class="panel-body">
								<?php echo $this->config->item('contactBox1'); ?>
							</div>
						</div>
                        <div class="panel panel-color panel-inverse">
							<div class="panel-heading">
								<h3 class="panel-title font-15"><?php echo $this->config->item('contactBox2Title'); ?></h3>
							</div>
							<div class="panel-body">
								<?php echo $this->config->item('contactBox2'); ?>
							</div>
						</div>
                    </div>
                </div>
            </div>
		</div> <!-- end row -->
	</div> <!-- end container -->
</section>
