<?php

if (!function_exists('snake_to_title')) { 

    /**
     * Converts a snake_case string to Title Case.
     *
     *
     * @param string $value The snake_case input string.
     * @return string The converted Title Case string.
     */
    function snake_to_title(string $value): string 
    {
        return ucwords(str_replace('_', ' ', $value));
    }
}




// Generate a URL-safe slug from a string
if (!function_exists('slugify_string')) {

    /**
     * Converts a string into a URL-friendly slug.
     *
     *
     * @param string $text The input string to slugify.
     * @return string The slugified version of the input.
     */
    function slugify_string(string $text): string 
    {
        
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);

        $text = preg_replace('~[^-\w]+~', '', $text);

        $text = trim($text, '-');

        $text = preg_replace('~-+~', '-', $text);

        $text = strtolower($text);

        return $text;
    }

}
