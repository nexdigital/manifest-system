<?php $class_status_request = array('Waiting Approval' => 'warning', 'Approved' => 'success', 'Rejected' => 'danger', 'Cancelled' => 'default'); ?>
<div id="wrapper">
    <div id="page-wrapper">
    	<div class="row">
			<div class="col-lg-12">
	            <div class="panel panel-default">
	                <div class="panel-heading">
	                    List Discount
	                </div>
	                <div class="panel-body">
	                	<table  class="table table-striped table-bordered table-hover">
				            <thead>
				                <tr>
				                    <th>Discount No</th>
				                    <th>Hawb No</th>
				                    <th>Discount type</th>
				                    <th>Normal</th>
				                    <th>Discount</th>
				                    <th>Status</th>
				                    <th>Created</th>
				                    <th>User</th>
				                    <th width="70px">Action</th>
				                </tr>
				            </thead>
				            <tbody>
				            	<?php
				            		if($list_dicount != false) {
				            			foreach ($list_dicount as $key => $row) {
				            				$manifest_data = $this->manifest_model->get_by_data_id($row->data_id);
				            				$normal_price = null;
				            				if($row->type == 'rate') $normal_price = $manifest_data->nt_kurs;
				            				if($row->type == 'value') $normal_price = $manifest_data->value;
				            				if($row->type == 'total') $normal_price = ($manifest_data->kg * $manifest_data->value) * $manifest_data->nt_kurs;
				            				echo '
				            				<tr>
				            					<td>'.$row->discount_id.'</td>
				            					<td>'.$manifest_data->hawb_no.'</td>
				            					<td>'.$row->type.'</td>
				            					<td>'.$normal_price.'</td>
				            					<td>'.number_format($row->discount).'</td>
				            					<td><span class="label label-'.$class_status_request[$row->status].'" title="'.$row->status.'">'.$row->status.'</span></td>
				            					<td>'.$row->created_date.'</td>
				            					<td>'.$this->user_model->get_by_id($row->user_id)->username.'</td>
				            					<td>';
				            				if($row->status != 'Cancelled') {
				            				echo '
				            						<div class="btn-group btn-group-xs">
		                                                <button type="button" class="btn btn-primary open-file discount-edit" discount_id="'.$row->discount_id.'" title="Edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></button>
		                                                <button type="button" class="btn btn-danger open-file discount-cancel" discount_id="'.$row->discount_id.'" title="Cancel"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
		                                            </div>
				            					</td>';
				            				} else echo 'None';
				            				echo '
				            				</tr>
				            				';
				            			}
				            		} else echo '<tr><td colspan="9" class="text-center">No have list discount</td>';
				            	?>
				            </tbody>
				        </table>
	                </div>
	            </div>
	            <div class="col-lg-12">
	            	<button class="btn btn-primary btn-sm" onclick="window.location = '<?=base_url()?>request/discount/select'">Add Discount</button>
	            </div>
	        </div>
	    </div>
	    <?php if($this->session->userdata('user_type') == 'Admin') { ?>
        <div class="panel panel-default" style="margin-top:40px;">
	        <div class="panel-heading">
	            List Request Discount
	        </div>
            <div class="panel-body">
            	<table  class="table table-striped table-bordered table-hover">
		            <thead>
		                <tr>
		                    <th>Discount No</th>
		                    <th>Hawb No</th>
		                    <th>Discount type</th>
		                    <th>Normal</th>
		                    <th>Discount</th>
		                    <th>Status</th>
		                    <th>Created</th>
		                    <th>User</th>
		                    <th width="70px">Action</th>
		                </tr>
		            </thead>
		            <tbody>
		            	<?php
		            		if($list_dicount_request != false) {
		            			foreach ($list_dicount_request as $key => $row) {
		            				$manifest_data = $this->manifest_model->get_by_data_id($row->data_id);
		            				$normal_price = null;
		            				if($row->type == 'rate') $normal_price = $manifest_data->nt_kurs;
		            				if($row->type == 'value') $normal_price = $manifest_data->value;
		            				if($row->type == 'total') $normal_price = ($manifest_data->kg * $manifest_data->value) * $manifest_data->nt_kurs;
		            				echo '
		            				<tr>
		            					<td>'.$row->discount_id.'</td>
		            					<td>'.$manifest_data->hawb_no.'</td>
		            					<td>'.$row->type.'</td>
		            					<td>'.number_format($normal_price).'</td>
		            					<td>'.number_format($row->discount).'</td>
		            					<td><span class="label label-'.$class_status_request[$row->status].'" title="'.$row->status.'">'.$row->status.'</span></td>
		            					<td>'.$row->created_date.'</td>
		            					<td>'.$this->user_model->get_by_id($row->user_id)->username.'</td>
		            					<td>
		            						<div class="btn-group btn-group-xs">
                                                <button type="button" class="btn btn-primary open-file discount-approve" discount_id="'.$row->discount_id.'" title="Approve"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-danger open-file discount-reject" discount_id="'.$row->discount_id.'" title="Reject"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                                            </div>
		            					</td>
		            				</tr>
		            				';
		            			}
		            		} else echo '<tr><td colspan="9" class="text-center">No have list discount</td>';
		            	?>
		            </tbody>
		        </table>
            </div>
	    </div>
	    <?php } ?>
    </div>
</div>


<script type="text/javascript">
$(document).ready(function(){
	$('.discount-edit').click(function(){
		discount_id = $(this).attr('discount_id');
		window.location = '<?=base_url()?>request/discount/discount_id=' + discount_id;
	})
	$('.discount-cancel').click(function(){
		discount_id = $(this).attr('discount_id');
		$.post('<?=base_url()?>manifest/ajax/discount?type=cancel',{'discount_id':discount_id},function(data){
			location.reload();
		})
	})
	$('.discount-approve').click(function(){
		discount_id = $(this).attr('discount_id');
		$.post('<?=base_url()?>manifest/ajax/discount?type=approve',{'discount_id':discount_id},function(data){
			location.reload();
		})
	})
	$('.discount-reject').click(function(){
		discount_id = $(this).attr('discount_id');
		$.post('<?=base_url()?>manifest/ajax/discount?type=reject',{'discount_id':discount_id},function(data){
			location.reload();
		})
	})
})
</script>