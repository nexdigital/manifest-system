<?php
$shipper = $this->customers_model->get_by_id($data->shipper);
$shipper = $shipper->name.'
'.$shipper->address.'
'.$shipper->country;

$consignee = $this->customers_model->get_by_id($data->consignee);
$consignee = $consignee->name.'
'.$consignee->address.'
'.$consignee->country;
?>

<nav class="navbar navbar-default navbar-fixed-top text-left" role="navigation" style="padding:0px 20px;">
	<a class="navbar-brand" href="#">Details Data</a>
</nav>

<nav class="navbar navbar-default navbar-fixed-bottom text-right" role="navigation" style="padding:0px 10px;">
    <button type="button" class="btn btn-sm btn-primary navbar-btn" id="manifest_update_btn">Save</button>
    <button type="button" class="btn btn-sm btn-danger navbar-btn" onClick="parent.$.colorbox.close();">Close</button>
</nav>

<div id="wrapper" style="padding:20px; margin-top:40px; margin-bottom:30px; background-color:#fff;">
	<div class="row">
		<form method="post" action="<?=base_url()?>manifest/ajax/update" id="manifest_data_update">
		    <div class="col-sm-6">
		        <div class="form-group">
		            <label>Data ID</label>
		            <input class="form-control" type="text" name="data_id" value="<?=$data->data_id;?>" disabled>
		            <input class="form-control" type="hidden" name="data_id" value="<?=$data->data_id;?>">
		        </div>
		        <div class="form-group">
		            <label>Hawb No</label>
		            <input class="form-control" type="text" name="hawb_no" value="<?=$data->hawb_no;?>">
		        </div>
		    </div>
		    <div class="col-sm-2">
		    	<div class="form-group">
		            <label>Pkg</label>
		            <input class="form-control" type="text" name="pkg" value="<?=$data->pkg;?>">
		        </div>
		    </div>
		    <div class="col-sm-2">
		    	<div class="form-group">
		            <label>Pcs</label>
		            <input class="form-control" type="text" name="pcs" value="<?=$data->pcs;?>">
		        </div>
		    </div>
		    <div class="col-sm-2">
		    	<div class="form-group">
		            <label>KG</label>
		            <input class="form-control" type="text" name="kg" value="<?=$data->kg;?>">
		        </div>
		    </div>
		    <div class="col-sm-2">
		    	<div class="form-group">
		            <label>Value</label>
		            <input class="form-control" type="text" name="value" value="<?=$data->value;?>">
		        </div>
		    </div>
		    <div class="col-sm-2">
		    	<div class="form-group">
		            <label>Prepaid</label>
		            <input class="form-control" type="text" name="prepaid" value="<?=$data->prepaid;?>">
		        </div>
		    </div>
		    <div class="col-sm-2">
		    	<div class="form-group">
		            <label>Collect</label>
		            <input class="form-control" type="text" name="collect" value="<?=$data->collect;?>">
		        </div>
		    </div>
		    <div class="col-sm-6">
		    	<div class="form-group">
			    	<label>Shipper</label>
			        <textarea class="form-control" rows="4" disabled><?=ucfirst($shipper);?></textarea>
			    </div>
		    </div>
		    <div class="col-sm-6">
		    	<div class="form-group">
		    		<label>Consignee</label>
		        	<textarea class="form-control" rows="4" disabled><?=ucfirst($shipper);?></textarea>
		    	</div>
		    </div>
		    <div class="col-sm-6">
		     	<div class="form-group">
			    	<label>Description</label>
			        <textarea class="form-control" rows="3" name="description"><?=ucfirst($data->description);?></textarea>
			    </div>
		    </div>
		    <div class="col-sm-3">
		     	<div class="form-group">
			    	<label>Other Charge Tata</label>
			        <input class="form-control" type="text" name="other_charge_tata" value="<?=$data->other_charge_tata;?>">
			    </div>
		    </div>
		   	<div class="col-sm-3">
		     	<div class="form-group">
			    	<label>Other Charge PML</label>
			        <input class="form-control" type="text" name="other_charge_pml" value="<?=$data->other_charge_pml;?>">
			    </div>
		    </div>
		</form>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('#manifest_update_btn').click(function(){
		$('#manifest_data_update').ajaxSubmit({
			dataType:'json',
			success:function(data){
				window.location = '<?=base_url()?>manifest/modal/details?data_id=' + data.data_id;
			}
		})
	})
})
</script>