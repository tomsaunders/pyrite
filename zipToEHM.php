<?php
require_once 'bootstrap.php';
require_once 'www/bootstrap.php';

if (isset($_POST['submit']) && isset($_FILES['zip'])) {
	// do that
	$battle  = \Pyrite\EHBL\Battle::fromZipUpload($_FILES['zip']);
	$package = \Pyrite\EHBL\Packager::fromBattle($battle);
	$ehmPath = $package->toEHM();
	$ehmFile = basename($ehmPath);

	header('Content-Type: application/ehm');
	header("Content-Disposition: attachment; filename=$ehmFile");
	echo file_get_contents($ehmPath);
} else {
	$content = <<<FORM
<form action="zipToEHM.php" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="exampleFormControlFile1">Upload ZIP</label>
    <input type="file" class="form-control-file" name="zip">
  </div>
  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>
FORM;

	echo bs_header();
	echo bs_navbar("Zip To EHM", "Upload", []);
	echo bs_two_column([], $content);
	echo bs_footer();
}
