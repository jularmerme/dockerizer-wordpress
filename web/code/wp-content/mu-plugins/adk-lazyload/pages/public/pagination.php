<?php
    if(isset($max_num_pages)){
        echo '<ul class="'.$pag_class_block.' adkll-sc-classicPagination">';
        if($max_num_pages > 1){
            $disabledFirst = ($paged == 1) ? $pag_class_disabled : "";
            $disabledLast = ($paged == $max_num_pages) ? $pag_class_disabled : "";
            $prev = ($paged <= 1) ? 1 : $paged - 1;
            $next = ($paged >= $max_num_pages) ? $max_num_pages : $paged + 1;

            $start = 1;
            $ends = $max_num_pages;
            if($max_num_pages - $start > 5){
                $start = ($paged - 2 < 1) ? 1 : $paged - 2;
                if($start + 4 <= $max_num_pages){
                    $ends = $start + 4;
                }else{
                    $start = ($max_num_pages - 4 >= 1) ? $max_num_pages - 4 : 1;
                }
            }
            
            echo '<li class="'.$pag_class_item.'"><a href="#" value="1" class="btnChangePage '.$pag_class_link.' '.$disabledFirst.'"><<</a></li>';
            echo '<li class="'.$pag_class_item.'"><a href="#" value="'.$prev.'" class="btnChangePage '.$pag_class_link.' '.$disabledFirst.'"><</a></li>';
            for($i=$start; $i<=$ends; $i++){
                $is_active = "";
                if($i == $paged){
                    $is_active = $pag_class_active;
                }
                echo '<li class="'.$pag_class_item.'"><a href="#" value="'.$i.'" class="btnChangePage '.$pag_class_link.' '.$is_active.'">'.$i.'</a></li>';
            }
            echo '<li class="'.$pag_class_item.'"><a href="#" value="'.$next.'" class="btnChangePage '.$pag_class_link.' '.$disabledLast.'">></a></li>';
            echo '<li class="'.$pag_class_item.'"><a href="#" value="'.$max_num_pages.'" class="btnChangePage '.$pag_class_link.' '.$disabledLast.'">>></a></li>';
        }
        echo '</ul>';
    }
?>