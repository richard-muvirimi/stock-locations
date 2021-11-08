<?php

function rename_dir($target, $match = "", $replace = "")
{
    $paths = scandir($target);

    foreach ($paths as $file) {
        if (!in_array($file, array(".", ".."))) {

            $path = rtrim($target, "\\/") . DIRECTORY_SEPARATOR . $file;

            if (is_dir($path)) {

                //if dir rescan
                rename_dir($path, $match, $replace);
            } else {

                //file_put_contents($path, preg_replace($match, $replace, file_get_contents($path)));
            }

            @rename($path, rtrim($target, "\\/") . DIRECTORY_SEPARATOR . preg_replace($match, $replace, basename($path)));
        }
    }
}

rename_dir(__DIR__, "/stock(-|_| )locations(-|_| )woocommerce/i", "stock$1locations");