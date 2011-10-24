<!doctype html>
<html lang="no">
<head>
<meta charseth="utf-8" />
<title>GIVAS Grue filkonvertering</title>
</head>
<body>

<h1>Filkonvertering av avtale- og gebyrfiler fra Komtek</h1>

<?php
// Skru på debugging ved å kommentere inn linjen nedenfor.
ini_set('display_errors', 'On');
// Mappe som filene lastes opp til.
$ufilepath = 'upload/';

// Sjekker om fila er valgt
if(isset($_POST['submit'])) {
	// Sjekker om fillopplastingen gikk greit
	if($_FILES["file"]["error"] > 0) {
		echo "Feil ved lagring av fil: " . $_FILES["file"]["error"] . "<br />";
	} else {	
		$ufile = $_FILES["file"]["name"];
		$ufilefullpath = $ufilepath . $ufile;
		$ufiletmp = $_FILES["file"]["tmp_name"];
		// Sjekker om fila gikk greit å flytte temporær fil til upload.
		if(!move_uploaded_file($ufiletmp, $ufilefullpath)) {
			echo "Feil ved flytting av fil.<br />";
		}
		// Åpner fil for lesing
		$handle = fopen($ufilefullpath, 'r') or die("Kunne ikke åpne fila for lesing");
		// Leser fildata
		$filedata = fread($handle,filesize($ufilefullpath));
		// Lukker fil.
		fclose($handle);
		// regex-mønster for å erstatte 00 med 99.
		$pattern = '/^(\w{5})(\w{2})(.+$)/m';
		$replacement = '${1}00$3';
		
		// Ny fil for konverterte data
		$rfile = 'converted_' . $ufile;
		$rfilefullpath = $ufilepath . $rfile;
		// Åpner fil for skriving
		$handle = fopen($rfilefullpath, 'w') or die("Kunne ikke åpne ny fil for skriving");
		// Skriver data til fil
		fwrite($handle, preg_replace($pattern, $replacement, $filedata));
		// Lukker fil
		fclose($handle);

		// Skriver ut link til fila som er konvertert
		echo $ufile . ' har n&aring; blitt konvertert. ';
		echo 'H&oslash;yreklikk p&aring; filnavnet under og velg <em>Lagre som</em> for &aring; laste ned den konverterte fila.<br /><a href="' . $rfilefullpath . '">' . $rfile . '</a>';
	}
} else {
// Skriver ut skjema, hvis fil ikke er valgt
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
<label for="file">Last opp fil som skal konverteres:</label>
<input type="file" name="file" id="file" />
<br />
<input type="submit" name="submit" value="Konverter" />
</form>

<?
}
?>

</body>
</html>
