<section class="row">
    <div class="col-sm-12">
        <div style="background-image:url('<?php echo site_url('/assets/images/landing-subscribe.jpg'); ?>')">
            <!-- Demo Bar -->
            <?php if($this->config->item('demo') && !$this->session->demo_panel) { ?>
                <div id="demo-panel">
                    <ul>
                        <li class="theme-select">
                            <a href="#">Select a theme<i class="fa fa-angle-down"></i></a>
                            <ul>
                                <li><a href="<?php echo site_url('Linkswitch/switchTheme/default/'); ?>">Default <span style="background:#82b340">Include</span></a></li>
                                <li><a href="<?php echo site_url('Linkswitch/switchTheme/darktheme/'); ?>">Dark Theme <span style="background:#DE1212">Include</span></a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="links-panel">
                        <li><a href="https://codecanyon.net/item/videoplay-video-subscription-platform/20656861?ref=coffeetheme">Buy now</a></li>
                        <li><a href="<?php echo site_url('Linkswitch/switchPanel/'); ?>"><i class="fa fa-times"></i> Remove Frame</a></li>
                        <li><a href="https://codecanyon.net/item/videoplay-video-subscription-platform/20656861?ref=coffeetheme"><i class="fa fa-shopping-cart"></i></a></li>
                        <li><a href="<?php echo site_url('Linkswitch/switchPanel/'); ?>"><i class="fa fa-times"></i></a></li>
                    </ul>
                </div>
            <?php } ?>
            <div class="topbar <?php echo ($this->config->item('demo') && !$this->session->demo_panel) ? 'with-demo-panel' : ''; ?>">
                <div class="navbar navbar-default navbar-hide bg-transparent" role="navigation">
                    <div class="landing-container">
                        <!-- Button mobile view to collapse sidebar menu -->
                        <div class="pull-left">
                            <button class="button-menu-mobile open-left waves-effect waves-light">
                                <i class="md md-menu"></i>
                            </button>
                            <span class="clearfix"></span>
                        </div>
                        <!-- LOGO -->
                        <div class="topbar-left p-0">
                            <a href="<?php echo site_url(''); ?>" class="logo"><?php if(NULL!==$this->config->item('logo')) echo $this->config->item('logo'); ?></a>
                        </div>
                        <ul class="nav navbar-nav navbar-right navbar-login">
                            <li class="top-menu-item-xs"><a href="<?php echo site_url('login/') ?>"><i class="fa fa-lock"></i> <?php echo $this->lang->line('Login'); ?></a></li>
                            <li class="top-menu-item-xs"><a class="btn btn-red btn-login waves-effect waves-light" href="<?php echo site_url('login/register/') ?>"> <?php echo $this->lang->line('Sign Up'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="content-page <?php echo ($this->config->item('demo') && !$this->session->demo_panel) ? 'with-demo-panel' : ''; ?>">
                <div class="landing-title">
                    <div class="landing-container">
                        <div>
                            <h1>See what’s next.</h1>
                            <p>WATCH ANYWHERE. CANCEL ANYTIME.</p>
                        </div>
                        <div>
                            <form action="/signup" method="GET" id="formstart">
                                <input type="hidden" name="action" value="startAction">
                                <input type="hidden" name="locale" value="en-UY">
                                <a href="<?php echo site_url('login/'); ?>" class="btn btn-custom btn-large btn-red">JOIN FREE FOR A MONTH</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="darktheme">
    <div class="row">
        <div class="col-sm-12">
            <!-- Start tabs -->
            <div class="landing-tab-navigation">
                <div class="container">
                    <div class="col-sm-4 active" data-tab="1">
                        <i class="fa fa-sign-out" style="font-size: 64px; font-weight: lighter;"></i>
                        <p>No commitments<br>Cancel online at anytime</p>
                    </div>
                    <div class="col-sm-4" data-tab="2">
                        <i class="fa fa-laptop" style="font-size: 64px; font-weight: lighter;"></i>
                        <p>No commitments<br>Cancel online at anytime</p>
                    </div>
                    <div class="col-sm-4" data-tab="3">
                        <i class="fa fa-tags fa-flip-horizontal" style="font-size: 64px; font-weight: lighter;"></i>
                        <p>No commitments<br>Cancel online at anytime</p>
                    </div>
                </div>
            </div> 
            <div class="landing-tab-content-1">
                <div class="row">
                    <div class="container container-mobile header-box">
                        <div class="col-sm-6">
                            <p>If you decide VideoPlay isn't for you - no problem. No commitment. Cancel online anytime.</p>
                            <a href="<?php echo site_url('login/'); ?>" class="btn btn-custom btn-large btn-red">JOIN FREE FOR A MONTH</a>
                        </div>
                        <div class="col-sm-6">
                            <img class="img-responsive" src="https://assets.nflxext.com/ffe/siteui/acquisition/home/thisIsNetflix/modules/asset_cancelanytime_withdevice.png">
                        </div>
                    </div>
                </div>
            </div>
            <div class="landing-tab-content-2" style="display:none;">
                <div class="row">
                    <div class="container">
                        <div class="col-sm-12 header-title-box-landing">
                            <h2 class="header-title-landing inline-block">Watch TV shows and movies anytime, anywhere — personalized for you.</h2>
                            <a href="<?php echo site_url('login/'); ?>" class="btn btn-custom btn-large btn-lg btn-red pull-right">JOIN FREE FOR A MONTH</a>
                        </div>
                    </div>
                </div>
                <div class="row p-t-b-20">
                    <div class="container">
                        <div class="col-sm-4">
                            <img class="img-responsive" src="https://assets.nflxext.com/ffe/siteui/acquisition/home/thisIsNetflix/modules/asset_TV_UI.png">
                            Watch on your TV
                            Smart TVs, PlayStation, Xbox, Chromecast, Apple TV, Blu-ray players and more.
                        </div>
                        <div class="col-sm-4">
                            <img class="img-responsive" src="https://assets.nflxext.com/ffe/siteui/acquisition/home/thisIsNetflix/modules/asset_mobile_tablet_UI_2.png">
                            Watch instantly or download for later
                            Available on phone and tablet, wherever you go.
                        </div>
                        <div class="col-sm-4">
                            <img class="img-responsive" src="https://assets.nflxext.com/ffe/siteui/acquisition/home/thisIsNetflix/modules/asset_website_UI.png">
                            Use any computer
                            Watch right on VideoPlay.com.
                        </div>
                    </div>
                </div>
            </div>
            <div class="landing-tab-content-3" style="display:none;">
                <div class="row">
                    <div class="container">
                        <div class="col-sm-12 header-title-box-landing">
                            <h2 class="header-title-landing inline-block ">Choose one plan and watch everything on VideoPlay.</h2>
                            <a href="<?php echo site_url('login/'); ?>" class="btn btn-custom btn-large btn-lg btn-red pull-right">JOIN FREE FOR A MONTH</a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="container">
                        <div class="col-sm-12">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th></th>
                                        <th>Basic</th>
                                        <th>Standard</th>
                                        <th>Premium</th>
                                    </tr>
                                    <tr>
                                        <td>Monthly price after free month ends on 12/5/17</td>
                                        <td>USD8.99</td>
                                        <td>USD10.99</td>
                                        <td>USD13.99</td>
                                    </tr>
                                    
                                    <tr class="active">
                                        <td>HD available</td>
                                        <td>
                                            <span class="ion-close-round"></span>
                                        </td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Screens you can watch on at the same time</td>
                                        <td>Unlimited</td>
                                        <td>Unlimited</td>
                                        <td>Unlimited</td>
                                    </tr>
                                    
                                    <tr class="active">
                                        <td>Watch on your laptop, TV, phone and tablet</td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>Unlimited movies and TV shows</td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                    </tr>
                                    
                                    <tr class="active">
                                        <td>Cancel anytime</td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>First month free</td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                        <td>
                                            <span class="ion-checkmark-round"></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End tabs -->
        </div>
    </div>
</section>
<footer class="footer navbar-default">
    <div class="container">
        <div class="row">
            <div class="col-sm-7 col-md-9 footer-row">
                <span class="copyright"><?php echo $this->lang->line('Copyright'); ?> © 2017 <?php if(NULL!==$this->config->item('sitename')) echo $this->config->item('sitename'); ?></span>
            </div>
            <div class="col-sm-5 col-md-3 footer-row">
                <?php if($this->config->item('socialIconsFooter')) { ?>
                    <ul class="social-icons pull-right">
                        <?php if($this->config->item('demo')) { ?>
                        <li><a href="http://www.coffeetheme.com"><i class="fa fa-coffee"></i></a></li>
                        <?php } ?>
                        <?php if($this->config->item('facebook')) { ?>
                        <li><a href="<?php echo $this->config->item('facebook'); ?>"><i class="fa fa-facebook"></i></a></li>
                        <?php } ?>
                        <?php if($this->config->item('twitter')) { ?>
                        <li><a href="<?php echo $this->config->item('twitter'); ?>"><i class="fa fa-twitter"></i></a></li>
                        <?php } ?>
                        <?php if($this->config->item('google')) { ?>
                        <li><a href="<?php echo $this->config->item('google'); ?>"><i class="fa fa-google-plus"></i></a></li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </div>
        </div> <!-- end row -->
    </div> <!-- end container -->
</footer>

<script>
    window.onload = function() {
        $('.landing-tab-navigation > div > div').click(function () {
            $('.landing-tab-navigation > div > div').removeClass('active');
            $(this).addClass('active');
            var tab = $(this).data('tab');
            $('[class*="landing-tab-content"]').hide();
            $('.landing-tab-content-' + tab).show();
        })
    };
</script>