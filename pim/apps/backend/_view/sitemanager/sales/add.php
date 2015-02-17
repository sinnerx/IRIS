<h3 class='m-b-xs text-black'>
	Sales
</h3>
<div class='well well-sm'>
	Insert sales record.
</div>
<div class='row'>
	<div class='col-sm-6'>
		<div class='table-responsive bg-white'>		
          
              
           
              <form method="post">

              <table class='table'>
				<tr>
					<td width="150px">Sales Income</td><td><?php echo form::text('salesIncome',"class='form-control'"); ?></td>
				</tr>
				<tr>
					<td>Type of sales</td><td><?php echo form::select('salesType', $types);?></td>
				</tr>
					<tr>
					<td>Remark</td><td><?php echo form::text('remark',"class='form-control'"); ?></td>
				</tr>
				<tr>
					 <td></td><td><input type='submit' class='btn btn-primary'  value='SUBMIT' /></td> 
					<!-- <td></td><td>: <input type='button' class='btn btn-primary'  value='SUBMIT' /></td> -->
				</tr>

			</table>
            
                 
                </form>  
           
              
		</div>
	</div>
</div>