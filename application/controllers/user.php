<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        $this->load->helper('text');
        $this->load->helper('form');
        $this->load->library('image_lib');
        $this->load->library('form_validation');
         $this->load->model('user_model');
        
        date_default_timezone_set("Asia/Jakarta");


        if (!($this->session->userdata('lembaga'))) {
            redirect('login');
        }
    }

    public function index() {
        $data['halaman'] = 'Dashboard';
        $date = date('Y-m-d H:i:s');

        //Jika ada input
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');      
        $provinsi = $this->input->post('prov');

        //Jika input kosong
        if(!$tahun || !$bulan) {
            $bulan = date("m",strtotime($date));
            $bulan = $this->user_model->trans_bulan($bulan);
            $tahun = date("Y",strtotime($date)); 
            $provinsi = "Jawa Timur";
        }

        //Data isian provinsi tahun dan bulan
        $data['pilihan_provinsi'] = $this->user_model->get_provinsi();
        $data['pilihan_tahun'] = $this->user_model->get_tahun();
        $data['pilihan_bulan'] = $this->user_model->get_bulan();


        $data['provinsi_pilih'] = NULL;
        $data['tahun_pilih'] = NULL;
        $data['bulan_pilih'] = NULL;  

        //Jika tahun bulan dan provinsi sudah ada isinya
        if($tahun && $bulan && $provinsi) {
            $data['tahun_pilih'] = $tahun;
            $data['bulan_pilih'] = $bulan;
            $data['provinsi_pilih'] = $provinsi;
            
            //reverse bulan dan prov menjadi angka
            $bulan = $this->user_model->ubah_bulan($bulan);        
            $prov = $this->user_model->ubah_provinsi($provinsi);

            $cek_data_aktual = $this->user_model->cek_waktu_aktual($bulan,$tahun,$prov);
            $cek_data_prediksi = $this->user_model->cek_waktu_prediksi($bulan,$tahun,$prov);

            if($cek_data_aktual && $cek_data_prediksi) {
                $data['aktual'] = true;
                $data['prediksi'] = true;     

                $data['data_aktual_setaun'] = $this->user_model->get_data_aktual_setaun($tahun,$prov);
                $data['data_prediksi_setaun'] = $this->user_model->get_data_prediksi_setaun($tahun,$prov); 
                $data['rank_harga'] = $this->user_model->rank_harga($bulan,$tahun);
                $data['rank_produksi'] = $this->user_model->rank_produksi($bulan,$tahun);
            }
            else if($cek_data_prediksi) {
                $data['aktual'] = false;
                $data['prediksi'] = true;
                $data['data_prediksi_setaun'] = $this->user_model->get_data_prediksi_setaun($tahun,$prov); 
                $data['rank_harga'] = $this->user_model->rank_harga_prediksi($bulan,$tahun);
                $data['rank_produksi'] = $this->user_model->rank_produksi_prediksi($bulan,$tahun);
            }

            else if($cek_data_aktual) {
                $data['aktual'] = true;
                $data['prediksi'] = false;
                $data['data_aktual_setaun'] = $this->user_model->get_data_aktual_setaun($tahun,$prov); 
                $data['rank_harga'] = $this->user_model->rank_harga($bulan,$tahun);
                $data['rank_produksi'] = $this->user_model->rank_produksi($bulan,$tahun);
            } else {
                $data['aktual'] = false;
                $data['prediksi'] = false; 
                $data['rank_harga'] = NULL;
                $data['rank_produksi'] = NULL;
            }
        }
        
        $this->load->view('user/index_view',$data);
    }

    public function rekomendasi() {
        $data['halaman'] = 'Rekomendasi';
        $date = date('Y-m-d H:i:s');

        $this->form_validation->set_rules('tahun','Tahun', 'required');
        $this->form_validation->set_rules('bulan','Bulan', 'required');
        $this->form_validation->set_rules('prov','Provinsi', 'required');

        $data['provinsi_pilih'] = NULL;
        $data['tahun_pilih'] = NULL;
        $data['bulan_pilih'] = NULL;  
        
        //Data isian provinsi tahun dan bulan
        $data['pilihan_provinsi'] = $this->user_model->get_provinsi();
        $data['pilihan_tahun'] = $this->user_model->get_tahun();
        $data['pilihan_bulan'] = $this->user_model->get_bulan();

        if ($this->form_validation->run() == FALSE){
            $data['status_rekomendasi'] = FALSE;
            $data['aktual'] = false;
            $data['prediksi'] = false;
            $this->session->set_flashdata('msg2', '<div class="alert animated fadeInRight alert-danger">Pastikan  Provinsi, Tahun dan Bulan sudah terpilih.</div>');
            $this->load->view('user/rekomendasi_view',$data);
        }
        else {
            $this->session->set_flashdata('msg2',FALSE);
            $data['status_rekomendasi'] = TRUE;
            $data['pengaturan'] = $this->user_model->get_setting1();

            //Jika ada input
            $tahun = $this->input->post('tahun');
            $bulan = $this->input->post('bulan');      
            $provinsi = $this->input->post('prov');

            //Jika tahun bulan dan provinsi sudah ada isinya
            if($tahun && $bulan && $provinsi) {
                $data['tahun_pilih'] = $tahun;
                $data['bulan_pilih'] = $bulan;
                $data['provinsi_pilih'] = $provinsi;
                
                //reverse bulan dan prov menjadi angka
                $bulan = $this->user_model->ubah_bulan($bulan);
                $prov = $this->user_model->ubah_provinsi($provinsi);

                $bulan_depan = $bulan+1;
                $empat_bulan_lalu=$bulan-4;

                $tanggal = $tahun.'-'.$bulan.'-01';
                $date = date("Y-m-d",strtotime($tanggal));

                $one_month_ago = date("m",strtotime("-1 months",strtotime($date)));             
                $two_month_ago = date("m",strtotime("-2 months",strtotime($date))); 
                $three_month_ago = date("m",strtotime("-3 months",strtotime($date)));
                $four_month_ago = date("m",strtotime("-4 months",strtotime($date)));

                $data['bulan_depan'] = $this->user_model->trans_bulan($bulan_depan);
                $data['last_one_month'] = $this->user_model->trans_bulan($one_month_ago);
                $data['last_two_month'] = $this->user_model->trans_bulan($two_month_ago); 
                $data['last_three_month'] = $this->user_model->trans_bulan($three_month_ago); 
                $data['last_four_month'] = $this->user_model->trans_bulan($four_month_ago);   

                $data['status_HET'] = $data['pengaturan'][0]['status_HET'];
                $data['HET'] = $this->user_model->get_HET($prov);
                if ($prov=='01' || $prov=='02' || $prov=='03') {
                    $data['produktivitas'] = $data['pengaturan'][0]['produktivitas_jawa']; 
                } else {
                    $data['produktivitas'] = $data['pengaturan'][0]['produktivitas_luarjawa'];
                }

                $cek_data_aktual = $this->user_model->cek_waktu_aktual($bulan,$tahun,$prov);
                $cek_data_prediksi = $this->user_model->cek_waktu_prediksi($bulan,$tahun,$prov);

                if($cek_data_aktual && $cek_data_prediksi) {
                    $data['aktual'] = true;
                    $data['prediksi'] = true;     

                    $data['data_aktual'] = $this->user_model->get_data_aktual_pilih($bulan,$tahun,$prov);
                    $data['data_prediksi'] = $this->user_model->get_data_prediksi_pilih($bulan,$tahun,$prov);
                    $data['rekomendasi'] = $this->user_model->get_data_aktual_pilih($bulan,$tahun,$prov);

                    $data['stabilitas'] = $this->user_model->get_stabilitas($bulan,$tahun,$prov);
                    $data['data_aktual_setaun'] = $this->user_model->get_data_aktual_setaun($tahun,$prov);
                    $data['data_prediksi_setaun'] = $this->user_model->get_data_prediksi_setaun($tahun,$prov); 

                    if($bulan_depan=='13'){
                        $data['data_prediksi_bulan_depan'] = $this->user_model->get_data_prediksi_pilih(($bulan_depan-12),($tahun+1),$prov);
                    }else {
                        $data['data_prediksi_bulan_depan'] = $this->user_model->get_data_prediksi_pilih(($bulan_depan),($tahun),$prov);
                    }

                    if($bulan=='04' || $bulan=='03' || $bulan=='02' || $bulan=='01') {
                        $data['luas_tanam_empat_bulan_sebelum'] = $this->user_model->get_luastanam_aktual($bulan+8,$tahun-1,$prov);
                    } else {
                        $data['luas_tanam_empat_bulan_sebelum'] = $this->user_model->get_luastanam_aktual($bulan-4,$tahun,$prov);
                    }
                    $data['data_aktual_setaun'] = $this->user_model->get_data_aktual_setaun($tahun,$prov);
                    $data['data_prediksi_setaun'] = $this->user_model->get_data_prediksi_setaun($tahun,$prov);
                    $data['rule_based_system'] = $this->user_model->get_rule();

                }
                else if($cek_data_prediksi) {

                    $data['aktual'] = false;
                    $data['prediksi'] = true;
                    $data['data_prediksi'] = $this->user_model->get_data_prediksi_pilih($bulan,$tahun,$prov);
                    $data['rekomendasi'] = $this->user_model->get_data_prediksi_pilih($bulan,$tahun,$prov);
                    if($bulan_depan=='13'){
                        $data['data_prediksi_bulan_depan'] = $this->user_model->get_data_prediksi_pilih(($bulan_depan-12),($tahun+1),$prov);
                    }else {
                        $data['data_prediksi_bulan_depan'] = $this->user_model->get_data_prediksi_pilih(($bulan_depan),($tahun),$prov);
                    }

                    if($bulan=='04' || $bulan=='03' || $bulan=='02' || $bulan=='01') {
                        $data['luas_tanam_empat_bulan_sebelum'] = $this->user_model->get_luastanam_prediksi($bulan+8,$tahun-1,$prov);
                    } else {
                        $data['luas_tanam_empat_bulan_sebelum'] = $this->user_model->get_luastanam_prediksi($bulan-4,$tahun,$prov);
                    }

                    $data['stabilitas'] = $this->user_model->get_stabilitas_prediksi($bulan,$tahun,$prov);
                    $data['data_prediksi_setaun'] = $this->user_model->get_data_prediksi_setaun($tahun,$prov);
                    $data['rule_based_system'] = $this->user_model->get_rule(); 
                }

                else if($cek_data_aktual) {
                    $data['aktual'] = true;
                    $data['prediksi'] = false;
                    $data['data_aktual'] = $this->user_model->get_data_aktual_pilih($bulan,$tahun,$prov);
                    $data['rekomendasi'] = $this->user_model->get_data_aktual_pilih($bulan,$tahun,$prov);
                    $data['stabilitas'] = $this->user_model->get_stabilitas($bulan,$tahun,$prov);
                    $data['data_aktual_setaun'] = $this->user_model->get_data_aktual_setaun($tahun,$prov); 

                    if($bulan=='04' || $bulan=='03' || $bulan=='02' || $bulan=='01') {
                        $data['luas_tanam_empat_bulan_sebelum'] = $this->user_model->get_luastanam_aktual($bulan+8,$tahun-1,$prov);
                    } else {
                        $data['luas_tanam_empat_bulan_sebelum'] = $this->user_model->get_luastanam_aktual($bulan-4,$tahun,$prov);
                    }
                    $data['rule_based_system'] = $this->user_model->get_rule();
                } else {
                    $data['aktual'] = false;
                    $data['prediksi'] = false;
                    $data['status_rekomendasi'] = false;
                }
            }
            //print_r($data['aktual']);exit();
            $this->load->view('user/rekomendasi_view',$data);
        }
        
        
    }

    public function lihatData() {
        $data['halaman'] = 'Lihat Data';
        //post isian bulan tahun dan provinsi
        $tahun = $this->input->post('tahun');
        $bulan = $this->input->post('bulan');      
        $provinsi = $this->input->post('prov');        

        $data['kategori1'] = 'Aktual';
        $data['kategori2'] = 'Prediksi';
        //jika tidak memilih bulan tahun dan provinsi
        $data['data_aktual'] = $this->user_model->get_data_aktual();
        $data['data_prediksi'] = $this->user_model->get_data_prediksi();
        $data['pilihan_provinsi'] = $this->user_model->get_provinsi();
        $data['pilihan_tahun'] = $this->user_model->get_tahun();
        $data['pilihan_bulan'] = $this->user_model->get_bulan(); 

        $data['provinsi_pilih'] = NULL;  
        $data['tahun_pilih'] = NULL;
        $data['bulan_pilih'] = NULL; 
        //jika memilih bulan tahun dan provinsi
        if($tahun && $bulan && $provinsi) {   
            $data['tahun_pilih'] = $tahun;
            $data['bulan_pilih'] = $bulan;
            $data['provinsi_pilih'] = $provinsi;

            $bulan = $this->user_model->ubah_bulan($bulan);        
            $prov = $this->user_model->ubah_provinsi($provinsi);      

            $data['data_aktual'] = $this->user_model->get_data_aktual_pilih($bulan,$tahun,$prov);
            $data['data_prediksi'] = $this->user_model->get_data_prediksi_pilih($bulan,$tahun,$prov);
        }
        //print_r($data['data_aktual']);exit();

        $this->load->view('user/listdata_view',$data);
    }

    public function inputData(){
        $data['halaman'] = 'Entri Data';
        $this->form_validation->set_rules('kategori','kategori', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['provinsi']=$this->user_model->get_provinsi();
            $data['bulan']=$this->user_model->get_bulan();
            $this->load->view('user/inputdata_view',$data);
        }
        else {
            $kategori = $this->input->post('kategori');     
            $provinsi = $this->input->post('prov'); 
            $tahun = $this->input->post('tahun');
            $bulan = $this->input->post('bulan');       
            $harga = $this->clean($this->input->post('harga'));       
            $produksi = $this->clean($this->input->post('produksi'));

            $bln = $this->user_model->ubah_bulan($bulan);
            $waktu = $tahun.'-'.$bln.'-'.'01';
            $date = date("Y-m-d",strtotime($waktu));
            $bulanangka = date("m",strtotime($waktu));
            $tahun = date("Y",strtotime($waktu));
            $bulan = $this->user_model->trans_bulan($bulanangka);
            $prov = $this->user_model->ubah_provinsi($provinsi);

            //cek apakah sudah ada dimensi waktu
            $cek_dimensi_waktu = $this->user_model->cek($bulanangka,$tahun);
            $cek_waktu_aktual = $this->user_model->cek_waktu_aktual($bulanangka,$tahun,$prov);
            $cek_waktu_prediksi = $this->user_model->cek_waktu_prediksi($bulanangka,$tahun,$prov);
            if (!$cek_dimensi_waktu) {
                    $dimensi_waktu = array(                
                        'id_waktu'  => $date,
                        'bulan'  => $bulan,
                        'tahun'  => $tahun,                
                    );
                    //print_r($dimensi_waktu);exit();
                    $this->user_model->insertData('waktu',$dimensi_waktu);
                }

            //insert data untuk masing-masing kategori
            if ($kategori=='Prediksi') {
                if ($cek_waktu_prediksi['prediksi_harga'] && $cek_waktu_prediksi['prediksi_produksi']) {
                    $this->session->set_flashdata('msg', '<div class="alert animated fadeInRight alert-danger">Anda sudah memasukkan data pada bulan ini. Silahkan masuk ke menu <b>Lihat Data</b> untuk melakukan perubahan.</div>');
                        redirect('User/inputData');
                } else if ($cek_waktu_prediksi['prediksi_harga'] && !$cek_waktu_prediksi['prediksi_produksi']) {
                    $id=$cek_waktu_prediksi['id_prediksi'];
                    if($harga > 0 && $produksi > 0){
                        $data_prediksi = array(     
                            'prediksi_harga'  => $harga,
                            'prediksi_produksi'  => $produksi
                        );
                    }else if ($harga > 0) {
                        $data_prediksi = array(     
                            'prediksi_harga'  => $harga
                        );
                    } else if ($produksi > 0){
                        $data_prediksi = array(
                            'prediksi_produksi'  => $produksi
                        );
                    }
                    $this->user_model->updateData('id_prediksi', $id, 'beras_prediksi', $data_prediksi);
                } else if ($cek_waktu_prediksi['prediksi_produksi'] && !$cek_waktu_prediksi['prediksi_harga']) {
                    $id=$cek_waktu_prediksi['id_prediksi'];
                    if($harga > 0 && $produksi > 0){
                        $data_prediksi = array(     
                            'prediksi_harga'  => $harga,
                            'prediksi_produksi'  => $produksi
                        );
                    }else if ($harga > 0) {
                        $data_prediksi = array(     
                            'prediksi_harga'  => $harga
                        );
                    } else if ($produksi > 0){
                        $data_prediksi = array(
                            'prediksi_produksi'  => $produksi
                        );
                    } 
                    $this->user_model->updateData('id_prediksi', $id, 'beras_prediksi', $data_prediksi);
                } else {
                    $data_prediksi = array(                
                        'id_waktu'  => $date,
                        'id_provinsi'  => $prov,
                        'prediksi_harga'  => $harga,
                        'prediksi_produksi'  => $produksi
                    );
                    $this->user_model->insertData('beras_prediksi',$data_prediksi);
                }
            }
            else if ($kategori=='Aktual') {
                if ($cek_waktu_aktual['aktual_harga'] && $cek_waktu_aktual['aktual_produksi']) {
                    $this->session->set_flashdata('msg', '<div class="alert animated fadeInRight alert-danger">Anda sudah memasukkan data pada bulan ini. Silahkan masuk ke menu <b>Lihat Data</b> untuk melakukan perubahan.</div>');
                        redirect('User/inputData');
                } else if ($cek_waktu_aktual['aktual_harga'] && !$cek_waktu_aktual['aktual_produksi']) {
                    $id=$cek_waktu_aktual['id_aktual'];
                    if ($harga > 0 && $produksi > 0){
                        $data_aktual = array(     
                            'aktual_harga'  => $harga,
                            'aktual_produksi'  => $produksi
                        );
                    }else if ($harga > 0) {
                        $data_aktual = array(     
                            'aktual_harga'  => $harga
                        );
                    } else if ($produksi > 0){
                        $data_aktual = array(
                            'aktual_produksi'  => $produksi
                        );
                    } 
                    $this->user_model->updateData('id_aktual', $id, 'beras_aktual', $data_aktual);
                    $this->forecast($date,$prov,$produksi);
                } else if ($cek_waktu_aktual['aktual_produksi'] && !$cek_waktu_aktual['aktual_harga']) {
                    $id=$cek_waktu_aktual['id_aktual'];
                    if ($harga > 0 && $produksi > 0){
                        $data_aktual = array(     
                            'aktual_harga'  => $harga,
                            'aktual_produksi'  => $produksi
                        );
                    } else if ($harga > 0) {
                        $data_aktual = array(     
                            'aktual_harga'  => $harga
                        );
                    } else if ($produksi > 0){
                        $data_aktual = array(
                            'aktual_produksi'  => $produksi
                        );
                    } 
                    $this->user_model->updateData('id_aktual', $id, 'beras_aktual', $data_aktual);
                    $this->forecast($date,$prov,$produksi);
                } else {
                    $data_aktual = array(                
                        'id_waktu'  => $date,
                        'id_provinsi'  => $prov,
                        'aktual_harga'  => $harga,
                        'aktual_produksi'  => $produksi
                    );
                    $this->user_model->insertData('beras_aktual',$data_aktual);

                    if($produksi){
                        $this->forecast($date,$prov,$produksi);
                    }
                }      
            }
            $message = "Data berhasil disimpan.";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href='".base_url("user/lihatData")."';</script>";
        }
    }

    public function editData($kategori,$id){
        $data['halaman'] = 'Entri Data';
        $this->form_validation->set_rules('harga','Harga', 'required');
        $this->form_validation->set_rules('produksi','Produksi', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['kategori'] = $kategori;
            $data['id'] = $id;
            if ($kategori=='Prediksi') {
                $data['data'] = $this->user_model->findPrediksi($id);
            }
            else {
                $data['data'] = $this->user_model->findAktual($id);
            }
            $data['provinsi']=$this->user_model->get_provinsi();
            $data['bulan']=$this->user_model->get_bulan();

            $this->load->view('user/editdata_view',$data);
        }
        else {
            $kategori = $this->input->post('kategori');     
            $provinsi = $this->input->post('prov'); 
            $tahun = $this->input->post('tahun');
            $bulan = $this->input->post('bulan');       
            $harga = $this->clean($this->input->post('harga'));       
            $produksi = $this->clean($this->input->post('produksi'));
            if ($kategori=='Prediksi') {
                $data = array(                
                        'id_prediksi'  => $id,
                        'prediksi_harga'  => $harga,
                        'prediksi_produksi'  => $produksi
                );
                $this->user_model->updateData('id_prediksi', $id, 'beras_prediksi', $data);
            }
            else {
                $data = array(                
                        'id_aktual'  => $id,
                        'aktual_harga'  => $harga,
                        'aktual_produksi'  => $produksi
                );
                $this->user_model->updateData('id_aktual', $id, 'beras_aktual', $data);
            }
            $message = "Data berhasil diedit.";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href='".base_url("user/lihatData")."';</script>";
        }
    }

    function forecast($date,$prov,$produksi) {
        $data['data_beras']=NULL;
        $bulannow = date("m",strtotime($date));
        $tahun = date("Y",strtotime($date));
        $cek_data_prediksi = $this->user_model->cek_waktu_prediksi($bulannow,$tahun,$prov);

        $bulanpast = $bulannow-1;
        //Jika bulan sekarang januari, maka bulan lalu adalah desember tahun lalu
        if ($bulanpast==0) {
            $bulanpast=12;
            $tahun=$tahun-1;
        }
        $cek_data_prediksi_past = $this->user_model->cek_waktu_prediksi($bulanpast,$tahun,$prov);
        $cek_data_aktual_past = $this->user_model->cek_waktu_aktual($bulanpast,$tahun,$prov);

        if($cek_data_aktual_past && $cek_data_prediksi_past) {
            $data['data_beras'] = $this->user_model->get_data_aktual_pilih($bulanpast,$tahun,$prov);
        } else if ($cek_data_prediksi_past) {
            $data['data_beras'] = $this->user_model->get_data_prediksi_pilih($bulanpast,$tahun,$prov);
        } else if ($cek_data_aktual_past){
            $data['data_beras'] = $this->user_model->get_data_aktual_pilih($bulanpast,$tahun,$prov);
        }

        if($data['data_beras'][0]['harga'] > 0 && $prov > 0){
            //Model Stepwise Regresi Masing Masing Provinsi 
            if($prov==01) {
                $harga = 177.1 + 0.9966*$data['data_beras'][0]['harga'] - 0.000128*$produksi;
            } else if($prov==02) {
                $harga = 186.3 + 0.9951*$data['data_beras'][0]['harga']- 0.0001349*$produksi;
            } else if($prov==03) {
                $harga = 94.98 + 1.003*$data['data_beras'][0]['harga']-0.00007929*$produksi;
            } else if($prov==04) {
                $harga = 103.5 + 0.9968*$data['data_beras'][0]['harga']- 0.0001121*$produksi;
            } else if($prov==05) {
                $harga = 146.8 + 0.9939*$data['data_beras'][0]['harga'] - 0.0002551*$produksi;
            } else if($prov==06) {
                $harga = 103.2 + 0.994*$data['data_beras'][0]['harga'] -0.00008089*$produksi;
            } else if($prov==07) {
                $harga = 173.2 + 0.9874*$data['data_beras'][0]['harga'] - 0.0002333*$produksi;
            } else if($prov==08) {
                $harga = -192 + 1.035*$data['data_beras'][0]['harga'] - 0.002558*$produksi;
            } else if($prov==09) {
                $harga = 164.4 + 0.9869*$data['data_beras'][0]['harga'] - 0.000325*$produksi;
            } else if($prov==10) {
                $harga = -25.698886 + 1.013305*$data['data_beras'][0]['harga']- 0.002035*$produksi;
            } else if($prov==11) {
                $harga = 200.5 + 0.9794*$data['data_beras'][0]['harga'] - 0.0004304*$produksi;
            }

            //Mengecek apakah sudah terdapat data prediksi atau belum
            if(!$cek_data_prediksi){
                $data_prediksi = array(                
                'id_waktu'  => $date,
                'id_provinsi'  => $prov,
                'prediksi_harga'  => round($harga)
                );
                $this->user_model->insertData('beras_prediksi',$data_prediksi);
            }
        }
    }

    //Kondisi
    public function dataKondisi() {
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'Pengetahuan';
            $data['kondisi'] = $this->user_model->get_kondisi();
            $this->load->view('user/datakondisi_view',$data);
        }
    }

    public function tambahKondisi(){
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'Pengetahuan';
            $this->form_validation->set_rules('id_kondisi','Kode Kondisi', 'required');

            if ($this->form_validation->run() == FALSE){
                $data['kondisi'] = $this->user_model->get_kondisi();
                $this->load->view('user/datakondisi_view',$data);
            }
            else {
                $kode = $this->input->post('id_kondisi');     
                $kondisi = $this->input->post('nama_kondisi'); 
                $kategori = $this->input->post('kategori_kondisi');

                $data_kondisi = array(                
                        'id_kondisi'  => $kode,
                        'nama_kondisi'  => $kondisi,
                        'kategori_kondisi'  => $kategori              
                );
                //print_r($data_kondisi);exit();
                $this->user_model->insertData('kondisi',$data_kondisi);
                $message = "Kondisi berhasil disimpan.";
                echo "<script type='text/javascript'>alert('$message');
                window.location.href='".base_url("user/dataKondisi")."';</script>";
            }
        }
    }

    public function editKondisi($id_kondisi){
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'Pengetahuan';
            $this->form_validation->set_rules('id_kondisi','Kode Kondisi', 'required');

            if ($this->form_validation->run() == FALSE){
                $data['kondisi'] = $this->user_model->get_kondisi();
                $this->load->view('user/datakondisi_view',$data);
            }
            else {
                $kode = $this->input->post('id_kondisi');     
                $kondisi = $this->input->post('nama_kondisi'); 
                $kategori = $this->input->post('kategori_kondisi');

                $data_kondisi = array(                
                        'id_kondisi'  => $kode,
                        'nama_kondisi'  => $kondisi,
                        'kategori_kondisi'  => $kategori              
                );
                //print_r($data_kondisi);exit();
                $this->user_model->updateData('id_kondisi',$id_kondisi,'kondisi',$data_kondisi);
                $message = "Kondisi berhasil diupdate.";
                echo "<script type='text/javascript'>alert('$message');
                window.location.href='".base_url("user/dataKondisi")."';</script>";
            }
        }
    }

    public function hapusKondisi($id_kondisi) {
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $this->user_model->deleteData('id_kondisi', $id_kondisi, 'kondisi');
            $message = "Data kondisi berhasil dihapus";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href='".base_url("user/dataKondisi")."';</script>";
        }
    }

    //Rekomendasi
    public function dataRekomendasi() {
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'Pengetahuan';
            $data['rekomendasi'] = $this->user_model->get_rekomendasi();
            $this->load->view('user/datarekomendasi_view',$data);
        }
    }

    public function tambahRekomendasi(){
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'Pengetahuan';
            $this->form_validation->set_rules('id_rekomendasi','Kode Rekomendasi', 'required');

            if ($this->form_validation->run() == FALSE){
                $data['rekomendasi'] = $this->user_model->get_rekomendasi();
                $this->load->view('user/datarekomendasi_view',$data);
            }
            else {
                $data_rekomendasi = array(                
                        'id_rekomendasi'  => $this->input->post('id_rekomendasi'),
                        'nama_rekomendasi'  => $this->input->post('nama_rekomendasi'),
                        'rekomendasi_1'  => $this->input->post('rekomendasi_1'),
                        'rekomendasi_2'  => $this->input->post('rekomendasi_2'),
                        'rekomendasi_3'  => $this->input->post('rekomendasi_3'),              
                );
                //print_r($data_rekomendasi);exit();
                $this->user_model->insertData('rekomendasi',$data_rekomendasi);
                $message = "Rekomendasi berhasil disimpan.";
                echo "<script type='text/javascript'>alert('$message');
                window.location.href='".base_url("user/dataRekomendasi")."';</script>";
            }
        }
    }

    public function editRekomendasi($id_rekomendasi){
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'Pengetahuan';
            $this->form_validation->set_rules('id_rekomendasi','Kode Rekomendasi', 'required');

            if ($this->form_validation->run() == FALSE){
                $data['rekomendasi'] = $this->user_model->get_rekomendasi();
                $this->load->view('user/datarekomendasi_view',$data);
            }
            else {
                $data_rekomendasi = array(                
                        'id_rekomendasi'  => $this->input->post('id_rekomendasi'),
                        'nama_rekomendasi'  => $this->input->post('nama_rekomendasi'),
                        'rekomendasi_1'  => $this->input->post('rekomendasi_1'),
                        'rekomendasi_2'  => $this->input->post('rekomendasi_2'),
                        'rekomendasi_3'  => $this->input->post('rekomendasi_3'),            
                );
                //print_r($data_rekomendasi);exit();
                $this->user_model->updateData('id_rekomendasi',$id_rekomendasi,'rekomendasi',$data_rekomendasi);
                $message = "Rekomendasi berhasil diupdate.";
                echo "<script type='text/javascript'>alert('$message');
                window.location.href='".base_url("user/dataRekomendasi")."';</script>";
            }
        }
    }

    public function hapusRekomendasi($id_rekomendasi) {
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $this->user_model->deleteData('id_rekomendasi', $id_rekomendasi, 'rekomendasi');
            $message = "Data rekomendasi berhasil dihapus";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href='".base_url("user/dataRekomendasi")."';</script>";
        }
    }

    //Aturan
    public function dataAturan() {
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'Pengetahuan';
            $data['kondisi'] = $this->user_model->get_kondisi();
            $data['rekomendasi'] = $this->user_model->get_rekomendasi();
            $data['aturan'] = $this->user_model->get_aturan();
            $data['rule_based_system'] = $this->user_model->get_rule();
            $this->load->view('user/dataaturan_view',$data);
        }
    }

    public function tambahAturan(){
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'Pengetahuan';
            $this->form_validation->set_rules('id_aturan','Kode Aturan', 'required');

            if ($this->form_validation->run() == FALSE){
                $data['kondisi'] = $this->user_model->get_kondisi();
                $data['rekomendasi'] = $this->user_model->get_rekomendasi();
                $data['aturan'] = $this->user_model->get_aturan();
                $this->load->view('user/dataaturan_view',$data);
            }
            else {
                $data_aturan = array(                
                        'id_aturan'  => $this->input->post('id_aturan'),
                        'kondisi_harga'  => $this->input->post('kondisi_harga'),
                        'kondisi_musim'  => $this->input->post('kondisi_musim'),
                        'kondisi_bencana'  => $this->input->post('kondisi_bencana'),
                        'kondisi_hama'  => $this->input->post('kondisi_hama'),
                        'rekomendasi'  => $this->input->post('rekomendasi'),              
                );
                //print_r($data_aturan);exit();
                $this->user_model->insertData('aturan',$data_aturan);
                $message = "Aturan berhasil disimpan.";
                echo "<script type='text/javascript'>alert('$message');
                window.location.href='".base_url("user/dataAturan")."';</script>";
            }
        }
    }

    public function editAturan($id_aturan){
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'Pengetahuan';
            $this->form_validation->set_rules('id_aturan','Nama Aturan', 'required');

            if ($this->form_validation->run() == FALSE){
                $data['kondisi'] = $this->user_model->get_kondisi();
                $data['rekomendasi'] = $this->user_model->get_rekomendasi();
                $data['aturan'] = $this->user_model->get_aturan();
                $this->load->view('user/dataaturan_view',$data);
            }
            else {
                $data_aturan = array(                
                        'id_aturan'  => $this->input->post('id_aturan'),
                        'kondisi_harga'  => $this->input->post('kondisi_harga'),
                        'kondisi_musim'  => $this->input->post('kondisi_musim'),
                        'kondisi_bencana'  => $this->input->post('kondisi_bencana'),
                        'kondisi_hama'  => $this->input->post('kondisi_hama'),
                        'rekomendasi'  => $this->input->post('rekomendasi'),            
                );
                //print_r($data_aturan);exit();
                $this->user_model->updateData('id_aturan',$id_aturan,'aturan',$data_aturan);
                $message = "Aturan berhasil diupdate.";
                echo "<script type='text/javascript'>alert('$message');
                window.location.href='".base_url("user/dataAturan")."';</script>";
            }
        }
    }

    public function hapusAturan($id_aturan) {
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $this->user_model->deleteData('id_aturan', $id_aturan, 'aturan');
            $message = "Data aturan berhasil dihapus";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href='".base_url("user/dataAturan")."';</script>";
        }
    }

    //User
    public function manajemenUser() {
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'User';
            $data['provinsi'] = $this->user_model->get_provinsi();
            $data['user'] = $this->user_model->get_user();
            $this->load->view('user/manajemenuser_view',$data);
        }
    }

    public function tambahUser(){
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'User';
            $this->form_validation->set_rules('username','Username', 'required');

            if ($this->form_validation->run() == FALSE){
                $data['provinsi'] = $this->user_model->get_provinsi();
                $data['user'] = $this->user_model->get_user();
                $this->load->view('user/manajemenuser_view',$data);
            }
            else {
                $lembaga = $this->input->post('nama_lembaga');     
                $username = $this->input->post('username'); 
                $password = $this->input->post('password');
                $role = $this->input->post('role');       
                $provinsi = $this->input->post('prov');    

                if ($role=='Admin Pusat') {
                    $data_user = array(                
                        'lembaga'  => $lembaga,
                        'username'  => $username,
                        'password'  => $password,
                        'role'  => $role               
                    );
                    //print_r($data_user);exit();
                    $this->user_model->insertData('user',$data_user);
                } else {
                    $data_user = array(                
                            'lembaga'  => $lembaga,
                            'username'  => $username,
                            'password'  => $password,
                            'role'  => $role,
                            'provinsi'  => $provinsi,                
                    );
                    //print_r($data_user);exit();
                    $this->user_model->insertData('user',$data_user);
                }
                $message = "User berhasil disimpan.";
                echo "<script type='text/javascript'>alert('$message');
                window.location.href='".base_url("user/manajemenUser")."';</script>";
            }
        }
    }

    public function hapusUser($id) {
        if ($this->session->userdata('role')!=0) {
            $this->load->view('error');
        }
        else {
            $this->user_model->deleteData('id_user', $id, 'user');
            $message = "Data user berhasil dihapus";
            echo "<script type='text/javascript'>alert('$message');
            window.location.href='".base_url("user/manajemenUser")."';</script>";
        }
    }

    //Pengaturan
    public function setting() {
        if (!$this->session->userdata('username')) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'Setting';
            $data['pengaturan1'] = $this->user_model->get_setting1();
            $data['pengaturan2'] = $this->user_model->get_setting2();
            $this->load->view('user/setting_view',$data);
        }
    }

    public function editHET($id_provinsi){
        if (!$this->session->userdata('username')) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'Setting';
            $this->form_validation->set_rules('HET','HET', 'required');

            if ($this->form_validation->run() == FALSE){
                $data['pengaturan1'] = $this->user_model->get_setting1();
                $data['pengaturan2'] = $this->user_model->get_setting2();
                $this->load->view('user/setting_view',$data);
            }
            else {
                $data_setting = array(                
                        'HET'  => $this->clean($this->input->post('HET'))           
                );
                //print_r($data_setting);exit();
                $this->user_model->updateData('id_provinsi',$id_provinsi,'provinsi',$data_setting);
                $this->session->set_flashdata('msg', '<div class="alert animated fadeInRight alert-success">Berhasil diupdate.</div>');
                redirect('user/setting');
            }
        }
    }

    public function editSetting($id){
        if (!$this->session->userdata('username')) {
            $this->load->view('error');
        }
        else {
            $data['halaman'] = 'Setting';
            $this->form_validation->set_rules('status_HET','status_HET', 'required');

            if ($this->form_validation->run() == FALSE){
                $data['pengaturan1'] = $this->user_model->get_setting1();
                $data['pengaturan2'] = $this->user_model->get_setting2();
                $this->session->set_flashdata('msg', '<div class="alert animated fadeInRight alert-danger">Gagal diupdate.</div>');
                $this->load->view('user/setting_view',$data);
            }
            else {
                $data_setting = array(                
                        'status_HET'  => $this->input->post('status_HET'),
                        'produktivitas_jawa'  => $this->input->post('prod_jawa'),
                        'produktivitas_luarjawa'  => $this->input->post('prod_luarjawa'),           
                );
                //print_r($data_setting);exit();
                $this->user_model->updateData('id_setting',$id,'setting',$data_setting);
                $this->session->set_flashdata('msg', '<div class="alert animated fadeInRight alert-success">Berhasil diupdate.</div>');
                redirect('user/setting');
            }
        }
    }


    //Forecast Data
    public function generateData() {
        $data['halaman'] = 'Generate';

        $this->form_validation->set_rules('tahun','Tahun', 'required');
        $this->form_validation->set_rules('prov','Provinsi', 'required');

        $data['provinsi_pilih'] = NULL;
        $data['tahun_pilih'] = NULL;  
        
        //Data isian provinsi tahun dan bulan
        $data['pilihan_provinsi'] = $this->user_model->get_provinsi();
        $data['pilihan_tahun'] = $this->user_model->get_tahun();

        if ($this->form_validation->run() == FALSE){
            $this->session->set_flashdata('msg2', '<div class="alert animated fadeInRight alert-danger">Pastikan  Provinsi dan Tahun sudah terpilih.</div>');
            $this->load->view('user/forecast_view',$data);
        }
        else {
            $this->session->set_flashdata('msg2', '<div class="alert animated fadeInRight alert-success">Proses forecast berhasil dilakukan.</div>');
            
            //Jika ada input
            $tahun = $this->input->post('tahun');    
            $provinsi = $this->input->post('prov');

            //Jika tahun bulan dan provinsi sudah ada isinya
            if($tahun && $provinsi) {
                $data['tahun_pilih'] = $tahun;
                $data['provinsi_pilih'] = $provinsi;

                //reverse bulan dan prov menjadi angka
                $tahun=2018;
                $prov = $this->user_model->ubah_provinsi($provinsi);
                $this->forecastProduksi($tahun,$prov);
                $this->forecastHarga($tahun,$prov);
                
                
                exit();
                //$cek_data_aktual = $this->user_model->cek_waktu_aktual($bulan,$tahun,$prov);
                //$cek_data_prediksi = $this->user_model->cek_waktu_prediksi($bulan,$tahun,$prov);
                
            }
            $this->load->view('user/forecast_view',$data);
        }
        
        
    }

    function forecastProduksi($tahun,$prov){
        for ($i=1; $i <= 12 ; $i++) { 
            $a=0;

            $tanggal = $tahun.'-'.$i.'-01';
            $date_now = date("Y-m-d",strtotime($tanggal));
            $date_4_month_ago = date("Y-m-d",strtotime("-4 months",strtotime($date_now)));

            //Mengecek apakah menggunakan data aktual atau prediksi
            $cek_data_aktual = $this->user_model->cek_aktual($date_4_month_ago,$prov);
            $cek_data_prediksi = $this->user_model->cek_prediksi($date_4_month_ago,$prov);    

            if ($cek_data_aktual && $cek_data_prediksi) {
                $data['data_olah'] = $this->user_model->get_aktual_pilih($date_4_month_ago,$prov);
                $luas_4_bulan_lalu=$data['data_olah'][0]['aktual_luastanam'];
            }
            else if($cek_data_prediksi) {
                $data['data_olah'] = $this->user_model->get_prediksi_pilih($date_4_month_ago,$prov);
                $luas_4_bulan_lalu=$data['data_olah'][0]['prediksi_luastanam'];
            }
            else if($cek_data_aktual) {
                $data['data_olah'] = $this->user_model->get_aktual_pilih($date_4_month_ago,$prov);
                $luas_4_bulan_lalu=$data['data_olah'][0]['aktual_luastanam'];
            }

            //Memilih fungsi berdasarkan provinsi
            if($prov==01) {
                $prediksi_produksi = -116100 + 6.505*$luas_4_bulan_lalu;
            } else if($prov==02) {
                $prediksi_produksi = -109800 + 6.164*$luas_4_bulan_lalu;
            } else if($prov==03) {
                $prediksi_produksi = -27830 + 5.505*$luas_4_bulan_lalu;
            } else if($prov==04) {
                $prediksi_produksi = 110700 + 3.039*$luas_4_bulan_lalu;
            } else if($prov==05) {
                $prediksi_produksi = 18510 + 4.062*$luas_4_bulan_lalu;
            } else if($prov==06) {
                $prediksi_produksi = -1381.1742 + 5.6747*$luas_4_bulan_lalu;
            } else if($prov==07) {
                $prediksi_produksi = 40120 + 3.046*$luas_4_bulan_lalu;
            } else if($prov==08) {
                $prediksi_produksi = 1436.7493 + 3.1679*$luas_4_bulan_lalu;
            } else if($prov==09) {
                $prediksi_produksi = 8034.3292 + 2.4666*$luas_4_bulan_lalu;
            } else if($prov==10) {
                $prediksi_produksi = 389.0367 + 3.8527*$luas_4_bulan_lalu;
            } else if($prov==11) {
                $prediksi_produksi = 31200 + 6.505*$luas_4_bulan_lalu;
            }
            $data_prediksi = array(                
                        'id_waktu'  => $date_now,
                        'prediksi_produksi'  => round($prediksi_produksi)          
            );
            print_r($data_prediksi); echo "<br>";
            $a++;
        }
    }

    function forecastHarga($tahun,$prov){
        for ($i=1; $i <= 12 ; $i++) { 
            $a=0;

            $tanggal = $tahun.'-'.$i.'-01';
            $date_now = date("Y-m-d",strtotime($tanggal));
            $date_1_month_ago = date("Y-m-d",strtotime("-1 months",strtotime($date_now)));

            //Mengecek apakah menggunakan data aktual atau prediksi
            $cek_data_aktual_bulan_lalu = $this->user_model->cek_aktual($date_1_month_ago,$prov);
            $cek_data_prediksi_bulan_lalu = $this->user_model->cek_prediksi($date_1_month_ago,$prov);   
            $cek_data_aktual = $this->user_model->cek_aktual($date_now,$prov);
            $cek_data_prediksi = $this->user_model->cek_prediksi($date_now,$prov);    
            /*print_r($cek_data_aktual_bulan_lalu);
            echo "<br>";
            print_r($cek_data_prediksi_bulan_lalu);
            echo "<br>";
            print_r($cek_data_aktual);
            echo "<br>";
            print_r($cek_data_prediksi);
            echo "<br>";*/
            if ($cek_data_aktual_bulan_lalu && $cek_data_prediksi_bulan_lalu) {
                $data['data_bulan_lalu'] = $this->user_model->get_aktual_pilih($date_1_month_ago,$prov);
                $harga_bulan_lalu=$data['data_bulan_lalu'][0]['aktual_harga'];
            }
            else if($cek_data_prediksi_bulan_lalu) {
                $data['data_bulan_lalu'] = $this->user_model->get_prediksi_pilih($date_1_month_ago,$prov);
                $harga_bulan_lalu=$data['data_bulan_lalu'][0]['prediksi_harga'];
            }
            else if($cek_data_aktual_bulan_lalu) {
                $data['data_bulan_lalu'] = $this->user_model->get_aktual_pilih($date_1_month_ago,$prov);
                $harga_bulan_lalu=$data['data_bulan_lalu'][0]['aktual_harga'];
            }

            if ($cek_data_aktual && $cek_data_prediksi) {
                $data['data_bulan_ini'] = $this->user_model->get_aktual_pilih($date_now,$prov);
                $produksi_bulan_ini=$data['data_bulan_ini'][0]['aktual_produksi'];
            }
            else if($cek_data_prediksi) {
                $data['data_bulan_ini'] = $this->user_model->get_prediksi_pilih($date_now,$prov);
                $produksi_bulan_ini=$data['data_bulan_ini'][0]['prediksi_produksi'];
            }
            else if($cek_data_aktual) {
                $data['data_bulan_ini'] = $this->user_model->get_aktual_pilih($date_now,$prov);
                $produksi_bulan_ini=$data['data_bulan_ini'][0]['aktual_produksi'];
            }

            //Memilih fungsi berdasarkan provinsi
            if($prov==01) {
                $prediksi_harga = 177.1 + 0.9966*$harga_bulan_lalu - 0.000128*$produksi_bulan_ini;
            } else if($prov==02) {
                $prediksi_harga = 186.3 + 0.9951*$harga_bulan_lalu- 0.0001349*$produksi_bulan_ini;
            } else if($prov==03) {
                $prediksi_harga = 94.98 + 1.003*$harga_bulan_lalu-0.00007929*$produksi_bulan_ini;
            } else if($prov==04) {
                $prediksi_harga = 103.5 + 0.9968*$harga_bulan_lalu- 0.0001121*$produksi_bulan_ini;
            } else if($prov==05) {
                $prediksi_harga = 146.8 + 0.9939*$harga_bulan_lalu - 0.0002551*$produksi_bulan_ini;
            } else if($prov==06) {
                $prediksi_harga = 103.2 + 0.994*$harga_bulan_lalu -0.00008089*$produksi_bulan_ini;
            } else if($prov==07) {
                $prediksi_harga = 173.2 + 0.9874*$harga_bulan_lalu - 0.0002333*$produksi_bulan_ini;
            } else if($prov==08) {
                $prediksi_harga = -192 + 1.035*$harga_bulan_lalu - 0.002558*$produksi_bulan_ini;
            } else if($prov==09) {
                $prediksi_harga = 164.4 + 0.9869*$harga_bulan_lalu - 0.000325*$produksi_bulan_ini;
            } else if($prov==10) {
                $prediksi_harga = -25.698886 + 1.013305*$harga_bulan_lalu- 0.002035*$produksi_bulan_ini;
            } else if($prov==11) {
                $prediksi_harga = 200.5 + 0.9794*$harga_bulan_lalu - 0.0004304*$produksi_bulan_ini;
            }

            $data_prediksi = array(                
                        'id_waktu'  => $date_now,
                        'prediksi_harga'  => round($prediksi_harga)          
            );
            print_r($data_prediksi); echo "<br>";
            $a++;
        }
    }


    function clean($string) {
        $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^0-9]/', '', $string); // Removes special chars.
    }


    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }
}