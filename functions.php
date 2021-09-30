<?php


//PAGENATION

function pagination($pages = '', $range = 2)
{
     $showitems = ($range * 2)+1;//表示するページ数（５ページを表示）

     global $paged;//現在のページ値
     if(empty($paged)) $paged = 1;//デフォルトのページ

     if($pages == '')
     {
         global $wp_query;
         $pages = $wp_query->max_num_pages;//全ページ数を取得
         if(!$pages)//全ページ数が空の場合は、１とする
         {
             $pages = 1;
         }
     }
     if(1 != $pages)//全ページが１でない場合はページネーションを表示する
     {
		 echo "<ul class=\"pager__list\">\n";
		 //Prev：現在のページ値が１より大きい場合は表示
         if($paged > 1) echo "<li class=\"pager__item\"><a class=\"js-async pager__link prev\" href='".get_pagenum_link($paged - 1)."'>Prev</a></li>\n";

         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                //三項演算子での条件分岐
                echo ($paged == $i)? "<li class=\"is-current pager__item\"><a class=\"js-async pager__link is-active pager__link\" href='".get_pagenum_link($i)."'>".$i."</a></li>\n":"<li class=\"pager__item\"><a class=\"js-async pager__link\" href='".get_pagenum_link($i)."'>".$i."</a></li>\n";
             }
         }
      //Next：総ページ数より現在のページ値が小さい場合は表示
        if ($paged < $pages)
        {
          echo "<li class=\"is-next pager__item\"><a class=\"is-next pager__link next\" href=\"".get_pagenum_link($paged + 1)."\">Next</a></li>";
          echo "</ul>\n";
        }
     }
}
