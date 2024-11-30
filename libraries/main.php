<?php

/*
    MAIN FILE FOR LIBRARY IMPORTER FUNCTIONALITY

    Make sure to import this file in every .php
    request to make this library to work.

    For example, in .htaccess you can implement:

        php_value include_path "/path/to/root"
        php_value auto_prepend_file "libraries/main.php"

    Things to be added later:
        * Save directories that have been added, this for not making the process of
          searching the file again. Before doing this, evaluate if this file is
          prepended static or dynamically.

*/

// Iterates through all subfolders
/** (Dont use this function, this is for the library function) */
function _main_search_and_import($directory, &$ignore): void {
    $files_array = scandir($directory, SCANDIR_SORT_NONE);
    foreach ($files_array as $file) {
        if ($file === "." || $file === "..") continue;

        // Imports file if it has a .php extension or if the file is
        // an directory, we call again the function to iterate in it:
        $path = $directory . DIRECTORY_SEPARATOR . $file;

        if (in_array($path, $ignore) === true) {
            continue;
        }

        if (strtolower(substr($path, -4)) === ".php") {
            require_once($path);
        } else if (is_dir($path) === true) {
            _main_search_and_import($path, $ignore);
        }
    }
}

// Discards every comment (from # to \n)
/** (Dont use this function, this is for the library function) */
function _main_discard_comments(string &$content): void {
    $content = trim($content);
    for (;;) {
        $from = strpos($content, "#");
        $to = strpos($content, "\r\n");

        // If there is no new line then we use the length of the content
        if ($to === false) {
            $to = strlen($content);
        }
        
        if ($from === false || $to === false) {
            break;
        }

        // If a comment is added at the end of the file and there is 
        // new lines before it, it would bug. This conditional fixes
        // this.
        if ($to < $from) {
            $to = strlen($content);
        }

        $delete = substr($content, $from, ($to + 2) - $from);
        $content = trim(str_replace($delete, "\r\n", $content));
    }
    $content = str_replace(" ", "", $content);
}

// Get an array of folders/files to ignore
/** (Dont use this function, this is for the library function) */
function _main_files_to_ignore(): array {
    $content = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".ignore");
    if ($content === false) {
        return [];
    }

    _main_discard_comments($content);

    $ignore_files = [];
    $list = explode("\r\n", $content);

    foreach ($list as $value) {
        $path = realpath(__DIR__ . DIRECTORY_SEPARATOR . $value);
        if ($path === false) {
            throw new Exception("file or folder named '$value' is not a valid resource!");
        }
        array_push($ignore_files, $path);
    }

    return $ignore_files;
}

// Verifies if the given path is real and inside the 'libraries' folder
// path ($path given must be passed as the realpath() return value).
/** (Dont use this function, this is for the library function) */
function _main_is_valid_path(bool|string $path): bool {
    return $path !== false && strpos($path, __DIR__) !== false;
}

/** 
 * Imports an individual file or all the descendant files in the 'libraries' folder
 * @param string $uri Folder or file to be imported in the 'libraries' folder
 */
function import(string $uri): void {
    // This if someone for a particular reason and i don't know why, sends
    // an empty string.
    if (trim($uri) === "") {
        throw new Exception("empty string given on import() function");
    }
    
    // Get the directory path and the php file url
    $directory_dir = realpath(__DIR__ . DIRECTORY_SEPARATOR . $uri);
    $resource_dir = __DIR__ . DIRECTORY_SEPARATOR . $uri;
    if (strtolower(substr($resource_dir, -4)) !== ".php") {
        $resource_dir .= ".php";
    }

    // Tries to convert the php url into a real location
    $real_resource = realpath($resource_dir);

    /* 
      If '$real_resource' is a valid path, it only require
      the file, else if the '$directory_dir' is valid, then 
      the directory will be iterated to include all php files.
      
      (If both of these fails, the function throws an error.)
    */
    if (_main_is_valid_path($real_resource) === true) {
        require_once($real_resource);
    } else if (_main_is_valid_path($directory_dir) === true) {
        $ignore = _main_files_to_ignore();
        
        // This is if the same folder we're going to iterate is in the ignore list
        foreach ($ignore as $value) {
            $path = realpath($value);
            if ($path === false) continue;
            if ($path === $directory_dir)
                throw new Exception("this folder is forbidden to be iterated");
        }

        _main_search_and_import($directory_dir, $ignore);
    } else {
        throw new Exception("resource given ($uri) is neither a valid library directory nor valid file!");
    }
}