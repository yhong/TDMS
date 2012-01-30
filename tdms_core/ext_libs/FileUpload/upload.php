<html>
	<head>
		<title>Upload</title>
	</head>
<body>

<?
require("fileupload.class");

#--------------------------------#
# Variables
#--------------------------------#

// The path to the directory where you want the 
// uploaded files to be saved. This MUST end with a 
// trailing slash unless you use $PATH = ""; to 
// upload to the current directory. Whatever directory
// you choose, please chmod 777 that directory.

	$PATH = "uploads/";

// The name of the file field in your form.

	$FILENAME = "userfile";

// ACCEPT mode - if you only want to accept
// a certain type of file.
// possible file types that PHP recognizes includes:
//
// OPTIONS INCLUDE:
//  text/plain
//  image/gif
//  image/jpeg
//  image/png

	$ACCEPT = "image/gif";

// If no extension is supplied, and the browser or PHP
// can not figure out what type of file it is, you can
// add a default extension - like ".jpg" or ".txt"

	$EXTENSION = "";

// SAVE_MODE: if your are attempting to upload
// a file with the same name as another file in the
// $PATH directory
//
// OPTIONS:
//   1 = overwrite mode
//   2 = create new with incremental extention
//   3= do nothing if exists, highest protection

	$SAVE_MODE = 1;


#--------------------------------#
# PHP
#--------------------------------#

function print_file($file, $type, $mode) {
	if($file) {
		if(ereg("image", $type)) {
			echo "<img src=\"" . $file . "\" border=\"0\" alt=\"\">";
		}
		else {
			$userfile = fopen($file, "r");
			while(!feof($userfile)) {
				$line = fgets($userfile, 255);
				switch($mode){
					case 1:
						echo $line;
						break;
					case 2:
						echo nl2br(ereg_replace("\t", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", htmlentities($line)));
						break;
				}	
			}
		}
	}
}

$upload = new uploader;
$upload->max_filesize(30000);

if($upload->upload("$FILENAME", "$ACCEPT", "$EXTENSION")) {
	while(list($key, $var) = each($upload->file)){
		echo $key . " = " . $var . "<br>";
	}
	if($upload->save_file("$PATH", $SAVE_MODE)) {
		print("<p>Saved as: " . $upload->new_file . "<p>");
		print_file($upload->new_file, $upload->file["type"], 2);
	}
}

if($upload->errors) {
	while(list($key, $var) = each($upload->errors)){
		echo "<p>" . $var . "<br>";
	}
}

if ($NEW_NAME) {
	print("<p>Name of image save: <b>$NEW_NAME</b></p>");
}

#--------------------------------#
# HTML FORM
#--------------------------------#
?>

	<form enctype="multipart/form-data" action="<?print($PHP_SELF);?>" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="100000">Send this file:
		<input name="userfile" type="file">
		<input type="submit" value="Send File">
	</form>
	<hr>
<?
	if ($ACCEPT) {
		print("This form only accepts <b>" . $ACCEPT . "</b> files\n");
	}
?>

</body>
</html>
