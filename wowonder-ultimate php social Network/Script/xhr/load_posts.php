<?php 
if ($f == 'load_posts') {
    $load = sanitize_output(Wo_LoadPage('home/load-posts'));
    echo $load;
    exit();
}
