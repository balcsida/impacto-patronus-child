<?php
/**
 * Child-Theme functions and definitions
 */

function impacto_patronus_child_scripts() {
    global $post;

    wp_enqueue_style( 'impacto-patronus-parent-style', get_template_directory_uri(). '/style.css' );
    wp_enqueue_style( 'impacto-patronus-child-style', get_stylesheet_uri() );

    /**
     * A contact form 7-hez tartozo scriptek csak azokon az oldalakon
     * toltodjenek be, ahol be van huzva a form.
     */
    if (!strstr($post->post_content, '[contact-form-7')) {
        wp_dequeue_script('contact-form-7');
        wp_dequeue_script('google-recaptcha');
        wp_dequeue_script('wpcf7-recaptcha');
        wp_dequeue_style('contact-form-7');
    }
}
add_action( 'wp_enqueue_scripts', 'impacto_patronus_child_scripts' );

/**
 * Overwrites donate form shortcode.
 *
 * plugin: furik
 * method: furik_shortcode_donate_form
 */
function overwrite_furik_shortcode_donate_form() {
    function furik_shortcode_donate_form_custom( $atts ) {
    	global $furik_card_registration_statement_url, $furik_data_transmission_declaration_url, $furik_monthly_explanation_url;
        $a = shortcode_atts( array(
    	   'amount' => '5000',
           'skip_message' => false,
           'enable_cash' => false,
           'enable_monthly' => false,
           'enable_newsletter' => false
        ), $atts );

        $amount = is_numeric($_GET['furik_amount']) ? $_GET['furik_amount'] : $atts['amount'];

        if (is_numeric($_GET['furik_campaign'])) {
            $post = get_post($_GET['furik_campaign']);
        }
        else {
            $post = get_post();
        }

        $amount_content = "";
        if ($post->post_type == 'campaign') {
            $campaign = $post->post_title;
            $campaign_id = $post->ID;
            $meta = get_post_custom($post->ID);

            if (isset($meta['AMOUNT_CONTENT'][0]) && $meta['AMOUNT_CONTENT'][0]) {
                $amount_content = $meta['AMOUNT_CONTENT'][0];
            }
            else {
                if ($post->post_parent) {
                    $parent_campaign_meta = get_post_custom($post->post_parent);
                    if (isset($parent_campaign_meta['AMOUNT_CONTENT'][0]) && $parent_campaign_meta['AMOUNT_CONTENT'][0]) {
                        $amount_content = $parent_campaign_meta['AMOUNT_CONTENT'][0];
                    }
                }
            }
        }
        else {
            $campaign = __('General donation', 'furik');
        }

        $r = '<script type="text/javascript">
        function toggle_data_transmission() {
            var monthly = document.getElementById("furik_form_recurring_1");
            var method = document.getElementById("furik_form_type_0");
            if (monthly.checked && method.checked) {
                document.getElementById("furik_form_accept_reg_div").style.display="block";
                document.getElementById("furik_form_submit_button").value="' . __('Donation with card registration', 'furik').'";
                document.getElementById("furik_form_accept_reg").required=true
            }
            else {
                document.getElementById("furik_form_accept_reg_div").style.display="none";
                document.getElementById("furik_form_submit_button").value="' . __('Send', 'furik').'";
                document.getElementById("furik_form_accept_reg").required=false
            }
        }
        </script>';

        $r .= "<form method=\"POST\" action=\"".$_SERVER['REQUEST_URI']."\">";
        $r .= "<input type=\"hidden\" name=\"furik_action\" value=\"process_payment_form\" />";
        $r .= "<input type=\"hidden\" name=\"furik_campaign\" value=\"$campaign_id\" />";

        $r .= "<div class=\"form-field form-group form-required\">";
        $r .= "<label for=\"furik_form_name\">".__('Your name', 'furik')."sdsds:</label>";
        $r .= "<input type=\"text\" name=\"furik_form_name\" id=\"furik_form_name\" class=\"form-control\" required=\"1\"/>";
        $r .= "</div>";

        if (!isset($meta['ANON_DISABLED'])) {
            $r .= "<div class=\"form-field form-check\">";
            $r .= "<label for=\"furik_form_anon\" class=\"form-check-label\"><input type=\"checkbox\"  class=\"form-check-input\" name=\"furik_form_anon\" id=\"furik_form_anon\"> ".__('Anonymous donation', 'furik')."</label>";
            $r .= "</div>";
        }

        $r .= "<div class=\"form-field form-group form-required\">";
        $r .= "<label for=\"furik_form_email\">".__('E-mail address', 'furik').":</label>";
        $r .= "<input type=\"email\" class=\"form-control\" name=\"furik_form_email\" id=\"furik_form_email\" required=\"1\" />";
        $r .= "</div>";

        if (!$a['skip_message']) {
            $r .= "<div class=\"form-field form-group\">";
            $r .= "<label for=\"furik_form_message\">".__('Message', 'furik').":</label>";
            $r .= "<textarea class=\"form-control\" name=\"furik_form_message\" id=\"furik_form_message\"></textarea>";
            $r .= "</div>";
        }

        $r .= "<hr />";

        if (isset($amount_content) && $amount_content) {
            $r .= $amount_content;
        }
        else {
            $r .= "<div class=\"form-field form-group form-required\">";
            $r .= "<label for=\"furik_form_amount\">".__('Donation amount', 'furik')." (Forint):</label>";
            $r .= "<input type=\"number\" class=\"form-control\" name=\"furik_form_amount\" id=\"furik_form_amount\" value=\"$amount\" required=\"1\" />";
            $r .= "</div>";
        }


        if ($a['enable_monthly']) {
            $r .= "<hr />";
            $r .= "<div class=\"form-field form-group furik-payment-recurring\">";
            $r .= "<div>";
            $r .= "<div class=\"form-check form-check-inline\">
                <input type=\"radio\" id=\"furik_form_recurring_0\" class=\"form-check-input\" name=\"furik_form_recurring\" value=\"0\" checked=\"1\"
                    onChange=\"toggle_data_transmission()\"
                />
                <label for=\"furik_form_recurring_0\" class=\"form-check-label\">".__('One time donation', 'furik')."</label></div>";

            $r .= "<div class=\"form-check form-check-inline\">
                <input type=\"radio\" id=\"furik_form_recurring_1\" class=\"form-check-input\" name=\"furik_form_recurring\" value=\"1\"
                    onChange=\"toggle_data_transmission()\"
                    />
                    <label for=\"furik_form_recurring_1\" class=\"form-check-label\">".__('Recurring donation', 'furik')." <a href=\"" . furik_url($furik_monthly_explanation_url) . "\" target=\"_blank\">". __("What's this?", 'furik')."</a></label></div>";

            $r .= "</div>";
            $r .= "</div>";
        }

        $r .= "<hr />";
        $r .= "<div class=\"form-field form-group furik-payment-method\">";
        $r .= "<div>";
        $r .= "<div class=\"form-check form-check-inline\">
            <input type=\"radio\" id=\"furik_form_type_0\" class=\"form-check-input\" name=\"furik_form_type\" value=\"0\" checked=\"1\"
                onChange=\"toggle_data_transmission()\"
            />
            <label for=\"furik_form_type_0\" class=\"form-check-label\">".__('Online payment', 'furik')."</label></div>";

        $r .= "<div class=\"form-check form-check-inline\">
            <input type=\"radio\" id=\"furik_form_type_1\" class=\"form-check-input\" name=\"furik_form_type\" value=\"1\"
                onChange=\"toggle_data_transmission()\"
                />
                <label for=\"furik_form_type_1\" class=\"form-check-label\">".__('Bank transfer', 'furik')."</label></div>";

        if ($a['enable_cash']) {
            $r .= "<div class=\"form-check form-check-inline\">
                <input type=\"radio\" id=\"furik_form_type_2\" class=\"form-check-input\" name=\"furik_form_type\" value=\"2\"
                    onChange=\"toggle_data_transmission()\"
                />
                <label for=\"furik_form_type_2\" class=\"form-check-label\">".__('Cash donation', 'furik')."</label></div>";
        }

        $r .= "</div>";
        $r .= "</div>";

        $r .= "<hr />";

        $r .= "<div class=\"form-field form-check furik-form-accept\">";
        $r .= "<label for=\"furik_form_accept\" class=\"form-check-label\"><input type=\"checkbox\" name=\"furik_form_accept\" id=\"furik_form_accept\" class=\"form-check-input\" required=\"1\"><a href=\"".furik_url($furik_data_transmission_declaration_url)."\" target=\"_blank\">".__('I accept the data transmission declaration', 'furik')."</a></label>";
        $r .= "</div>";

        $r .= "<div class=\"form-field form-check furik-form-accept-reg\" id=\"furik_form_accept_reg_div\" style=\"display: none\">";
        $r .= "<label for=\"furik_form_accept_reg\" class=\"form-check-label\"><input type=\"checkbox\" name=\"furik_form_accept-reg\" id=\"furik_form_accept_reg\" class=\"form-check-input\"><a href=\"".furik_url($furik_card_registration_statement_url)."\" target=\"_blank\">".__('I accept the card registration statement', 'furik')."</a></label>";
        $r .= "</div>";

        if ($a['enable_newsletter']) {
            $r .= "<div class=\"form-field form-check furik-form-newsletter\">";
            $r .= "<label for=\"furik_form_newsletter\" class=\"form-check-label\"><input type=\"checkbox\" name=\"furik_form_newsletter\" id=\"furik_form_newsletter\" class=\"form-check-input\">".__('Subscribe to our newsletter', 'furik')."</label>";
            $r .= "</div>";
        }

        $r .= "<div class=\"py-4 footer-btns\"><div class=\"submit-btn\">";
        $r .= "<p class=\"submit\"><input type=\"submit\" class=\"button button-primary rounded-xl btn btn-primary\" id=\"furik_form_submit_button\" value=\"".__('Send', 'furik')."\" /></p>";
        $r .= "</div><div class=\"simple-logo\">";
        $r .= "<a href=\"http://simplepartner.hu/PaymentService/Fizetesi_tajekoztato.pdf\" target=\"_blank\"><img src=\"".furik_url("/wp-content/plugins/furik/images/simplepay.png")."\" title=\"SimplePay - Online bankkártyás fizetés\" alt=\"SimplePay vásárlói tájékoztató\"></a>";
        $r .= "</div>";
        $r .= "</div>";

        $r .= "</form>";

        return $r;
    }

    remove_shortcode('furik_donate_form');
    add_shortcode( 'furik_donate_form', 'furik_shortcode_donate_form_custom' );
}
// add_action('wp_loaded', 'overwrite_furik_shortcode_donate_form');
