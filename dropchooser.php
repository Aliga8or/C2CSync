<html>
 <head>
 <script type="text/javascript" src="https://www.dropbox.com/static/api/1/dropbox.js" id="dropboxjs" data-app-key="rvnspzgwo5pxcxm"></script> 
 </head>
 <body>
  <input type="dropbox-chooser" name="selected-file" id="db-chooser"/>
  <script type="text/javascript">
    document.getElementById("db-chooser").addEventListener("DbxChooserSuccess",
        function(e) {
			var filename = e.files[0].name;
            alert("Here's the chosen file: " + e.files[0].name)
			window.location.href = "http://localhost/C2CSync/dropstore.php?filename="+filename;
        }, false);
  </script>
 </body>
</html>