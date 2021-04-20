<form class="form" role="form" method="post" action="<?php echo current_url(); ?>">
    <div class="row">
    	<div class="col-sm-12">
    		<?php if(isset($msg)) echo $msg; ?>
            <div class="card-box">
                <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('General settings'); ?></b></h4>
                <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Define general website settings'); ?></p>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
        					<label for="sitename"><?php echo $this->lang->line('sitename'); ?></label>
        					<input type="text" class="form-control" name="sitename" value="<?php echo html_escape($this->config->item('sitename')); ?>" placeholder="<?php echo $this->lang->line('Site name'); ?>" />
        				</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
        					<label for="emailsite"><?php echo $this->lang->line('email'); ?></label>
        					<input type="email" id="emailsite" class="form-control" name="emailsite" value="<?php echo html_escape($this->config->item('emailsite')); ?>" placeholder="<?php echo $this->lang->line('Site Email'); ?>">
        				</div>
                    </div>
                    <div class="col-sm-12">
        				<div class="form-group">
        					<label for="logo"><?php echo $this->lang->line('logo'); ?></label> <small>(<a href="http://www.coffeetheme.com/forums/topic/how-to-change-the-logo/" target="_blank"><?php echo $this->lang->line('How to change the logo ?'); ?></a>)</small>
        					<input type="text" class="form-control" name="logo" value="<?php echo html_escape($this->config->item('logo')); ?>" placeholder="" />
        				</div>
                    </div>
                    <div class="col-sm-12">
        				<div class="form-group">
        					<label for="theme"><?php echo $this->lang->line('Theme'); ?></label>
                            <select class="form-control selectpicker show-tick" data-style="btn-white" name="theme">
        						<option value="default" <?php if($this->config->item('theme') == 'default') echo 'selected="selected"'; ?>><?php echo $this->lang->line('Default'); ?></option>
        						<?php if(is_dir('application/views/darktheme')) { ?>
                                    <option value="darktheme" <?php if($this->config->item('theme') == 'darktheme') echo 'selected="selected"'; ?>><?php echo $this->lang->line('Dark Theme'); ?></option>
        						<?php } ?>
        					</select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
        					<label for="free-website"><?php echo $this->lang->line('Free Website'); ?></label>
        					<select class="form-control selectpicker show-tick" data-style="btn-white" name="free-website">
        						<option value="1" <?php if($this->config->item('freeWebsite') == 1) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Active'); ?></option>
        						<option value="0" <?php if($this->config->item('freeWebsite') == 0) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
        					</select>
        				</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
        					<label for="comments-moderation"><?php echo $this->lang->line('Comments moderation'); ?></label>
        					<select class="form-control selectpicker show-tick" data-style="btn-white" name="comments-moderation">
        						<option value="1" <?php if($this->config->item('comments_moderation') == 1) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Active'); ?></option>
        						<option value="0" <?php if($this->config->item('comments_moderation') == 0) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
        					</select>
        				</div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
        					<label for="confirmation-inscription"><?php echo $this->lang->line('Registration confirmation'); ?></label>
        					<select class="form-control selectpicker show-tick" data-style="btn-white" name="confirmation-inscription">
        						<option value="1" <?php if($this->config->item('confirmation_inscription') == 1) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Active'); ?></option>
        						<option value="0" <?php if($this->config->item('confirmation_inscription') == 0) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
        					</select>
        				</div>
                    </div>
                    <div class="col-sm-12">
        				<div class="form-group m-b-20">
        					<label for="termsOfUse"><?php echo $this->lang->line('termOfUse'); ?></label>
        					<select class="form-control select2" name="termsOfUse"> <?php if(isset($getPages)) echo $getPages; ?> </select>
        				</div>
                    </div> <!-- End col -->
                    <div class="col-sm-12">
                        <div class="form-group m-b-20">
                            <div class="checkbox checkbox-black">
                                <input type="checkbox" name="hidePromo" value="1" <?php if($this->config->item('hidePromo') === true) echo 'checked'; ?>>
                                <label for="hidePromo"><?php echo $this->lang->line('Hide promotion'); ?></label>
                            </div>
                        </div>
                        <div class="form-group text-right m-b-0">
        					<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
        					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
        				</div>
                    </div>
                </div> <!-- End row -->
            </div> <!-- End card-box -->
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('maintenance'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure maintenance'); ?></p>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
        					<label for="maintenance"><?php echo $this->lang->line('maintenance'); ?></label>
        					<select class="form-control selectpicker show-tick" data-style="btn-white" name="maintenance">
        						<option value="1" <?php if($this->config->item('maintenance') == 1) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Active'); ?></option>
        						<option value="0" <?php if($this->config->item('maintenance') == 0) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
        					</select>
        				</div>
                    </div>
                    <div class="col-sm-12">
        				<div class="form-group">
        					<label for="maintenance_message"><?php echo $this->lang->line('maintenanceMessage'); ?></label>
        					<textarea class="form-control" rows="5" name="maintenance_message" placeholder="<?php echo $this->lang->line('maintenanceMessage'); ?>"><?php echo html_escape($this->config->item('maintenance_message')); ?></textarea>
        				</div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group text-right m-b-0">
        					<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
        					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
        				</div>
                    </div>
                </div>
            </div>
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Social Links'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure social external links'); ?></p>
                    </div>
                    <div class="col-sm-12">
        				<div class="form-group">
        					<label for="facebook"><?php echo $this->lang->line('Facebook'); ?></label>
        					<input type="url" class="form-control" name="facebook" value="<?php echo html_escape($this->config->item('facebook')); ?>" placeholder="https://www.facebook.com/your_page/" />
        				</div>
                    </div>
                    <div class="col-sm-12">
        				<div class="form-group">
        					<label for="twitter"><?php echo $this->lang->line('Twitter'); ?></label>
        					<input type="url" class="form-control" name="twitter" value="<?php echo html_escape($this->config->item('twitter')); ?>" placeholder="https://twitter.com/your_account/" />
        				</div>
                    </div>
                    <div class="col-sm-12">
        				<div class="form-group">
        					<label for="google"><?php echo $this->lang->line('Google'); ?></label>
        					<input type="url" class="form-control" name="google" value="<?php echo html_escape($this->config->item('google')); ?>" placeholder="https://plus.google.com/your_page/" />
        				</div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group text-right m-b-0">
        					<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
        					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
        				</div>
                    </div>
                </div>
            </div>
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Social Widget'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure social Facebook widget (sidebar)'); ?></p>
                    </div>
                    <div class="col-sm-6">
        				<div class="form-group">
        					<label for="facebookPageName"><?php echo $this->lang->line('Facebook Page Name'); ?></label>
        					<input type="text" class="form-control" name="facebookPageName" value="<?php echo html_escape($this->config->item('facebookPageName')); ?>" placeholder="" />
        				</div>
                    </div>
                    <div class="col-sm-6">
        				<div class="form-group">
        					<label for="facebookPageLink"><?php echo $this->lang->line('Facebook Page Link'); ?></label>
        					<input type="text" class="form-control" name="facebookPageLink" value="<?php echo html_escape($this->config->item('facebookPageLink')); ?>" placeholder="" />
        				</div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group text-right m-b-0">
        					<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
        					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
        				</div>
                    </div>
                </div>
            </div>
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Social Login'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure social login options'); ?></p>
                    </div>
                    <div class="col-sm-6">
        				<div class="form-group">
        					<label for="clientId"><?php echo $this->lang->line('Google Client ID'); ?></label> <small>(<a href="https://console.developers.google.com/" target="_blank">developers google</a>)</small>
        					<input type="text" class="form-control" name="clientId" value="<?php echo html_escape($this->config->item('clientId')); ?>" placeholder="" />
        				</div>
                    </div>
                    <div class="col-sm-6">
        				<div class="form-group">
        					<label for="clientSecret"><?php echo $this->lang->line('Google Client Secret'); ?></label> 
        					<input type="text" class="form-control" name="clientSecret" value="<?php echo html_escape($this->config->item('clientSecret')); ?>" placeholder="" />
        				</div>
                    </div>
                    <div class="col-sm-6">
        				<div class="form-group">
        					<label for="facebook_app_id"><?php echo $this->lang->line('Facebook App ID'); ?></label> <small>(<a href="https://developers.facebook.com/apps/" target="_blank">developers facebook</a>)</small>
        					<input type="text" class="form-control" name="facebook_app_id" value="<?php echo html_escape($this->config->item('facebook_app_id')); ?>" placeholder="" />
        				</div>
                    </div>
                    <div class="col-sm-6">
        				<div class="form-group">
        					<label for="facebook_app_secret"><?php echo $this->lang->line('Facebook App Secret'); ?></label>
        					<input type="text" class="form-control" name="facebook_app_secret" value="<?php echo html_escape($this->config->item('facebook_app_secret')); ?>" placeholder="" />
        				</div>
                    </div>
                    <div class="col-sm-6">
        				<div class="form-group">
        					<label for="consumerKey"><?php echo $this->lang->line('Twitter Consumer Key'); ?></label> <small>(<a href="https://apps.twitter.com/app/new" target="_blank">twitter apps</a>)</small>
        					<input type="text" class="form-control" name="consumerKey" value="<?php echo html_escape($this->config->item('consumerKey')); ?>" placeholder="" />
        				</div>
                    </div>
                    <div class="col-sm-6">
        				<div class="form-group">
        					<label for="consumerSecret"><?php echo $this->lang->line('Twitter Consumer Secret'); ?></label>
        					<input type="text" class="form-control" name="consumerSecret" value="<?php echo html_escape($this->config->item('consumerSecret')); ?>" placeholder="" />
        				</div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group text-right m-b-0">
        					<button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
        					<button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
        				</div>
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-sm-12">
            		<div class="card-box">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Mailchimp configuration'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure integration options with Mailchimp'); ?></p>
                        <div class="form-group">
        					<label for="mailchimpApi"><?php echo $this->lang->line('API key'); ?></label> <small>(<a href="https://us9.admin.mailchimp.com/account/api/" target="_blank"><?php echo $this->lang->line('Mailchimp API'); ?></a>)</small>
        					<input type="text" class="form-control" name="mailchimpApi" value="<?php echo html_escape($this->config->item('mailchimpApi')); ?>" placeholder="<?php echo $this->lang->line('API key'); ?>" />
        				</div>
                        <div class="form-group">
        					<label for="mailchimpList"><?php echo $this->lang->line('Mailchimp List'); ?></label>
        					<input type="text" class="form-control" name="mailchimpList" value="<?php echo html_escape($this->config->item('mailchimpList')); ?>" placeholder="<?php echo $this->lang->line('Mailchimp List'); ?>" />
        				</div>
                        <div class="form-group text-right m-b-0">
                            <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                            <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
            	<div class="col-sm-12">
            		<div class="card-box">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Amazon S3 configuration'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure integration options with Amazon S3'); ?></p>
                        <div class="form-group">
        					<label for="amazonApiKey"><?php echo $this->lang->line('API key'); ?></label> <small>(<a href="https://console.aws.amazon.com/iam/home?#/security_credential" target="_blank"><?php echo $this->lang->line('Amazon API'); ?></a>)</small>
        					<input type="text" class="form-control" name="amazonApiKey" value="<?php echo html_escape($this->config->item('amazonApiKey')); ?>" placeholder="<?php echo $this->lang->line('API key'); ?>" />
        				</div>
                        <div class="form-group">
        					<label for="amazonSecretKey"><?php echo $this->lang->line('Secret key'); ?></label>
        					<input type="text" class="form-control" name="amazonSecretKey" value="<?php echo html_escape($this->config->item('amazonSecretKey')); ?>" placeholder="<?php echo $this->lang->line('Secret key'); ?>" />
        				</div>
                        <div class="form-group">
        					<label for="amazonRegion"><?php echo $this->lang->line('Region'); ?></label>  <small>(<a href="https://s3.console.aws.amazon.com/s3/home" target="_blank"><?php echo $this->lang->line('Amazon bucket'); ?></a>)</small>
        					<input type="text" class="form-control" name="amazonRegion" value="<?php echo html_escape($this->config->item('amazonRegion')); ?>" placeholder="<?php echo $this->lang->line('Region'); ?>" />
        				</div>
                        <div class="form-group">
        					<label for="amazonBucket"><?php echo $this->lang->line('Bucket'); ?></label>
        					<input type="text" class="form-control" name="amazonBucket" value="<?php echo html_escape($this->config->item('amazonBucket')); ?>" placeholder="<?php echo $this->lang->line('Bucket'); ?>" />
        				</div>
                        <div class="form-group">
        					<label for="amazonCloudFront"><?php echo $this->lang->line('Domain'); ?></label>  <small>(<a href="https://console.aws.amazon.com/cloudfront/home" target="_blank"><?php echo $this->lang->line('Amazon CloudFront'); ?></a>)</small>
        					<input type="text" class="form-control" name="amazonCloudFront" value="<?php echo html_escape($this->config->item('amazonCloudFront')); ?>" placeholder="<?php echo $this->lang->line('Domain'); ?>" />
        				</div>
                        <div class="form-group">
        					<label for="amazonBrowserUpload"><?php echo $this->lang->line('Upload type'); ?></label>
        					<select class="form-control selectpicker show-tick" data-style="btn-white" name="amazonBrowserUpload">
        						<option value="0" <?php if($this->config->item('amazonBrowserUpload') == 0) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Server upload'); ?></option>
        						<option value="1" <?php if($this->config->item('amazonBrowserUpload') == 1) echo 'selected="selected"'; ?>><?php echo $this->lang->line('Browser upload'); ?></option>
        					</select>
        				</div>
                        <div class="form-group text-right m-b-0">
                            <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                            <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
    	</div> <!-- End col -->
    </div> <!-- End row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $this->lang->line('Custom CSS'); ?></h3>
                    </div>
                    <div class="panel-body p-0">
                        <textarea id="customCss" name="customCss"><?php if(isset($getCustomCss)) echo $getCustomCss; ?></textarea>
                    </div>
                </div>
                <div class="form-group text-right m-b-0">
                    <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                    <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $this->lang->line('Custom Javascript'); ?></h3>
                    </div>
                    <div class="panel-body p-0"><textarea id="customJs" name="customJs"><?php if(isset($getCustomJs)) echo $getCustomJs; ?></textarea>
                    </div>
                </div>
                <div class="form-group text-right m-b-0">
                    <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                    <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                </div>
            </div> <!-- End card-box -->
    	</div> <!-- End col -->
    </div> <!-- End row -->
</form>
