<section>
    <div class="slider-full-width">
        <div id="owl-slider" class="owl-carousel">
            <?php if ($this->config->item('nbSlider') >= 1) { ?>
                <div class="item" style="background: #444444 url(<?php echo (!empty($this->config->item('image1'))) ? $this->config->item('image1') : '/assets/images/polygon.png'; ?>) center center;">
                    <div class="slider-content <?php if(!empty($this->config->item('image1'))) echo 'dark-background'; ?>">
                        <h1><?php if(null !== ($this->config->item('title1'))) echo $this->config->item('title1'); ?></h1>
                        <p><?php if(null !== ($this->config->item('paragraph1'))) echo $this->config->item('paragraph1'); ?></p>
                        <a class="btn btn-green waves-effect waves-light btn-slider m-t-20" href="<?php echo (!isset($this->session->url)) ? $this->config->item('button1link') : site_url('user/subscription/'.$this->session->url.'/'); ?>"><?php if(null !== ($this->config->item('button1'))) echo $this->config->item('button1'); ?></a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($this->config->item('nbSlider') >= 2) { ?>
                <div class="item" style="background: #444444 url(<?php echo (!empty($this->config->item('image2'))) ? $this->config->item('image2') : '/assets/images/polygon.png'; ?>) center center;">
                    <div class="slider-content <?php if(!empty($this->config->item('image2'))) echo 'dark-background'; ?>">
                        <h1><?php if(null !== ($this->config->item('title2'))) echo $this->config->item('title2'); ?></h1>
                        <p><?php if(null !== ($this->config->item('paragraph2'))) echo $this->config->item('paragraph2'); ?></p>
                        <a class="btn btn-green waves-effect waves-light btn-slider m-t-20" href="<?php echo (!isset($this->session->url)) ? $this->config->item('button2link') : site_url('user/subscription/'.$this->session->url.'/'); ?>"><?php if(null !== ($this->config->item('button2'))) echo $this->config->item('button2'); ?></a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($this->config->item('nbSlider') >= 3) { ?>
                <div class="item" style="background: #444444 url(<?php echo (!empty($this->config->item('image3'))) ? $this->config->item('image3') : '/assets/images/polygon.png'; ?>) center center;">
                    <div class="slider-content <?php if(!empty($this->config->item('image3'))) echo 'dark-background'; ?>">
                        <h1><?php if(null !== ($this->config->item('title3'))) echo $this->config->item('title3'); ?></h1>
                        <p><?php if(null !== ($this->config->item('paragraph3'))) echo $this->config->item('paragraph3'); ?></p>
                        <a class="btn btn-green waves-effect waves-light btn-slider m-t-20" href="<?php echo (!isset($this->session->url)) ? $this->config->item('button3link') : site_url('user/subscription/'.$this->session->url.'/'); ?>"><?php if(null !== ($this->config->item('button3'))) echo $this->config->item('button3'); ?></a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($this->config->item('nbSlider') >= 4) { ?>
                <div class="item" style="background: #444444 url(<?php echo (!empty($this->config->item('image4'))) ? $this->config->item('image4') : '/assets/images/polygon.png'; ?>) center center;">
                    <div class="slider-content <?php if(!empty($this->config->item('image4'))) echo 'dark-background'; ?>">
                        <h1><?php if(null !== ($this->config->item('title4'))) echo $this->config->item('title4'); ?></h1>
                        <p><?php if(null !== ($this->config->item('paragraph4'))) echo $this->config->item('paragraph4'); ?></p>
                        <a class="btn btn-green waves-effect waves-light btn-slider m-t-20" href="<?php echo (!isset($this->session->url)) ? $this->config->item('button4link') : site_url('user/subscription/'.$this->session->url.'/'); ?>"><?php if(null !== ($this->config->item('button4'))) echo $this->config->item('button4'); ?></a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($this->config->item('nbSlider') >= 5) { ?>
                <div class="item" style="background: #444444 url(<?php echo (!empty($this->config->item('image5'))) ? $this->config->item('image5') : '/assets/images/polygon.png'; ?>) center center;">
                    <div class="slider-content <?php if(!empty($this->config->item('image5'))) echo 'dark-background'; ?>">
                        <h1><?php if(null !== ($this->config->item('title5'))) echo $this->config->item('title5'); ?></h1>
                        <p><?php if(null !== ($this->config->item('paragraph5'))) echo $this->config->item('paragraph5'); ?></p>
                        <a class="btn btn-green waves-effect waves-light btn-slider m-t-20" href="<?php echo (!isset($this->session->url)) ? $this->config->item('button5link') : site_url('user/subscription/'.$this->session->url.'/'); ?>"><?php if(null !== ($this->config->item('button5'))) echo $this->config->item('button5'); ?></a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($this->config->item('nbSlider') >= 6) { ?>
                <div class="item" style="background: #444444 url(<?php echo (!empty($this->config->item('image6'))) ? $this->config->item('image6') : '/assets/images/polygon.png'; ?>) center center;">
                    <div class="slider-content <?php if(!empty($this->config->item('image6'))) echo 'dark-background'; ?>">
                        <h1><?php if(null !== ($this->config->item('title6'))) echo $this->config->item('title6'); ?></h1>
                        <p><?php if(null !== ($this->config->item('paragraph6'))) echo $this->config->item('paragraph6'); ?></p>
                        <a class="btn btn-green waves-effect waves-light btn-slider m-t-20" href="<?php echo (!isset($this->session->url)) ? $this->config->item('button6link') : site_url('user/subscription/'.$this->session->url.'/'); ?>"><?php if(null !== ($this->config->item('button6'))) echo $this->config->item('button6'); ?></a>
                    </div>
                </div>
            <?php } ?>
        </div> <!-- end slider -->
    </div>
    <div class="container">
        <div class="row home-content">
            <div class="col-sm-12">
                <h2 class="container-mobile"><?php echo $this->lang->line('mostPopularVideos') ?></h2>
                <div class="owl-carousel owl-theme owl-multi">
                    <?php if(isset($getPopularVideos)) echo $getPopularVideos; ?>
                </div>
                <h2 class="container-mobile"><?php echo $this->lang->line('recentVideos') ?></h2>
                <div class="owl-carousel owl-theme owl-multi">
                    <?php if(isset($getNewsVideos)) echo $getNewsVideos; ?>
                </div>
                <h2 class="container-mobile"><?php echo $this->lang->line('favoriteVideos') ?></h2>
                <div class="owl-carousel owl-theme owl-multi">
                    <?php if(isset($getRatedVideos)) echo $getRatedVideos; ?>
                </div>
                <h2 class="container-mobile"><?php echo $this->lang->line('ratedVideos') ?></h2>
                <div class="owl-carousel owl-theme owl-multi">
                    <?php if(isset($getTitleVideo)) echo $getTitleVideo; ?>
                </div>
                <?php if ($this->config->item('homeCategory1') !== 'false') { ?>
                    <h2 class="container-mobile"><?php if(isset($getCategories1Title->title)) echo $getCategories1Title->title; ?></h2>
                    <div class="owl-carousel owl-theme owl-multi">
                        <?php if(isset($getCategories1)) echo $getCategories1; ?>
                    </div>
                <?php } ?>
                <?php if ($this->config->item('homeCategory2') !== 'false') { ?>
                    <h2 class="container-mobile"><?php if(isset($getCategories2Title->title)) echo $getCategories2Title->title; ?></h2>
                    <div class="owl-carousel owl-theme owl-multi">
                        <?php if(isset($getCategories2)) echo $getCategories2; ?>
                    </div>
                <?php } ?>
                <?php if ($this->config->item('homeCategory3') !== 'false') { ?>
                    <h2 class="container-mobile"><?php if(isset($getCategories3Title->title)) echo $getCategories3Title->title; ?></h2>
                    <div class="owl-carousel owl-theme owl-multi">
                        <?php if(isset($getCategories3)) echo $getCategories3; ?>
                    </div>
                <?php } ?>
                <?php if ($this->config->item('homeCategory4') !== 'false') { ?>
                    <h2 class="container-mobile"><?php if(isset($getCategories4Title->title)) echo $getCategories4Title->title; ?></h2>
                    <div class="owl-carousel owl-theme owl-multi">
                        <?php if(isset($getCategories4)) echo $getCategories4; ?>
                    </div>
                <?php } ?>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div>
</section> <!-- end container -->
