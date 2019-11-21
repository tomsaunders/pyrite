<?php
require_once 'bootstrap.php';
require_once 'www/bootstrap.php';

	$content = <<<FORM
<form action="ehb.php" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <label for="exampleFormControlFile1">Upload EHBzx</label>
    <input type="file" class="form-control-file" name="ehb">
  </div>
  <button type="submit" name="submit" class="btn btn-primary">Submit</button>
</form>
FORM;

if (isset($_POST['submit']) && isset($_FILES['ehb'])) {
	$ehb = file_get_contents($_FILES['ehb']['tmp_name']);
	$content .= '<pre>';
	$content .= "\nEHBxxxxxxx\n";
	$content .= \Pyrite\Hex::hexToStr($ehb);
	$content .= print_r($_FILES,1);
	$content .= '</pre>';
}

	echo bs_header();
	echo bs_navbar("EHB", "Upload", []);
	echo bs_two_column([], $content);
	echo bs_footer();
