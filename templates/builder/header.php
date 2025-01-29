<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package raw_addons
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>

<body <?php body_class('raw_addons-header-footer'); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

	<?php do_action( 'ant_hf_header_markup' ); ?>

    <div id="content" class="site-content">
		<?php get_template_part( 'views/content', 'banner' ); ?>
