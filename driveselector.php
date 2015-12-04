<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Google Picker Example</title>

    <!-- The standard Google Loader script; use your own key. -->
    <script src="http://www.google.com/jsapi?key=AIzaSyD5ihEurmBeEL5CRd0x0hT8GtdpJmwjzI8"></script>
    <script type="text/javascript">

    // Use the Google Loader script to load the google.picker script.
    google.setOnLoadCallback(createPicker);
    google.load('picker', '1');

    // Create and render a Picker object for searching images.
    function createPicker() {
      var view = new google.picker.View(google.picker.ViewId.DOCS);
      view;    
      var picker = new google.picker.PickerBuilder()
          .enableFeature(google.picker.Feature.NAV_HIDDEN)
          .enableFeature(google.picker.Feature.MULTISELECT_ENABLED)
          .setAppId("207450131748-77dqmsu30ru8koskj5tm05av30da4e74.apps.googleusercontent.com")  //Optional: The auth token used in the current Drive API session.
          .addView(view)
          .addView(new google.picker.DocsUploadView())
          .setCallback(pickerCallback)
          .build();
       picker.setVisible(true);
    }

    // A simple callback implementation.
    function pickerCallback(data) {
      if (data.action == google.picker.Action.PICKED) {
        var fileId = data.docs[0].id;
        alert('The user selected: ' + fileId);
		window.location.href = "http://localhost/C2CSync/drivestore.php?fileId="+fileId;
      }
    }
    </script>
  </head>
  <body>
    <div id="result"></div>
  </body>
</html>