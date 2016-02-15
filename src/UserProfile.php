<?php

    function add_phone_number_field( $user ) {
    ?>
        <h3><?php _e('Extra Profiel Informatie', ''); ?></h3>
        
        <table class="form-table">
            <tr>
                <th>
                    <label for="phone_number"><?php _e('Telefoonnnummer', ''); ?>
                </label></th>
                <td>
                    <input type="text" name="phone_number" id="phone_number" value="+<?php echo esc_attr( get_the_author_meta( 'phone_number', $user->ID ) ); ?>" class="regular-text" /><br />
                    <span class="description"><?php _e('Please enter your phone number.', ''); ?></span>
                </td>
            </tr>
        </table>
    <?php }

    function save_phone_number_field( $user_id ) {
        if ( !current_user_can('edit_user', $user_id) )
            return FALSE;

        $phone_number = substr($_POST['phone_number'], 1);

        global $wpdb;    
        $wpdb->update($wpdb->users, array('phone_number' => $phone_number), array('ID' => $user_id));
    }

    function check_fields($errors, $update, $user) {
        $phone_number = $_POST['phone_number'];

        if(! preg_match("/\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|4[987654310]|3[9643210]|2[70]|7|1)\d{1,14}/", $phone_number))
        {
            $errors->add( 'invalid_phone_number', __( '<strong>ERROR</strong>: Ongeldig telefoonnummer. Gebruik het volgende formaat: +[twee cijfers landnummer][regiocode][nummer], bv: +31612345678' ), array( 'form-field' => 'invalid_phone_number' ) );
            return false; 
        }
    }

    add_action( 'show_user_profile', 'add_phone_number_field' );
    add_action( 'edit_user_profile', 'add_phone_number_field' );

    add_filter('user_profile_update_errors', 'check_fields', 10, 3);

    add_action( 'personal_options_update', 'save_phone_number_field' );
    add_action( 'edit_user_profile_update', 'save_phone_number_field' );
