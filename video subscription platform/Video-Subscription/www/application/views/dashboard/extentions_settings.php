<div class="row">
    <div class="col-sm-12">
    <?php 
        if($this->session->userdata('message')) {
            echo $this->session->userdata('message'); 
		    $this->session->unset_userdata('message');
        } 
        ?>
        <div class="row">
            <form class="form" role="form" method="post" action="/settings/extensions/">
                <div class="col-sm-3">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('PayPal Express Checkout'); ?></b></h4>
                        <p class="text-muted p-t-b-10 font-14"><?php echo $this->lang->line('Add PayPal Express Checkout as a payment method'); ?></p>
                        <div class="form-group">
                            <?php if (!$this->config->item('paypalSerialNumber')) { ?>
                                <a href="#" class="btn btn-inverse waves-effect waves-light toggle-action m-b-10" target="_blank">Enter a serial number</a>
                                <a href="https://www.coffeetheme.com/order?edd_action=add_to_cart&amp;download_id=5796" class="btn btn-default waves-effect waves-light m-b-10" target="_blank">Get this extension</a>
                            <?php } else { ?> 
                                <a href="#" class="btn btn-default waves-effect waves-light m-b-10">Extension activated</a>
                                <a href="#" class="btn btn-inverse waves-effect waves-light toggle-action m-b-10" target="_blank">Change</a>
                            <?php } ?> 
                        </div> 
                        <div style="display:none;">
                            <div class="form-group">
                                <label for="paypal-serial-number"><?php echo $this->lang->line('Serial number'); ?></label>
                                <input type="text" class="form-control" name="paypal-checkout-serial-number" placeholder="" value="<?php echo $this->config->item('paypalSerialNumber'); ?>">
                            </div>
                            <div class="form-group m-b-0">
                                <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                                <button type="button" class="btn btn-default waves-effect waves-light m-l-5 toggle-action"><?php echo $this->lang->line('cancel'); ?></button>
                            </div>
                        </div>                      
                    </div>
                </div>
            </form>
            <form class="form" role="form" method="post" action="/settings/extensions/">
                <div class="col-sm-3">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('PayPal Pro & Recurring Payment'); ?></b></h4>
						<p class="text-muted p-t-b-10 font-14"><?php echo $this->lang->line('Add PayPal Pro as a payment method'); ?></p>
                        <div class="form-group">
                            <?php if (!$this->config->item('paypalProSerialNumber')) { ?>
                                <a href="#" class="btn btn-inverse waves-effect waves-light toggle-action m-b-10" target="_blank">Enter a serial number</a>
                                <a href="https://www.coffeetheme.com/order?edd_action=add_to_cart&amp;download_id=5935" class="btn btn-default waves-effect waves-light m-b-10" target="_blank">Get this extension</a>
                            <?php } else { ?> 
                                <a href="#" class="btn btn-default waves-effect waves-light m-b-10">Extension activated</a>
                                <a href="#" class="btn btn-inverse waves-effect waves-light toggle-action m-b-10" target="_blank">Change</a>
                            <?php } ?> 
						</div> 
                        <div style="display:none;">
                            <div class="form-group">
                                <label for="paypal-serial-number"><?php echo $this->lang->line('Serial number'); ?></label>
                                <input type="text" class="form-control" name="paypal-pro-serial-number" placeholder="" value="<?php echo $this->config->item('paypalProSerialNumber'); ?>">
                            </div>
                            <div class="form-group m-b-0">
                                <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                                <button type="button" class="btn btn-default waves-effect waves-light m-l-5 toggle-action"><?php echo $this->lang->line('cancel'); ?></button>
                            </div>
                        </div>                      
                    </div>
                </div>
            </form>
    	</div> <!-- End col -->
    </div> <!-- End row -->
</form>
<script>
    window.onload = function() {
        $('a.toggle-action').click(function(e){
            e.preventDefault();
            $(this).parent().next().toggle();
            $(this).parent().toggle();
        });
        $('button.toggle-action').click(function(e){
            e.preventDefault();
            $(this).parent().parent().toggle();
            $(this).parent().parent().prev().toggle();
        });
    };
</script>