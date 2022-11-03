<?php
/*
  Plugin Name: Soil Samples V1
  Plugin URI:
  Description: Soil Sample Custom Post Type for ESRM Project
  Author: Bill Kudrle
  Version: 1.0
  Author URI: http://esrm-portal.org
 */

/****************************************************************************
 * Split code up into manageable pieces by separating out the action based
 * functions.
 ****************************************************************************/
include_once 'includes/soil_sample_actions.php';

/************************************************************************************
 * Display single custom post type items using a custom layout
 ************************************************************************************/
add_filter( 'template_include', 'esrm_template_include', 1 );

function esrm_template_include( $template_path ) {
    if ( is_single() && 'soil_samples' == get_post_type()) {
        // checks if the file exists in theme first,
        // otherwise install content filter
        if ( $theme_file = locate_template( array( 'single-soil_samples.php' ) ) ) {
            return $theme_file;
        } else {
            add_filter( 'the_content', 'esrm_display_single_soil_sample',  20 );
        }
    }
    return $template_path;
}

/**
 * Display the Soil Sample data on the public website
 * @param $content
 * @return string|void
 */
function esrm_display_single_soil_sample( $content ) {

    if ( empty( get_the_ID() ) ) {
        return;
    }

    // Display featured image in right-aligned floating div
    $content = '<div style="float: right; margin: 10px">';
    $content .= get_the_post_thumbnail( get_the_ID(), 'medium' );
    $content .= '</div>';

    $content .= '<div class="entry-content">';

    // Display Author Name
    $content .= '<strong>Author: </strong>';
    $content .= esc_html( get_post_meta( get_the_ID(), 'soil_sample_author', true ) );
    $content .= '<br />';

    // Display Paraoxon count
    $content .= '<strong>Paraoxon Count: </strong>';
    $content .= esc_html( get_post_meta( get_the_ID(), 'soil_sample_paraoxon_count', true ) );
    $content .= '<br />';

    // Display Methyl Parathion count
    $content .= '<strong>Methyl Parthion Count: </strong>';
    $content .= esc_html( get_post_meta( get_the_ID(), 'soil_sample_methyl_parathion_count', true ) );
    $content .= '<br />';

    // Display the sample classification
    $soil_sample_classifications = wp_get_post_terms( get_the_ID(), 'soil_sample_classification' );
    $content .= '<br /><strong>Classification: </strong>';
    if ( $soil_sample_classifications ) {
        $classifications_array = array();
        foreach ( $soil_sample_classifications as $soil_sample_classification ) {
            $classifications_array[] = $soil_sample_classification->name;
        }
        $content .= esc_html( implode( ',', $classifications_array ) );
    } else {
        $content .= 'None Assigned';
    }

    // Display the semester
    $course_semesters = wp_get_post_terms( get_the_ID(), 'course_semester' );
    $content .= '<br /><strong>Semester: </strong>';
    if ( $course_semesters ) {
        $semesters_array = array();
        foreach ( $course_semesters as $course_semester ) {
            $semesters_array[] = $course_semester->name;
        }
        $content .= esc_html( implode( ',', $semesters_array ) );
    } else {
        $content .= 'None Assigned';
    }

    // Display the year
    $course_years = wp_get_post_terms( get_the_ID(), 'course_year' );
    $content .= '<br /><strong>Year: </strong>';
    if ( $course_years ) {
        $years_array = array();
        foreach ( $course_years as $course_year ) {
            $years_array[] = $course_year->name;
        }
        $content .= esc_html( implode( ',', $years_array ) );
    } else {
        $content .= 'None Assigned';
    }

    // Display the Lab Section
    $lab_sections = wp_get_post_terms( get_the_ID(), 'lab_section' );
    $content .= '<br /><strong>Lab Section: </strong>';
    if ( $lab_sections ) {
        $sections_array = array();
        foreach ( $lab_sections as $lab_section ) {
            $sections_array[] = $lab_section->name;
        }
        $content .= esc_html( implode( ',', $sections_array ) );
    } else {
        $content .= 'None Assigned';
    }

    // Display the institution
    $course_institutions = wp_get_post_terms( get_the_ID(), 'course_institution' );
    $content .= '<br /><strong>Institution: </strong>';
    if ( $course_institutions ) {
        $institutions_array = array();
        foreach ( $course_institutions as $course_institution ) {
            $institutions_array[] = $course_institution->name;
        }
        $content .= esc_html( implode( ',', $institutions_array ) );
    } else {
        $content .= 'None Assigned';
    }

    // Display soil_sample contents
    $content .= '<h4>Description:</h4>';
    $content .= '<br /><br />' . get_the_content( get_the_ID() ) . '</div>';

    return $content;
}

/****************************************************************************
 * Code from recipe 'Tailoring search output for Custom Post Type items'
 ****************************************************************************/

add_filter( 'get_the_excerpt', 'esrm_search_display' );
add_filter( 'the_excerpt', 'esrm_search_display' );
add_filter( 'the_content', 'esrm_search_display' );

function esrm_search_display( $content ) {
    if ( !is_search() &&
        'soil_samples' != get_post_type() ) {
        return $content;
    }

    $content =
        '<div style="float: right; margin: 10px">'
        . get_the_post_thumbnail( get_the_ID(), 'medium' )
        . '</div><div class="entry-content">'
        . '<strong>Author: </strong>'
        . esc_html( get_post_meta( get_the_ID(), 'soil_sample_author', true ) );

    $content .= '<br /><br />';
    $content .= wp_trim_words( get_the_content( get_the_ID() ), 20 );
    $content .= '</div>';

    return $content;
}


add_filter( 'the_title', 'esrm_soil_sample_title', 10, 2 );

function esrm_soil_sample_title( $title, $id = null ) {
    if ( !is_admin() && is_search() && !empty( $id ) ) {
        $post = get_post( $id );
        if ( !empty( $post ) && $post->post_type == 'soil_samples' ) {
            return 'Soil Sample: ' . $title;
        }
    }
    return $title;
}

/****************************************************************************
 * Display custom post type data in shortcode
 ****************************************************************************/

add_shortcode( 'soil_sample-list', 'esrm_soil_sample_list_shortcode' );

// Implementation of short code function
function esrm_soil_sample_list_shortcode() {

    // Preparation of query array to retrieve 5 soil_sample items
    $query_params = array( 'post_type' => 'soil_samples',
        'post_status' => 'publish',
        'posts_per_page' => 5 );

    // Retrieve page query variable, if present
    $page_num = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

    // If page number is higher than 1, add to query array
    if ( $page_num != 1 ) {
        $query_params['paged'] = $page_num;
    }

    // Execution of post query
    $soil_sample_query = new WP_Query;
    $soil_sample_query->query( $query_params );

    // Check if any posts were returned by query
    if ( $soil_sample_query->have_posts() ) {
        // Display posts in table layout
        $output = '<table>';
        $output .= '<tr><th style="text-align:left;"> ';
        $output .= '<strong>Title</strong></th>';
        $output .= '<th style="text-align:left">';
        $output .= '<strong>Researcher</strong></th></tr>';

        // Cycle through all items retrieved
        while ( $soil_sample_query->have_posts() ) {
            $soil_sample_query->the_post();
            $output .= '<tr><td style="padding-right: 20px">';
            $output .= '<a href="' . get_permalink();
            $output .= '">' . get_the_title( get_the_ID() );
            $output .= '</a></td><td>';
            $output .= esc_html( get_post_meta( get_the_ID(), 'soil_sample_author', true ) );
            $output .= '</td></tr>';
        }

        $output .= '</table>';

        // Display page navigation links
        if ( $soil_sample_query->max_num_pages > 1 ) {
            $output .= '<nav id="nav-below">';
            $output .= '<div class="nav-previous">';
            $output .= get_next_posts_link( '<span class="meta-nav">&larr;</span> Older reviews', $soil_sample_query->max_num_pages );
            $output .= '</div>';
            $output .= "<div class='nav-next'>";
            $output .= get_previous_posts_link( 'Newer reviews <span class="meta-nav">&rarr;</span>', $soil_sample_query->max_num_pages );
            $output .= '</div>';
            $output .= '</nav>';
        }

        // Reset post data query
        wp_reset_postdata();
    }

    return $output;
}

/****************************************************************************
 * Set up additional column display in custom post list page
 ****************************************************************************/

// Register function to be called when column list is being prepared
add_filter( 'manage_edit-soil_samples_columns', 'esrm_add_columns' );

// Function to add columns for author and type in soil_sample listing
// and remove comments columns
function esrm_add_columns( $columns ) {

    $new_columns = array();
    $new_columns['soil_samples_author'] = 'Researcher';
    //$new_columns['soil_sample_classification'] = 'Classification';
    $new_columns['course_semester'] = 'Semester';

    unset( $columns['comments'] );
    $columns = array_slice( $columns, 0, 2 ) + $new_columns + array_slice( $columns, 2 );

    return $columns;
}

add_filter( 'manage_edit-soil_samples_sortable_columns', 'esrm_author_column_sortable' );

// Register the author and rating columns are sortable columns
function esrm_author_column_sortable( $columns ) {
    $columns['soil_samples_author'] = 'soil_samples_author';
    //$columns['soil_sample_classification'] = 'soil_sample_classification';
    $columns['course_semester'] = 'course_semester';

    return $columns;
}

// Register function to be called when queries are being prepared to
// display post listing
add_filter( 'request', 'esrm_column_ordering' );

// Function to add elements to query variable based on incoming arguments
function esrm_column_ordering( $vars ) {
    if ( !is_admin() ) {
        return $vars;
    }

    if ( isset( $vars['orderby'] ) && 'soil_samples_author' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'soil_sample_author',
            'orderby' => 'meta_value'
        ) );
    }
    elseif ( isset( $vars['orderby'] ) && 'soil_sample_classification' == $vars['orderby'] ) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'soil_sample_classification',
            'orderby' => 'meta_value_num'
        ) );
    }

    return $vars;
}

// Register function to be called when preparing post query
add_filter( 'parse_query', 'esrm_perform_soil_sample_classification_filtering' );

// Function to modify query variable based on filter selection
function esrm_perform_soil_sample_classification_filtering( $query ) {

    $qv = &$query->query_vars;

    if ( isset( $qv['soil_sample_classification'] ) &&
        !empty( $qv['soil_sample_classification'] ) &&
        is_numeric( $qv['soil_sample_classification'] ) ) {

        $term = get_term_by( 'id',$qv['soil_sample_classification'],'soil_sample_classification' );
        $qv['soil_sample_classification'] = $term->slug;
    }
}

// Register function to be called when preparing post query
add_filter( 'parse_query', 'esrm_perform_course_semester_filtering' );

// Function to modify query variable based on filter selection
function esrm_perform_course_semester_filtering( $query ) {

    $qv = &$query->query_vars;

    if ( isset( $qv['course_semester'] ) &&
        !empty( $qv['course_semester'] ) &&
        is_numeric( $qv['course_semester'] ) ) {

        $term = get_term_by( 'id',$qv['course_semester'],'course_semester' );
        $qv['course_semester'] = $term->slug;
    }
}


add_filter( 'post_row_actions', 'esrm_quick_edit_link', 10, 2 );

function esrm_quick_edit_link( $act, $post )    {

    global $current_screen;
    $post_id = '';

    if ( ( isset( $current_screen ) && $current_screen->id != 'edit-soil_samples' && $current_screen->post_type != 'soil_samples' ) || ( isset( $_POST['screen'] ) && $_POST['screen'] != 'edit-soil_samples' ) ) {
        return $act;
    }

    if ( !empty( $post->ID ) ) {
        $post_id = $post->ID;
    } elseif ( isset( $_POST['post_ID'] ) ) {
        $post_id = intval( $_POST['post_ID'] );
    }

    if ( !empty( $post_id ) ) {
        $soil_sample_author = esc_html( get_post_meta( $post_id, 'soil_sample_author', true ) );
        $soil_sample_classifications = wp_get_post_terms( $post_id, 'soil_sample_classification', array( 'fields' => 'all' ) );
        $course_semesters = wp_get_post_terms( $post_id, 'course_semester', array( 'fields' => 'all' ) );

        if ( empty( $soil_sample_classifications ) ) {
            $soil_sample_classification = 0;
        } else {
            $soil_sample_classification = $soil_sample_classifications[0]->term_id;
        }

        if ( empty( $course_semesters ) ) {
            $course_semester = 0;
        } else {
            $course_semester = $course_semesters[0]->term_id;
        }

        $idx = 'inline hide-if-no-js';
        $act[$idx] = '<a href="#" class="editinline" '
            . " onclick=\"var reviewArray = "
            . "new Array('"
            . "{$soil_sample_author}', "
            . "'{$soil_sample_classification}', "
            . "'{$course_semester}');"
            . "set_inline_soil_samples(reviewArray)\">"
            . __( 'Quick&nbsp;Edit' )
            . '</a>';

    }
    return $act;
}


/****************************************************************************
 * Code from recipe 'Updating page title to include custom post data using
 * plugin filters'
 ****************************************************************************/
add_filter( 'document_title_parts', 'esrm_format_soil_sample_review_title' );

function esrm_format_soil_sample_review_title( $the_title )     {

    if ( 'soil_samples' == get_post_type() && is_single() ) {
        $soil_sample_author = esc_html( get_post_meta( get_the_ID(),
            'soil_sample_author', true ) );
        if ( !empty( $soil_sample_author ) ) {
            $the_title['title'] .= ' by ';
            $the_title['title'] .= $soil_sample_author;
        }
    }

    return $the_title;
}

