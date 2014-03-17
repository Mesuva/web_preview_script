web_preview_script
==================

A single PHP script to automatically display web design images from a folder.

Simply upload images into a folder named 'images' alongside this file and visit the script in a browser.

Options
--------
Although the script is intended to be zero configuration, there are some options at the start of the index.php file that can be adjusted:
```php
$logopath  = ''; // use a url to an logo image, to place it in the top left.
$projecttitle = ''; // enter a value here to force a project title
$autonamefromfolder = true;  // if true and no project title is set, use parent folder name as project title
$autoremovenumericalprefix = true;  // change to false if you want to keep prefixes like '05 - ' on your titles.
```
