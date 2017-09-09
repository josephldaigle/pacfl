<?php

/* Template Name: PAC Sponsors */

?>
<?php get_header(); ?>
<div class="row">


<!--Content-->
<div id="sub_banner">
<h1>
<?php the_title(); ?>
</h1>
</div>
<div id="content">
<div class="top-content" style="width: 100% !important; padding-left: 0px !important;">

<!-- SPONSORS -->
<div class="work-carousel">
	<div class="carousel_content">
	<div class="caroufredsel_wrapper" >
	<!-- ROW 1 -->
		<div id="row">
				<ul>
				
				<li>
					
					<?php $image_attributes = wp_get_attachment_image_src( 154, 'large'); ?>

					<?php if( $image_attributes ) { ?>
					<div class="client">
					
					<a href="https://www.ccbg.com"><img  src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" alt="" /></a>
					</div>
					
					<?php } ?>
					</li>
					
					
					<li>
					
					<?php $image_attributes = wp_get_attachment_image_src( 110, 'large'); ?>

					<?php if( $image_attributes ) { ?>
					<div class="client">
					
					<a href="http://www.genesisdoor.com/"><img  src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" alt="" /></a> 
					</div>
					
					<?php } ?>
					</li>
					
					<li>
					
					<?php $image_attributes = wp_get_attachment_image_src( 183, 'large'); ?>

					<?php if( $image_attributes ) { ?>
					<div class="client">
					
					<a href="http://www.palmsmg.org"><img  src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" alt="" /></a> 
					</div>
					
					<?php } ?>
					</li>
					
					<li>
					
					<?php $image_attributes = wp_get_attachment_image_src( 182, 'large'); ?>

					<?php if( $image_attributes ) { ?>
					<div class="client">
					
					<a href="http://www.rollinsspeedandcustom.com"><img  src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" alt="" /></a> 
					</div>
					
					<?php } ?>
					</li>
									
				</ul>
		</div>
		
		<!-- ROW 2 -->
		<div id="row">
				<ul>
				
				<li>
					
					<?php $image_attributes = wp_get_attachment_image_src( 163, 'large'); ?>

					<?php if( $image_attributes ) { ?>
					<div class="client">
					
					<a href="http://ww3.truevalue.com/highspringsfl"><img  src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" alt="" /></a>
					</div>
					
					<?php } ?>
					</li>
					
					
					<li>
					
					<?php $image_attributes = wp_get_attachment_image_src( 175, 'large'); ?>

					<?php if( $image_attributes ) { ?>
					<div class="client">
					
					<a href="http://gilchristcountyjournal.net"><img  src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" alt="" /></a> 
					</div>
					
					<?php } ?>
					</li>



<li>
					
					<?php $image_attributes = wp_get_attachment_image_src( 201, 'large'); ?>

					<?php if( $image_attributes ) { ?>
					<div class="client">
					
					<a href="https://www.facebook.com/pages/red-Williams-auction/116679861751260"><img  src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" alt="" /></a> 
					</div>
					
					<?php } ?>
					</li>
					
					
					
					
					<li>
					
					<?php $image_attributes = wp_get_attachment_image_src( 215, 'large'); ?>

					<?php if( $image_attributes ) { ?>
					<div class="client">
					
					<a href="#"><img  src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>" alt="" /></a> 
					</div>
					
					<?php } ?>
					</li>				
				</ul>
		</div>
		
</div>		
	

</div>
</div>

<?php get_footer(); ?>