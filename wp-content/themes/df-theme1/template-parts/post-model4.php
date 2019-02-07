<?php
$mpost = get_post();
$mcats=Array();
$category = get_the_category();
$firstCategory = $category[0]->cat_name;
?>

<div class="clearfix model4" style="background-image: url(<?php echo the_post_thumbnail_url('portfolio-square');?>); background-position-y:center; background-size: 50%; background-color: #ffffff;">
    <div class="post_info_item category">
        <a href="<?php echo get_category_link($category[0]->cat_ID)?>"><?php echo $firstCategory;?> </a>
    </div>
    <div class="latest_post">
        <div class="latest_post_text">
            <div class="latest_post_inner">
                <div class="latest_post_text_inner">
 					<h2 class="latest_post_title ontex_title"><a href="<?php echo get_the_permalink();?>"><?php echo $mpost->post_title;?></a></h2>
                    <span class="post_infos">
						<span class="date_hour_holder">
							<span class="date"><?php echo get_the_date();?> </span>
						</span>
						<!--<a href="<?php echo get_the_author_link()?>"> - <?php echo get_the_author();?></a>-->
						 by <?php echo get_the_author_link()?>
					</span>
				</div>
            </div>
        </div>
        
        <!-- <div class="outils">
            <span class="blog_like">
			    <?php if( function_exists('qode_like') ) qode_like(get_the_ID()); ?>
			</span>
			<a href="<?php echo get_the_permalink();?>" class="comment"><span><?php echo get_comments_number();?></span><i class="fa fa fa-comment" aria-hidden="true"></i></a>
			<a href="mailto:?subject=Ontex Connect - I want to share this news - <?php echo get_the_title();?>&amp;body=I would like to Share with you this intersting news - <?php echo get_the_permalink();?>" class="email">
			<i class="fa fa fa-envelope" aria-hidden="true"></i></a><a href="<?php echo get_the_permalink();?>?print" class="print"><i class="fa fa-print" aria-hidden="true"></i></a>
       </div> -->
        
    </div>
</div>



