<form method="post" action="<?php echo current_url().'/'; ?>" role="form">
    
    <?php if(isset($msg)) echo $msg; ?>
    
    <div class="card-box">
        <div class="row">

            <div class="col-sm-12">
                <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Subscription page'); ?></b></h4>
                <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customize the subscription page'); ?></p>
            </div> <!-- End col -->

            <div class="col-sm-12">
                <div class="form-group">
                    <label for="contentText"><?php echo $this->lang->line('Text'); ?></label>
                    <textarea class="form-control cnt2" rows="5" name="contentText"><?php echo html_escape($this->config->item('contentText')); ?></textarea>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="paymentMethod"><?php echo $this->lang->line('Payment method'); ?></label>
                    <select class="form-control selectpicker show-tick" data-style="btn-white" name="paymentMethod">
                        <option value="Stripe" <?php if($this->config->item('paymentMethod') === 'Stripe') echo 'selected'; ?>>Stripe</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label for="hasSidebar"><?php echo $this->lang->line('Sidebar'); ?></label>
                    <select class="form-control selectpicker show-tick" data-style="btn-white" name="hasSidebar">
                        <option value="1" <?php if($this->config->item('hasSidebar') === '1') echo 'selected'; ?>><?php echo $this->lang->line('Active'); ?></option>
                        <option value="0" <?php if($this->config->item('hasSidebar') === '0') echo 'selected'; ?>><?php echo $this->lang->line('Inactive'); ?></option>
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

    <!-- Stripe Payment -->
    <div id="Stripe" style="<?php echo ($this->config->item('paymentMethod') !== 'Stripe') ? 'display:none;' : ''; ?>">
        <div class="row">

            <div class="col-sm-12">
                <div class="card-box">
                    <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Stripe configuration'); ?></b></h4>
                    <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Configure the Stripe API settings'); ?></p>
                    <div class="form-group">
                        <label for="publishablekey"><?php echo $this->lang->line('Publishable key'); ?></label> <small>(<a href="https://dashboard.stripe.com/account/apikeys" target="_blank"><?php echo $this->lang->line('API keys'); ?></a>)</small>
                        <input type="text" class="form-control" name="publishablekey" value="<?php echo html_escape($this->config->item('publishablekey')); ?>" placeholder="<?php echo $this->lang->line('Publishable key'); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="secretkey"><?php echo $this->lang->line('Secret key'); ?></label>
                        <input type="text" class="form-control" name="secretkey" value="<?php echo html_escape($this->config->item('secretkey')); ?>" placeholder="<?php echo $this->lang->line('Secret key'); ?>" />
                    </div>
                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                        <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">

            <div class="col-sm-4 subscriptionsActive">
                <div class="card-box">
                    
                    <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Stripe subscription'); ?></b></h4>
                    <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customise your Stripe subscription'); ?></p>
                    
                    <div class="form-group">
                        <label for="plan"><?php echo $this->lang->line('Plan'); ?></label> <small>(<a href="https://dashboard.stripe.com/test/plans" target="_blank"><?php echo $this->lang->line('Plan'); ?></a>)</small>
                        <input type="text" class="form-control" name="plan" value="<?php echo html_escape($this->config->item('plan')); ?>" placeholder="<?php echo $this->lang->line('Plan'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="planTitle"><?php echo $this->lang->line('Form Title'); ?></label>
                        <input type="text" class="form-control" name="planTitle" value="<?php echo html_escape($this->config->item('planTitle')); ?>" placeholder="<?php echo $this->lang->line('Form Title'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="planDescription"><?php echo $this->lang->line('Form Description'); ?></label>
                        <input type="text" class="form-control" name="planDescription" value="<?php echo html_escape($this->config->item('planDescription')); ?>" placeholder="<?php echo $this->lang->line('Form Description'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="planPrice"><?php echo $this->lang->line('Price'); ?></label>
                        <input type="number" step="0.01" class="form-control" name="planPrice" value="<?php echo html_escape($this->config->item('planPrice')); ?>" placeholder="<?php echo $this->lang->line('Price'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="planCurrency"><?php echo $this->lang->line('Currency'); ?></label> <small>(<a href="https://stripe.com/docs/currencies#charge-currencies" target="_blank"><?php echo $this->lang->line('Currencies'); ?></a>)</small>
                        <input type="text" class="form-control" name="planCurrency" value="<?php echo html_escape($this->config->item('planCurrency')); ?>" placeholder="USD" />
                    </div>

                    <div class="form-group">
                        <label for="planTrial"><?php echo $this->lang->line('Trial Days'); ?></label>
                        <input type="number" class="form-control" name="planTrial" value="<?php echo html_escape($this->config->item('planTrial')); ?>" placeholder="<?php echo $this->lang->line('Trial Days'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="planItemList"><?php echo $this->lang->line('Detailed List'); ?></label>
                        <textarea type="text" class="form-control cnt3" name="planItemList"><?php echo $this->config->item('planItemList'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="planBtn"><?php echo $this->lang->line('Form label button'); ?></label>
                        <input type="text" class="form-control" name="planBtn" value="<?php echo html_escape($this->config->item('planBtn')); ?>" placeholder="<?php echo $this->lang->line('Subscribe!'); ?>" />
                    </div>

                    <div class="form-group m-b-20">
                        <label for="planActive"><?php echo $this->lang->line('Status'); ?></label>
                        <select class="form-control selectpicker show-tick" data-style="btn-white" name="planActive">
                            <option value="1" <?php if($this->config->item('planActive') === '1') echo 'selected'; ?>><?php echo $this->lang->line('active'); ?></option>
                            <option value="0" <?php if($this->config->item('planActive') === '0') echo 'selected'; ?>><?php echo $this->lang->line('inactive'); ?></option>
                        </select>
                    </div>

                    <div class="form-group m-b-20">
                        <div class="checkbox checkbox-black">
                            <input type="checkbox" name="planFocus" value="1" <?php if($this->config->item('planFocus') === '1') echo 'checked'; ?>>
                            <label for="planFocus"><?php echo $this->lang->line('CSS Active Class'); ?></label>
                        </div>
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                        <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                    </div>

                </div>
            </div>
            <div class="col-sm-4 subscriptionsActive">
                <div class="card-box">

                    <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Stripe subscription'); ?></b></h4>
                    <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customise your Stripe subscription'); ?></p>
                    
                    <div class="form-group">
                        <label for="plan2"><?php echo $this->lang->line('Plan'); ?></label> <small>(<a href="https://dashboard.stripe.com/test/plans" target="_blank"><?php echo $this->lang->line('Plan'); ?></a>)</small>
                        <input type="text" class="form-control" name="plan2" value="<?php echo html_escape($this->config->item('plan2')); ?>" placeholder="<?php echo $this->lang->line('Plan'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="plan2Title"><?php echo $this->lang->line('Form Title'); ?></label>
                        <input type="text" class="form-control" name="plan2Title" value="<?php echo html_escape($this->config->item('plan2Title')); ?>" placeholder="<?php echo $this->lang->line('Form Title'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="plan2Description"><?php echo $this->lang->line('Form Description'); ?></label>
                        <input type="text" class="form-control" name="plan2Description" value="<?php echo html_escape($this->config->item('plan2Description')); ?>" placeholder="<?php echo $this->lang->line('Form Description'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="plan2Price"><?php echo $this->lang->line('Price'); ?></label>
                        <input type="number" step="0.01" class="form-control" name="plan2Price" value="<?php echo html_escape($this->config->item('plan2Price')); ?>" placeholder="<?php echo $this->lang->line('Price'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="plan2Currency"><?php echo $this->lang->line('Currency'); ?></label> <small>(<a href="https://stripe.com/docs/currencies#charge-currencies" target="_blank"><?php echo $this->lang->line('Currencies'); ?></a>)</small>
                        <input type="text" class="form-control" name="plan2Currency" value="<?php echo html_escape($this->config->item('plan2Currency')); ?>" placeholder="USD" />
                    </div>

                    <div class="form-group">
                        <label for="plan2Trial"><?php echo $this->lang->line('Trial Days'); ?></label>
                        <input type="number" class="form-control" name="plan2Trial" value="<?php echo html_escape($this->config->item('plan2Trial')); ?>" placeholder="<?php echo $this->lang->line('Trial Days'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="plan2ItemList"><?php echo $this->lang->line('Detailed List'); ?></label>
                        <textarea type="text" class="form-control cnt3" name="plan2ItemList"><?php echo $this->config->item('plan2ItemList'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="plan2Btn"><?php echo $this->lang->line('Form label button'); ?></label>
                        <input type="text" class="form-control" name="plan2Btn" value="<?php echo html_escape($this->config->item('plan2Btn')); ?>" placeholder="<?php echo $this->lang->line('Subscribe!'); ?>" />
                    </div>

                    <div class="form-group m-b-20">
                        <label for="plan2Active"><?php echo $this->lang->line('Status'); ?></label>
                        <select class="form-control selectpicker show-tick" data-style="btn-white" name="plan2Active">
                            <option value="1" <?php if($this->config->item('plan2Active') === '1') echo 'selected'; ?>><?php echo $this->lang->line('active'); ?></option>
                            <option value="0" <?php if($this->config->item('plan2Active') === '0') echo 'selected'; ?>><?php echo $this->lang->line('inactive'); ?></option>
                        </select>
                    </div>

                    <div class="form-group m-b-20">
                        <div class="checkbox checkbox-black">
                            <input type="checkbox" name="plan2Focus" value="1" <?php if($this->config->item('plan2Focus') === '1') echo 'checked'; ?>>
                            <label for="plan2Focus"><?php echo $this->lang->line('CSS Active Class'); ?></label>
                        </div>
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                        <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                    </div>

                </div>
            </div>
            <div class="col-sm-4 subscriptionsActive">
                <div class="card-box">

                    <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Stripe subscription'); ?></b></h4>
                    <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customise your Stripe subscription'); ?></p>
                    
                    <div class="form-group">
                        <label for="plan3"><?php echo $this->lang->line('Plan'); ?></label> <small>(<a href="https://dashboard.stripe.com/test/plans" target="_blank"><?php echo $this->lang->line('Plan'); ?></a>)</small>
                        <input type="text" class="form-control" name="plan3" value="<?php echo html_escape($this->config->item('plan3')); ?>" placeholder="<?php echo $this->lang->line('Plan'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="plan3Title"><?php echo $this->lang->line('Form Title'); ?></label>
                        <input type="text" class="form-control" name="plan3Title" value="<?php echo html_escape($this->config->item('plan3Title')); ?>" placeholder="<?php echo $this->lang->line('Form Title'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="plan3Description"><?php echo $this->lang->line('Form Description'); ?></label>
                        <input type="text" class="form-control" name="plan3Description" value="<?php echo html_escape($this->config->item('plan3Description')); ?>" placeholder="<?php echo $this->lang->line('Form Description'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="plan3Price"><?php echo $this->lang->line('Price'); ?></label>
                        <input type="number" step="0.01" class="form-control" name="plan3Price" value="<?php echo html_escape($this->config->item('plan3Price')); ?>" placeholder="<?php echo $this->lang->line('Price'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="plan3Currency"><?php echo $this->lang->line('Currency'); ?></label> <small>(<a href="https://stripe.com/docs/currencies#charge-currencies" target="_blank"><?php echo $this->lang->line('Currencies'); ?></a>)</small>
                        <input type="text" class="form-control" name="plan3Currency" value="<?php echo html_escape($this->config->item('plan3Currency')); ?>" placeholder="USD" />
                    </div>

                    <div class="form-group">
                        <label for="plan3Trial"><?php echo $this->lang->line('Trial Days'); ?></label>
                        <input type="number" class="form-control" name="plan3Trial" value="<?php echo html_escape($this->config->item('plan3Trial')); ?>" placeholder="<?php echo $this->lang->line('Trial Days'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="plan3ItemList"><?php echo $this->lang->line('Detailed List'); ?></label>
                        <textarea type="text" class="form-control cnt3" name="plan3ItemList"><?php echo $this->config->item('plan3ItemList'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="plan3Btn"><?php echo $this->lang->line('Form label button'); ?></label>
                        <input type="text" class="form-control" name="plan3Btn" value="<?php echo html_escape($this->config->item('plan3Btn')); ?>" placeholder="<?php echo $this->lang->line('Subscribe!'); ?>" />
                    </div>

                    <div class="form-group m-b-20">
                        <label for="plan3Active"><?php echo $this->lang->line('Status'); ?></label>
                        <select class="form-control selectpicker show-tick" data-style="btn-white" name="plan3Active">
                            <option value="1" <?php if($this->config->item('plan3Active') === '1') echo 'selected'; ?>><?php echo $this->lang->line('active'); ?></option>
                            <option value="0" <?php if($this->config->item('plan3Active') === '0') echo 'selected'; ?>><?php echo $this->lang->line('inactive'); ?></option>
                        </select>
                    </div>

                    <div class="form-group m-b-20">
                        <div class="checkbox checkbox-black">
                            <input type="checkbox" name="plan3Focus" value="1" <?php if($this->config->item('plan3Focus') === '1') echo 'checked'; ?>>
                            <label for="plan3Focus"><?php echo $this->lang->line('CSS Active Class'); ?></label>
                        </div>
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                        <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                    </div>

                </div>
            </div>
            <div class="col-sm-4 paymentsActive">
                <div class="card-box">

                    <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Stripe payment'); ?></b></h4>
                    <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customise your Stripe payment'); ?></p>
                    
                    <div class="form-group">
                        <label for="payTitle"><?php echo $this->lang->line('Form Title'); ?></label>
                        <input type="text" class="form-control" name="payTitle" value="<?php echo html_escape($this->config->item('payTitle')); ?>" placeholder="<?php echo $this->lang->line('Form Title'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="payDescription"><?php echo $this->lang->line('Form Description'); ?></label>
                        <input type="text" class="form-control" name="payDescription" value="<?php echo html_escape($this->config->item('payDescription')); ?>" placeholder="<?php echo $this->lang->line('Form Description'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="payPrice"><?php echo $this->lang->line('Price'); ?></label>
                        <input type="number" step="0.01" class="form-control" name="payPrice" value="<?php echo html_escape($this->config->item('payPrice')); ?>" placeholder="<?php echo $this->lang->line('Price'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="payCurrency"><?php echo $this->lang->line('Currency'); ?></label> <small>(<a href="https://stripe.com/docs/currencies#charge-currencies" target="_blank"><?php echo $this->lang->line('Currencies'); ?></a>)</small>
                        <input type="text" class="form-control" name="payCurrency" value="<?php echo html_escape($this->config->item('payCurrency')); ?>" placeholder="USD" />
                    </div>

                    <div class="form-group">
                        <label for="payPeriod"><?php echo $this->lang->line('Active Period'); ?></label>
                        <input type="number" class="form-control" name="payPeriod" value="<?php echo html_escape($this->config->item('payPeriod')); ?>" placeholder="Active period" />
                    </div>

                    <div class="form-group">
                        <label for="payItemList"><?php echo $this->lang->line('Detailed List'); ?></label>
                        <textarea type="text" class="form-control cnt3" name="payItemList"><?php echo $this->config->item('payItemList'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="payBtn"><?php echo $this->lang->line('Form label button'); ?></label>
                        <input type="text" class="form-control" name="payBtn" value="<?php echo html_escape($this->config->item('payBtn')); ?>" placeholder="<?php echo $this->lang->line('Payment!'); ?>" />
                    </div>

                    <div class="form-group m-b-20">
                        <label for="payActive"><?php echo $this->lang->line('Status'); ?></label>
                        <select class="form-control selectpicker show-tick" data-style="btn-white" name="payActive">
                            <option value="1" <?php if($this->config->item('payActive') === '1') echo 'selected'; ?>><?php echo $this->lang->line('active'); ?></option>
                            <option value="0" <?php if($this->config->item('payActive') === '0') echo 'selected'; ?>><?php echo $this->lang->line('inactive'); ?></option>
                        </select>
                    </div>

                    <div class="form-group m-b-20">
                        <div class="checkbox checkbox-black">
                            <input type="checkbox" name="payFocus" value="1" <?php if($this->config->item('payFocus') === '1') echo 'checked'; ?>>
                            <label for="payFocus"><?php echo $this->lang->line('CSS Active Class'); ?></label>
                        </div>
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                        <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                    </div>

                </div>
            </div>
            <div class="col-sm-4 paymentsActive">
                <div class="card-box">

                    <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Stripe payment'); ?></b></h4>
                    <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customise your Stripe payment'); ?></p>
                    
                    <div class="form-group">
                        <label for="pay2Title"><?php echo $this->lang->line('Form Title'); ?></label>
                        <input type="text" class="form-control" name="pay2Title" value="<?php echo html_escape($this->config->item('pay2Title')); ?>" placeholder="<?php echo $this->lang->line('Form Title'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="pay2Description"><?php echo $this->lang->line('Form Description'); ?></label>
                        <input type="text" class="form-control" name="pay2Description" value="<?php echo html_escape($this->config->item('pay2Description')); ?>" placeholder="<?php echo $this->lang->line('Form Description'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="pay2Price"><?php echo $this->lang->line('Price'); ?></label>
                        <input type="number" step="0.01" class="form-control" name="pay2Price" value="<?php echo html_escape($this->config->item('pay2Price')); ?>" placeholder="<?php echo $this->lang->line('Price'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="pay2Currency"><?php echo $this->lang->line('Currency'); ?></label> <small>(<a href="https://stripe.com/docs/currencies#charge-currencies" target="_blank"><?php echo $this->lang->line('Currencies'); ?></a>)</small>
                        <input type="text" class="form-control" name="pay2Currency" value="<?php echo html_escape($this->config->item('pay2Currency')); ?>" placeholder="USD" />
                    </div>

                    <div class="form-group">
                        <label for="pay2Period"><?php echo $this->lang->line('Active Period'); ?></label>
                        <input type="number" class="form-control" name="pay2Period" value="<?php echo html_escape($this->config->item('pay2Period')); ?>" placeholder="<?php echo $this->lang->line('Active Period'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="pay2ItemList"><?php echo $this->lang->line('Detailed List'); ?></label>
                        <textarea type="text" class="form-control cnt3" name="pay2ItemList"><?php echo $this->config->item('pay2ItemList'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="pay2Btn"><?php echo $this->lang->line('Form label button'); ?></label>
                        <input type="text" class="form-control" name="pay2Btn" value="<?php echo html_escape($this->config->item('pay2Btn')); ?>" placeholder="<?php echo $this->lang->line('Payment!'); ?>" />
                    </div>

                    <div class="form-group m-b-20">
                        <label for="pay2Active"><?php echo $this->lang->line('Status'); ?></label>
                        <select class="form-control selectpicker show-tick" data-style="btn-white" name="pay2Active">
                            <option value="1" <?php if($this->config->item('pay2Active') === '1') echo 'selected'; ?>><?php echo $this->lang->line('active'); ?></option>
                            <option value="0" <?php if($this->config->item('pay2Active') === '0') echo 'selected'; ?>><?php echo $this->lang->line('inactive'); ?></option>
                        </select>
                    </div>

                    <div class="form-group m-b-20">
                        <div class="checkbox checkbox-black">
                            <input type="checkbox" name="pay2Focus" value="1" <?php if($this->config->item('pay2Focus') === '1') echo 'checked'; ?>>
                            <label for="pay2Focus"><?php echo $this->lang->line('CSS Active Class'); ?></label>
                        </div>
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                        <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                    </div>

                </div>
            </div>
            <div class="col-sm-4 paymentsActive">
                <div class="card-box">

                    <h4 class="m-t-0 header-title"><b><?php echo $this->lang->line('Stripe payment'); ?></b></h4>
                    <p class="text-muted m-b-30 font-13"><?php echo $this->lang->line('Customise your Stripe payment'); ?></p>
                    
                    <div class="form-group">
                        <label for="pay3Title"><?php echo $this->lang->line('Form Title'); ?></label>
                        <input type="text" class="form-control" name="pay3Title" value="<?php echo html_escape($this->config->item('pay3Title')); ?>" placeholder="<?php echo $this->lang->line('Form Title'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="pay3Description"><?php echo $this->lang->line('Form Description'); ?></label>
                        <input type="text" class="form-control" name="pay3Description" value="<?php echo html_escape($this->config->item('pay3Description')); ?>" placeholder="<?php echo $this->lang->line('Form Description'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="pay3Price"><?php echo $this->lang->line('Price'); ?></label>
                        <input type="number" step="0.01" class="form-control" name="pay3Price" value="<?php echo html_escape($this->config->item('pay3Price')); ?>" placeholder="<?php echo $this->lang->line('Price'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="pay3Currency"><?php echo $this->lang->line('Currency'); ?></label> <small>(<a href="https://stripe.com/docs/currencies#charge-currencies" target="_blank"><?php echo $this->lang->line('Currencies'); ?></a>)</small>
                        <input type="text" class="form-control" name="pay3Currency" value="<?php echo html_escape($this->config->item('pay3Currency')); ?>" placeholder="USD" />
                    </div>

                    <div class="form-group">
                        <label for="pay3Period"><?php echo $this->lang->line('Active Period'); ?></label>
                        <input type="number" class="form-control" name="pay3Period" value="<?php echo html_escape($this->config->item('pay3Period')); ?>" placeholder="<?php echo $this->lang->line('Active Period'); ?>" />
                    </div>

                    <div class="form-group">
                        <label for="pay3ItemList"><?php echo $this->lang->line('Detailed List'); ?></label>
                        <textarea type="text" class="form-control cnt3" name="pay3ItemList"><?php echo $this->config->item('pay3ItemList'); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="pay3Btn"><?php echo $this->lang->line('Form label button'); ?></label>
                        <input type="text" class="form-control" name="pay3Btn" value="<?php echo html_escape($this->config->item('pay3Btn')); ?>" placeholder="<?php echo $this->lang->line('Payment!'); ?>" />
                    </div>

                    <div class="form-group m-b-20">
                        <label for="pay3Active"><?php echo $this->lang->line('Status'); ?></label>
                        <select class="form-control selectpicker show-tick" data-style="btn-white" name="pay3Active">
                            <option value="1" <?php if($this->config->item('pay3Active') === '1') echo 'selected'; ?>><?php echo $this->lang->line('active'); ?></option>
                            <option value="0" <?php if($this->config->item('pay3Active') === '0') echo 'selected'; ?>><?php echo $this->lang->line('inactive'); ?></option>
                        </select>
                    </div>

                    <div class="form-group m-b-20">
                        <div class="checkbox checkbox-black">
                            <input type="checkbox" name="pay3Focus" value="1" <?php if($this->config->item('pay3Focus') === '1') echo 'checked'; ?>>
                            <label for="pay3Focus"><?php echo $this->lang->line('CSS Active Class'); ?></label>
                        </div>
                    </div>

                    <div class="form-group text-right m-b-0">
                        <button class="btn btn-inverse waves-effect waves-light" type="submit"><?php echo $this->lang->line('submit'); ?></button>
                        <button type="reset" class="btn btn-default waves-effect waves-light m-l-5"><?php echo $this->lang->line('cancel'); ?></button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- End Stripe Payment -->
    
</form>

<script>
    window.onload = function () {

        $('select[name="paymentMethod"]').change(function (e) {

            $('#Stripe, #PayPal').hide();

            if ($(this).val() === 'PayPal') {

                $('#PayPal').show();

            } else {

                $('#Stripe').show();

            }
        });

        $('select.open-billing-period').change(function (e) {

            $(this).parent().parent().next().toggle();

        });
    }
</script>