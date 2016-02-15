<style type="text/css">
	
.text label
{
	color:#8c8c8c;
}	

</style>
<div class='text'>
This class could help you in writing an form based html elements. Currently there's only few usable API, like :<br>
<ul>
	<li>text <label>// shortcut for &lt;input type='text' /></label></li> 
	<li>password <label>// shortcut for &lt;input type='password' /></label></li>
	<li>textarea <label>// shortcut for &lt;textarea>&lt;/textarea></label></li>
	<li>select <label>// shortcute for &lt;select> element</label></li>
	<li>file <label>// &lt;input type='file' /></label></li>
	<li>submit <label>// &lt;input type='submit' /></label></li>
	<li>submitted <label>// &lt; a check if form has been submitted.</label></li>
</ul>
</div>
<div class='text'><div class='text-title'>1. Input, textarea, select, and submit.</div>
<div class='text-subtitle'>1.1 Input type : text, password, textarea</div>
Basically all this three have like 3 parameters. :<br>
1. name (mandatory)<br>
2. attribute<br>
3. value<br><br>
Example, let say somewhere in your view :
<pre><code>
&lt;php
// text : will return &lt;input type='text' name='myname' id='myname' value='remi' size='10' />
echo form::text("myname","size='10'","remi");

// password : will return &lt;input type='text' name='mypassword' id='mypassword' size='10' />
echo form::password("mypassword","size='10'");

// textarea : will return &lt;textarea id='mytext' name='mytext'>some value&gt;/textarea>
echo form::textarea("mytext","","some value");
?&gt;
</code></pre>

<div class='text-subtitle'>1.2 Select and submit</div>
Example, let say you write this somewhere in view :
<pre><code class='hljs php'>
&lt;php
// select
echo form::select("fruit",Array(1=>"Apple",2=>"Orange",3=>"Banana"),2);
/* will return 
&lt;select name='fruit' id='fruit'>
	&lt;option value='1'>Apple&lt;/option>
	&lt;option selected value='2'>Orange&lt;/option>
	&lt;option value='3'>Banana&lt;/option>
&lt;/select>
*/
?&gt;

//submit button : &lt;input type='submit' value='mybutton' />
echo form::submit("mybutton");?>
</code></pre>
</div>
<div class='text'><div class='text-title'>2. File</div>
No, i haven't test the processing part for 'file' yet. So, i wouldn't want to write any docs about this yet. It's almost midnight already. >,<
</div>
<div class='text'><div class='text-title'>3. Processing The Inputs</div>
Okay, after all, you might want to process all the input sent by form, either using this class or not. To check how, you can visit <?php echo html::link("input","docs/core/input");?> documentation.
</div>