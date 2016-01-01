<!--
SAPS Data Fetcher
Fetch student data from SAPS using their IC
Creator :
Mohd Shahril (shahril96) - Main dev
Afif Zafri (afzafri) - add form and UI
Date : 1/1/2016
-->
<html>
<head>
<title>SAPS Data Fetcher</title>
</head>
<body>
<h1>SAPS Data Fetcher</h1>

<form action="index.php" method="post">
IC : <input type="text" name="ic" value="<?php echo (isset($_POST['ic']) ? htmlentities($_POST['ic']) : null) ?>"><br>
Year :
 <select name="tahun_semasa" id="tahun_semasa" ?>">
 <option value="">-- Pilih Tahun --</option>
    <?php
    for($year = 2011; $year <= date('Y'); $year++) {
        echo "<option value='" . $year . "'>" . $year . "</option>";
    }
    ?>
 </select>
<br><br>
<input type="submit" name="submit">
</form>

<?php

if(!empty($_POST['ic']) && !empty($_POST['tahun_semasa']))
{
    echo "<h2>Data</h2><br>";

    // check if ic format is correct
    // matches : 999999029999
    //           999999-99-9999
    if(!preg_match('#\d{6}-?\d{2}-?\d{4}#', $_POST['ic'])) {
        die("Ic format is incorrect");
    }

    // check if year is incorrect
    if(!preg_match('#20\d{2}#', $_POST['tahun_semasa'])) {
        die("invalid year format");
    }

	$ic = str_replace('-', '' ,$_POST['ic']); // remove any '-' chars in input
	$tahun = $_POST['tahun_semasa'];

    $get = file_get_contents("https://sapsnkra.moe.gov.my/ibubapa2/semak.php?txtIC={$ic}&Semak=Semak+Laporan&jenissek=2&tahun_semasa={$tahun}");

	// build up custom header + cookie
	preg_match_all('#Set-Cookie: (.*?);#', implode('', $http_response_header), $out);
	$opts = array('http'=>array('header'=> "Cookie: " . implode('; ', $out[1]) . "\r\n"));
	$get = file_get_contents("https://sapsnkra.moe.gov.my/ibubapa2/menu.php", false, stream_context_create($opts));
	preg_match_all('#<strong>(.*?)&nbsp;</strong>#', $get, $out);


    if(empty($out[1][1])) {
        die('record don\'t exist');
    }

	echo '<ul>';

	for($i=0;$i<count($out[1]);$i++)
	{
		echo "<li>". $out[1][$i] . "</li>";
	}

	echo "</ul>";

}

?>
