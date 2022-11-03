<?php
/*
  Plugin Name: Soil Sample
  Plugin URI:
  Description: Soil Sample Custom Post Type for ESRM Project
  Author: Bill Kudrle
  Version: 1.0
  Author URI: http://esrm-portal.org
 */

// Register function to be called when admin interface is visited
add_action( 'admin_init', 'esrm_admin_init_results' );

// Function to register new meta box for soil_sample review post editor
function esrm_admin_init_results()
{
    add_meta_box('esrm_review_results_meta_box', 'Analysis Results', 'esrm_display_review_results_mb', 'soil_samples', 'normal', 'high');
}

// Function to display meta box contents
// This is apparently the box beneath the text editor in the form for the soil sample. It contains all the
// metadata for the soil sample (soil collection location)
function esrm_display_review_results_mb( $soil_sample_review ) {

    // Retrieve current counts based on soil_sample review ID
    $soil_sample_paraoxon_count = get_post_meta( $soil_sample_review->ID, 'soil_sample_paraoxon_count', true );
    $soil_sample_methyl_parathion_count = get_post_meta( $soil_sample_review->ID, 'soil_sample_methyl_parathion_count', true );
    ?>

    <table>
        <tr>
            <td style="width: 150px">Number of Colonies of Paraoxon</td>
            <td><input type="text" style="width:100%" name="soil_sample_paraoxon_count" value="<?php echo esc_html( $soil_sample_paraoxon_count ); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 150px">Number of Colonies of Methyl Parathion</td>
            <td><input type="text" style="width:100%" name="soil_sample_methyl_parathion_count" value="<?php echo esc_html( $soil_sample_methyl_parathion_count ); ?>" /></td>
        </tr>

        <tr>
            <td>Soil Sample Classification</td>
            <td>
                <?php

                // Retrieve array of types assigned to post
                $assigned_types = wp_get_post_terms( $soil_sample_review->ID, 'soil_sample_classification' );

                // Retrieve array of all soil_sample types in system
                $soil_sample_classifications = get_terms( 'soil_sample_classification',
                    array( 'orderby' => 'name',
                        'hide_empty' => 0 ) );

                // Check if soil_sample types were found
                if ( $soil_sample_classifications ) {
                    echo '<select name="soil_sample_classification" style="width: 400px">';

                    echo '<option value="">Select type</option>';

                    // Display all soil_sample types
                    foreach ( $soil_sample_classifications as $soil_sample_classification ) {
                        echo '<option value="' . intval( $soil_sample_classification->term_id ) . '" ';
                        if ( !empty( $assigned_types ) ) {
                            selected( $assigned_types[0]->term_id, $soil_sample_classification->term_id );
                        }
                        echo '>' . esc_html( $soil_sample_classification->name ) . '</option>';
                    }
                    echo '</select>';
                } ?>
            </td>
        </tr>

    </table>

<?php }

/**
 * Soil Samples Classification
 */
add_action( 'soil_sample_classification_edit_form_fields', 'esrm_soil_sample_classification_new_fields', 10, 2 );
add_action( 'soil_sample_classification_add_form_fields', 'esrm_soil_sample_classification_new_fields', 10, 2 );

function esrm_soil_sample_classification_new_fields( $tag ) {
    $mode = 'new';

    if ( is_object( $tag ) ) {
        $mode = 'edit';
        $cat_color = get_term_meta( $tag->term_id, 'soil_sample_classification_color', true );
    }
    $cat_color = empty( $cat_color ) ? '#' : $cat_color;

    if ( 'edit' == $mode ) {
        echo '<tr class="form-field">';
        echo '<th scope="row" valign="top">';
    } elseif ( 'new' == $mode ) {
        echo '<div class="form-field">';
    } ?>

    <label for="soil_sample_classification_color">Color</label>
    <?php if ( 'edit' == $mode ) {
        echo '</th><td>';
    } ?>

    <input type="text" id="soil_sample_classification_color" name="soil_sample_classification_color" value="<?php echo $cat_color; ?>" />
    <p class="description">Color associated with soil_sample type (e.g. #199C27 or #CCC)</p>

    <?php if ( 'edit' == $mode ) {
        echo '</td></tr>';
    } elseif ( 'new' == $mode ) {
        echo '</div>';
    }
}

add_action( 'edited_soil_sample_classification', 'esrm_save_soil_sample_classification_new_fields', 10, 2 );
add_action( 'created_soil_sample_classification', 'esrm_save_soil_sample_classification_new_fields', 10, 2 );

function esrm_save_soil_sample_classification_new_fields( $term_id, $tt_id ) {
    if ( !$term_id || !isset( $_POST['soil_sample_classification_color'] ) ) {
        return;
    }

    if ( '#' == $_POST['soil_sample_classification_color'] || preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $_POST['soil_sample_classification_color'] ) ) {
        update_term_meta( $term_id, 'soil_sample_classification_color', sanitize_text_field( $_POST['soil_sample_classification_color'] ) );
    }
}

