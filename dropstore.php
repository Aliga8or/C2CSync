<?php
require_once("connection.php");
$filename = $_GET["filename"];
mysql_query("UPDATE files SET fname='".$filename."',flow='up' WHERE sr='1'") or die('error updating');
?>
<script type="text/javascript">
window.location.href = "http://localhost/Dropbox-master/examples/getFile.php";
</script>