<?php

declare(strict_types=1);

if (!function_exists('redirect')) {
    /**
     * Redirect the user to given path.
     *
     * @param string $path
     *
     * @return void
     */
    function redirect(string $path)
    {
        header("Location: ${path}");
        exit;
    }
}

/**
 * Removes given directory and all its content.
 *
 * @param string $dir
 *
 * @return void
 */
if(!function_exists('rrmdir')) {

    function rrmdir($dir) {

        foreach(glob($dir . '/*') as $file) {

            if(is_dir($file)) rrmdir($file);
            else unlink($file);

        } rmdir($dir);
    }
}
