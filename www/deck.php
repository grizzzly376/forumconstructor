<? include 'top.php'; ?>
<div id=main>
  <input id=addr style='width:500px;'>
  <div id=deck>
	
  </div>
  <div id=decklist>
	<table>
	</table>
  </div>
</div>
</body>
</html>
<script>
$('#addr').val(window.location.href)
var db = []
db[0] = {"name":"Абаддон", "race":"neander","stars":5,"atk15":662,"atk10":562,"hp15":1723,"hp10":1548,"timer":6,"cost10":17,"cost15":21}
db[1] = {"name":"Адепт крови", "race":"neander","stars":3,"atk15":550,"atk10":425,"hp15":880,"hp10":780,"timer":4,"cost10":9,"cost15":12}

for(var i=0;i<db.length;i++){
	$("#decklist table").append("<tr><td cid="+i+" class=card>"+db[i].name+"</td><td>"+db[i].stars+"</td></tr>")
}
$(".card").on("click",function(){
	var cid = $(this).attr("cid")*1
	$("#deck").append("<div class=cardindeck><img src=images/cards/"+cid+".gif></div>")
})

</script>