<?php

/**
 * Furik Donate Form template
 *
 * This template can be overriden by copying this file to your-theme/furik/furik_donate_form.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?><script type="text/javascript">
    function toggle_data_transmission() {
        var monthly = document.getElementById("furik_form_recurring_1");
        var method = document.getElementById("furik_form_type_0");
        if (monthly.checked && method.checked) {
            document.getElementById("furik_form_accept_reg_div").style.display="block";
            document.getElementById("furik_form_submit_button").value="<?php echo __('Donation with card registration', 'impacto-patronus-child'); ?>";
            document.getElementById("furik_form_accept_reg").required=true
        }
        else {
            document.getElementById("furik_form_accept_reg_div").style.display="none";
            document.getElementById("furik_form_submit_button").value="<?php echo __('Send', 'impacto-patronus-child'); ?>";
            document.getElementById("furik_form_accept_reg").required=false
        }
    }

    jQuery(function () {
        // var step1 = jQuery('.furik-donate-step-1');
        // var step2 = jQuery('.furik-donate-step-2');

        jQuery('body')
            .on('change', 'input[name=furik_form_type]', function (event) {
                var selected = parseInt(jQuery(event.target).val(), 10);
                if (selected === 1) {
                    jQuery('body,html').animate({ scrollTop: jQuery('#bankszamlaszam').offset().top }, 500 );
                }
            })
            .on('change', 'input[name=furik_form_recurring]', function () {
                var recurring = parseInt(jQuery(this).val(), 10) === 1;
                var oneTimeOptions = jQuery('.furik-donate-one-time-options');
                var monthlyOptions = jQuery('.furik-donate-monthly-options');
                var amountField = jQuery('#furik_form_amount');

                if (recurring === true) {
                    oneTimeOptions.hide();
                    monthlyOptions.show();
                } else {
                    oneTimeOptions.show();
                    monthlyOptions.hide();
                }

                amountField.trigger('change');
            })
            .on('click', '.js-set-amount', function () {
                var selectedOption = jQuery(this);
                var amount = selectedOption.data('amount');
                var amountField = jQuery('#furik_form_amount');
                var recurring = parseInt(jQuery('input[name=furik_form_recurring]:checked').val(), 10) === 1;

                jQuery('.js-set-amount').removeClass('active');
                selectedOption.addClass('active');

                if (amount === '') {
                    amountField.val((recurring === true ? '6000' : '50000'));
                    amountField.focus();
                } else {
                    amountField.val(amount);
                }

                return false;
            })
            .on('change', '#furik_form_amount', function () {
                var val = jQuery(this).val();
                var recurring = parseInt(jQuery('input[name=furik_form_recurring]:checked').val(), 10) === 1;

                var container;
                if (recurring === true) {
                    container = jQuery('.furik-donate-monthly-options');
                } else {
                    container = jQuery('.furik-donate-one-time-options');
                }

                jQuery('.js-set-amount').removeClass('active');

                var option = container.find('.js-set-amount[data-amount="' + val + '"]');
                if (option.length > 0) {
                    option.addClass('active');
                } else {
                    container.find('.js-set-amount[data-amount=""]').addClass('active');
                }
            })
            // .on('click', '#furik_form_next_button', function () {
            //     var amount = jQuery('#furik_form_amount').val();
            //
            //     if (amount === '' || parseInt(amount, 10) <= 0) {
            //         alert('A támogatás összege lemaradt.');
            //         return false;
            //     }
            //
            //     step1.fadeOut(function () {
            //         step2.show();
            //     });
            //
            //     return false;
            // })
            // .on('click', '#furik_form_back_button', function () {
            //     step2.fadeOut(function () {
            //         step1.show();
            //     });
            //
            //     return false;
            // })
            // .on('submit', '.furik-donate-form', function () {
            //     // Before this event, browser runs default validation methods.
            //
            //     var amount = jQuery('#furik_form_amount').val();
            //
            //     if (amount === '' || parseInt(amount, 10) <= 0) {
            //         alert('A támogatás összege lemaradt.');
            //         return false;
            //     }
            // })
        ;

        // init
        jQuery('#furik_form_amount').trigger('change');
    });
</script>

<form class="furik-donate-form" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="furik_action" value="process_payment_form"/>
    <input type="hidden" name="furik_campaign" value="<?php echo $args['campaign_id']; ?>"/>

    <!-- Donate Form: Step 1 -->
    <div class="furik-donate-step-1">
        <?php if ($args['a']['enable_monthly']) : ?>
            <div class="form-field form-group furik-payment-recurring">
                <div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="furik_form_recurring_1" class="form-check-input" name="furik_form_recurring" value="1" onChange="toggle_data_transmission()" checked="1" />
                        <label for="furik_form_recurring_1" class="form-check-label">
                            <?php echo __('Recurring donation', 'impacto-patronus-child'); ?> <a href="<?php echo furik_url($args['furik_monthly_explanation_url']); ?>" target="_blank"><?php echo __("What's this?", 'impacto-patronus-child'); ?></a>
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="furik_form_recurring_0" class="form-check-input" name="furik_form_recurring" value="0" onChange="toggle_data_transmission()" />
                        <label for="furik_form_recurring_0" class="form-check-label"><?php echo __('One time donation', 'impacto-patronus-child'); ?></label>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="furik-donate-monthly-options">
            <input type="button" class="js-set-amount button button-primary rounded-xl btn btn-primary" data-amount="1500" value="1500 Ft" />
            <input type="button" class="js-set-amount button button-primary rounded-xl btn btn-primary" data-amount="3000" value="3000 Ft" />
            <input type="button" class="js-set-amount button button-primary rounded-xl btn btn-primary" data-amount="5000" value="5000 Ft" />
            <input type="button" class="js-set-amount button button-primary rounded-xl btn btn-primary" data-amount="7500" value="7500 Ft" />
            <input type="button" class="js-set-amount button button-primary rounded-xl btn btn-primary" data-amount="" value="<?php echo __('Custom amount', 'impacto-patronus-child'); ?>" />
        </div>

        <div class="furik-donate-one-time-options" style="display:none">
            <input type="button" class="js-set-amount button button-primary rounded-xl btn btn-primary" data-amount="2500" value="2500 Ft" />
            <input type="button" class="js-set-amount button button-primary rounded-xl btn btn-primary" data-amount="5000" value="5000 Ft" />
            <input type="button" class="js-set-amount button button-primary rounded-xl btn btn-primary" data-amount="10000" value="10 000 Ft" />
            <input type="button" class="js-set-amount button button-primary rounded-xl btn btn-primary" data-amount="20000" value="20 000 Ft" />
            <input type="button" class="js-set-amount button button-primary rounded-xl btn btn-primary" data-amount="" value="<?php echo __('Custom amount', 'impacto-patronus-child'); ?>" />
        </div>


        <?php if (isset($args['amount_content']) && $args['amount_content']) : ?>
            <?php echo $args['amount_content']; ?>
        <?php else : ?>
            <div class="form-field form-group form-required furik-amount">
                <label for="furik_form_amount"><?php echo __('Donation amount', 'impacto-patronus-child'); ?> (Forint):</label>
                <input type="number" min="500" step="500" class="form-control" name="furik_form_amount" id="furik_form_amount" value="<?php echo $args['amount']; ?>" required/>
            </div>
        <?php endif; ?>

        <div class="form-field form-group furik-payment-method">
            <div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="furik_form_type_0" class="form-check-input" name="furik_form_type" value="0" checked="1" onChange="toggle_data_transmission()" />
                    <label for="furik_form_type_0" class="form-check-label"><?php echo __('Online payment', 'impacto-patronus-child'); ?></label>
                </div>

                <div class="form-check form-check-inline">
                    <input type="radio" id="furik_form_type_1" class="form-check-input" name="furik_form_type" value="1" onChange="toggle_data_transmission()" />
                    <label for="furik_form_type_1" class="form-check-label"><?php echo __('Bank transfer', 'impacto-patronus-child'); ?></label>
                </div>

                <?php if ($args['a']['enable_cash']) : ?>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="furik_form_type_2" class="form-check-input" name="furik_form_type" value="2" onChange="toggle_data_transmission()" />
                        <label for="furik_form_type_2" class="form-check-label"><?php echo __('Cash donation', 'impacto-patronus-child'); ?></label>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Donate Form: Step 2 -->
    <div class="furik-donate-step-2">
        <div class="furik-contact-info">
            <?php if (!furik_extra_field_enabled('name_separation')) : ?>
                <div class="form-field form-group form-required">
                    <label for="furik_form_name"><?php echo __('Your name', 'impacto-patronus-child'); ?>:</label>
                    <input type="text" name="furik_form_name" id="furik_form_name" class="form-control" required />
                </div>
            <?php else : ?>
                <div class="form-field form-group form-required">
                    <label for="furik_form_last_name"><?php echo __('Last name', 'impacto-patronus-child'); ?>:</label>
                    <input type="text" name="furik_form_last_name" id="furik_form_last_name" class="form-control" required />
                </div>
                <div class="form-field form-group form-required">
                    <label for="furik_form_first_name"><?php echo __('First name', 'impacto-patronus-child'); ?>:</label>
                    <input type="text" name="furik_form_first_name" id="furik_form_first_name" class="form-control" required />
                </div>
            <?php endif; ?>

            <?php if (!isset($args['meta']['ANON_DISABLED'])) : ?>
                <div class="form-field form-check">
                    <label for="furik_form_anon" class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="furik_form_anon" id="furik_form_anon"><?php echo __('Anonymous donation', 'impacto-patronus-child'); ?>
                    </label>
                </div>
            <?php endif; ?>

            <div class="form-field form-group form-required">
                <label for="furik_form_email"><?php echo __('E-mail address', 'impacto-patronus-child'); ?>:</label>
                <input type="email" class="form-control" name="furik_form_email" id="furik_form_email" required/>
            </div>

            <?php if (furik_extra_field_enabled('phone_number')) : ?>
                <div class="form-field form-group">
                    <label for="furik_form_phone_number"><?php echo __('Phone number', 'impacto-patronus-child'); ?>:</label>
                    <input type="tel" class="form-control" name="furik_form_phone_number" id="furik_form_phone_number" />
                </div>
            <?php endif; ?>

            <?php if (!$args['a']['skip_message']) : ?>
                <div class="form-field form-group">
                    <label for="furik_form_message"><?php echo __('Message', 'impacto-patronus-child'); ?>:</label>
                    <textarea class="form-control" name="furik_form_message" id="furik_form_message"></textarea>
                </div>
            <?php endif; ?>

            <div class="form-field form-check furik-form-gtc">
                <label for="furik_form_gtc" class="form-check-label">
                    <input type="checkbox" name="furik_form_gtc" id="furik_form_gtc" class="form-check-input" required>
                    <a href="/altalanos-szerzodesi-feltetelek" target="_blank"><?php echo __('I have read and accept the terms and conditions', 'impacto-patronus-child'); ?></a>
                </label>
            </div>

            <div class="form-field form-check furik-form-accept">
                <label for="furik_form_accept" class="form-check-label">
                    <input type="checkbox" name="furik_form_accept" id="furik_form_accept" class="form-check-input" required>
                    <a href="<?php echo furik_url($args['furik_data_transmission_declaration_url']); ?>" target="_blank"><?php echo __('I accept the data transmission declaration', 'impacto-patronus-child'); ?></a>
                </label>
            </div>

            <div class="form-field form-check furik-form-accept-reg" id="furik_form_accept_reg_div" style="display: none">
                <label for="furik_form_accept_reg" class="form-check-label">
                    <input type="checkbox" name="furik_form_accept-reg" id="furik_form_accept_reg" class="form-check-input">
                    <a href="<?php echo furik_url($args['furik_card_registration_statement_url']); ?>" target="_blank"><?php echo __('I accept the card registration statement', 'impacto-patronus-child'); ?></a>
                </label>
            </div>

            <?php if ($args['a']['enable_newsletter']) : ?>
                <div class="form-field form-check furik-form-newsletter">
                    <label for="furik_form_newsletter" class="form-check-label">
                        <input type="checkbox" name="furik_form_newsletter" id="furik_form_newsletter" class="form-check-input">
                        <?php echo __('Subscribe to our newsletter', 'impacto-patronus-child'); ?>
                    </label>
                </div>
            <?php endif; ?>
        </div>

        <div class="submit-btn furik-submit">
            <p class="submit">
                <input type="submit" class="button button-primary rounded-xl btn btn-primary" id="furik_form_submit_button" value="<?php echo __('Send', 'impacto-patronus-child'); ?>" />
            </p>
        </div>
    </div>
</form>

<!-- Donate Form: Footer -->
<div class="simple-logo furik-donate-form-footer">
    <a href="http://simplepartner.hu/PaymentService/Fizetesi_tajekoztato.pdf" target="_blank">
        <img src="<?php echo furik_url("/wp-content/uploads/noar-custom/simplepay.png"); ?>" title="SimplePay - Online bankkártyás fizetés" alt="SimplePay vásárlói tájékoztató">
    </a>
</div>



<?php return false; ?>







<form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="furik_action" value="process_payment_form"/>
    <input type="hidden" name="furik_campaign" value="<?php echo $args['campaign_id']; ?>"/>

    <div class="form-field form-group form-required">
        <label for="furik_form_name"><?php echo __('Your name', 'impacto-patronus-child'); ?>:</label>
        <input type="text" name="furik_form_name" id="furik_form_name" class="form-control" required/>
    </div>

    <?php if (!isset($args['meta']['ANON_DISABLED'])) : ?>
        <div class="form-field form-check">
            <label for="furik_form_anon" class="form-check-label">
                <input type="checkbox" class="form-check-input" name="furik_form_anon" id="furik_form_anon"><?php echo __('Anonymous donation', 'impacto-patronus-child'); ?>
            </label>
        </div>
    <?php endif; ?>

    <div class="form-field form-group form-required">
        <label for="furik_form_email"><?php echo __('E-mail address', 'impacto-patronus-child'); ?>:</label>
        <input type="email" class="form-control" name="furik_form_email" id="furik_form_email" required/>
    </div>

    <?php if (!$args['a']['skip_message']) : ?>
        <div class="form-field form-group">
            <label for="furik_form_message"><?php echo __('Message', 'impacto-patronus-child'); ?>:</label>
            <textarea class="form-control" name="furik_form_message" id="furik_form_message"></textarea>
        </div>
    <?php endif; ?>

    <hr/>

    <?php if (isset($args['amount_content']) && $args['amount_content']) : ?>
        <?php echo $args['amount_content']; ?>
    <?php else : ?>
        <div class="form-field form-group form-required">
            <label for="furik_form_amount"><?php echo __('Donation amount', 'impacto-patronus-child'); ?> (Forint):</label>
            <input type="number" class="form-control" name="furik_form_amount" id="furik_form_amount" value="<?php echo $args['amount']; ?>" required/>
        </div>
    <?php endif; ?>


    <?php if ($args['a']['enable_monthly']) : ?>
        <hr/>
        <div class="form-field form-group furik-payment-recurring">
            <div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="furik_form_recurring_0" class="form-check-input" name="furik_form_recurring" value="0" checked="1" onChange="toggle_data_transmission()" />
                    <label for="furik_form_recurring_0" class="form-check-label"><?php echo __('One time donation', 'impacto-patronus-child'); ?></label>
                </div>

                <div class="form-check form-check-inline">
                    <input type="radio" id="furik_form_recurring_1" class="form-check-input" name="furik_form_recurring" value="1" onChange="toggle_data_transmission()" />
                    <label for="furik_form_recurring_1" class="form-check-label">
                        <?php echo __('Recurring donation', 'impacto-patronus-child'); ?> <a href="<?php echo furik_url($args['furik_monthly_explanation_url']); ?>" target="_blank"><?php echo __("What's this?", 'impacto-patronus-child'); ?></a>
                    </label>
                </div>

            </div>
        </div>
    <?php endif; ?>

    <hr/>
    <div class="form-field form-group furik-payment-method">
        <div>
            <div class="form-check form-check-inline">
                <input type="radio" id="furik_form_type_0" class="form-check-input" name="furik_form_type" value="0" checked="1" onChange="toggle_data_transmission()" />
                <label for="furik_form_type_0" class="form-check-label"><?php echo __('Online payment', 'impacto-patronus-child'); ?></label>
            </div>

            <div class="form-check form-check-inline">
                <input type="radio" id="furik_form_type_1" class="form-check-input" name="furik_form_type" value="1" onChange="toggle_data_transmission()" />
                <label for="furik_form_type_1" class="form-check-label"><?php echo __('Bank transfer', 'impacto-patronus-child'); ?></label>
            </div>

            <?php if ($args['a']['enable_cash']) : ?>
                <div class="form-check form-check-inline">
                    <input type="radio" id="furik_form_type_2" class="form-check-input" name="furik_form_type" value="2" onChange="toggle_data_transmission()" />
                    <label for="furik_form_type_2" class="form-check-label"><?php echo __('Cash donation', 'impacto-patronus-child'); ?></label>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <hr/>

    <div class="form-field form-check furik-form-gtc">
        <label for="furik_form_gtc" class="form-check-label">
            <input type="checkbox" name="furik_form_gtc" id="furik_form_gtc" class="form-check-input" required>
            <a href="/altalanos-szerzodesi-feltetelek" target="_blank"><?php echo __('I have read and accept the terms and conditions', 'impacto-patronus-child'); ?></a>
        </label>
    </div>

    <div class="form-field form-check furik-form-accept">
        <label for="furik_form_accept" class="form-check-label">
            <input type="checkbox" name="furik_form_accept" id="furik_form_accept" class="form-check-input" required>
            <a href="<?php echo furik_url($args['furik_data_transmission_declaration_url']); ?>" target="_blank"><?php echo __('I accept the data transmission declaration', 'impacto-patronus-child'); ?></a>
        </label>
    </div>

    <div class="form-field form-check furik-form-accept-reg" id="furik_form_accept_reg_div" style="display: none">
        <label for="furik_form_accept_reg" class="form-check-label">
            <input type="checkbox" name="furik_form_accept-reg" id="furik_form_accept_reg" class="form-check-input">
            <a href="<?php echo furik_url($args['furik_card_registration_statement_url']); ?>" target="_blank"><?php echo __('I accept the card registration statement', 'impacto-patronus-child'); ?></a>
        </label>
    </div>

    <?php if ($args['a']['enable_newsletter']) : ?>
        <div class="form-field form-check furik-form-newsletter">
            <label for="furik_form_newsletter" class="form-check-label">
                <input type="checkbox" name="furik_form_newsletter" id="furik_form_newsletter" class="form-check-input">
                <?php echo __('Subscribe to our newsletter', 'impacto-patronus-child'); ?>
            </label>
        </div>
    <?php endif; ?>

    <div class="py-4 footer-btns">
        <div class="submit-btn">
            <p class="submit">
                <input type="submit" class="button button-primary rounded-xl btn btn-primary" id="furik_form_submit_button" value="<?php echo __('Send', 'impacto-patronus-child'); ?>" />
            </p>
        </div>
        <div class="simple-logo">
            <a href="http://simplepartner.hu/PaymentService/Fizetesi_tajekoztato.pdf" target="_blank">
                <img src="<?php echo furik_url("/wp-content/plugins/furik/images/simplepay.png"); ?>" title="SimplePay - Online bankkártyás fizetés" alt="SimplePay vásárlói tájékoztató">
            </a>
        </div>
    </div>
</form>
