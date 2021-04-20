<form class="form" role="form" method="post" action="<?php echo current_url(); ?>">
    <div class="row">
    	<div class="col-sm-12">
    		<?php if(isset($msg)) echo $msg; ?>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Header configuration'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customize the header.'); ?></p>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
            						<label for="headerMenu1"><?php echo $this->lang->line('Main menu'); ?></label>
            						<select class="form-control  selectpicker show-tick" data-style="btn-white" name="headerMenu1">
                                        <?php if(isset($getHeaderMenu1)) echo $getHeaderMenu1; ?>
            						</select>
            					</div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="headerMenu2"><?php echo $this->lang->line('Second menu'); ?></label>
                                    <select class="form-control  selectpicker show-tick" data-style="btn-white" name="headerMenu2">
                                        <?php if(isset($getHeaderMenu2)) echo $getHeaderMenu2; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right m-b-0">
                            <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                            <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                        </div>
                    </div> <!-- End card-box -->
                </div> <!-- End col -->
            </div> <!-- End row -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Footer configuration'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customize the footer.'); ?></p>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="backgroundLogin"><?php echo $this->lang->line('Menu 1'); ?></label>
                                    <input type="text" name="menu1Title" class="form-control" placeholder="Menu Title" value="<?php echo $this->config->item('footerMenu1Title'); ?>">
                                </div>
                                <div class="form-group">
            						<select class="form-control selectpicker show-tick" data-style="btn-white" name="menu1">
                                        <?php if(isset($getMenu1)) echo $getMenu1; ?>
            						</select>
            					</div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="backgroundLogin"><?php echo $this->lang->line('Menu 2'); ?></label>
                                    <input type="text" name="menu2Title" class="form-control" placeholder="Menu Title" value="<?php echo $this->config->item('footerMenu2Title'); ?>">
                                </div>
                                <div class="form-group">
                                    <select class="form-control selectpicker show-tick" data-style="btn-white" name="menu2">
                                        <?php if(isset($getMenu2)) echo $getMenu2; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="backgroundLogin"><?php echo $this->lang->line('Menu 3'); ?></label>
                                    <input type="text" name="menu3Title" class="form-control" placeholder="Menu Title" value="<?php echo $this->config->item('footerMenu3Title'); ?>">
                                </div>
                                <div class="form-group">
                                    <select class="form-control selectpicker show-tick" data-style="btn-white" name="menu3">
                                        <?php if(isset($getMenu3)) echo $getMenu3; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                					<label for="footer_message"><?php echo $this->lang->line('Footer message'); ?></label>
                					<textarea class="form-control" rows="5" name="footer_message"><?php echo html_escape($this->config->item('footer_message')); ?></textarea>
                				</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="socialIconsWidget"><?php echo $this->lang->line('Social icons'); ?></label> <small>(<?php echo $this->lang->line('widget area'); ?>)</small>
                                    <select class="form-control selectpicker show-tick" data-style="btn-white" name="socialIconsWidget">
                                        <option value="1" <?php if($this->config->item('socialIconsWidget') === '1') echo 'selected'; ?>><?php echo $this->lang->line('Active'); ?></option>
                                        <option value="0" <?php if($this->config->item('socialIconsWidget') === '0') echo 'selected'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="socialIconsFooter"><?php echo $this->lang->line('Social icons'); ?></label> <small>(<?php echo $this->lang->line('bottom of site'); ?>)</small>
                                    <select class="form-control selectpicker show-tick" data-style="btn-white" name="socialIconsFooter">
                                        <option value="1" <?php if($this->config->item('socialIconsFooter') === '1') echo 'selected'; ?>><?php echo $this->lang->line('Active'); ?></option>
                                        <option value="0" <?php if($this->config->item('socialIconsFooter') === '0') echo 'selected'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group text-right m-b-0">
                            <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                            <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                        </div>
                    </div> <!-- End card-box -->
                </div> <!-- End col -->
            </div> <!-- End row -->
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Login configuration'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customize the login page'); ?></p>
                    </div> <!-- End col -->
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="facebookLogin"><?php echo $this->lang->line('Facebook'); ?></label>
                            <select class="form-control selectpicker show-tick" data-style="btn-white" name="facebookLogin">
                                <option value="1" <?php if($this->config->item('facebookLogin') === '1') echo 'selected'; ?>><?php echo $this->lang->line('Active'); ?></option>
                                <option value="0" <?php if($this->config->item('facebookLogin') === '0') echo 'selected'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="googleLogin"><?php echo $this->lang->line('Google'); ?></label>
                            <select class="form-control selectpicker show-tick" data-style="btn-white" name="googleLogin">
                                <option value="1" <?php if($this->config->item('googleLogin') === '1') echo 'selected'; ?>><?php echo $this->lang->line('Active'); ?></option>
                                <option value="0" <?php if($this->config->item('googleLogin') === '0') echo 'selected'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="twitterLogin"><?php echo $this->lang->line('Twitter'); ?></label>
                            <select class="form-control selectpicker show-tick" data-style="btn-white" name="twitterLogin">
                                <option value="1" <?php if($this->config->item('twitterLogin') === '1') echo 'selected'; ?>><?php echo $this->lang->line('Active'); ?></option>
                                <option value="0" <?php if($this->config->item('twitterLogin') === '0') echo 'selected'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="backgroundLogin"><?php echo $this->lang->line('Background image'); ?></label>
                            <input type="text" name="backgroundLogin" class="form-control" value="<?php echo $this->config->item('backgroundLogin'); ?>">
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
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Home page configuration'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Choose the video categories to display on the homepage'); ?></p>
                    </div> <!-- End col -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="homeCategory1"><?php echo $this->lang->line('Categorie 1'); ?></label>
                            <select class="form-control selectpicker show-tick" data-style="btn-white" name="homeCategory1">
                                <?php if(isset($getCategories1)) echo $getCategories1; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="homeCategory2"><?php echo $this->lang->line('Categorie 2'); ?></label>
                            <select class="form-control selectpicker show-tick" data-style="btn-white" name="homeCategory2">
                                <?php if(isset($getCategories2)) echo $getCategories2; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="homeCategory3"><?php echo $this->lang->line('Categorie 3'); ?></label>
                            <select class="form-control selectpicker show-tick" data-style="btn-white" name="homeCategory3">
                                <?php if(isset($getCategories3)) echo $getCategories3; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="homeCategory4"><?php echo $this->lang->line('Categorie 4'); ?></label>
                            <select class="form-control selectpicker show-tick" data-style="btn-white" name="homeCategory4">
                                <?php if(isset($getCategories4)) echo $getCategories4; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
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
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Contact page configuration'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customize the contact page'); ?></p>
                    </div> <!-- End col -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="contactBox1Title"><?php echo $this->lang->line('Box 1 title'); ?></label>
                            <input type="text" name="contactBox1Title" class="form-control" value="<?php echo $this->config->item('contactBox1Title'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="contactBox1"><?php echo $this->lang->line('Box 1 content'); ?></label>
                            <textarea class="form-control" rows="5" name="contactBox1"><?php echo html_escape($this->config->item('contactBox1')); ?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="contactBox2Title"><?php echo $this->lang->line('Box 2 title'); ?></label>
                            <input type="text" name="contactBox2Title" class="form-control" value="<?php echo $this->config->item('contactBox2Title'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="contactBox2"><?php echo $this->lang->line('Box 2 content'); ?></label>
                            <textarea class="form-control" rows="5" name="contactBox2"><?php echo html_escape($this->config->item('contactBox2')); ?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group text-right m-b-0">
                            <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                            <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                        </div>
                    </div>
                </div> <!-- End row -->
            </div> <!-- End card-box -->
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Video player configuration'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customize the video player'); ?></p>
                        <label for="vpl-color"><?php echo $this->lang->line('Color customization'); ?></label>
                        <div class="input-group m-t-10">
                            <input type="text" name="vpl-color" class="form-control" value="<?php echo $this->config->item('vplColor'); ?>">
                            <span class="input-group-btn"><button type="submit" class="btn waves-effect waves-light btn-inverse"><?php echo $this->lang->line('Submit'); ?></button></span>
                        </div>
                    </div> <!-- End col -->
                </div> <!-- End row -->
            </div> <!-- End card-box -->
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-6">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Slider configuration'); ?></b></h4>
                        <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customize the home sliders'); ?></p>
                        <label for="nbSlider"><?php echo $this->lang->line('Number of sliders'); ?></label>
                        <div class="input-group m-t-10">
                            <input type="number" min="0" max="6" name="nbSlider" class="form-control" value="<?php echo $this->config->item('nbSlider'); ?>">
                            <span class="input-group-btn"><button type="submit" class="btn waves-effect waves-light btn-inverse"><?php echo $this->lang->line('Submit'); ?></button></span>
                        </div>
                    </div> <!-- End col -->
                </div> <!-- End row -->
            </div> <!-- End card-box -->
            <?php if ($this->config->item('nbSlider') >= 1) { ?>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Slider 1'); ?></b></h4>
                            <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure your slider.'); ?></p>
                            <div class="form-group">
                                <label for="title1"><?php echo $this->lang->line('Title'); ?></label>
                                <input type="text" class="form-control" name="title1" value="<?php if(null !== ($this->config->item('title1'))) echo $this->config->item('title1'); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="paragraph1"><?php echo $this->lang->line('Paragraph'); ?></label>
                                <input type="text" class="form-control" name="paragraph1" value="<?php if(null !== ($this->config->item('paragraph1'))) echo $this->config->item('paragraph1'); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="button1"><?php echo $this->lang->line('Button label'); ?></label>
                                <input type="text" class="form-control" name="button1" value="<?php if(null !== ($this->config->item('button1'))) echo $this->config->item('button1'); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="button1link"><?php echo $this->lang->line('Button link (visitor)'); ?></label>
                                <input type="text" class="form-control" name="button1link" value="<?php if(null !== ($this->config->item('button1link'))) echo $this->config->item('button1link'); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="image1"><?php echo $this->lang->line('Image'); ?></label>
                                <input type="text" class="form-control" name="image1" value="<?php if(null !== ($this->config->item('image1'))) echo $this->config->item('image1'); ?>" />
                            </div>
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                                <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                            </div>
                        </div>
                    </div> <!-- End col -->
                    <?php if ($this->config->item('nbSlider') >= 2) { ?>
                        <div class="col-sm-6">
                            <div class="card-box">
                                <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Slider 2'); ?></b></h4>
                                <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure your slider.'); ?></p>
                                <div class="form-group">
                                    <label for="title2"><?php echo $this->lang->line('Title'); ?></label>
                                    <input type="text" class="form-control" name="title2" value="<?php if(null !== ($this->config->item('title2'))) echo $this->config->item('title2'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="paragraph2"><?php echo $this->lang->line('Paragraph'); ?></label>
                                    <input type="text" class="form-control" name="paragraph2" value="<?php if(null !== ($this->config->item('paragraph2'))) echo $this->config->item('paragraph2'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="button2"><?php echo $this->lang->line('Button label'); ?></label>
                                    <input type="text" class="form-control" name="button2" value="<?php if(null !== ($this->config->item('button2'))) echo $this->config->item('button2'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="button2link"><?php echo $this->lang->line('Button link (visitor)'); ?></label>
                                    <input type="text" class="form-control" name="button2link" value="<?php if(null !== ($this->config->item('button2link'))) echo $this->config->item('button2link'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="image2"><?php echo $this->lang->line('Image'); ?></label>
                                    <input type="text" class="form-control" name="image2" value="<?php if(null !== ($this->config->item('image2'))) echo $this->config->item('image2'); ?>" />
                                </div>
                                <div class="form-group text-right m-b-0">
                                    <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                                    <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                                </div>
                            </div>
                        </div> <!-- End col -->
                    </div> <!-- End row -->
                    <?php } ?>
            <?php } ?>
            <?php if ($this->config->item('nbSlider') >= 3) { ?>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Slider 3'); ?></b></h4>
                            <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure your slider.'); ?></p>
                            <div class="form-group">
                                <label for="title3"><?php echo $this->lang->line('Title'); ?></label>
                                <input type="text" class="form-control" name="title3" value="<?php if(null !== ($this->config->item('title3'))) echo $this->config->item('title3'); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="paragraph3"><?php echo $this->lang->line('Paragraph'); ?></label>
                                <input type="text" class="form-control" name="paragraph3" value="<?php if(null !== ($this->config->item('paragraph3'))) echo $this->config->item('paragraph3'); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="button3"><?php echo $this->lang->line('Button label'); ?></label>
                                <input type="text" class="form-control" name="button3" value="<?php if(null !== ($this->config->item('button3'))) echo $this->config->item('button3'); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="button3link"><?php echo $this->lang->line('Button link (visitor)'); ?></label>
                                <input type="text" class="form-control" name="button3link" value="<?php if(null !== ($this->config->item('button3link'))) echo $this->config->item('button3link'); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="image3"><?php echo $this->lang->line('Image'); ?></label>
                                <input type="text" class="form-control" name="image3" value="<?php if(null !== ($this->config->item('image3'))) echo $this->config->item('image3'); ?>" />
                            </div>
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                                <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                            </div>
                        </div>
                    </div> <!-- End col -->
                    <?php if ($this->config->item('nbSlider') >= 4) { ?>
                        <div class="col-sm-6">
                            <div class="card-box">
                                <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Slider 4'); ?></b></h4>
                                <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure your slider.'); ?></p>
                                <div class="form-group">
                                    <label for="title4"><?php echo $this->lang->line('Title'); ?></label>
                                    <input type="text" class="form-control" name="title4" value="<?php if(null !== ($this->config->item('title4'))) echo $this->config->item('title4'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="paragraph4"><?php echo $this->lang->line('Paragraph'); ?></label>
                                    <input type="text" class="form-control" name="paragraph4" value="<?php if(null !== ($this->config->item('paragraph4'))) echo $this->config->item('paragraph4'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="button4"><?php echo $this->lang->line('Button label'); ?></label>
                                    <input type="text" class="form-control" name="button4" value="<?php if(null !== ($this->config->item('button4'))) echo $this->config->item('button4'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="button4link"><?php echo $this->lang->line('Button link (visitor)'); ?></label>
                                    <input type="text" class="form-control" name="button4link" value="<?php if(null !== ($this->config->item('button4link'))) echo $this->config->item('button4link'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="image4"><?php echo $this->lang->line('Image'); ?></label>
                                    <input type="text" class="form-control" name="image4" value="<?php if(null !== ($this->config->item('image4'))) echo $this->config->item('image4'); ?>" />
                                </div>
                                <div class="form-group text-right m-b-0">
                                    <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                                    <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                                </div>
                            </div>
                        </div> <!-- End col -->
                    </div> <!-- End row -->
                    <?php } ?>
            <?php } ?>
            <?php if ($this->config->item('nbSlider') >= 5) { ?>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-box">
                            <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Slider 5'); ?></b></h4>
                            <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure your slider.'); ?></p>
                            <div class="form-group">
                                <label for="title5"><?php echo $this->lang->line('Title'); ?></label>
                                <input type="text" class="form-control" name="title5" value="<?php if(null !== ($this->config->item('title5'))) echo $this->config->item('title5'); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="paragraph5"><?php echo $this->lang->line('Paragraph'); ?></label>
                                <input type="text" class="form-control" name="paragraph5" value="<?php if(null !== ($this->config->item('paragraph5'))) echo $this->config->item('paragraph5'); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="button5"><?php echo $this->lang->line('Button label'); ?></label>
                                <input type="text" class="form-control" name="button5" value="<?php if(null !== ($this->config->item('button5'))) echo $this->config->item('button5'); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="button5link"><?php echo $this->lang->line('Button link (visitor)'); ?></label>
                                <input type="text" class="form-control" name="button5link" value="<?php if(null !== ($this->config->item('button5link'))) echo $this->config->item('button5link'); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="image5"><?php echo $this->lang->line('Image'); ?></label>
                                <input type="text" class="form-control" name="image5" value="<?php if(null !== ($this->config->item('image5'))) echo $this->config->item('image5'); ?>" />
                            </div>
                            <div class="form-group text-right m-b-0">
                                <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                                <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                            </div>
                        </div>
                    </div> <!-- End col -->
                    <?php if ($this->config->item('nbSlider') >= 6) { ?>
                        <div class="col-sm-6">
                            <div class="card-box">
                                <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Slider 6'); ?></b></h4>
                                <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure your slider.'); ?></p>
                                <div class="form-group">
                                    <label for="title6"><?php echo $this->lang->line('Title'); ?></label>
                                    <input type="text" class="form-control" name="title6" value="<?php if(null !== ($this->config->item('title6'))) echo $this->config->item('title6'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="paragraph6"><?php echo $this->lang->line('Paragraph'); ?></label>
                                    <input type="text" class="form-control" name="paragraph6" value="<?php if(null !== ($this->config->item('paragraph6'))) echo $this->config->item('paragraph6'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="button6"><?php echo $this->lang->line('Button label'); ?></label>
                                    <input type="text" class="form-control" name="button6" value="<?php if(null !== ($this->config->item('button6'))) echo $this->config->item('button6'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="button6link"><?php echo $this->lang->line('Button link (visitor)'); ?></label>
                                    <input type="text" class="form-control" name="button6link" value="<?php if(null !== ($this->config->item('button6link'))) echo $this->config->item('button6link'); ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="image6"><?php echo $this->lang->line('Image'); ?></label>
                                    <input type="text" class="form-control" name="image6" value="<?php if(null !== ($this->config->item('image6'))) echo $this->config->item('image6'); ?>" />
                                </div>
                                <div class="form-group text-right m-b-0">
                                    <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                                    <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                                </div>
                            </div>
                        </div> <!-- End col -->
                    <?php } ?>
                </div> <!-- End row -->
            <?php } ?>
    	</div> <!-- End col -->
    </div> <!-- End row -->
</form>
