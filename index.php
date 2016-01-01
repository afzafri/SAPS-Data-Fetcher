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
IC : <input type="text" name="ic" value="<?php (isset($_POST['ic']) ? $_POST['ic'] : null) ?>"><br>
Year :
 <select name="tahun_semasa" id="tahun_semasa" value="<?php (isset($_POST['tahun_semasa']) ? $_POST['tahun_semasa'] : null) ?>">
 <option value="">-- Pilih Tahun --</option>
 <option value='2011'>2011</option>
 <option value='2012'>2012</option>
 <option value='2013'>2013</option>
 <option value='2014'>2014</option>
 <option value='2015'>2015</option>
 <option value='2016'>2016</option>                       
 </select>
<br><br>
<input type="submit" name="submit">
</form>

<?php 

if(isset($_POST['submit']))
{
	
	$ic = $_POST['ic'];
	$tahun = $_POST['tahun_semasa'];
	
    $get = file_get_contents("https://sapsnkra.moe.gov.my/ibubapa2/semak.php?txtIC={$ic}&Semak=Semak+Laporan&jenissek=2&tahun_semasa={$tahun}");
	
	// build up custom header + cookie
	preg_match_all('#Set-Cookie: (.*?);#', implode('', $http_response_header), $out);
	$opts = array('http'=>array('header'=> "Cookie: " . implode('; ', $out[1]) . "\r\n"));
	$get = file_get_contents("https://sapsnkra.moe.gov.my/ibubapa2/menu.php", false, stream_context_create($opts));
	preg_match_all('#<strong>(.*?)&nbsp;</strong>#', $get, $out);
	
	echo "<h2>Data</h2><ul>";
	
	for($i=0;$i<count($out[1]);$i++)
	{
		echo "<li>". $out[1][$i] . "</li>";
	}
	
	echo "</ul>";
	
}

?>
