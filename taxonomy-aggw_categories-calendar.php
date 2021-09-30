<?php get_header('gw'); ?>



<?php 
if ($_GET["aggw_school"]):
$catA = $_GET["aggw_school"];
endif;
if ($_GET["aggw_tags"]):
$catB = $_GET["aggw_tags"];
endif;
if ($_GET["paged"]):
  $paged = $_GET["paged"];
endif;
// var_dump($catA);
// var_dump($catB);

//IF CAT AB IS ON
//$paged = get_query_var('paged') ? get_query_var('paged') : 1;

if (($_GET["aggw_school"])&&($_GET["aggw_tags"])):
$args = array(
  'post_type' => 'gw_post',
  'meta_query' =>  array(
    'relation' => 'AND',
    'events_date' => array(
        'key'     => 'MOD-events_date',
      ),
    ),
    'orderby' => array(
      'events_date' => 'ASC'
 ),
  'tax_query' => array(
    'relation' => 'AND',
      array(
          'taxonomy' => 'aggw_categories',
          'field'    => 'slug',
          'terms'    => 'calendar',
          'operator' => 'AND',
      ),
      array(
          'taxonomy' => 'aggw_school',
          'field'    => 'slug',
          'terms'    => $catA,
          'operator' => 'AND',
      ),
      array(
        'taxonomy' => 'aggw_tags',
        'field'    => 'slug',
        'terms'    => $catB,
        'operator' => 'AND',
    ),
  ),
  'post_status' =>  array( 'publish' ),
  'posts_per_page' =>  3,
  'paged' => $paged
);

//ELSE IF CAT A IS ON
elseif ($_GET["aggw_school"]):
$args = array(
  'post_type' => 'gw_post',
  'meta_query' =>  array(
    'relation' => 'AND',
    'events_date' => array(
        'key'     => 'MOD-events_date',
      ),
    ),
    'orderby' => array(
      'events_date' => 'ASC'
 ),
  'tax_query' => array(
    'relation' => 'AND',
      array(
          'taxonomy' => 'aggw_categories',
          'field'    => 'slug',
          'terms'    => 'calendar',
          'operator' => 'AND',
      ),
      array(
          'taxonomy' => 'aggw_school',
          'field'    => 'slug',
          'terms'    => $catA,
          'operator' => 'AND',
      ),
  ),
  'post_status' =>  array( 'publish' ),
  'posts_per_page' =>  3,
  'paged' => $paged
);
elseif ($_GET["aggw_tags"]):
//ESLE IF CAT B IS ON
$args = array(
  'post_type' => 'gw_post',
  'meta_query' =>  array(
    'relation' => 'AND',
    'events_date' => array(
        'key'     => 'MOD-events_date',
      ),
    ),
    'orderby' => array(
      'events_date' => 'ASC'
 ),
  'tax_query' => array(
    'relation' => 'AND',
      array(
          'taxonomy' => 'aggw_categories',
          'field'    => 'slug',
          'terms'    => 'calendar',
          'operator' => 'AND',
      ),
      array(
        'taxonomy' => 'aggw_tags',
        'field'    => 'slug',
        'terms'    => $catB,
        'operator' => 'AND',
    ),
  ),
  'post_status' =>  array( 'publish' ),
  'posts_per_page' =>  3,
  'paged' => $paged
);
endif;

$the_query = new WP_Query($args);
//$the_query = query_posts($args);
//var_dump($args);
//var_dump($the_query);

?>

<!--/ CONTENT /-->
<div class="content" id="pjax-content" data-template="sub">
      <div class="sub">
        <div class="sub__wrapper">
              <?php get_template_part('partials/gw/events/hero') ?>
              <?php get_template_part('partials/gw/events/breadcrumb') ?>
          <main class="main">

          
            <!--/ CATEGPRY /-->
            <div class="post__category">
                <?php get_template_part('partials/gw/events/categories') ?>
            </div>
            <!--/ NEWS /-->
            <div class="news__posts">
              <?php if($the_query->have_posts()): ?>
                <?php while($the_query->have_posts()): $the_query->the_post(); ?>

                <a class="js-async news__updates__item" href="<?php the_permalink(); ?>">
                    <div class="news__updates__date">
                    <?php 
                         $date = strtotime(get_field('MOD-events_date'));
                         //var_dump($date);
                    ?>
                    <?php if(get_field('MOD-events_date_free')): echo '<p class="post__date__day">' . get_field('MOD-events_date_free') . '</p>';
                          else: echo '<p class="post__date__day">' . date('m.d', $date) . '</p>';
                    endif; ?>
                    <p class="post__date__year-month"><?php echo date('Y', $date); ?></p>
                    </div>
                    <div class="news__updates__text">
                    <div class="news__updates__text__wrapper">
                        <?php if(get_field('eng_title')): echo '<p class="post__text--en">' . get_field('eng_title') . '</p>'; endif; ?>
                        <?php if(get_the_title()): echo '<p class="post__text--ja">' . get_the_title() . '</p>'; endif; ?>
                    </div>
                    <div class="post__tag">
                        <ul class="post__tag__list">
                        <?php
                            $terms = get_the_terms( $post->ID , 'aggw_school' ); 
                            foreach ( $terms as $term ) {
                                $term_link = get_term_link( $term, 'aggw_school' );
                                if( is_wp_error( $term_link ) )
                                continue;
                                echo '<li class="post__tag__item"><div class="post__tag__link">＃' . $term->name . '</div></li>';
                            } 
                            $terms = get_the_terms( $post->ID , 'aggw_tags' ); 
                            foreach ( $terms as $term ) {
                                $term_link = get_term_link( $term, 'aggw_tags' );
                                if( is_wp_error( $term_link ) )
                                continue;
                                echo '<li class="post__tag__item"><div class="post__tag__link">＃' . $term->name . '</div></li>';
                            } 
                        ?>
                        </ul>
                    </div>
                    </div>
                </a>

                <?php endwhile; ?>

                <?php if (function_exists("pagination")) {
                  pagination($the_query->max_num_pages);
                  }
                ?>

              <?php else :?>
                <div class="news__updates__item">
                  <div class="news__updates__text__wrapper">
                    <p class="post__text--en">There were not any related Events</p>
                    <p class="post__text--ja">該当の記事はありません。</p>
                  </div>
                </div>
              <?php endif; ?>
            </div>

        
          </main>


<?php wp_reset_postdata(); ?>
<?php get_footer('gw'); ?>
