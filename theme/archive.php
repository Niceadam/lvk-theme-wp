<?php
/**
 * @package WordPress
 * @subpackage Timberland
 * @since Timberland 2.0.1
 */

$templates = array( 'archive.twig', 'index.twig' );

$context = Timber::context();
$context['posts'] = Timber::get_posts();
Timber::render( $templates, $context );
