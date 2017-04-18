<?php
/*
 * CKFinder : configuration file for Joomla!
 * Replace the original config.php file provided with CKFinder with this file.
 */

define( '_JEXEC', 1 );
define( 'DS', DIRECTORY_SEPARATOR );
$rootFolder = explode(DS,dirname(__FILE__));
array_splice($rootFolder,-4);
$base_folder = implode(DS,$rootFolder);
define('JPATH_BASE',str_replace('\plugins','',$base_folder));

require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

if ($_COOKIE['ckfinder_app'] == 1)
{
	$mainframe =& JFactory::getApplication('administrator');
}else
{
	$mainframe =& JFactory::getApplication('site');
}
$mainframe->initialise();

function CheckAuthentication()
{

	// WARNING : DO NOT simply return "true". By doing so, you are allowing
	// "anyone" to upload and list the files on your server. You must implement
	// some kind of session validation here. Even something very simple such as...

	//get joomla session
	$session =& JFactory::getSession();
	if($session->getState() == 'active' && $session->get('CKFinderAccess') == true)
	{
		// If license name and key are set in plugin config, use them.
		if ($session->get('LicenseName') !='' && $session->get('LicenseKey') != '')
		{
			$GLOBALS['config']['LicenseName'] = trim($session->get('LicenseName'), ' "');
			$GLOBALS['config']['LicenseKey'] = trim($session->get('LicenseKey'), ' "');
		}
		else
		{ // Set CKFinder to demonstration mode.
			$GLOBALS['config']['LicenseName'] = '';
			$GLOBALS['config']['LicenseKey'] = '';
		}

		return true;
	}
	return false;
}
$session =& JFactory::getSession();

$baseUrl = 'media/';
$baseDir = JPATH_BASE.DS."media";

/*
 * ### Advanced Settings
 */

/*
Thumbnails : thumbnails settings. All thumbnails will end up in the same
directory, no matter the resource type.
*/
$config['Thumbnails'] = Array(
		'url' => ($session->get('CKFinderThumbsUrl'))?$session->get('CKFinderThumbsUrl'):$baseUrl . '_thumbs',
		'directory' =>  ($session->get('CKFinderThumbsPath'))?$session->get('CKFinderThumbsPath'):$baseDir . '_thumbs',
		'enabled' => true,
		'directAccess' => false,
		'maxWidth' => ($session->get('CKFinderMaxThumbnailWidth'))?$session->get('CKFinderMaxThumbnailWidth'):100,
		'maxHeight' => ($session->get('CKFinderMaxThumbnailHeight'))?$session->get('CKFinderMaxThumbnailHeight'):100,
		'bmpSupported' => false,
		'quality' => 80);

$config['Images'] = Array(
		'maxWidth' => ($session->get('CKFinderMaxImageWidth'))?$session->get('CKFinderMaxImageWidth'):1600,
		'maxHeight' => ($session->get('CKFinderMaxImageHeight'))?$session->get('CKFinderMaxImageHeight'):1200,
		'quality' => 80);
/*
RoleSessionVar : the session variable name that CKFinder must use to retrieve
the "role" of the current user. The "role", can be used in the "AccessControl"
settings (bellow in this page).

To be able to use this feature, you must initialize the session data by
uncommenting the following "session_start()" call.
*/
$config['RoleSessionVar'] = 'CKFinder_UserRole';
//session_start();

/*
AccessControl : used to restrict access or features to specific folders.

Many "AccessControl" entries can be added. All attributes are optional.
Subfolders inherit their default settings from their parents' definitions.

	- The "role" attribute accepts the special '*' value, which means
		"everybody".
	- The "resourceType" attribute accepts the special value '*', which
		means "all resource types".
*/

$config['AccessControl'][] = Array(
		'role' => '*',
		'resourceType' => '*',
		'folder' => '/',

		'folderView' => true,
		'folderCreate' => true,
		'folderRename' => true,
		'folderDelete' => true,

		'fileView' => true,
		'fileUpload' => true,
		'fileRename' => true,
		'fileDelete' => true);

/*
For example, if you want to restrict the upload, rename or delete of files in
the "Logos" folder of the resource type "Images", you may uncomment the
following definition, leaving the above one:

$config['AccessControl'][] = Array(
		'role' => '*',
		'resourceType' => 'Images',
		'folder' => '/Logos',

		'folderView' => true,
		'folderCreate' => true,
		'folderRename' => true,
		'folderDelete' => true,

		'fileView' => true,
		'fileUpload' => false,
		'fileRename' => false,
		'fileDelete' => false);
*/

/*
ResourceType : defines the "resource types" handled in CKFinder. A resource
type is nothing more than a way to group files under different paths, each one
having different configuration settings.

Each resource type name must be unique.

When loading CKFinder, the "type" querystring parameter can be used to display
a specific type only. If "type" is omitted in the URL, the
"DefaultResourceTypes" settings is used (may contain the resource type names
separated by a comma). If left empty, all types are loaded.

maxSize is defined in bytes, but shorthand notation may be also used.
Available options are: G, M, K (case insensitive).
1M equals 1048576 bytes (one Megabyte), 1K equals 1024 bytes (one Kilobyte), 1G equals one Gigabyte.
Example: 'maxSize' => "8M",

==============================================================================
ATTENTION: Flash files with `swf' extension, just like HTML files, can be used
to execute JavaScript code and to e.g. perform an XSS attack. Grant permission
to upload `.swf` files only if you understand and can accept this risk.
==============================================================================
*/
$config['DefaultResourceTypes'] = '';

$config['ResourceType'][] = Array(
		'name' => 'Files',        // Single quotes not allowed
		'url' => ($session->get('CKFinderFilesUrl'))?$session->get('CKFinderFilesUrl'):$baseUrl . 'files',
		'directory' => ($session->get('CKFinderFilesPath'))?$session->get('CKFinderFilesPath'):$baseDir . 'files',
		'maxSize' => ($session->get('CKFinderMaxFilesSize'))?$session->get('CKFinderMaxFilesSize'):0,
		'allowedExtensions' =>($session->get('CKFinderResourceFiles'))?$session->get('CKFinderResourceFiles'):'7z,aiff,asf,avi,bmp,csv,doc,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pxd,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,sitd,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,zip',
		'deniedExtensions' => '');

$config['ResourceType'][] = Array(
		'name' => 'Images',
		'url' => ($session->get('CKFinderImagesUrl'))?$session->get('CKFinderImagesUrl'):$baseUrl . 'images',
		'directory' => ($session->get('CKFinderImagesPath'))?$session->get('CKFinderImagesPath'):$baseDir . 'images',
		'maxSize' => ($session->get('CKFinderMaxImagesSize'))?$session->get('CKFinderMaxImagesSize'):0,
		'allowedExtensions' =>($session->get('CKFinderResourceImages'))?$session->get('CKFinderResourceImages'):'bmp,gif,jpeg,jpg,png',
		'deniedExtensions' => '');

$config['ResourceType'][] = Array(
		'name' => 'Flash',
		'url' => ($session->get('CKFinderFlashUrl'))?$session->get('CKFinderFlashUrl'):$baseUrl . 'flash',
		'directory' => ($session->get('CKFinderFlashPath'))?$session->get('CKFinderFlashPath'):$baseDir . 'flash',
		'maxSize' => ($session->get('CKFinderMaxFlashSize'))?$session->get('CKFinderMaxFlashSize'):0,
		'allowedExtensions' =>($session->get('CKFinderResourceFlash'))?$session->get('CKFinderResourceFlash'):'swf,flv',
		'deniedExtensions' => '');

/*
Due to security issues with Apache modules, it is recommended to leave the
following setting enabled.

How does it work? Suppose the following:

	- If "php" is on the denied extensions list, a file named foo.php cannot be
		uploaded.
	- If "rar" (or any other) extension is allowed, one can upload a file named
		foo.rar.
	- The file foo.php.rar has "rar" extension so, in theory, it can be also
		uploaded.

In some conditions Apache can treat the foo.php.rar file just like any PHP
script and execute it.

If CheckDoubleExtension is enabled, each part of the file name after a dot is
checked, not only the last part. In this way, uploading foo.php.rar would be
denied, because "php" is on the denied extensions list.
*/
$config['CheckDoubleExtension'] = true;

/*
Increases the security on an IIS web server.
If enabled, CKFinder will disallow creating folders and uploading files whose names contain characters
that are not safe under an IIS web server.
*/
$config['DisallowUnsafeCharacters'] = false;

/*
If you have iconv enabled (visit http://php.net/iconv for more information),
you can use this directive to specify the encoding of file names in your
system. Acceptable values can be found at:
	http://www.gnu.org/software/libiconv/

Examples:
	$config['FilesystemEncoding'] = 'CP1250';
	$config['FilesystemEncoding'] = 'ISO-8859-2';
*/
$config['FilesystemEncoding'] = 'UTF-8';

/*
Perform additional checks for image files
if set to true, validate image size
*/
$config['SecureImageUploads'] = true;

/*
Indicates that the file size (maxSize) for images must be checked only
after scaling them. Otherwise, it is checked right after uploading.
*/
$config['CheckSizeAfterScaling'] = true;

/*
For security, HTML is allowed in the first Kb of data for files having the
following extensions only.
*/
$config['HtmlExtensions'] = array('html', 'htm', 'xml', 'js');

/*
Folders to not display in CKFinder, no matter their location.
No paths are accepted, only the folder name.
The * and ? wildcards are accepted.
".*" disallows the creation of folders starting with a dot character.
*/
$config['HideFolders'] = Array(".*", "CVS");

/*
Files to not display in CKFinder, no matter their location.
No paths are accepted, only the file name, including extension.
The * and ? wildcards are accepted.
*/
$config['HideFiles'] = Array(".*");

/*
After file is uploaded, sometimes it is required to change its permissions
so that it was possible to access it at the later time.
If possible, it is recommended to set more restrictive permissions, like 0755.
Set to 0 to disable this feature.
Note: not needed on Windows-based servers.
*/
$config['ChmodFiles'] = 0777 ;

/*
See comments above.
Used when creating folders that does not exist.
*/
$config['ChmodFolders'] = 0755 ;

/*
Force ASCII names for files and folders.
If enabled, characters with diactric marks, like å, ä, ö, ć, č, đ, š
will be automatically converted to ASCII letters.
*/
$config['ForceAscii'] = false;

$plugins = $session->get('CKFinderSettingsPlugins');
if ( !empty($plugins['imageresize']) ) {
	include_once dirname(__FILE__).DS."plugins".DS."imageresize".DS."plugin.php";
}
if ( !empty($plugins['fileedit']) ){
	include_once dirname(__FILE__).DS."plugins".DS."fileeditor".DS."plugin.php";
}
if ( !empty($plugins['zip']) ){
  include_once dirname(__FILE__).DS."plugins".DS."zip".DS."plugin.php";
}

/*
Send files using X-Sendfile module
Mod X-Sendfile (or similar) is avalible on Apache2, Nginx, Cherokee, Lighttpd

Enabling X-Sendfile option can potentially cause security issue.
- server path to the file may be send to the browser with X-Sendfile header
- if server is not configured properly files will be send with 0 length

For more complex configuration options visit our Developer's Guide
	http://docs.cksource.com/CKFinder_2.x/Developers_Guide/PHP
*/
$config['XSendfile'] = false;

$config['plugin_imageresize']['smallThumb'] = '90x90';
$config['plugin_imageresize']['mediumThumb'] = '120x120';
$config['plugin_imageresize']['largeThumb'] = '180x180';
