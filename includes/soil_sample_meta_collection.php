<?php
/*
  Plugin Name: Soil Samples
  Plugin URI:
  Description: Soil Sample Custom Post Type for ESRM Project
  Author: Bill Kudrle
  Version: 1.0
  Author URI: http://esrm-portal.org
 */

// Register function to be called when admin interface is visited
add_action( 'admin_init', 'esrm_admin_collection' );

// Function to register new meta box for soil_sample review post editor
function esrm_admin_collection()
{
    add_meta_box('esrm_review_collection_meta_box', 'Soil Sample Collection', 'esrm_display_review_collection_mb', 'soil_samples', 'normal', 'high');
}

// Function to display meta box contents
// This is apparently the box beneath the text editor in the form for the soil sample. It contains all the
// metadata for the soil sample (soil collection location)
function esrm_display_review_collection_mb( $soil_sample_review ) {

    // Retrieve current author and rating based on soil_sample review ID
    $soil_sample_author = get_post_meta( $soil_sample_review->ID, 'soil_sample_author', true );
    $soil_sample_loc_name = get_post_meta( $soil_sample_review->ID, 'soil_sample_loc_name', true );
    $soil_sample_loc_street = get_post_meta( $soil_sample_review->ID, 'soil_sample_loc_street', true );
    $soil_sample_loc_city = get_post_meta( $soil_sample_review->ID, 'soil_sample_loc_city', true );
    $soil_sample_loc_county = get_post_meta( $soil_sample_review->ID, 'soil_sample_loc_county', true );
    $soil_sample_loc_state = get_post_meta( $soil_sample_review->ID, 'soil_sample_loc_state', true );
    $soil_sample_loc_country = get_post_meta( $soil_sample_review->ID, 'soil_sample_loc_country', true );
    $soil_sample_loc_postal_code = get_post_meta( $soil_sample_review->ID, 'soil_sample_loc_postal_code', true );
    $soil_sample_loc_latitude = get_post_meta( $soil_sample_review->ID, 'soil_sample_loc_latitude', true );
    $soil_sample_loc_longitude = get_post_meta( $soil_sample_review->ID, 'soil_sample_loc_longitude', true );
    ?>
    <table>
        <tr>
            <td style="width: 150px">Researcher Name</td>
            <td><input type="text" style="width:100%" name="soil_sample_author_name" value="<?php echo esc_html( $soil_sample_author ); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 150px">Location Name</td>
            <td><input type="text" style="width:100%" name="soil_sample_review_loc_name" value="<?php echo esc_html( $soil_sample_loc_name ); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 150px">Location Street</td>
            <td><input type="text" style="width:100%" name="soil_sample_review_loc_street" value="<?php echo esc_html( $soil_sample_loc_street ); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 150px">Location City</td>
            <td><input type="text" style="width:100%" name="soil_sample_review_loc_city" value="<?php echo esc_html( $soil_sample_loc_city ); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 150px">Location County</td>
            <td><input type="text" style="width:100%" name="soil_sample_review_loc_county" value="<?php echo esc_html( $soil_sample_loc_county ); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 150px">Location State</td>
            <td><input type="text" style="width:100%" name="soil_sample_review_loc_state" value="<?php echo esc_html( $soil_sample_loc_state ); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 150px">Location Country</td>
            <td><input type="text" style="width:100%" name="soil_sample_review_loc_country" value="<?php echo esc_html( $soil_sample_loc_country ); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 150px">Location Postal Code</td>
            <td><input type="text" style="width:100%" name="soil_sample_review_loc_postal_code" value="<?php echo esc_html( $soil_sample_loc_postal_code ); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 150px">Location Latitude</td>
            <td><input type="text" style="width:100%" name="soil_sample_review_loc_latitude" value="<?php echo esc_html( $soil_sample_loc_latitude ); ?>" /></td>
        </tr>
        <tr>
            <td style="width: 150px">Location Longitude</td>
            <td><input type="text" style="width:100%" name="soil_sample_review_loc_longitude" value="<?php echo esc_html( $soil_sample_loc_longitude ); ?>" /></td>
        </tr>

    </table>

<?php }

