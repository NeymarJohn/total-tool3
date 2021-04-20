<?php
if (!function_exists('s3_site_url')) {
    function s3_site_url($uri = '')
    {
        return (AWS_URL . $uri);
    }
}

if (!function_exists('get_aws_path')) {
    function get_aws_path($path = '', $file_name = '')
    {
        $new_path = '';
        $new_path = AWS_URL . $path . $file_name;
        return $new_path;
    }
}