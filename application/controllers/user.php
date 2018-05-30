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
        $this->form_validation->set_rules('prov','Provinsi', 'required');
        $this->form_validation->set_rules('tahun','Tahun', 'required');
        $this->form_validation->set_rules('bulan','Bulan', 'required');

        if ($this->form_validation->run() == FALSE){
            $data['provinsi']=$this->user_model->get_provinsi();
            $data['bulan']=$this->user_model->get_bulan();
            $this->load->view('user/inputdata_view',$data);
        }
        else {  
            $provinsi = $this->input->post('prov'); 
            $tahun = $this->input->post('tahun');
            $bulan = $this->input->post('bulan');       
            $harga = $this->clean($this->input->post('harga'));       
            $produksi = $this->input->post('produksi');
            $luastanam = $this->input->post('luastanam');
            $curah_hujan = $this->input->post('curahhujan');
            $banjir = $this->input->post('banjir');
            $hama = $this->input->post('hama');

            $bln = $this->user_model->ubah_bulan($bulan);
            $waktu = $tahun.'-'.$bln.'-'.'01';
            $date = date("Y-m-d",strtotime($waktu));
            $bulanangka = date("m",strtotime($waktu));
            $tahun = date("Y",strtotime($waktu));
            $bulan = $this->user_model->trans_bulan($bulanangka);
            $prov = $this->user_model->ubah_provinsi($provinsi);

            //cek apakah sudah ada dimensi waktu
            $cek_dimensi_waktu = $this->user_model->cek($bulanangka,$tahun);

            //cek sudah ada input atau belum
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

            //insert data
            if ($cek_waktu_aktual) {
                $this->session->set_flashdata('msg', '<div class="alert animated fadeInRight alert-danger">Anda sudah memasukkan data pada bulan ini. Silahkan masuk ke menu <b>Lihat Data</b> untuk melakukan perubahan.</div>');
                    redirect('User/inputData');
            } else {
                $data_aktual = array(                
                    'id_waktu'  => $date,
                    'id_provinsi'  => $prov,
                    'aktual_harga'  => $harga,
                    'aktual_produksi'  => $produksi,
                    'aktual_luastanam' => $luastanam,
                    'aktual_curahhujan' => $curah_hujan,
                    'aktual_banjir' => $banjir,
                    'aktual_hama' => $hama
                );
                $this->user_model->insertData('beras_aktual',$data_aktual);
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
            //print_r($data['provinsi'][0]['provinsi']);exit();
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

    public function editUser($id_user){
        $lembaga = $this->input->post('lembaga');     
        $username = $this->input->post('username'); 
        $password = $this->input->post('password');
        $role = $this->input->post('role');       
        $provinsi = $this->input->post('provinsi');    

        if ($role=='Admin Pusat') {
            $data_user = array(                
                'lembaga'  => $lembaga,
                'username'  => $username,
                'password'  => $password,
                'role'  => $role               
            );
            $this->user_model->updateData('id_user',$id_user,'user',$data_user);
        } else {
            $data_user = array(                
                    'lembaga'  => $lembaga,
                    'username'  => $username,
                    'password'  => $password,
                    'role'  => $role,
                    'provinsi'  => $provinsi,                
            );
            $this->user_model->updateData('id_user',$id_user,'user',$data_user);
        }
        $message = "User berhasil diubah.";
        echo "<script type='text/javascript'>alert('$message');
        window.location.href='".base_url("user/manajemenUser")."';</script>";
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
                //$tahun=2016;
                $prov = $this->user_model->ubah_provinsi($provinsi);

                $tanggal_pilih = $tahun.'-'.'01'.'-01'; 
                $tanggal_pilih_tahun_sebelumnya = ($tahun-1).'-'.'01'.'-01'; 
                $cek_data_prediksi = $this->user_model->cek_prediksi($tanggal_pilih,$prov);
                $cek_data_prediksi_tahun_sebelumnya = $this->user_model->cek_prediksi($tanggal_pilih_tahun_sebelumnya,$prov);

                

                if ($cek_data_prediksi) {
                    $this->session->set_flashdata('msg2', '<div class="alert animated fadeInRight alert-danger">Anda sudah melakukan forecast pada tahun ini.</div>');
                } else if (!$cek_data_prediksi && !$cek_data_prediksi_tahun_sebelumnya) {
                    $this->session->set_flashdata('msg2', '<div class="alert animated fadeInRight alert-danger">Pastikan tahun sebelumnya sudah terforecast.</div>');
                } else {

                    if($prov==01) {
                        $alpha_luas = 0.078836919; $beta_luas = 0; $gamma_luas = 0.586779366;
                        $alpha_curah = 0; $beta_curah = 0; $gamma_curah = 0;
                        $alpha_bencana = 0; $beta_bencana = 0; $gamma_bencana = 0;
                        $alpha_hama = 0; $beta_hama = 0; $gamma_hama = 0;
                    } else if($prov==02) {
                        $alpha_luas = 0; $beta_luas = 0; $gamma_luas = 0;
                        $alpha_curah = 0; $beta_curah = 0; $gamma_curah = 0;
                        $alpha_bencana = 0; $beta_bencana = 0; $gamma_bencana = 0;
                        $alpha_hama = 0; $beta_hama = 0; $gamma_hama = 0;
                    } else if($prov==03) {
                        $alpha_luas = 0; $beta_luas = 0; $gamma_luas = 0;
                        $alpha_curah = 0; $beta_curah = 0; $gamma_curah = 0;
                        $alpha_bencana = 0; $beta_bencana = 0; $gamma_bencana = 0;
                        $alpha_hama = 0; $beta_hama = 0; $gamma_hama = 0;
                    } else if($prov==04) {
                        $alpha_luas = 0; $beta_luas = 0; $gamma_luas = 0;
                        $alpha_curah = 0; $beta_curah = 0; $gamma_curah = 0;
                        $alpha_bencana = 0; $beta_bencana = 0; $gamma_bencana = 0;
                        $alpha_hama = 0; $beta_hama = 0; $gamma_hama = 0;
                    } else if($prov==05) {
                        $alpha_luas = 0; $beta_luas = 0; $gamma_luas = 0;
                        $alpha_curah = 0; $beta_curah = 0; $gamma_curah = 0;
                        $alpha_bencana = 0; $beta_bencana = 0; $gamma_bencana = 0;
                        $alpha_hama = 0; $beta_hama = 0; $gamma_hama = 0;
                    } else if($prov==06) {
                        $alpha_luas = 0; $beta_luas = 0; $gamma_luas = 0;
                        $alpha_curah = 0; $beta_curah = 0; $gamma_curah = 0;
                        $alpha_bencana = 0; $beta_bencana = 0; $gamma_bencana = 0;
                        $alpha_hama = 0; $beta_hama = 0; $gamma_hama = 0;
                    } else if($prov==07) {
                        $alpha_luas = 0; $beta_luas = 0; $gamma_luas = 0;
                        $alpha_curah = 0; $beta_curah = 0; $gamma_curah = 0;
                        $alpha_bencana = 0; $beta_bencana = 0; $gamma_bencana = 0;
                        $alpha_hama = 0; $beta_hama = 0; $gamma_hama = 0;
                    } else if($prov==08) {
                        $alpha_luas = 0; $beta_luas = 0; $gamma_luas = 0;
                        $alpha_curah = 0; $beta_curah = 0; $gamma_curah = 0;
                        $alpha_bencana = 0; $beta_bencana = 0; $gamma_bencana = 0;
                        $alpha_hama = 0; $beta_hama = 0; $gamma_hama = 0;
                    } else if($prov==09) {
                        $alpha_luas = 0; $beta_luas = 0; $gamma_luas = 0;
                        $alpha_curah = 0; $beta_curah = 0; $gamma_curah = 0;
                        $alpha_bencana = 0; $beta_bencana = 0; $gamma_bencana = 0;
                        $alpha_hama = 0; $beta_hama = 0; $gamma_hama = 0;
                    } else if($prov==10) {
                        $alpha_luas = 0; $beta_luas = 0; $gamma_luas = 0;
                        $alpha_curah = 0; $beta_curah = 0; $gamma_curah = 0;
                        $alpha_bencana = 0; $beta_bencana = 0; $gamma_bencana = 0;
                        $alpha_hama = 0; $beta_hama = 0; $gamma_hama = 0;
                    } else if($prov==11) {
                        $alpha_luas = 0; $beta_luas = 0; $gamma_luas = 0;
                        $alpha_curah = 0; $beta_curah = 0; $gamma_curah = 0;
                        $alpha_bencana = 0; $beta_bencana = 0; $gamma_bencana = 0;
                        $alpha_hama = 0; $beta_hama = 0; $gamma_hama = 0;
                    }
                
                //$this->holtWinterBuWiwik($tahun,$season_length = 6, $alpha_luas, $beta_luas, $gamma_luas,$loc=$prov);


                $this->forecastLuasTanam($tahun,$season_length = 12, $alpha_luas, $beta_luas, $gamma_luas,$loc=$prov);
                $this->forecastProduksi($tahun,$prov);
                $this->forecastHarga($tahun,$prov);
                /*$this->forecastCurahHujan($season_length = 12, $alpha_curah, $beta_curah, $gamma_curah,$loc=$prov);
                $this->forecastBencana($season_length = 12, $alpha_bencana, $beta_bencana, $gamma_bencana,$loc=$prov);
                $this->forecastHama($season_length = 12, $alpha_hama, $beta_hama, $gamma_hama,$loc=$prov);*/
                
                }
                
                //$cek_data_aktual = $this->user_model->cek_waktu_aktual($bulan,$tahun,$prov);
                //$cek_data_prediksi = $this->user_model->cek_waktu_prediksi($bulan,$tahun,$prov);
               
            }
            $this->load->view('user/forecast_view',$data);
        }
        
        
    }


    function forecastLuasTanam($tahun,$season_length, $alpha, $beta, $gamma,$loc) {
    $data = array (194599,76497,121151,260823,222666,125537,61885,78181,50220,123550,282295,340623,148100,91611,166660,259635,185047,117545,96525,89635,42683,92042,257353,329541,206084,113830,141104,229219,235581,134658,136069,94785,105647,178140,258919,279910,176936,130045,134839,189167,212180,161052,115853,60388,65371,56844,222749,382951,222048,105671,102665,226562,254944,135609,110371,68651,60463,81077,240507,363323,216940,112426,112955,225483,247383,143133,132867,102240,65581,81148,183321,364507,245689,120827,124884,196627,218754,195425,129256,105910,73512,66793,176480,326378,278742,119919,100666,194599,247756,152509,94717,84967,69417,41005,87356,348383,121226,101740,191464,245142,152457,93894,83590,68699,34712,79631,348189,129427);
    
    $tahun_akhir_forecast=2016;
    $length_forecast = ($tahun-$tahun_akhir_forecast)*12;
    // Menghitung initial level
    $initial_level = 0;
        for($i = 0; $i < $season_length; $i++) {
            $initial_level += $data[$i];
        }
    $initial_level /= $season_length;  

    //Menghitung initial trend
    $trend1 = 0;
        for($i = 0; $i < $season_length; $i++) {
            $trend1 += $data[$i];
        }
    $trend1 /= $season_length;
    
    $trend2 = 0;
        for($i = $season_length; $i < 2*$season_length; $i++) {
            $trend2 += $data[$i];
        }
    $trend2 /= $season_length;

    $initial_trend = ($trend2 - $trend1) / $season_length;
    
    // Menghitung initial season
    $season = array_fill(0, count($data), 0);
        for($i = 0; $i < $season_length; $i++) {
            $season[$i] = $data[$i] - $initial_level;
        }
    
    $holt_winters = array_fill(0, count($data)+$length_forecast, 0);
    $regression = array_fill(0, count($data)+$length_forecast, 0);
    $level = $initial_level;
    $trend = $initial_trend;
    $m=1;
        for($i=0;$i<count($data)+$length_forecast;$i++){
            if($i<$season_length) {

            } else if ($i>=count($data)){
                $alpha_level = $level;
                $beta_trend = $trend*$m;
                $season[$i] = $season[$i%$season_length+(count($data)-$season_length)]; 

                if($loc==01) {
                    $regression[$i] = -78990.3064005526 + 1.5202869917021*$alpha_level + 0*$beta_trend + 1.01128062797808 * $season[$i];
                } else if($loc==02) {
                    $regression[$i] = -157663.557244406 + 2.37814702176707*$alpha_level + 42.7692417201128*$beta_trend + 1.02761909746456* $season[$i];
                } else if($loc==03) {
                    $regression[$i] = -24406.6292092276 + 1.25174874219277*$alpha_level + 4.41452932029465*$beta_trend + 1.02323446514704 * $season[$i];
                } else if($loc==04) {
                    $regression[$i] =  67949419.2020113 + 1.12783457524249*$alpha_level + 215959.462573575*$beta_trend + 1.02867318606618 * $season[$i];
                } else if($loc==05) {
                    $regression[$i] = -3.27418092638254E-11 + 1*$alpha_level + -3.10036691064369E-15*$beta_trend + 1 * $season[$i];
                } else if($loc==06) {
                    $regression[$i] = -478.17186156489 + 1.01184610400827*$alpha_level + -0.0804756783555209*$beta_trend + 1.00594416094877 * $season[$i];
                } else if($loc==07) {
                    $regression[$i] = 73198.6660710314 + -0.763106500885188*$alpha_level + 24.9495351940806*$beta_trend + 0.951496584215959 * $season[$i];
                } else if($loc==08) {
                    $regression[$i] = -2166.86018826386 + 0.732384663813179*$alpha_level + -29.4173666932935*$beta_trend + 1.13384611870156 * $season[$i];
                } else if($loc==09) {
                    $regression[$i] = -6.13908923696727E-11 + 1*$alpha_level + 8.38708377611989E-15*$beta_trend + 1 * $season[$i];
                } else if($loc==10) {
                    $regression[$i] = -11067.3232420229 + 9.05444880796968*$alpha_level + 260.948287783574*$beta_trend + 1.22858168206032 * $season[$i];
                } else if($loc==11) {
                    $regression[$i] = 10135.5522619964 + 0.0374029780864185*$alpha_level + 0.85900464816731*$beta_trend + 0.0996083133906275 * $season[$i];
                } 
                $m++;
            } else {
                $temp_level = $level;
                $temp_trend = $trend;
                $level = $alpha * ($data[$i] - $season[$i-$season_length]) + (1.0 - $alpha) * ($temp_level + $temp_trend);
                $trend = $beta * ($level - $temp_level) + ( 1.0 - $beta ) * $temp_trend;
                $season[$i] = $gamma * ($data[$i] - $level) + (1.0 - $gamma) * $season[$i-$season_length];
                $holt_winters[$i] = $temp_level + $temp_trend + $season[$i-$season_length];

                if($loc==01) {
                    $regression[$i] = -78990.3064005526 + 1.5202869917021*$level + 0*$trend + 1.01128062797808 * $season[$i];
                } else if($loc==02) {
                    $regression[$i] = -157663.557244406 + 2.37814702176707*$level + 42.7692417201128*$trend + 1.02761909746456* $season[$i];
                } else if($loc==03) {
                    $regression[$i] = -24406.6292092276 + 1.25174874219277*$level + 4.41452932029465*$trend + 1.02323446514704 * $season[$i];
                } else if($loc==04) {
                    $regression[$i] =  67949419.2020113 + 1.12783457524249*$level + 215959.462573575*$trend + 1.02867318606618 * $season[$i];
                } else if($loc==05) {
                    $regression[$i] = -3.27418092638254E-11 + 1*$level + -3.10036691064369E-15*$trend + 1 * $season[$i];
                } else if($loc==06) {
                    $regression[$i] = -478.17186156489 + 1.01184610400827*$level + -0.0804756783555209*$trend + 1.00594416094877 * $season[$i];
                } else if($loc==07) {
                    $regression[$i] = 73198.6660710314 + -0.763106500885188*$level + 24.9495351940806*$trend + 0.951496584215959 * $season[$i];
                } else if($loc==08) {
                    $regression[$i] = -2166.86018826386 + 0.732384663813179*$level + -29.4173666932935*$trend + 1.13384611870156 * $season[$i];
                } else if($loc==09) {
                    $regression[$i] = -6.13908923696727E-11 + 1*$level + 8.38708377611989E-15*$trend + 1 * $season[$i];
                } else if($loc==10) {
                    $regression[$i] = -11067.3232420229 + 9.05444880796968*$level + 260.948287783574*$trend + 1.22858168206032 * $season[$i];
                } else if($loc==11) {
                    $regression[$i] = 10135.5522619964 + 0.0374029780864185*$level + 0.85900464816731*$trend + 0.0996083133906275 * $season[$i];
                } 
            }
        }
    //print_r($regression);
        $a=1;
        $k = count($regression)-12;
        for ($i=1; $i <= 12; $i++) { 
                $tanggal = $tahun.'-'.$a.'-01';
                $date_now = date("Y-m-d",strtotime($tanggal));
                $bulan = $this->user_model->trans_bulan($a);

                //cek apakah sudah ada dimensi waktu
                $cek_dimensi_waktu = $this->user_model->cek($i,$tahun);
                if (!$cek_dimensi_waktu) {
                        $dimensi_waktu = array(                
                            'id_waktu'  => $tanggal,
                            'bulan'  => $bulan,
                            'tahun'  => $tahun,                
                        );
                        //print_r($dimensi_waktu);echo "<br>";
                        $this->user_model->insertData('waktu',$dimensi_waktu);
                    }

                $data_prediksi = array(                
                            'id_waktu'  => $date_now,
                            'id_provinsi' => $loc,
                            'prediksi_luastanam'  => round($regression[$k])          
                );
                //print_r($data_prediksi); echo "<br>";
                $this->user_model->insertData('beras_prediksi2',$data_prediksi);
                $a++;$k++;

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
            //print_r($data_prediksi); echo "<br>";
            $this->user_model->updateData('id_waktu', $date_now, 'beras_prediksi2', $data_prediksi);
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

            //print_r($data_prediksi); echo "<br>";
            $this->user_model->updateData('id_waktu', $date_now, 'beras_prediksi2', $data_prediksi);
            $a++;
        }
    }

    

    function holtWinterBuWiwik($tahun,$season_length, $alpha, $beta, $gamma,$loc) {
    $data = array (194599,76497,121151,260823,222666,125537,61885,78181,50220,123550,282295,340623,148100,91611,166660,259635,185047,117545,96525,89635,42683,92042,257353,329541,206084,113830,141104,229219,235581,134658,136069,94785,105647,178140,258919,279910,176936,130045,134839,189167,212180,161052,115853,60388,65371,56844,222749,382951,222048,105671,102665,226562,254944,135609,110371,68651,60463,81077,240507,363323,216940,112426,112955,225483,247383,143133,132867,102240,65581,81148,183321,364507,245689,120827,124884,196627,218754,195425,129256,105910,73512,66793,176480,326378,278742,119919,100666,194599,247756,152509,94717,84967,69417,41005,87356,348383,121226,101740,191464,245142,152457,93894,83590,68699,34712,79631,348189,129427);
    
    $tahun_akhir_forecast=2016;
    $length_forecast = ($tahun-$tahun_akhir_forecast)*12;
    // Menghitung initial level
    $initial_level = 0;
        for($i = 0; $i < $season_length; $i++) {
            $initial_level += $data[$i];
        }
    $initial_level /= $season_length;  

    //Menghitung initial trend
    $trend1 = 0;
        for($i = 0; $i < $season_length; $i++) {
            $trend1 += $data[$i];
        }
    $trend1 /= $season_length;
    
    $trend2 = 0;
        for($i = $season_length; $i < 2*$season_length; $i++) {
            $trend2 += $data[$i];
        }
    $trend2 /= $season_length;

    $initial_trend = ($trend2 - $trend1) / $season_length;
    
    // Menghitung initial season
    $season = array_fill(0, count($data), 0);
        for($i = 0; $i < $season_length; $i++) {
            $season[$i] = $data[$i] / $initial_level;
        }
    
    $holt_winters = array_fill(0, count($data)+$length_forecast, 0);
    $regression = array_fill(0, count($data)+$length_forecast, 0);
    $level = $initial_level;
    $trend = $initial_trend;

    //print_r($season);print_r($trend);exit();
    $m=1;
        for($i=0;$i<count($data)+$length_forecast;$i++){
            if($i<$season_length) {

            } else if ($i>=count($data)){
                $temp_level = $level;
                $temp_trend = $trend;
                $temp_data=$holt_winters[$i-12];
                $level = ($alpha * ($temp_data/$season[$i-$season_length])) + ((1.0 - $alpha) * ($temp_level + $temp_trend));
                $trend = $beta * ($level - $temp_level) + ((1.0 - $beta) * $temp_trend);
                $season[$i] = $gamma * ($temp_data/$level) + (1.0 - $gamma) * $season[$i-$season_length];
                $holt_winters[$i] = ($level + $trend) * $season[$i-$season_length];
                echo ($i+1)." : ".$level."===".$trend."===".$season[$i]."===".$holt_winters[$i]."<br>";
                $m++;
            } else {
                $temp_level = $level;
                $temp_trend = $trend;
                $level = ($alpha * ($data[$i]/$season[$i-$season_length])) + ((1.0 - $alpha) * ($temp_level + $temp_trend));
                $trend = $beta * ($level - $temp_level) + ((1.0 - $beta) * $temp_trend);
                $season[$i] = $gamma * ($data[$i]/$level) + (1.0 - $gamma) * $season[$i-$season_length];
                $holt_winters[$i] = ($level + $trend) * $season[$i-$season_length];
                echo ($i+1)." : ".$level."===".$trend."===".$season[$i]."===".$holt_winters[$i]."<br>";
            }
        }
    //print_r($regression);
        $a=1;
        $k = count($holt_winters)-12;
        for ($i=1; $i <= 12; $i++) { 
                $tanggal = $tahun.'-'.$a.'-01';
                $date_now = date("Y-m-d",strtotime($tanggal));

                $data_prediksi = array(                
                            'id_waktu'  => $date_now,
                            'prediksi_luastanam'  => round($holt_winters[$k])          
                );
                print_r($data_prediksi); echo "<br>";
                //$this->user_model->insertData('beras_prediksi2',$data_prediksi);
                $a++;$k++;

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