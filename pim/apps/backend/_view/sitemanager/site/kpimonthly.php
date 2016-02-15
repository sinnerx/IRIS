<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
			<h4 class="modal-title"><!-- ☮ -->
			<span>Yearly KPI view  <?php echo $year?></span>
			</h4>
		</div>
		<?php echo flash::data();?>
		<div class="modal-body" style='padding-top:5px;'>
		<form method='post' action='<?php //echo url::base('sales/edit/'.$sales->salesID);?>'>
			<div class='mb-content'>
				<table class="table table-striped m-b-none">
					<thead>
						<tr>
                          	<th></th>
                          	<th>Event</th>                    
                          	<th>Entre. Class</th>
                          	<th>Entre. Program (RM)</th>
                          	<th>Training (HOUR)</th>                    
                          	<th>Active Member</th>
                        </tr>
                    </thead>
                    <tbody>


                    	<?php foreach ($data as $month => $row):?>
                    	<tr>                    
                        	<td><?php echo $row[monthName]; ?></td>
                          	<td><?php echo $row[event]; 		$totalEvent 	= $totalEvent 		+ $row[event]; ?></td>
                          	<td><?php echo $row[entreclass]; 	$totalClass 	= $totalClass 		+ $row[entreclass]; ?></td>
                          	<td><?php echo $row[entreprogram]; 	$totalProgram 	= $totalProgram 	+ $row[entreprogram]; ?></td>
                          	<td><?php echo $row[training]; 		$totalTraining 	= $totalTraining	+ $row[training]; ?></td>
                          	<td><?php echo $row[login]; 		$totalLogin 	= $totalLogin 		+ $row[login]; ?></td>
                        </tr>               
                  		
                    	<?php endforeach;?>
    					<tr>                    
                        	<td>TOTAL</td>
                          	<td><?php echo $totalEvent; ?></td>
                          	<td><?php echo $totalClass; ?></td>
                          	<td><?php echo $totalProgram; ?></td>
                          	<td><?php echo $totalTraining; ?></td> 
                          	<td><?php echo number_format((($totalLogin/1200)*100),2); ?>%</td>
                        </tr>               
                  		

                    	        
                    </tbody>
                </table>
			</div>
			<div class='mb-footer'>
				<!-- <input type='submit' value='Update' class='btn btn-primary' /> -->
			</div>
		</form>
		</div>
	</div>
</div>