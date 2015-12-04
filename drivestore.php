<?php
require_once("connection.php");
$fileId = $_GET["fileId"];
mysql_query("UPDATE files SET fid='".$fileId."',flow='down' WHERE sr='1'") or die('error updating');
?>
<script type="text/javascript">
window.location.href = "http://localhost/C2CSync/drive.php";
</script>