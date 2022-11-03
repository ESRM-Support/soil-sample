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
add_action( 'admin_init', 'esrm_admin_init_course' );

// Function to register new meta box for soil_sample editing of course metadata
function esrm_admin_init_course() {
    add_meta_box( 'esrm_course_meta_box', 'This Course', 'esrm_display_course_mb', 'soil_samples', 'normal', 'high' );
}

// Function to display meta box contents
// This is apparently the box beneath the text editor in the form for the soil sample. It contains all the
// metadata for the soil sample (semester, year, institution, etc.)
function esrm_display_course_mb( $soil_sample_review ) {
    // Retrieve current author and rating based on soil_sample review ID
    //$soil_sample_author = get_post_meta( $soil_sample_review->ID, 'soil_sample_author', true );
    $soil_sample_rating = get_post_meta( $soil_sample_review->ID, 'soil_sample_rating', true );
    ?>
    <table>
        <!-- tr>
            <td style="width: 150px">Researcher Name</td>
            <td><input type="text" style="width:100%" name="soil_sample_author_name" value="<?php //echo esc_html( $soil_sample_author ); ?>" /></td>
        </tr -->

        <tr>
            <td>Course Semester</td>
            <td>
                <?php

                // Retrieve array of types assigned to post
                $assigned_types = wp_get_post_terms( $soil_sample_review->ID, 'course_semester' );

                // Retrieve array of all soil_sample types in system
                $course_semesters = get_terms( 'course_semester',
                    array( 'orderby' => 'name',
                        'hide_empty' => 0 ) );

                // Check if soil_sample types were found
                if ( $course_semesters ) {
                    echo '<select name="soil_sample_review_course_semester" style="width: 400px">';

                    echo '<option value="">Select type</option>';

                    // Display all soil_sample types
                    foreach ( $course_semesters as $course_semester ) {
                        echo '<option value="' . intval( $course_semester->term_id ) . '" ';
                        if ( !empty( $assigned_types ) ) {
                            selected( $assigned_types[0]->term_id, $course_semester->term_id );
                        }
                        echo '>' . esc_html( $course_semester->name ) . '</option>';
                    }
                    echo '</select>';
                } ?>
            </td>
        </tr>

        <tr>
            <td>Course Year</td>
            <td>
                <?php

                // Retrieve array of types assigned to post
                $assigned_types = wp_get_post_terms( $soil_sample_review->ID, 'course_year' );

                // Retrieve array of all soil_sample types in system
                $course_years = get_terms( 'course_year',
                    array( 'orderby' => 'name',
                        'hide_empty' => 0 ) );

                // Check if soil_sample types were found
                if ( $course_years ) {
                    echo '<select name="soil_sample_review_course_year" style="width: 400px">';

                    echo '<option value="">Select type</option>';

                    // Display all soil_sample types
                    foreach ( $course_years as $course_year ) {
                        echo '<option value="' . intval( $course_year->term_id ) . '" ';
                        if ( !empty( $assigned_types ) ) {
                            selected( $assigned_types[0]->term_id, $course_year->term_id );
                        }
                        echo '>' . esc_html( $course_year->name ) . '</option>';
                    }
                    echo '</select>';
                } ?>
            </td>
        </tr>

        <tr>
            <td>Lab Section</td>
            <td>
                <?php

                // Retrieve array of types assigned to post
                $assigned_types = wp_get_post_terms( $soil_sample_review->ID, 'lab_section' );

                // Retrieve array of all soil_sample types in system
                $lab_sections = get_terms( 'lab_section',
                    array( 'orderby' => 'name',
                        'hide_empty' => 0 ) );

                // Check if soil_sample types were found
                if ( $lab_sections ) {
                    echo '<select name="soil_sample_review_lab_section" style="width: 400px">';

                    echo '<option value="">Select type</option>';

                    // Display all soil_sample types
                    foreach ( $lab_sections as $lab_section ) {
                        echo '<option value="' . intval( $lab_section->term_id ) . '" ';
                        if ( !empty( $assigned_types ) ) {
                            selected( $assigned_types[0]->term_id, $lab_section->term_id );
                        }
                        echo '>' . esc_html( $lab_section->name ) . '</option>';
                    }
                    echo '</select>';
                } ?>
            </td>
        </tr>

        <tr>
            <td>Course Institution</td>
            <td>
                <?php

                // Retrieve array of types assigned to post
                $assigned_types = wp_get_post_terms( $soil_sample_review->ID, 'course_institution' );

                // Retrieve array of all soil_sample types in system
                $course_institutions = get_terms( 'course_institution',
                    array( 'orderby' => 'name',
                        'hide_empty' => 0 ) );

                // Check if soil_sample types were found
                if ( $course_institutions ) {
                    echo '<select name="soil_sample_review_course_institution" style="width: 400px">';

                    echo '<option value="">Select type</option>';

                    // Display all soil_sample types
                    foreach ( $course_institutions as $course_institution ) {
                        echo '<option value="' . intval( $course_institution->term_id ) . '" ';
                        if ( !empty( $assigned_types ) ) {
                            selected( $assigned_types[0]->term_id, $course_institution->term_id );
                        }
                        echo '>' . esc_html( $course_institution->name ) . '</option>';
                    }
                    echo '</select>';
                } ?>
            </td>
        </tr>
    </table>

<?php }


/**
 * course_semester
 */
add_action( 'course_semester_edit_form_fields', 'esrm_course_semester_new_fields', 10, 2 );
add_action( 'course_semester_add_form_fields', 'esrm_course_semester_new_fields', 10, 2 );

function esrm_course_semester_new_fields( $tag ) {
    $mode = 'new';

    if ( is_object( $tag ) ) {
        $mode = 'edit';
        $cat_color = get_term_meta( $tag->term_id, 'course_semester_color', true );
    }
    $cat_color = empty( $cat_color ) ? '#' : $cat_color;

    if ( 'edit' == $mode ) {
        echo '<tr class="form-field">';
        echo '<th scope="row" valign="top">';
    } elseif ( 'new' == $mode ) {
        echo '<div class="form-field">';
    } ?>

    <label for="course_semester_color">Color</label>
    <?php if ( 'edit' == $mode ) {
        echo '</th><td>';
    } ?>

    <input type="text" id="course_semester_color" name="course_semester_color" value="<?php echo $cat_color; ?>" />
    <p class="description">Color associated with soil_sample type (e.g. #199C27 or #CCC)</p>

    <?php if ( 'edit' == $mode ) {
        echo '</td></tr>';
    } elseif ( 'new' == $mode ) {
        echo '</div>';
    }
}

add_action( 'edited_course_semester', 'esrm_save_course_semester_new_fields', 10, 2 );
add_action( 'created_course_semester', 'esrm_save_course_semester_new_fields', 10, 2 );

function esrm_save_course_semester_new_fields( $term_id, $tt_id ) {
    if ( !$term_id || !isset( $_POST['course_semester_color'] ) ) {
        return;
    }

    if ( '#' == $_POST['course_semester_color'] || preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $_POST['course_semester_color'] ) ) {
        update_term_meta( $term_id, 'course_semester_color', sanitize_text_field( $_POST['course_semester_color'] ) );
    }
}


// Register function to be called when displaying post filter drop-down lists
add_action( 'restrict_manage_posts', 'esrm_course_semester_filter_list' );

// Function to display soil_sample type drop-down list for soil_sample reviews
function esrm_course_semester_filter_list() {
    $screen = get_current_screen();
    if ( 'soil_samples' != $screen->post_type ) {
        return;
    }

    global $wp_query;
    wp_dropdown_categories( array(
        'show_option_all' => 'Show All Semesters',
        'taxonomy' => 'course_semester',
        'name' => 'course_semester',
        'orderby' => 'name',
        'selected' => ( isset( $wp_query->query['course_semester'] ) ? $wp_query->query['course_semester'] : '' ),
        'hierarchical' => false,
        'depth' => 3,
        'show_count' => false,
        'hide_empty' => true,
    ) );
}

/**************************** End of course_semester **********************************/

/**
 * course_year
 */
add_action( 'course_year_edit_form_fields', 'esrm_course_year_new_fields', 10, 2 );
add_action( 'course_year_add_form_fields', 'esrm_course_year_new_fields', 10, 2 );

function esrm_course_year_new_fields( $tag ) {
    $mode = 'new';

    if ( is_object( $tag ) ) {
        $mode = 'edit';
        $cat_color = get_term_meta( $tag->term_id, 'course_year_color', true );
    }
    $cat_color = empty( $cat_color ) ? '#' : $cat_color;

    if ( 'edit' == $mode ) {
        echo '<tr class="form-field">';
        echo '<th scope="row" valign="top">';
    } elseif ( 'new' == $mode ) {
        echo '<div class="form-field">';
    } ?>

    <label for="course_year_color">Color</label>
    <?php if ( 'edit' == $mode ) {
        echo '</th><td>';
    } ?>

    <input type="text" id="course_year_color" name="course_year_color" value="<?php echo $cat_color; ?>" />
    <p class="description">Color associated with soil_sample type (e.g. #199C27 or #CCC)</p>

    <?php if ( 'edit' == $mode ) {
        echo '</td></tr>';
    } elseif ( 'new' == $mode ) {
        echo '</div>';
    }
}

add_action( 'edited_course_year', 'esrm_save_course_year_new_fields', 10, 2 );
add_action( 'created_course_year', 'esrm_save_course_year_new_fields', 10, 2 );

function esrm_save_course_year_new_fields( $term_id, $tt_id ) {
    if ( !$term_id || !isset( $_POST['course_year_color'] ) ) {
        return;
    }

    if ( '#' == $_POST['course_year_color'] || preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $_POST['course_year_color'] ) ) {
        update_term_meta( $term_id, 'course_year_color', sanitize_text_field( $_POST['course_year_color'] ) );
    }
}


// Register function to be called when displaying post filter drop-down lists
add_action( 'restrict_manage_posts', 'esrm_course_year_filter_list' );

// Function to display soil_sample type drop-down list for soil_sample reviews
function esrm_course_year_filter_list() {
    $screen = get_current_screen();
    if ( 'soil_samples' != $screen->post_type ) {
        return;
    }

    global $wp_query;
    wp_dropdown_categories( array(
        'show_option_all' => 'Show All Years',
        'taxonomy' => 'course_year',
        'name' => 'course_year',
        'orderby' => 'name',
        'selected' => ( isset( $wp_query->query['course_year'] ) ? $wp_query->query['course_year'] : '' ),
        'hierarchical' => false,
        'depth' => 3,
        'show_count' => false,
        'hide_empty' => true,
    ) );
}


/**************************** End of course_year **********************************/


/**
 * lab_section
 */
add_action( 'lab_section_edit_form_fields', 'esrm_lab_section_new_fields', 10, 2 );
add_action( 'lab_section_add_form_fields', 'esrm_lab_section_new_fields', 10, 2 );

function esrm_lab_section_new_fields( $tag ) {
    $mode = 'new';

    if ( is_object( $tag ) ) {
        $mode = 'edit';
        $cat_color = get_term_meta( $tag->term_id, 'lab_section_color', true );
    }
    $cat_color = empty( $cat_color ) ? '#' : $cat_color;

    if ( 'edit' == $mode ) {
        echo '<tr class="form-field">';
        echo '<th scope="row" valign="top">';
    } elseif ( 'new' == $mode ) {
        echo '<div class="form-field">';
    } ?>

    <label for="lab_section_color">Color</label>
    <?php if ( 'edit' == $mode ) {
        echo '</th><td>';
    } ?>

    <input type="text" id="lab_section_color" name="lab_section_color" value="<?php echo $cat_color; ?>" />
    <p class="description">Color associated with soil_sample type (e.g. #199C27 or #CCC)</p>

    <?php if ( 'edit' == $mode ) {
        echo '</td></tr>';
    } elseif ( 'new' == $mode ) {
        echo '</div>';
    }
}

add_action( 'edited_lab_section', 'esrm_save_lab_section_new_fields', 10, 2 );
add_action( 'created_lab_section', 'esrm_save_lab_section_new_fields', 10, 2 );

function esrm_save_lab_section_new_fields( $term_id, $tt_id ) {
    if ( !$term_id || !isset( $_POST['lab_section_color'] ) ) {
        return;
    }

    if ( '#' == $_POST['lab_section_color'] || preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $_POST['lab_section_color'] ) ) {
        update_term_meta( $term_id, 'lab_section_color', sanitize_text_field( $_POST['lab_section_color'] ) );
    }
}

// Register function to be called when displaying post filter drop-down lists
add_action( 'restrict_manage_posts', 'esrm_lab_section_filter_list' );

// Function to display soil_sample type drop-down list for soil_sample reviews
function esrm_lab_section_filter_list() {
    $screen = get_current_screen();
    if ( 'soil_samples' != $screen->post_type ) {
        return;
    }

    global $wp_query;
    wp_dropdown_categories( array(
        'show_option_all' => 'Show All Lab Sections',
        'taxonomy' => 'lab_section',
        'name' => 'lab_section',
        'orderby' => 'name',
        'selected' => ( isset( $wp_query->query['lab_section'] ) ? $wp_query->query['lab_section'] : '' ),
        'hierarchical' => false,
        'depth' => 3,
        'show_count' => false,
        'hide_empty' => true,
    ) );
}

/**************************** End of lab_section **********************************/


/**
 * course_institution
 */
add_action( 'course_institution_edit_form_fields', 'esrm_course_institution_new_fields', 10, 2 );
add_action( 'course_institution_add_form_fields', 'esrm_course_institution_new_fields', 10, 2 );

function esrm_course_institution_new_fields( $tag ) {
    $mode = 'new';

    if ( is_object( $tag ) ) {
        $mode = 'edit';
        $cat_color = get_term_meta( $tag->term_id, 'course_institution_color', true );
    }
    $cat_color = empty( $cat_color ) ? '#' : $cat_color;

    if ( 'edit' == $mode ) {
        echo '<tr class="form-field">';
        echo '<th scope="row" valign="top">';
    } elseif ( 'new' == $mode ) {
        echo '<div class="form-field">';
    } ?>

    <label for="course_institution_color">Color</label>
    <?php if ( 'edit' == $mode ) {
        echo '</th><td>';
    } ?>

    <input type="text" id="course_institution_color" name="course_institution_color" value="<?php echo $cat_color; ?>" />
    <p class="description">Color associated with soil_sample type (e.g. #199C27 or #CCC)</p>

    <?php if ( 'edit' == $mode ) {
        echo '</td></tr>';
    } elseif ( 'new' == $mode ) {
        echo '</div>';
    }
}

add_action( 'edited_course_institution', 'esrm_save_course_institution_new_fields', 10, 2 );
add_action( 'created_course_institution', 'esrm_save_course_institution_new_fields', 10, 2 );

function esrm_save_course_institution_new_fields( $term_id, $tt_id ) {
    if ( !$term_id || !isset( $_POST['course_institution_color'] ) ) {
        return;
    }

    if ( '#' == $_POST['course_institution_color'] || preg_match( '/#([a-f0-9]{3}){1,2}\b/i', $_POST['course_institution_color'] ) ) {
        update_term_meta( $term_id, 'course_institution_color', sanitize_text_field( $_POST['course_institution_color'] ) );
    }
}

// Register function to be called when displaying post filter drop-down lists
add_action( 'restrict_manage_posts', 'esrm_course_institution_filter_list' );

// Function to display soil_sample type drop-down list for soil_sample reviews
function esrm_course_institution_filter_list() {
    $screen = get_current_screen();
    if ( 'soil_samples' != $screen->post_type ) {
        return;
    }

    global $wp_query;
    wp_dropdown_categories( array(
        'show_option_all' => 'Show All Institutions',
        'taxonomy' => 'course_institution',
        'name' => 'course_institution',
        'orderby' => 'name',
        'selected' => ( isset( $wp_query->query['course_institution'] ) ? $wp_query->query['course_institution'] : '' ),
        'hierarchical' => false,
        'depth' => 3,
        'show_count' => false,
        'hide_empty' => true,
    ) );
}




