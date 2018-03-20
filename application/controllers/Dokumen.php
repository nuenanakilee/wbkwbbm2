<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dokumen extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('DokumenModel','dokumen');
		$this->load->model('DokumenModel');
	}

	public function index()
	{
		
		$komponen = $this->DokumenModel->getKomponen();
		$bagkomponen = $this->DokumenModel->getBagkomponen();
		$kegiatan = $this->DokumenModel->getKegiatan();
		$seksi = $this->DokumenModel->getSeksi();
		$this->load->helper('url');
		$this->load->view('dokumen_view');
	}



	public function ajax_list()
	{
		$list = $this->dokumen->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $dokumen) {
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $dokumen->idkomp;
			$row[] = $dokumen->idbagkomp;
			$row[] = $dokumen->idkeg;
			$row[] = $dokumen->idpic;
			$row[] = $dokumen->idoutput;
			$row[] = $dokumen->outputkeg;
			$row[] = '<a href="'.$dokumen->filedok.'" target=_blank">Lihat Dokumen</a>';


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_dokumen('."'".$dokumen->idoutput."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_dokumen('."'".$dokumen->idoutput."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->dokumen->count_all(),
						"recordsFiltered" => $this->dokumen->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($idoutput)
	{
		$data = $this->dokumen->get_by_id($idoutput);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'idkomp' => $this->input->post('idkomp'),
				'idbagkomp' => $this->input->post('idbagkomp'),
				'idkeg' => $this->input->post('idkeg'),
				'idpic' => $this->input->post('idpic'),
				'idoutput' => $this->input->post('idoutput'),
				'outputkeg' => $this->input->post('outputkeg'),
				'filedok' => $this->input->post('filedok'),
			);
		$insert = $this->dokumen->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'idkomp' => $this->input->post('idkomp'),
				'idbagkomp' => $this->input->post('idbagkomp'),
				'idkeg' => $this->input->post('idkeg'),
				'idpic' => $this->input->post('idpic'),
				'idoutput' => $this->input->post('idoutput'),
				'outputkeg' => $this->input->post('outputkeg'),
				'filedok' => $this->input->post('filedok'),
			);
		$this->dokumen->update(array('idoutput' => $this->input->post('idoutput')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($idoutput)
	{
		$this->person->delete_by_id($idoutput);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('idkomp') == '')
		{
			$data['inputerror'][] = 'idkomp';
			$data['error_string'][] = 'Data Komponen Wajib Diinput ';
			$data['status'] = FALSE;
		}

		if($this->input->post('idbagkomp') == '')
		{
			$data['inputerror'][] = 'idbagkomp';
			$data['error_string'][] = 'Data Bagian Komponen Wajib Diinput';
			$data['status'] = FALSE;
		}

		if($this->input->post('idkeg') == '')
		{
			$data['inputerror'][] = 'idkeg';
			$data['error_string'][] = 'Data Kegiatan Wajib Diinput';
			$data['status'] = FALSE;
		}

		if($this->input->post('idpic') == '')
		{
			$data['inputerror'][] = 'idpic';
			$data['error_string'][] = 'Data Subbag/Seksi Wajib Diinput';
			$data['status'] = FALSE;
		}

		if($this->input->post('idoutput') == '')
		{
			$data['inputerror'][] = 'idoutput';
			$data['error_string'][] = 'ID Dokumen Wajib Diinput';
			$data['status'] = FALSE;
		}

		if($this->input->post('outputkeg') == '')
		{
			$data['inputerror'][] = 'outputkeg';
			$data['error_string'][] = 'Nama Dokumen Wajib Diinput';
			$data['status'] = FALSE;
		}

		if($this->input->post('filedok') == '')
		{
			$data['inputerror'][] = 'filedok';
			$data['error_string'][] = 'Link File Dokumen Wajib Diinput';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}


}
