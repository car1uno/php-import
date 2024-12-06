# PHP import() Library

> [!WARNING]
> This library is pending of being evaluated for security, utility and performance issues. You can use this library by your own risk.

## How the library works?:

The library consists in making including php files more easy, instead
of doing like **include(__DIR__ . "/folder/to/file.php")**, with this
framework you can use **import("folder/to/file")**. Also you can import
the whole folder just sending the directory as the parameter. This is
useful if some web servers _(rarely)_ don't support **/** as the root directory
or if you want to import the whole folder without spamming __require__ or 
__require_once__.

If you're importing the whole folder and you don't want to include some
file you can add the file or folder into __libraries/.ignore__. For example, you can
set the following files/folder ***(by the way, you must include the extension)***:
```
folder/subfolder
folder/dont_autoimport.php
folder/dangerous.php
folder/another_folder/ignore_this.php
```
## How to use:

If you want to create or use a library or framework made for this module, it's easy
to import and no need to do extra steps. Just throwing the folder into the
__libraries__ folder. And only use __import("path/to/file.or.folder")__ to import the
file or folder.

## How to configure:

Only make the __libraries/main.php__ prepend into every php file you execute.
For example, using __.htaccess__ you can add:
```
php_value include_path "/path/to/docs/"
php_value auto_prepend_file "libraries/main.php"
```
and, thats all! You can use the __import()__ function now! For intellisense you
can add install the composer extension, create 'composer.json' and add this:
```json
{
    "autoload": {
        "classmap": ["libraries/"]
    }
}
```
