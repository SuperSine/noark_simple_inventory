<script type="text/javascript">

	function postData(){
		var xmlhttp = new window.XMLHttpRequest();

		var doc_number = document.getElementById("doc_number").value;

		xmlhttp.open("GET","<?php echo action('ajax@assign_docnumber'); ?>?doc_number="+doc_number);

		xmlhttp.onreadystatechange=function(){
			
		}

		xmlhttp.send();

	}

   function getData()
   {
      var xmlhttp = new window.XMLHttpRequest();

	  xmlhttp.onreadystatechange=function(){
	  	if (xmlhttp.readyState==4 && xmlhttp.status==200){
	  		var data = JSON.parse(xmlhttp.responseText);
	  		document.getElementById("doc_number").value = data.doc_number;
	  	}
	  }

      xmlhttp.open(
         "GET", "<?php echo action('ajax@fetch_docnumber'); ?>");
      xmlhttp.send();
      
   }

   getData();
/*
	$(document).ready(function(e){
	
		$.getJSON('<?php echo action('ajax@checkin_fields'); ?>',{code : '1200589'} , function(data) {
			$("#item_name").val(data.name);
        });

		$("#item_name").val("123");		
	});
*/

</script>

<p>Assign Doc# to Scanner</p>
<div><span>Doc#:</span><input id="doc_number"/></div>
<input type="button" value="Submit" onclick='postData()'/>