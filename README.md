# PHP import() Library

## How the library works?:

The library consists in making including php files more easy, instead
of doing like 'include(getcwd() . "/folder/to/file.php");', with this
framework you can use 'import("folder/to/file");'. Also you can import
the whole folder just sending the directory as the parameter. This is
useful if some web servers (rarely) don't support / as the root directory
or if you want to import the whole folder without spamming 'require' or 
'require_once'.

If you're importing the whole folder and you don't want to include some
file you can add the file or folder into '.ignore'. For example, you can
set the following files/folder (by the way, you must include the extension):
```
folder/subfolder
folder/dont_autoimport.php
folder/dangerous.php
folder/another_folder/ignore_this.php
```
## How to use:

If you want to create or use a library or framework made for this module, it's easy
to import and no need to do extra steps. Just throwing the folder into the
'libraries' folder. And only use 'import("path/to/file.or.folder")' to import the
file or folder.

## How to configure:

Only make the 'libraries/main.php' prepend into every php file you execute.
For example, using '.htaccess' you can add:
```
php_value include_path "/path/to/docs/"
php_value auto_prepend_file "libraries/main.php"
```
and, thats all! You can use the 'import' function now! For intellisense you
can add install the composer extension, create 'composer.json' and add this:
```json
{
    "autoload": {
        "classmap": ["libraries/"]
    }
}
```
