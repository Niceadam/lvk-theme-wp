<?php
/**
 * @package WordPress
 * @subpackage Timberland
 * @since Timberland 2.0.1
 */

$context = Timber::context();
$post = $context['post'];

Timber::render('single.twig', $context);
