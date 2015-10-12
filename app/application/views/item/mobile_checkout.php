<script type="text/javascript">
    function OkDisable(disable){
        document.getElementById("confirm").disable = disable;
    }

    function OkStatus(status){
        document.getElementById("confirm").value = status;
    }
	function postData(){
		var data = {}
		var xmlhttp = new window.XMLHttpRequest();
		var param;

        data.code     = document.getElementById("item_code").value;
        data.quantity = document.getElementById("item_quantity").value;
        data.document = document.getElementById("item_doc").value;
        data.date     = document.getElementById("item_date").value;
        data.pd_date  = document.getElementById("pd_date").value;
        data.submit   = 1;

        param = params(data);

		xmlhttp.open("POST","<?php echo action('item@checkout'); ?>",true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.setRequestHeader("Content-length", param.length);
		xmlhttp.setRequestHeader("Connection", "close");

	  xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200){
                alert(xmlhttp.responseText);
     
            }     
	  }


		
		xmlhttp.send(param);

	}

var current_code = "";
   function getData()
   {
      var name = document.getElementById("item_code").value;
      var xmlhttp = new window.XMLHttpRequest();
      var quantity;
      var po_num;
      var pd_date;

      var barCodePatt = /\((\d+)\)(\d+)/;
      var qrCodePattTest = /(\d+)\/([A-Za-z0-9]+)\/([0-9-]+)\/([0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]))/g;
      var qrCodePatt = /(\d+)`([A-Za-z0-9]+)`([0-9-]+)`([0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]))/g;
      var patt;
      var match;
      var mode;

      if(barCodePatt.test(name)){
        mode = 1;

        match    = barCodePatt.exec(name);
        quantity = match[1];
        name     = match[2];

        document.getElementById("item_quantity").value = quantity;        
      }else if(qrCodePattTest.test(name)){
        mode = 2;
        name    = name.replace(/\//g,"`");
        match   = qrCodePatt.exec(name);
        name    = match[1];
        po_num  = match[3];
        pd_date = match[4];

        document.getElementById("item_doc").value = po_num;
        document.getElementById("pd_date").value = pd_date;        
      }



      document.getElementById("item_code").value = name;

      xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            var data = JSON.parse(xmlhttp.responseText);
            document.getElementById("item_name").value = data.name;
            if(mode != 2)
                document.getElementById("item_doc").value = data.doc_number;
            document.getElementById("item_date").value = data.current_time;

            current_code = name;

            OkDisable(false);
            OkStatus("OK");         
        }
      }
      
        OkDisable(true);
        OkStatus("Processing");  

      xmlhttp.open(
         "GET", "<?php echo action('ajax@checkin_fields'); ?>?code=" + name);
      xmlhttp.send();
    
      
   }

window.setInterval(function(){
    if(document.getElementById("item_code").value != current_code)
        getData();
}, 1000);      

/*
   function getData()
   {
      var name = document.getElementById("item_code").value;
      var xmlhttp = new window.XMLHttpRequest();
      var quantity;

      name = name.replace(/\(\d+\)/,function(q){
		q = q.replace("(","").replace(")","");
		quantity = parseInt(q);

		return "";
	  });

	  document.getElementById("item_code").value = name;
	  document.getElementById("item_quantity").value = quantity;

	  xmlhttp.onreadystatechange=function(){
	  	if (xmlhttp.readyState==4 && xmlhttp.status==200){
	  		var data = JSON.parse(xmlhttp.responseText);
	  		document.getElementById("item_name").value = data.name;
	  		document.getElementById("item_doc").value = data.doc_number;
	  		document.getElementById("item_date").value = data.current_time;
	  	}
	  }

      xmlhttp.open(
         "GET", "<?php echo action('ajax@checkin_fields'); ?>?code=" + name);
      xmlhttp.send();
      
   }
*/
</script>

<p>Check-out</p>
<div><span>Code:</span><input id="item_code"/>
<input type="button" id="confirm" value="OK" onclick="getData()"/>
</div>
<div><span>Name:</span><input id="item_name" readonly=true/></div>
<div><span>Quantity:</span><input id="item_quantity"/></div>
<div><span>Doc #:</span><input id="item_doc"/></div>
<div><span>PrdDate:</span><input id="pd_date"/></div>
<div><span>Date:</span><input id="item_date"/></div>
<input type="button" value="Submit" onclick="postData()"/>