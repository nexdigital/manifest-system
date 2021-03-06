<?php

class Manifest_model extends CI_Model {


	#FILE MANIFEST ->
	function file_new_id(){
		$get = $this->db->count_all('manifest_file_table');
		$get = $get + 1;
		$len = strlen($get);
		switch ($len) {
			case '1': return '0000' . $get; break;
			case '2': return '000' . $get; break;
			case '3': return '00' . $get; break;
			case '4': return '0' . $get; break;			
			default: return $get; break;
		}
	}

	function file_insert_new($file){
		$this->db->insert('manifest_file_table',$file);
	}

	function get_all_file() {
		$this->db->join('manifest_data_table D','D.file_id = F.file_id');
		$this->db->group_by('F.file_id');
		$get = $this->db->get('manifest_file_table F');
		return $get->result();
	}
	function get_file() {
		$this->db->join('manifest_data_table D','D.file_id = F.file_id');
		$this->db->where('D.status','VALID');
		$this->db->group_by('F.file_id');
		$get = $this->db->get('manifest_file_table F');
		return $get->result();
	}

	function get_by_file_id($file_id) {
		$this->db->where('file_id',$file_id);
		$get = $this->db->get('manifest_file_table');
		if($get->num_rows() > 0) return $get->row();
		else return false;
	}


	#DATA MANIFEST ->
	function data_new_id(){
		$get = $this->db->count_all('manifest_data_table');
		$get = $get + 1;
		$len = strlen($get);
		switch ($len) {
			case '1': return '0000' . $get; break;
			case '2': return '000' . $get; break;
			case '3': return '00' . $get; break;
			case '4': return '0' . $get; break;			
			default: return $get; break;
		}
	}

	function data_insert_new($data) {
		$this->db->insert('manifest_data_table',$data);
	}

	function data_update($data,$data_id) {
		$this->db->where('data_id',$data_id);
		$this->db->update('manifest_data_table',$data);
	}

	function get_filtering_data($start = null,$limit = null,$where,$group_by = false) {
		foreach ($where as $key => $value) { 
			if(is_array($value)) $this->db->where_in($key,$value); else $this->db->where($key,$value); 
		}
		if(is_numeric($start)) $this->db->limit($limit,$start);
		if($group_by != false) $this->db->group_by($group_by);
		$get = $this->db->get('manifest_data_table D');

		if($get->num_rows() > 0) return $get->result();
		else return false;
	}

	function count_not_verified(){
		$QUERY = "
			SELECT * FROM manifest_data_table D
			WHERE D.status = 'NOT VERIFIED'
			GROUP BY D.file_id
		";
		$get = $this->db->query($QUERY);
		return $get->num_rows();
	}

	function set_status_data($data_id,$status) {
		$this->db->where('data_id',$data_id);
		$this->db->set('status',$status);
		$this->db->update('manifest_data_table');
	}

	function get_header_format(){
		$header = array('no','hawb_no','shipper','consignee','pkg','description','pcs','kg','value','pp','cc','remarks','other_charge_tata','other_charge_pml','mawb_type');
		return $header;
	}

	function get_by_data_id($data_id){
		$this->db->where('DATA_ID',$data_id);
		$get = $this->db->get('manifest_data_table');
		return $get->row();
	}

	function set_data_customer($cust_id,$data_id,$type) {
		$this->db->set($type,$cust_id);
		$this->db->where('data_id',$data_id);
		$this->db->update('manifest_data_table');
	}

	function check_valid_status($data) {
		$this->db->where('data_id',$data);
		$data = $this->db->get('manifest_data_table');
		$status = 0;

		$this->db->where('reference_id',$data->row()->shipper);
		$get = $this->db->get('customer_table');
		if($get->num_rows() > 0) $status++;

		$this->db->where('reference_id',$data->row()->consignee);
		$get = $this->db->get('customer_table');
		if($get->num_rows() > 0) $status++;

		if($status == 2) $this->set_status_data($data->row()->data_id,'VALID');

		return $status;
	}

	function search_hawb($hawb) {
		$this->db->like('hawb_no',$hawb);
		$this->db->where('status','VALID');
		$get = $this->db->get('manifest_data_table');
		if($get->num_rows() > 0) return $get->result();
		else return false;		
	}
	
	function deadline_data($days = NULL) {
		$limit_date = $this->tools->deadline($days);
		
		$this->db->where('deadline <',$limit_date);
		$this->db->where('status','VALID');
		$this->db->order_by('deadline','asc');
		$get = $this->db->get('manifest_data_table');
		if($get->num_rows() > 0) return $get->result();
		else return false;
	}

	#Counting
	function get_total($field, $file_id = 'FILE14111807191200001') {
		$this->db->select('SUM('.$field.') AS total');
		$this->db->where('file_id',$file_id);
		$get = $this->db->get('manifest_data_table');
		return $get->row()->total;
	}
	function get_total_where($field, $file_id = 'FILE14111807191200001', $where, $value) {
		$this->db->select('SUM('.$field.') AS total');
		$this->db->where('file_id',$file_id);
		$this->db->where($where,$value);
		$get = $this->db->get('manifest_data_table');
		return $get->row()->total;
	}
	function get_count_where($file_id = 'FILE14111807191200001', $where, $value) {
		$this->db->where('file_id',$file_id);
		$this->db->where($where,$value);
		$get = $this->db->get('manifest_data_table');
		return $get->num_rows();
	}

	#Extra Charge
	function add_extra_charge($charge) {
		$this->db->insert('manifest_extra_charge_table',$charge);
	}
	function delete_extra_charge($charge_id) {
		$this->db->where('charge_id',$charge_id);
		$this->db->delete('manifest_extra_charge_table');
	}
	function check_extra_charge($data_id,$type) {
		$this->db->where('data_id',$data_id);
		$this->db->where('type',$type);
		$get = $this->db->get('manifest_extra_charge_table');
		if($get->num_rows() > 0) return $get->row();
		else return FALSE;
	}

	function get_charge_type() {
		$get = $this->db->get('other_charge_type_table');
		return $get->result();
	}
	
	function get_extra_charge($data_id) {
		$this->db->where('data_id',$data_id);
		$get = $this->db->get('manifest_extra_charge_table');
		if($get->num_rows() > 0) return $get->result();
		else return FALSE;
	}
}
?>