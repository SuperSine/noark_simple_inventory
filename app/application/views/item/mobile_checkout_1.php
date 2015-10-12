<script type="text/javascript">
    var post_method = '<?php echo $post_method; ?>';

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


/*
    param = JSON.stringify({'body':data_array,'submit':1});

		xmlhttp.open("POST","<?php echo action('item@checkout'); ?>",true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.setRequestHeader("Content-length", param.length);
		xmlhttp.setRequestHeader("Connection", "close");
*/

    $.ajax({
      type:'POST',
      url: post_method,
      data: { body:data_array,submit:1 },
      success:function(e){
        var data = JSON.parse(e);
        alert(e);
      }
    });

/*
	  xmlhttp.onreadystatechange=function(){
      if (xmlhttp.readyState==4 && xmlhttp.status==200){
          alert(xmlhttp.responseText);

      }
	  }



		xmlhttp.send(param);
*/

	}

var data_array = [];
var current_code = "";
var tableId = "itemTable";

   function getData(name)
   {
      var xmlhttp = new window.XMLHttpRequest();
      var quantity;
      var po_num;
      var pd_date;

      var barCodePatt = /\((\d+)\)(\d+)/;
      var sBarCodePatt = /\d+/;
      var qrCodePattTest = /(\d+)\/([A-Za-z0-9]+)\/([0-9-]+)\/([0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]))/g;
      var qrCodePatt = /(\d+)`([A-Za-z0-9]+)`([0-9-]+)`([0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]))/g;
      var patt;
      var match;
      var mode;
      var entity = {};

      if(barCodePatt.test(name)){
        mode = 1;

        match    = barCodePatt.exec(name);
        quantity = match[1] ? match[1] : 1;
        name     = match[2];

        entity.quantity = quantity;

      }else if(qrCodePattTest.test(name)){
        mode = 2;
        name    = name.replace(/\//g,"`");
        match   = qrCodePatt.exec(name);
        name    = match[1];
        po_num  = match[3];
        pd_date = match[4];


        entity.document = po_num;
        entity.pd_date  = pd_date;
      }else if(sBarCodePatt.test(name)){
        mode = 1;

        match = sBarCodePatt.exec(name);
        name  = match[0];
        quantity = 1;

        entity.quantity = quantity;
      }else{
        alert("unrecognized code pattern!");
        return;
      }



      entity.code = name;
    /*
      xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            var data = JSON.parse(xmlhttp.responseText);
            entity.name = data.name;
            if(mode != 2){
                entity.document = document.getElementById("item_doc").value;
            }

            entity.date = data.current_time;

            insert(entity);

            OkDisable(false);
            OkStatus("OK");
        }else if(xmlhttp.readyState==2 && xmlhttp.status==200)
          alert("no item availiable! please create item into the system.");
      }

        OkDisable(true);
        OkStatus("Processing");

      xmlhttp.open(
         "GET", "<?php echo action('ajax@checkin_fields'); ?>?code=" + name);
      xmlhttp.send();
    */
        OkDisable(true);
        OkStatus("Processing");

        $.ajax({
            'type':'GET',
            'url' : '<?php echo action('ajax@checkin_fields'); ?>',
            'data': {'code':name},
            'success' : function(e){
                var data = JSON.parse(e);

                if(!data.name){
                    alert("no item availiable! please create item into the system.");
                    return;
                }

                entity.name = data.name;
                if(mode != 2){
                    entity.document = document.getElementById("item_doc").value;
                }

                entity.date = data.current_time;

                insert(entity);

                OkDisable(false);
                OkStatus("OK");
            }
        });

   }



    function deleteRow(row)
    {
        var i=row.parentNode.parentNode.remove();
        //document.getElementById(tableId).deleteRow(i);

        var table = document.getElementById(tableId);
        for (var i = 1; i < table.rows.length - 1; i++) {
           //iterate through rows
           //rows would be accessed using the "row" variable assigned in the for loop
          document.getElementById(tableId).rows[i].cells[0].innerText = i;
        }
    }


    function insRow(data)
    {
        var x=document.getElementById(tableId);

        var lastIndex = x.rows.length -1 ;
        var current_row = x.rows[lastIndex];
        var new_row = x.rows[lastIndex].cloneNode(true);

        var len = x.rows.length - 1;
        current_row.cells[0].innerHTML = len;

        current_row.cells[1].innerText = data.code;
        current_row.cells[2].innerText = data.name;
        current_row.cells[3].getElementsByTagName("input")[0].value = data.quantity;
        current_row.cells[4].innerText = data.document;


        x.appendChild( new_row );
    }

function add_data(code,name,quantity,document,pd_date,date){
  data_array.push({
                  'code':code,
                  'name':name,
                  'quantity':quantity,
                  'document':document,
                  'pd_date':pd_date,
                  'date':date,
                });
  return;
}
function remove_data(start){
  data_array.splice(start,1);
}

function insert(data){
  add_data(data.code,data.name,data.quantity,data.document,data.pd_date,data.date);
  insRow(data);
}

function remove(that){
  var i = that.parentNode.parentNode.rowIndex;
  remove_data(i+1);
  deleteRow(that);
}



  var currentCodeId = "item_code_1";
  var current_line = 0;

  window.setInterval(function(){
    var splitCodeList = document.getElementById(currentCodeId).value.split('\n');
    var code = splitCodeList[splitCodeList.length - 1];
    if(splitCodeList.length != 0 && splitCodeList.length > current_line && code != ''){
      getData(code);
      current_line = splitCodeList.length;
    }
  },200);

/*
alert($.ajax);
$.ajax({
  type:'GET',
  url:'<?php echo action('ajax@checkin_fields'); ?>',
  data:{code:1000000},
  success:function(e){
    alert(e);
  }
});
*/
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

<p><?php echo $check_mode ?></p>
<div><span>Doc #:</span><input id="item_doc"/></div>
<div><span>Code:</span><textarea id="item_code_1"></textarea>
<input type="button" id="confirm" value="OK" onclick="getData()"/>
</div>
<!--
<div><span>Name:</span><input id="item_name" readonly=true/></div>
<div><span>Quantity:</span><input id="item_quantity"/></div>
<div><span>Doc #:</span><input id="item_doc"/></div>
<div><span>PrdDate:</span><input id="pd_date"/></div>
<div><span>Date:</span><input id="item_date"/></div>
-->
<div id="itemtabldiv">
    <table id="itemTable">
        <tr>
            <td>#</td>
            <td>Code</td>
            <td>Name</td>
            <td>Qty</td>
            <td>Doc</td>
            <td>Act</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><input type="text" style="width:30px;"></td>
            <td></td>
            <td><input type="button" onclick="remove(this)" value="Del"/></td>
        </tr>
    </table>
</div>
<div id="hidenItemTableDiv" style="display:none;">
    <table id="hidenitemTable">
        <tr>
            <td>#</td>
            <td>Code</td>
            <td>Name</td>
            <td>Qty</td>
            <td>Doc</td>
            <td>Act</td>
        </tr>
        <tr>
            <td>1</td>
            <td></td>
            <td></td>
            <td><input type="text" style="width:30px;"></td>
            <td></td>
            <td><a href="#" onclick="remove(this)">Del</a></td>
        </tr>
    </table>
</div>
<input type="button" value="Submit" onclick="postData()"/>