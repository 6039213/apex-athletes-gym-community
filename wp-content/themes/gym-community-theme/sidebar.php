<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Gym_Community_Theme
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
    return;
}
?>

<aside id="secondary" class="sidebar">
    <?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside>
