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
add_action( 'admin_init', 'esrm_admin_init_files' );

// Function to register new meta box for soil_sample review post editor
function esrm_admin_init_results()
{
    add_meta_box('esrm_review_files_meta_box', 'Soil Sample Files', 'esrm_display_review_files_mb', 'soil_samples', 'normal', 'high');
}

// Function to display meta box contents
// This is apparently the box beneath the text editor in the form for the soil sample. It contains all the
// metadata for the soil sample (soil collection location)
function esrm_display_review_files_mb( $soil_sample_review ) {

    // Retrieve current counts based on soil_sample review ID
    $soil_sample_paraoxon_count = get_post_meta( $soil_sample_review->ID, 'soil_sample_paraoxon_count', true );
    $soil_sample_methyl_parathion_count = get_post_meta( $soil_sample_review->ID, 'soil_sample_methyl_parathion_count', true );
    ?>

    <p>Choose a file to upload.</p>
    <form id="upload_form" action="/wp-content/plugins/phpExcel/main.php" enctype="multipart/form-data" method="post" target="messages">
        <p><input name="upload" id="upload" type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" /></p>
        <p><input id="btnSubmit" type="submit" value="Upload Selected Spreadsheet" /></p>
        <iframe name="messages" id="messages"></iframe>
        <p><input id="reset_upload_form" type="reset" value="Reset form" /></p>
    </form>

<?php }

