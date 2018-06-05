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
                        $cek_data_aktual_4_bulan_lalu = $this->user_model->cek_waktu_aktual($bulan+8,$tahun-1,$prov);
                        if($cek_data_aktual_4_bulan_lalu) {
                        $data['luas_tanam_empat_bulan_sebelum'] = $this->user_model->get_luastanam_aktual($bulan+8,$tahun-1,$prov); } else {
                        $data['luas_tanam_empat_bulan_sebelum'] = $this->user_model->get_luastanam_prediksi($bulan+8,$tahun-1,$prov);   
                        }
                    } else {
                        $cek_data_aktual_4_bulan_lalu = $this->user_model->cek_waktu_aktual($bulan+8,$tahun-1,$prov);
                        if($cek_data_aktual_4_bulan_lalu) {
                        $data['luas_tanam_empat_bulan_sebelum'] = $this->user_model->get_luastanam_aktual($bulan-4,$tahun,$prov); } else {
                        $data['luas_tanam_empat_bulan_sebelum'] = $this->user_model->get_luastanam_prediksi($bulan-4,$tahun,$prov);   
                        }
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
            $luastanam = $this->input->post('luastanam');
            $curah_hujan = $this->input->post('curahhujan');
            $banjir = $this->input->post('banjir');
            $hama = $this->input->post('hama');
            if ($kategori=='Prediksi') {
                $data = array(                
                        'id_prediksi'  => $id,
                        'prediksi_harga'  => $harga,
                        'prediksi_produksi'  => $produksi,
                        'prediksi_luastanam' => $luastanam,
                        'prediksi_curahhujan' => $curah_hujan,
                        'prediksi_banjir' => $banjir,
                        'prediksi_hama' => $hama
                );
                $this->user_model->updateData('id_prediksi', $id, 'beras_prediksi', $data);
            }
            else {
                $data = array(                
                        'id_aktual'  => $id,
                        'aktual_harga'  => $harga,
                        'aktual_produksi'  => $produksi,
                        'aktual_luastanam' => $luastanam,
                        'aktual_curahhujan' => $curah_hujan,
                        'aktual_banjir' => $banjir,
                        'aktual_hama' => $hama
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
                //$tahun=2018;
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
                    $data_aktual_prediksi = $this->user_model->get_data_aktual_prediksi($prov,2008,2016);
                    $data_aktual_prediksi2 = $this->user_model->get_data_aktual_prediksi($prov,2008,2016);
                    $data_aktual_prediksi3 = $this->user_model->get_data_aktual_prediksi($prov,2008,2017);
                    $data_aktual_prediksi4 = $this->user_model->get_data_aktual_prediksi($prov,2010,2017);
                    $data_luas = array_fill(0, count($data_aktual_prediksi), 0);
                    $data_curah = array_fill(0, count($data_aktual_prediksi2), 0); 
                    $data_banjir = array_fill(0, count($data_aktual_prediksi3), 0);
                    $data_hama = array_fill(0, count($data_aktual_prediksi4), 0);
                
                    //membuat data
                    for ($i=0; $i < count($data_aktual_prediksi); $i++) { 
                        $data_luas[$i] = $data_aktual_prediksi[$i]['aktual_luastanam'];
                    }
                    for ($i=0; $i < count($data_aktual_prediksi2); $i++) { 
                        $data_curah[$i] = $data_aktual_prediksi2[$i]['aktual_curahhujan'];
                    }
                    for ($i=0; $i < count($data_aktual_prediksi3); $i++) { 
                        $data_banjir[$i] = $data_aktual_prediksi3[$i]['aktual_banjir'];
                    }
                    for ($i=0; $i < count($data_aktual_prediksi4); $i++) { 
                        $data_hama[$i] = $data_aktual_prediksi4[$i]['aktual_hama'];
                    }


                    if($prov==01) {
                        $alpha_luas = 0; $beta_luas = 0; $gamma_luas = 0.6300506;
                        $alpha_curah = 0.128221464; $beta_curah = 0; $gamma_curah = 0.06417193;
                        $alpha_bencana = 0.996052658; $beta_bencana = 0.007958421; $gamma_bencana = 0.109041698;
                        $alpha_hama = 0.128954573; $beta_hama = 0.045852844; $gamma_hama = 0.997973471;
                    } else if($prov==02) {
                        $alpha_luas = 0.003415; $beta_luas = 0.868953; $gamma_luas = 0.4306614;
                        $alpha_curah = 0.02846213; $beta_curah = 0.068183987; $gamma_curah = 0.434170925;
                        $alpha_bencana = 0.988178626; $beta_bencana = 0.022746341; $gamma_bencana = 1;
                        $alpha_hama = 0.037146395; $beta_hama = 0; $gamma_hama = 0.550835495;
                    } else if($prov==03) {
                        $alpha_luas = 0.002963; $beta_luas = 0.967999; $gamma_luas = 0.3953132;
                        $alpha_curah = 0.110510574; $beta_curah = 0; $gamma_curah = 0.23285226;
                        $alpha_bencana = 0.994848977; $beta_bencana = 0.010567373; $gamma_bencana = 1;
                        $alpha_hama = 0.058810224; $beta_hama = 0.067325231; $gamma_hama = 0.757071779;
                    } else if($prov==04) {
                        $alpha_luas = 0.063655; $beta_luas = 0.040429; $gamma_luas = 0.2861521;
                        $alpha_curah = 0.114097845; $beta_curah = 0; $gamma_curah = 0.309729618;
                        $alpha_bencana = 0.324546; $beta_bencana = 0.203452; $gamma_bencana = 0.505443;
                        $alpha_hama = 0.015304377; $beta_hama = 0.087128352; $gamma_hama = 0.022498269;
                    } else if($prov==05) {
                        $alpha_luas = 0.005431; $beta_luas = 1; $gamma_luas = 0.8973619;
                        $alpha_curah = 0.069281072; $beta_curah = 0.009186113; $gamma_curah = 0.280540002;
                        $alpha_bencana = 0.996818764; $beta_bencana = 0.006427062; $gamma_bencana = 0.375932812;
                        $alpha_hama = 0; $beta_hama = 0; $gamma_hama = 0.480689312;
                    } else if($prov==06) {
                        $alpha_luas = 0.003807; $beta_luas = 1; $gamma_luas = 0.5793015 ;
                        $alpha_curah = 0.291867898; $beta_curah = 0.01108796; $gamma_curah = 0.533729623;
                        $alpha_bencana = 0.99453; $beta_bencana = 0.034522; $gamma_bencana = 0.289342;
                        $alpha_hama = 0.326191327; $beta_hama = 0.011697057; $gamma_hama = 0.947391434;
                    } else if($prov==07) {
                        $alpha_luas = 0.018610; $beta_luas = 0; $gamma_luas = 0.5253342;
                        $alpha_curah = 0.319469416; $beta_curah = 0; $gamma_curah = 0;
                        $alpha_bencana = 0.983084738; $beta_bencana = 0.035613757; $gamma_bencana = 1;
                        $alpha_hama = 0.677206114; $beta_hama = 0; $gamma_hama = 0.198920786;
                    } else if($prov==08) {
                        $alpha_luas = 0.007335; $beta_luas = 0.306771; $gamma_luas = 0.3039362;
                        $alpha_curah = 0.234524328; $beta_curah = 0.000704396; $gamma_curah = 0.472553846;
                        $alpha_bencana = 0.995906264; $beta_bencana = 0.008271078; $gamma_bencana = 1;
                        $alpha_hama = 0.090185185; $beta_hama = 0.019732873; $gamma_hama = 0.339188915;
                    } else if($prov==09) {
                        $alpha_luas = 0.085126; $beta_luas = 0.052159; $gamma_luas = 0.0236350;
                        $alpha_curah = 0.370860926; $beta_curah = 0.009231879; $gamma_curah = 0.669860678;
                        $alpha_bencana = 0.99222733; $beta_bencana = 0.015321854; $gamma_bencana = 1;
                        $alpha_hama = 0; $beta_hama = 0.015320044; $gamma_hama = 0.332520883;
                    } else if($prov==10) {
                        $alpha_luas = 0.003063; $beta_luas = 1; $gamma_luas = 0.458545;
                        $alpha_curah = 0.101345869; $beta_curah = 0.009050179; $gamma_curah = 0.258687876;
                        $alpha_bencana = 0.990195326; $beta_bencana = 0.02052048; $gamma_bencana = 1;
                        $alpha_hama = 0.064935868; $beta_hama = 0.004366315; $gamma_hama = 0.411881538;
                    } else if($prov==11) {
                        $alpha_luas = 0.001708; $beta_luas = 0.802628; $gamma_luas = 0.3922652;
                        $alpha_curah = 0.202675221; $beta_curah = 0.017820966; $gamma_curah = 0.507814697;
                        $alpha_bencana = 0.994750163; $beta_bencana = 0.010733683; $gamma_bencana = 0.298129394;
                        $alpha_hama = 0.45463375; $beta_hama = 0; $gamma_hama = 0.551864136;
                    }

                    $this->forecastLuasTanam($data_luas,$tahun,$season_length = 12, $alpha_luas, $beta_luas, $gamma_luas,$loc=$prov);
                    $this->forecastProduksi($tahun,$prov);
                    $this->forecastHarga($tahun,$prov);
                    $this->forecastCurahHujan($data_curah,$tahun,$season_length = 12,$alpha_curah,$beta_curah,$gamma_curah,$loc=$prov);
                    $this->forecastHama($data_hama,$tahun,$season_length = 12,$alpha_hama,$beta_hama,$gamma_hama,$loc=$prov);
                    $this->forecastBencana($data_banjir,$tahun,$season_length = 12,$alpha_bencana,$beta_bencana,$gamma_bencana,$loc=$prov);
                
                }  
            }
            $this->load->view('user/forecast_view',$data);
        }
        
        
    }


    function forecastLuasTanam($data,$tahun,$season_length, $alpha, $beta, $gamma, $loc) {
    
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
    $level = $initial_level;
    $trend = $initial_trend;
    $m=1;
        for($i=0;$i<count($data)+$length_forecast;$i++){
            if($i<$season_length) {

            } else if ($i>=count($data)){
                $alpha_level = $level;
                $beta_trend = $trend;
                $season[$i] = $season[$i%$season_length+(count($data)-$season_length)]; 
                $holt_winters[$i] = $alpha_level + ($beta_trend*$m)+$season[$i];
                //echo ($i+1)." : ".$level."===".$trend*$m."===".$season[$i]."===".$holt_winters[$i]."<br>";
                $m++;
            } else {
                $temp_level = $level;
                $temp_trend = $trend;
                $level = $alpha * ($data[$i] - $season[$i-$season_length]) + (1.0 - $alpha) * ($temp_level + $temp_trend);
                $trend = $beta * ($level - $temp_level) + ( 1.0 - $beta ) * $temp_trend;
                $season[$i] = $gamma * ($data[$i] - $level) + (1.0 - $gamma) * $season[$i-$season_length];
                $holt_winters[$i] = $temp_level + $temp_trend + $season[$i-$season_length];
                //echo ($i+1)." : ".$level."===".$trend."===".$season[$i]."===".$holt_winters[$i]."<br>";
            }
        }
    //print_r($holt_winters);\
        $a=1;
        $k = count($holt_winters)-12;
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
                            'prediksi_luastanam'  => round($holt_winters[$k])          
                );
                //print_r($data_prediksi); echo "<br>";
                $this->user_model->insertData('beras_prediksi',$data_prediksi);
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
            $where = array(
                    'id_waktu' => $date_now,
                    'id_provinsi' => $prov
                );
            //print_r($data_prediksi); echo "<br>";
            $this->user_model->updateData2($where, 'beras_prediksi', $data_prediksi);
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
            $where = array(
                    'id_waktu' => $date_now,
                    'id_provinsi' => $prov
                );
            //print_r($data_prediksi); echo "<br>";
            $this->user_model->updateData2($where, 'beras_prediksi', $data_prediksi);
            $a++;
        }
    }

    function forecastCurahHujan($data,$tahun,$season_length, $alpha, $beta, $gamma, $loc) {
    
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
    $level = $initial_level;
    $trend = $initial_trend;
    $m=1;
        for($i=0;$i<count($data)+$length_forecast;$i++){
            if($i<$season_length) {

            } else if ($i>=count($data)){
                $alpha_level = $level;
                $beta_trend = $trend;
                $season[$i] = $season[$i%$season_length+(count($data)-$season_length)]; 
                $holt_winters[$i] = $alpha_level + ($beta_trend*$m)+$season[$i];
                //echo ($i+1)." : ".$level."===".$trend*$m."===".$season[$i]."===".$holt_winters[$i]."<br>";
                $m++;
            } else {
                $temp_level = $level;
                $temp_trend = $trend;
                $level = $alpha * ($data[$i] - $season[$i-$season_length]) + (1.0 - $alpha) * ($temp_level + $temp_trend);
                $trend = $beta * ($level - $temp_level) + ( 1.0 - $beta ) * $temp_trend;
                $season[$i] = $gamma * ($data[$i] - $level) + (1.0 - $gamma) * $season[$i-$season_length];
                $holt_winters[$i] = $temp_level + $temp_trend + $season[$i-$season_length];
                //echo ($i+1)." : ".$level."===".$trend."===".$season[$i]."===".$holt_winters[$i]."<br>";
            }
        }
    //print_r($holt_winters);\
        $a=1;
        $k = count($holt_winters)-12;
        for ($i=1; $i <= 12; $i++) { 
                $tanggal = $tahun.'-'.$a.'-01';
                $date_now = date("Y-m-d",strtotime($tanggal));

                $data_prediksi = array(                
                            'id_waktu'  => $date_now,
                            'id_provinsi' => $loc,
                            'prediksi_curahhujan'  => round($holt_winters[$k],2)          
                );

                $where = array(
                    'id_waktu' => $date_now,
                    'id_provinsi' => $loc
                );
                //print_r($data_prediksi); echo "<br>";
                $this->user_model->updateData2($where, 'beras_prediksi', $data_prediksi);
                $a++;$k++;

        }
    }

    function forecastHama($data,$tahun,$season_length, $alpha, $beta, $gamma, $loc) {
    
    $tahun_akhir_forecast=2017;
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
    $level = $initial_level;
    $trend = $initial_trend;
    $m=1;
        for($i=0;$i<count($data)+$length_forecast;$i++){
            if($i<$season_length) {

            } else if ($i>=count($data)){
                $alpha_level = $level;
                $beta_trend = $trend;
                $season[$i] = $season[$i%$season_length+(count($data)-$season_length)]; 
                $holt_winters[$i] = $alpha_level + ($beta_trend*$m)+$season[$i];
                //echo ($i+1)." : ".$level."===".$trend*$m."===".$season[$i]."===".$holt_winters[$i]."<br>";
                $m++;
            } else {
                $temp_level = $level;
                $temp_trend = $trend;
                $level = $alpha * ($data[$i] - $season[$i-$season_length]) + (1.0 - $alpha) * ($temp_level + $temp_trend);
                $trend = $beta * ($level - $temp_level) + ( 1.0 - $beta ) * $temp_trend;
                $season[$i] = $gamma * ($data[$i] - $level) + (1.0 - $gamma) * $season[$i-$season_length];
                $holt_winters[$i] = $temp_level + $temp_trend + $season[$i-$season_length];
                //echo ($i+1)." : ".$level."===".$trend."===".$season[$i]."===".$holt_winters[$i]."<br>";
            }
        }
    //print_r($holt_winters);\
        $a=1;
        $k = count($holt_winters)-12;
        for ($i=1; $i <= 12; $i++) { 
                $tanggal = $tahun.'-'.$a.'-01';
                $date_now = date("Y-m-d",strtotime($tanggal));

                $data_prediksi = array(                
                            'id_waktu'  => $date_now,
                            'id_provinsi' => $loc,
                            'prediksi_hama'  => round($holt_winters[$k],2)          
                );

                $where = array(
                    'id_waktu' => $date_now,
                    'id_provinsi' => $loc
                );
                //print_r($data_prediksi); echo "<br>";
                $this->user_model->updateData2($where, 'beras_prediksi', $data_prediksi);
                $a++;$k++;

        }
    }



    function forecastBencana($data,$tahun,$season_length, $alpha, $beta, $gamma, $loc) {
    
    $tahun_akhir_forecast=2017;
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
                $beta_trend = $trend;
                $season[$i] = $season[$i%$season_length+(count($data)-$season_length)]; 
                $holt_winters[$i] = $alpha_level + ($beta_trend*$m)+$season[$i];

                //Memilih fungsi berdasarkan provinsi
                if($loc==01) {
                    $regression[$i] = 1 / (1 + exp(-(-20.3246747736743+39.9353780368147*$alpha_level-13.3069086442463*$beta_trend+39.7370911810733*$season[$i])));
                } else if($loc==02) {
                    $regression[$i] = 1 / (1 + exp(-(-19.8232174050311+39.334946094649*$alpha_level+39.334946094649*$season[$i])));
                } else if($loc==03) {
                    $regression[$i] = 1 / (1 + exp(-(-19.4956729249286+38.8755651230481*$alpha_level+38.8755651230481*$season[$i])));
                } else if($loc==04) {
                    $regression[$i] = 1 / (1 + exp(-(-156.116598764488+279.658944758549*$alpha_level+1482.7160266092*$beta_trend+406.924045375036*$season[$i])));
                } else if($loc==05) {
                    $regression[$i] = 1 / (1 + exp(-(-19.3699548902529+38.7393025582527*$alpha_level-8.42557117598551*$beta_trend+38.5999189841977*$season[$i])));
                } else if($loc==06) {
                    $regression[$i] = 1 / (1 + exp(-(-18.8818137490035+38.1126475503864*$alpha_level+8.21479904482829*$beta_trend+41.082450817242*$season[$i])));
                } else if($loc==07) {
                    $regression[$i] = 1 / (1 + exp(-(-19.3000428656514+38.6420552850159*$alpha_level+38.6420552850159*$season[$i])));
                } else if($loc==08) {
                    $regression[$i] = 1 / (1 + exp(-(-19.4866678867802+39.2403156594594*$alpha_level+4.74465668766142E-12*$beta_trend+39.2403156594594*$season[$i])));
                } else if($loc==09) {
                    $regression[$i] = 1 / (1 + exp(-(-19.5660685185106+39.5524283336423*$alpha_level+39.5524283336422*$season[$i])));
                } else if($loc==10) {
                    $regression[$i] = 1 / (1 + exp(-(-19.5955148721781+39.6774973461151*$alpha_level+39.6774973461151*$season[$i])));
                } else if($loc==11) {
                    $regression[$i] = 1 / (1 + exp(-(-19.426137893808+38.8620378834958*$alpha_level-5.62323895791762*$beta_trend+38.7112850144303*$season[$i])));
                }
                //echo ($i+1)." : ".$level."===".$trend*$m."===".$season[$i]."===".round($regression[$i])."<br>";
                $m++;
            } else {
                $temp_level = $level;
                $temp_trend = $trend;
                $level = $alpha * ($data[$i] - $season[$i-$season_length]) + (1.0 - $alpha) * ($temp_level + $temp_trend);
                $trend = $beta * ($level - $temp_level) + ( 1.0 - $beta ) * $temp_trend;
                $season[$i] = $gamma * ($data[$i] - $level) + (1.0 - $gamma) * $season[$i-$season_length];
                $holt_winters[$i] = $temp_level + $temp_trend + $season[$i-$season_length];
                //Memilih fungsi berdasarkan provinsi
                if($loc==01) {
                    $regression[$i] = 1 / (1 + exp(-(-20.3246747736743+39.9353780368147*$level-13.3069086442463*$trend+39.7370911810733*$season[$i])));
                } else if($loc==02) {
                    $regression[$i] = 1 / (1 + exp(-(-19.8232174050311+39.334946094649*$level+39.334946094649*$season[$i])));
                } else if($loc==03) {
                    $regression[$i] = 1 / (1 + exp(-(-19.4956729249286+38.8755651230481*$level+38.8755651230481*$season[$i])));
                } else if($loc==04) {
                    $regression[$i] = 1 / (1 + exp(-(-156.116598764488+279.658944758549*$level+1482.7160266092*$trend+406.924045375036*$season[$i])));
                } else if($loc==05) {
                    $regression[$i] = 1 / (1 + exp(-(-19.3699548902529+38.7393025582527*$level-8.42557117598551*$trend+38.5999189841977*$season[$i])));
                } else if($loc==06) {
                    $regression[$i] = 1 / (1 + exp(-(-18.8818137490035+38.1126475503864*$level+8.21479904482829*$trend+41.082450817242*$season[$i])));
                } else if($loc==07) {
                    $regression[$i] = 1 / (1 + exp(-(-19.3000428656514+38.6420552850159*$level+38.6420552850159*$season[$i])));
                } else if($loc==08) {
                    $regression[$i] = 1 / (1 + exp(-(-19.4866678867802+39.2403156594594*$level+4.74465668766142E-12*$trend+39.2403156594594*$season[$i])));
                } else if($loc==09) {
                    $regression[$i] = 1 / (1 + exp(-(-19.5660685185106+39.5524283336423*$level+39.5524283336422*$season[$i])));
                } else if($loc==10) {
                    $regression[$i] = 1 / (1 + exp(-(-19.5955148721781+39.6774973461151*$level+39.6774973461151*$season[$i])));
                } else if($loc==11) {
                    $regression[$i] = 1 / (1 + exp(-(-19.426137893808+38.8620378834958*$level-5.62323895791762*$trend+38.7112850144303*$season[$i])));
                }
                //echo ($i+1)." : ".$level."===".$trend."===".$season[$i]."===".round($regression[$i])."<br>";
            }
        }
    //print_r($holt_winters);\
        $a=1;
        $k = count($regression)-12;
        for ($i=1; $i <= 12; $i++) { 
                $tanggal = $tahun.'-'.$a.'-01';
                $date_now = date("Y-m-d",strtotime($tanggal));

                $data_prediksi = array(                
                            'id_waktu'  => $date_now,
                            'id_provinsi' => $loc,
                            'prediksi_banjir'  => round($regression[$k])          
                );

                $where = array(
                    'id_waktu' => $date_now,
                    'id_provinsi' => $loc
                );
                //print_r($data_prediksi); echo "<br>";
                $this->user_model->updateData2($where, 'beras_prediksi', $data_prediksi);
                $a++;$k++;

        }
    }
    

    /*function holtWinterBuWiwik($tahun,$season_length, $alpha, $beta, $gamma,$loc) {
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
    }*/

    function clean($string) {
        $string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^0-9]/', '', $string); // Removes special chars.
    }


    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }
}