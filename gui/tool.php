
<div id='vor'>
	<form>
		<input type='text' name='zeil' value='<?php echo $_GET["zeil"]; ?>' />
		<input type="button" onclick="bin_call('ping?'+this.form.zeil.value)" value="Ping" />
		<input type="button" onclick="bin_call('ping_sl?'+this.form.zeil.value)" value="Ping-Slow" />
		<input type="button" onclick="bin_call('nslookup?'+this.form.zeil.value)" value="NS-Lookup" />
		<input type="button" onclick="bin_call('traceroute?'+this.form.zeil.value)" value="Traceroute" />
	</form>
</div>
<div id='bei' style='display: none;'>
	<img src="img/trans.gif" />
</div>

<textarea id='bin_out' cols='92' rows='38' style="font-size:8pt;" readonly>
</textarea>