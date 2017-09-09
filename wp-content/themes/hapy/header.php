<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 */
?>

<!DOCTYPE html >
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
  
 <?php wp_title('-',true,'left'); ?>

</title>

<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />	
	<?php wp_head(); ?>

</head>


<body <?php body_class(); ?>>


<!--HEADER START-->

<?php if( get_option( 'hapy' )){ ?>
 
 <?php get_template_part(''.$head = of_get_option('head_select', 'header').''); ?>
<?php }else{ ?>
 
 <?php get_template_part('dummy/dummy','head2'); ?>
        <?php } ?> 