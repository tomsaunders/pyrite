<?php
require_once 'bootstrap.php';
require_once 'www/bootstrap.php';

if (isset($_POST['submit']) && isset($_FILES['ehm'])) {
	// do that
	$battle  = \Pyrite\EHBL\Battle::fromEHMUpload($_FILES['ehm']);
	$package = \Pyrite\EHBL\Packager::fromBattle($battle);
	$zipPath = $package->toZip();
	$zipFile = basename($zipPath);

	if ($zipFile)

	header('Content-Type: application/ehm');
	header("Content-Disposition: attachment; filename='$zipFile'");
	echo file_get_contents($zipPath);
	// TODO this successfully outputs a file, but the EHBL does not load it.
} else {
	$content = <<<FORM
<form action="ehmToZip.php" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="exampleFormControlFile1">Upload EHM</label>
    <input type="file" class="form-control-file" name="ehm">
  </div>
  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>
FORM;

	echo bs_header();
	echo bs_navbar("EHM To Zip", "Upload", []);
	echo bs_two_column([], $content);
	echo bs_footer();
}
