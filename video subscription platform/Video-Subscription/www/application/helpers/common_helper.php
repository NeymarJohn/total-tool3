<?php
if (!function_exists('alert')) {
    function alert($msg, $color = 'success')
    {
        if ($color === 'success') {
            $class = "fa fa-check";
        } elseif ($color === 'warning') {
            $class = "fa fa-lightbulb-o";
        } elseif ($color === 'danger') {
            $class = "fa fa-exclamation";
        } else {
            $class = "";
        }
        return '<div class="alert alert-'.$color.'">
                    <div class="notification-body tip">
    		            <i class="'.$class.'"></i><div>'.$msg.'</div>
                    </div>
    			</div>';
    }
}

if (!function_exists('trim_max')) {
    function trim_max($text, $size = 50)
    {
        return substr($text, 0, $size) . ' ...';
    }
}

if (!function_exists('convertTexte')) {
    function convertTexte($text)
    {
        if (get_magic_quotes_gpc()) {
            $text = stripslashes($text);
        }
        $text = str_replace("'", "\'", $text);
        return $text;
    }
}

if (!function_exists('addQuote')) {
    function addQuote($text)
    {
        return('"'.$text.'"');
    }
}

if (!function_exists('delQuote')) {
    function delQuote($text)
    {
        return trim($text, '"');
    }
}

if (!function_exists('checkUrl')) {
    function checkUrl($url, $seg1)
    {
        $segUrl = explode('/', filter_var($url, FILTER_VALIDATE_URL));
        if (isset($segUrl[2]) && $segUrl[2] == $seg1) {
            return $url;
        } else {
            return null;
        }
    }
}

if (!function_exists('rating')) {
    function rating($nb, $class = '')
    {
        $is_int = (is_int($nb))?0:1;
        $i = $j = ceil($nb);
        $rating = '<div class="'.$class.' rating">
    	            	<ul class="list-inline">';
        for ($i; $i > $is_int; $i--) {
            $rating .= '<li><a class="fa fa-star"></a></li>';
        }
        $rating .= (is_int($nb))?'':'<li><a class="fa fa-star-half-o"></a></li>';
        for ($j; $j < 5; $j++) {
            $rating .= '<li><a class="fa fa-star-o"></a></li>';
        }
        $rating .= '	</ul>
    				</div>';
        return $rating;
    }
}

if (!function_exists('random')) {
    function random($car)
    {
        $string = "";
        $chaine = "abcdefghijklmnpqrstuvwxy0123456789";
        srand((double)microtime()*1000000);
        for ($i=0; $i<$car; $i++) {
            $string .= $chaine[rand()%strlen($chaine)];
        }
        return $string;
    }
}

if (!function_exists('share')) {
    function share($url = '', $title = '', $description = '')
    {
        $share = '<a class="btn btn-facebook waves-effect waves-light m-r-5 m-b-5" href="http://www.facebook.com/sharer.php?u='.$url.'&t='.$title.'" target="_blank"> <span class="btn-label"><i class="fa fa-facebook"></i> </span>Facebook</a>';
        $share .= '<a class="btn btn-twitter waves-effect waves-light m-r-5 m-b-5" href="https://twitter.com/share?url='.$url.'&text='.mb_strimwidth(strip_tags($description), 0, 90, '...').'" target="_blank"> <span class="btn-label"><i class="fa fa-twitter"></i> </span>Twitter</a>';
        $share .= '<a class="btn btn-googleplus waves-effect waves-light m-r-5 m-b-5" href="https://plus.google.com/share?url='.$url.'" target="_blank"> <span class="btn-label"><i class="fa fa-google"></i> </span>Google</a>';
        $share .= '<a class="btn btn-linkedin waves-effect waves-light m-b-5" href="mailto:?subject='.$title.'&body='.$title.':'.$url.'" target="_blank"> <span class="btn-label"> <i class="fa fa-send"></i> </span>Email</a>';
        return $share;
    }
}
