<?php
/*
  Plugin Name: Soil Samples
  Plugin URI:
  Description: Soil Sample Custom Post Type for ESRM Project
  Author: Bill Kudrle
  Version: 1.0
  Author URI: http://esrm-portal.org
 */

/****************************************************************************
 * Define the custom post type of soil_samples and associated taxonomies
 ****************************************************************************/
add_action( 'init', 'soil_sample_post_type' );
/**
 * Create the Soil Sample Custom Post Type along with registering its
 * taxonomies of course_semester, course_year, lab_section,
 * course_institution, and soil_sample_classification
 * @param
 * @return
 */
function soil_sample_post_type() {

    // Register soil_samples custom post type
    register_post_type( 'soil_samples',
        array(
            'labels' => array(
                'name' => 'Soil Samples',
                'singular_name' => 'Soil Sample',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Soil Sample',
                'edit' => 'Edit',
                'edit_item' => 'Edit Soil Sample',
                'new_item' => 'New Soil Sample',
                'view' => 'View',
                'view_item' => 'View Soil Sample',
                'search_items' => 'Search Soil Samples',
                'not_found' => 'No Soil Samples found',
                'not_found_in_trash' => 'No Soil Samples found in Trash',
                'parent' => 'Parent Soil Sample',
            ),
            'public' => true,
            'menu_position' => 20,
            'supports' => array( 'title', 'editor', 'author', 'comments', 'thumbnail', 'custom_fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' => 'dashicons-book-alt',
            'has_archive' => false,
            'exclude_from_search' => false,
        )
    );

    // Register course_semester taxonomy
    register_taxonomy(
        'course_semester',
        'soil_samples',
        array(
            'labels' => array(
                'name' => 'Course Semester',
                'add_new_item' => 'Add New Course Semester',
                'new_item_name' => 'New Course Semester Name',
            ),
            'show_ui' => true,
            'meta_box_cb' => false,
            'show_in_quick_edit' => false,
            'show_tagcloud' => false,
            'hierarchical' => true,
        )
    );

    // Register course_year taxonomy
    register_taxonomy(
        'course_year',
        'soil_samples',
        array(
            'labels' => array(
                'name' => 'Course Year',
                'add_new_item' => 'Add New Course Year',
                'new_item_name' => 'New Course Year Value',
            ),
            'show_ui' => true,
            'meta_box_cb' => false,
            'show_in_quick_edit' => false,
            'show_tagcloud' => false,
            'hierarchical' => true,
        )
    );

    // Register lab_section taxonomy
    register_taxonomy(
        'lab_section',
        'soil_samples',
        array(
            'labels' => array(
                'name' => 'Lab Section',
                'add_new_item' => 'Add New Lab Section',
                'new_item_name' => 'New Lab Section Name',
            ),
            'show_ui' => true,
            'meta_box_cb' => false,
            'show_in_quick_edit' => false,
            'show_tagcloud' => false,
            'hierarchical' => true,
        )
    );

    // Register course_institution taxonomy
    register_taxonomy(
        'course_institution',
        'soil_samples',
        array(
            'labels' => array(
                'name' => 'Course Institution',
                'add_new_item' => 'Add New Course Institution',
                'new_item_name' => 'New Course Institution Name',
            ),
            'show_ui' => true,
            'meta_box_cb' => false,
            'show_in_quick_edit' => false,
            'show_tagcloud' => false,
            'hierarchical' => true,
        )
    );

    // Register soil_sample_classification taxonomy
    register_taxonomy(
        'soil_sample_classification',
        'soil_samples',
        array(
            'labels' => array(
                'name' => 'Soil Sample Classification',
                'add_new_item' => 'Add New Soil Sample Classification',
                'new_item_name' => 'New Soil Sample Classification Name',
            ),
            'show_ui' => true,
            'meta_box_cb' => false,
            'show_in_quick_edit' => false,
            'show_tagcloud' => false,
            'hierarchical' => true,
        )
    );
}

/****************************************************************************
 * Add the metadata / boxes for the soil_sample
 ****************************************************************************/
include_once 'soil_sample_meta_course.php';
include_once 'soil_sample_meta_collection.php';
include_once 'soil_sample_meta_results.php';

/****************************************************************************
 * Register function to be called when posts are saved
 * The function will receive 2 arguments
 * This saves all the fields associated to the soil_samples into the database
 ****************************************************************************/
add_action( 'save_post', 'esrm_save_soil_sample_fields', 10, 2 );

function esrm_save_soil_sample_fields( $soil_sample_id, $soil_sample ) {

    if ( 'soil_samples' != $soil_sample->post_type ) {
        return;
    }

    // Researcher name
    if ( isset( $_POST['soil_sample_author_name'] ) ) {
        update_post_meta( $soil_sample_id, 'soil_sample_author', sanitize_text_field( $_POST['soil_sample_author_name'] ) );
    }

    // Location / Collection meta fields
    if ( isset( $_POST['soil_sample_review_loc_name'] ) ) {
        update_post_meta( $soil_sample_id, 'soil_sample_loc_name', sanitize_text_field( $_POST['soil_sample_review_loc_name'] ) );
    }
    if ( isset( $_POST['soil_sample_review_loc_street'] ) ) {
        update_post_meta( $soil_sample_id, 'soil_sample_loc_street', sanitize_text_field( $_POST['soil_sample_review_loc_street'] ) );
    }
    if ( isset( $_POST['soil_sample_review_loc_city'] ) ) {
        update_post_meta( $soil_sample_id, 'soil_sample_loc_city', sanitize_text_field( $_POST['soil_sample_review_loc_city'] ) );
    }
    if ( isset( $_POST['soil_sample_review_loc_county'] ) ) {
        update_post_meta( $soil_sample_id, 'soil_sample_loc_county', sanitize_text_field( $_POST['soil_sample_review_loc_county'] ) );
    }
    if ( isset( $_POST['soil_sample_review_loc_state'] ) ) {
        update_post_meta( $soil_sample_id, 'soil_sample_loc_state', sanitize_text_field( $_POST['soil_sample_review_loc_state'] ) );
    }
    if ( isset( $_POST['soil_sample_review_loc_country'] ) ) {
        update_post_meta( $soil_sample_id, 'soil_sample_loc_country', sanitize_text_field( $_POST['soil_sample_review_loc_country'] ) );
    }
    if ( isset( $_POST['soil_sample_review_loc_postal_code'] ) ) {
        update_post_meta( $soil_sample_id, 'soil_sample_loc_postal_code', sanitize_text_field( $_POST['soil_sample_review_loc_postal_code'] ) );
    }
    if ( isset( $_POST['soil_sample_review_loc_latitude'] ) ) {
        update_post_meta( $soil_sample_id, 'soil_sample_loc_latitude', sanitize_text_field( $_POST['soil_sample_review_loc_latitude'] ) );
    }
    if ( isset( $_POST['soil_sample_review_loc_longitude'] ) ) {
        update_post_meta( $soil_sample_id, 'soil_sample_loc_longitude', sanitize_text_field( $_POST['soil_sample_review_loc_longitude'] ) );
    }

    // Course meta fields
    if ( isset( $_POST['soil_sample_review_course_semester'] ) ) {
        wp_set_post_terms( $soil_sample_id, intval( $_POST['soil_sample_review_course_semester'] ), 'course_semester' );
    }
    if ( isset( $_POST['soil_sample_review_course_year'] ) ) {
        wp_set_post_terms( $soil_sample_id, intval( $_POST['soil_sample_review_course_year'] ), 'course_year' );
    }
    if ( isset( $_POST['soil_sample_review_lab_section'] ) ) {
        wp_set_post_terms( $soil_sample_id, intval( $_POST['soil_sample_review_lab_section'] ), 'lab_section' );
    }
    if ( isset( $_POST['soil_sample_review_course_institution'] ) ) {
        wp_set_post_terms( $soil_sample_id, intval( $_POST['soil_sample_review_course_institution'] ), 'course_institution' );
    }

    // Analysis Results meta fields
    if ( isset( $_POST['soil_sample_paraoxon_count'] ) ) {
        update_post_meta( $soil_sample_id, 'soil_sample_paraoxon_count', sanitize_text_field( $_POST['soil_sample_paraoxon_count'] ) );
    }
    if ( isset( $_POST['soil_sample_methyl_parathion_count'] ) ) {
        update_post_meta( $soil_sample_id, 'soil_sample_methyl_parathion_count', sanitize_text_field( $_POST['soil_sample_methyl_parathion_count'] ) );
    }
    if ( isset( $_POST['soil_sample_classification'] ) ) {
        wp_set_post_terms( $soil_sample_id, intval( $_POST['soil_sample_classification'] ), 'soil_sample_classification' );
    }

}

/****************************************************************************
 * Handle when custom post columns are rendered
 ****************************************************************************/
add_action( 'manage_posts_custom_column', 'esrm_populate_columns' );

// Function to send data for custom columns when displaying items
function esrm_populate_columns( $column ) {

    if ( 'soil_samples' != get_post_type() ) {
        return;
    }

    // Check column name and send back appropriate data
    if ( 'soil_samples_author' == $column ) {
        echo esc_html( get_post_meta( get_the_ID(), 'soil_sample_author', true ) );
    }
    elseif ( 'course_semester' == $column ) {
        $course_semesters = wp_get_post_terms( get_the_ID(), 'course_semester' );

        if ( $course_semesters ) {
            $cat_color = get_term_meta( $course_semesters[0]->term_id, 'course_semester_color', true );

            if ( !empty( $cat_color ) && '#' != $cat_color ) {
                echo '<span style="background-color: ' . esc_html( $cat_color );
                echo '; color: #fff; padding: 6px;">';
                echo esc_html( $course_semesters[0]->name ) . '</span>';
            } else {
                echo esc_html( $course_semesters[0]->name );
            }
        } else {
            echo 'None Assigned';
        }
    }
    elseif ( 'course_year' == $column ) {
        $course_years = wp_get_post_terms( get_the_ID(), 'course_year' );

        if ( $course_years ) {
            $cat_color = get_term_meta( $course_years[0]->term_id, 'course_year_color', true );

            if ( !empty( $cat_color ) && '#' != $cat_color ) {
                echo '<span style="background-color: ' . esc_html( $cat_color );
                echo '; color: #fff; padding: 6px;">';
                echo esc_html( $course_years[0]->name ) . '</span>';
            } else {
                echo esc_html( $course_years[0]->name );
            }
        } else {
            echo 'None Assigned';
        }
    }
    elseif ( 'lab_section' == $column ) {
        $lab_sections = wp_get_post_terms( get_the_ID(), 'lab_section' );

        if ( $lab_sections ) {
            $cat_color = get_term_meta( $lab_sections[0]->term_id, 'lab_section_color', true );

            if ( !empty( $cat_color ) && '#' != $cat_color ) {
                echo '<span style="background-color: ' . esc_html( $cat_color );
                echo '; color: #fff; padding: 6px;">';
                echo esc_html( $lab_sections[0]->name ) . '</span>';
            } else {
                echo esc_html( $lab_sections[0]->name );
            }
        } else {
            echo 'None Assigned';
        }
    }
    elseif ( 'course_institution' == $column ) {
        $course_institutions = wp_get_post_terms( get_the_ID(), 'course_institution' );

        if ( $course_institutions ) {
            $cat_color = get_term_meta( $course_institutions[0]->term_id, 'course_institution_color', true );

            if ( !empty( $cat_color ) && '#' != $cat_color ) {
                echo '<span style="background-color: ' . esc_html( $cat_color );
                echo '; color: #fff; padding: 6px;">';
                echo esc_html( $course_institutions[0]->name ) . '</span>';
            } else {
                echo esc_html( $course_institutions[0]->name );
            }
        } else {
            echo 'None Assigned';
        }
    }
}

/****************************************************************************
 * Code from recipe 'Adding filters for custom taxonomies to the custom
 * post list page'
 ****************************************************************************/

// Register function to be called when displaying post filter drop-down lists
add_action( 'restrict_manage_posts', 'esrm_soil_sample_classification_filter_list' );

// Function to display soil_sample type drop-down list for soil_sample reviews
function esrm_soil_sample_classification_filter_list() {
    $screen = get_current_screen();
    if ( 'soil_samples' != $screen->post_type ) {
        return;
    }

    global $wp_query;
    wp_dropdown_categories( array(
        'show_option_all' => 'Show All Classifications',
        'taxonomy' => 'soil_sample_classification',
        'name' => 'soil_sample_classification',
        'orderby' => 'name',
        'selected' => ( isset( $wp_query->query['soil_sample_classification'] ) ? $wp_query->query['soil_sample_classification'] : '' ),
        'hierarchical' => false,
        'depth' => 3,
        'show_count' => false,
        'hide_empty' => true,
    ) );
}

/****************************************************************************
 * Code from recipe 'Adding Quick Edit fields for custom categories'
 ****************************************************************************/
add_action( 'quick_edit_custom_box', 'esrm_display_custom_quickedit_link', 10, 2 );

function esrm_display_custom_quickedit_link( $column_name, $post_type ) {
    if ( 'soil_samples' != $post_type ) {
        return;
    }

    switch ( $column_name ) {
        case 'soil_samples_author': ?>
            <fieldset class="inline-edit-col-right"><div>
                <label><span class="title">Researcher</span></label>
                <input type="text" name="soil_samples_author" value=""></div>
            <?php break;
        case 'soil_sample_classification': ?>
            <div><label><span class="title">Classification</span></label>
                <?php $terms = get_terms( array( 'taxonomy' => 'soil_sample_classification', 'hide_empty' => false ) ); ?>
                <select name="soil_sample_classification">
                    <option value="">None Assigned</option>
                    <?php foreach ($terms as $index => $term) {
                        echo '<option ';
                        echo 'value="' . $term->term_id . '"';
                        selected( 0, $index );
                        echo '>' . $term->name. '</option>';
                    } ?></select></div></fieldset>
            <?php break;
        case 'course_semester': ?>
            <div><label><span class="title">Semester</span></label>
                <?php $terms = get_terms( array( 'taxonomy' => 'course_semester', 'hide_empty' => false ) ); ?>
                <select name="course_semester">
                    <option value="">None Assigned</option>
                    <?php foreach ($terms as $index => $term) {
                        echo '<option ';
                        echo 'value="' . $term->term_id . '"';
                        selected( 0, $index );
                        echo '>' . $term->name. '</option>';
                    } ?></select></div></fieldset>
            <?php break;
        case 'course_year': ?>
            <div><label><span class="title">Year</span></label>
                <?php $terms = get_terms( array( 'taxonomy' => 'course_year', 'hide_empty' => false ) ); ?>
                <select name="course_year">
                    <option value="">None Assigned</option>
                    <?php foreach ($terms as $index => $term) {
                        echo '<option ';
                        echo 'value="' . $term->term_id . '"';
                        selected( 0, $index );
                        echo '>' . $term->name. '</option>';
                    } ?></select></div></fieldset>
            <?php break;
        case 'lab_section': ?>
            <div><label><span class="title">Lab Section</span></label>
                <?php $terms = get_terms( array( 'taxonomy' => 'lab_section', 'hide_empty' => false ) ); ?>
                <select name="lab_section">
                    <option value="">None Assigned</option>
                    <?php foreach ($terms as $index => $term) {
                        echo '<option ';
                        echo 'value="' . $term->term_id . '"';
                        selected( 0, $index );
                        echo '>' . $term->name. '</option>';
                    } ?></select></div></fieldset>
            <?php break;
        case 'course_institution': ?>
            <div><label><span class="title">Institution</span></label>
                <?php $terms = get_terms( array( 'taxonomy' => 'course_institution', 'hide_empty' => false ) ); ?>
                <select name="course_institution">
                    <option value="">None Assigned</option>
                    <?php foreach ($terms as $index => $term) {
                        echo '<option ';
                        echo 'value="' . $term->term_id . '"';
                        selected( 0, $index );
                        echo '>' . $term->name. '</option>';
                    } ?></select></div></fieldset>
            <?php break;
    }
}

add_action( 'admin_footer', 'esrm_quick_edit_js' );

function esrm_quick_edit_js() {
    global $current_screen;
    if ( ( 'edit-soil_samples' !== $current_screen->id ) ||
        ( 'soil_samples' !== $current_screen->post_type ) ) {
        return;
    } ?>

    <script type="text/javascript">
        function set_inline_soil_samples( soilSampleReviewArray ) {
            // revert Quick Edit menu so that it refreshes properly
            inlineEditPost.revert();
            var SoilSampleResearcher =
                document.getElementsByName('soil_samples_author')[0];
            SoilSampleResearcher.value = soilSampleReviewArray[0];

            var SoilSampleClassification =
                document.getElementsByName('soil_sample_classification')[0];
            for (i = 0; i < SoilSampleClassification.options.length; i++) {
                if ( SoilSampleClassification.options[i].value == soilSampleReviewArray[2] ) {
                    SoilSampleClassification.options[i].setAttribute( 'selected',
                        'selected' );
                } else {
                    SoilSampleClassification.options[i].removeAttribute( 'selected' );
                }
            }

            var CourseSemester =
                document.getElementsByName('course_semester')[0];
            for (i = 0; i < CourseSemester.options.length; i++) {
                if ( CourseSemester.options[i].value == soilSampleReviewArray[3] ) {
                    CourseSemester.options[i].setAttribute( 'selected',
                        'selected' );
                } else {
                    CourseSemester.options[i].removeAttribute( 'selected' );
                }
            }

        }
    </script>
<?php }

add_action( 'save_post', 'esrm_save_quick_edit_data', 10, 2 );

function esrm_save_quick_edit_data( $ID = false, $post = false ) {
    $post_item = get_post( $ID );
    if ( ( isset( $_POST['post_type'] ) && 'soil_samples' != $_POST['post_type'] ) || !isset( $_POST['action'] ) || 'inline-save' != $_POST['action'] ) {
        return;
    }

    if ( isset( $_POST['soil_samples_author'] ) ) {
        update_post_meta( $ID, 'soil_sample_author', sanitize_text_field( $_POST['soil_samples_author'] ) );
    } else {
        update_post_meta( $ID, 'soil_sample_author', '' );
    }

    if ( isset( $_POST['soil_sample_paraoxon_count'] ) ) {
        update_post_meta( $ID, 'soil_sample_paraoxon_count', sanitize_text_field( $_POST['soil_sample_paraoxon_count'] ) );
    } else {
        update_post_meta( $ID, 'soil_sample_paraoxon_count', '' );
    }

    if ( isset( $_POST['soil_sample_methyl_parathion_count'] ) ) {
        update_post_meta( $ID, 'soil_sample_methyl_parathion_count', sanitize_text_field( $_POST['soil_sample_methyl_parathion_count'] ) );
    } else {
        update_post_meta( $ID, 'soil_sample_methyl_parathion_count', '' );
    }

    if ( isset( $_POST['soil_samples_rating'] ) ) {
        update_post_meta( $ID, 'soil_sample_rating', intval($_POST['soil_samples_rating']) );
    } else {
        update_post_meta( $ID, 'soil_sample_rating', '' );
    }

    if ( isset( $_POST['soil_sample_classification'] )  && !empty( $_POST['soil_sample_classification'] ) ) {
        $term = term_exists( intval( $_POST['soil_sample_classification'] ), 'soil_sample_classification' );
        if ( !empty( $term ) ) {
            wp_set_object_terms( $ID, intval( $_POST['soil_sample_classification'] ), 'soil_sample_classification' );
        }
    } else {
        $assigned_types = wp_get_post_terms( $ID, 'soil_sample_classification' );
        foreach( $assigned_types as $assigned_type ) {
            wp_remove_object_terms( $ID,
                $assigned_type->term_id,
                'soil_sample_classification' );
        }
    }

    if ( isset( $_POST['course_semester'] )  && !empty( $_POST['course_semester'] ) ) {
        $term = term_exists( intval( $_POST['course_semester'] ), 'course_semester' );
        if ( !empty( $term ) ) {
            wp_set_object_terms( $ID, intval( $_POST['course_semester'] ), 'course_semester' );
        }
    } else {
        $assigned_types = wp_get_post_terms( $ID, 'course_semester' );
        foreach( $assigned_types as $assigned_type ) {
            wp_remove_object_terms( $ID,
                $assigned_type->term_id,
                'course_semester' );
        }
    }

    if ( isset( $_POST['course_year'] )  && !empty( $_POST['course_year'] ) ) {
        $term = term_exists( intval( $_POST['course_year'] ), 'course_year' );
        if ( !empty( $term ) ) {
            wp_set_object_terms( $ID, intval( $_POST['course_year'] ), 'course_year' );
        }
    } else {
        $assigned_types = wp_get_post_terms( $ID, 'course_year' );
        foreach( $assigned_types as $assigned_type ) {
            wp_remove_object_terms( $ID,
                $assigned_type->term_id,
                'course_year' );
        }
    }

    if ( isset( $_POST['lab_section'] )  && !empty( $_POST['lab_section'] ) ) {
        $term = term_exists( intval( $_POST['lab_section'] ), 'lab_section' );
        if ( !empty( $term ) ) {
            wp_set_object_terms( $ID, intval( $_POST['lab_section'] ), 'lab_section' );
        }
    } else {
        $assigned_types = wp_get_post_terms( $ID, 'lab_section' );
        foreach( $assigned_types as $assigned_type ) {
            wp_remove_object_terms( $ID,
                $assigned_type->term_id,
                'lab_section' );
        }
    }

    if ( isset( $_POST['course_institution'] )  && !empty( $_POST['course_institution'] ) ) {
        $term = term_exists( intval( $_POST['course_institution'] ), 'course_institution' );
        if ( !empty( $term ) ) {
            wp_set_object_terms( $ID, intval( $_POST['course_institution'] ), 'course_institution' );
        }
    } else {
        $assigned_types = wp_get_post_terms( $ID, 'course_institution' );
        foreach( $assigned_types as $assigned_type ) {
            wp_remove_object_terms( $ID,
                $assigned_type->term_id,
                'course_institution' );
        }
    }
}

