<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends CI_Model {
    function __construct() {
        // Call the Model constructor
        parent::__construct();

    }

    public function checkAccount($username,$password) {
        
        $sql   = "SELECT * from user a LEFT JOIN provinsi b on a.provinsi = b.id_provinsi where a.username ='" . $username . "' and a.password = '$password'";
        $query = $this->db->query($sql);
        
        
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function get_user() {
        $sql = "SELECT * from user a LEFT JOIN provinsi b on a.provinsi = b.id_provinsi";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_provinsi() {
        $sql = "select * from provinsi";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_tahun() {            
        $sql = "select distinct tahun from waktu order by tahun DESC";
        $query = $this->db->query($sql);            
        return $query->result_array();
    }

    public function get_bulan() {
        $sql = "select distinct bulan from waktu";
        $query = $this->db->query($sql);            
        return $query->result_array();
    } 

    //Aktual
    public function get_data_aktual() {
        $sql = "select * from beras_aktual a JOIN waktu b on a.id_waktu=b.id_waktu JOIN provinsi c on a.id_provinsi=c.id_provinsi";
        $query = $this->db->query($sql);
        return $query->result_array();
    } 

    public function get_data_aktual_pilih($bln,$thn,$prov) {
        $date = $thn.'-'.$bln.'-01';
        $tgl = date("Y-m-d",strtotime($date)); 
        //echo $tgl;
        $sql = "select *,a.aktual_harga as harga from beras_aktual a JOIN waktu b on a.id_waktu=b.id_waktu JOIN provinsi c on a.id_provinsi=c.id_provinsi where a.id_waktu='$tgl' AND a.id_provinsi='$prov'";                
        $query = $this->db->query($sql);           
        return $query->result_array();
    }

    public function get_data_aktual_setaun($tahun,$provinsi){
        $sql = "select * from beras_aktual where year(id_waktu) = $tahun and id_provinsi=$provinsi ORDER BY id_waktu ASC";
        $query = $this->db->query($sql);            
        return $query->result_array();
    }

    public function findAktual($id_aktual) {
        $sql    = "SELECT * from beras_aktual a JOIN waktu b on a.id_waktu=b.id_waktu JOIN provinsi c on a.id_provinsi=c.id_provinsi where id_aktual=$id_aktual";
        $query  = $this->db->query($sql);
        $result = $query->row();
        return $result;
    }

    public function rank_harga($bulan,$tahun) {
        $sql = "SELECT c.id_provinsi,c.provinsi,a.aktual_harga as harga,c.long,c.lat FROM beras_aktual a JOIN waktu b ON a.id_waktu=b.id_waktu JOIN provinsi c ON a.id_provinsi=c.id_provinsi WHERE YEAR(b.id_waktu)=$tahun AND MONTH(b.id_waktu)=$bulan ORDER BY a.aktual_harga DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function rank_produksi($bulan,$tahun) {
        $sql = "SELECT c.id_provinsi,c.provinsi,a.aktual_produksi as produksi,c.long,c.lat FROM beras_aktual a JOIN waktu b ON a.id_waktu=b.id_waktu JOIN provinsi c ON a.id_provinsi=c.id_provinsi WHERE YEAR(b.id_waktu)=$tahun AND MONTH(b.id_waktu)=$bulan ORDER BY a.aktual_produksi DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_stabilitas($bulan,$tahun,$provinsi) {       
        $date = $tahun.'-'.$bulan.'-01';           
        $datenow = date("Y-m-d",strtotime($date));
        $dateago = date("Y-m-d",strtotime("-11 months",strtotime($datenow)));
        
        $sql = "select b.id_waktu,b.bulan,a.aktual_harga from beras_aktual a, waktu b, provinsi c where a.id_provinsi=c.id_provinsi and a.id_waktu=b.id_waktu and (b.id_waktu BETWEEN '$dateago' and '$datenow') and c.id_provinsi='$provinsi' order by b.id_waktu DESC";      
        $query = $this->db->query($sql);        
        return $query->result_array();
    }


    //Prediksi
    public function get_data_prediksi() {
        $sql = "select * from beras_prediksi a JOIN waktu b on a.id_waktu=b.id_waktu JOIN provinsi c on a.id_provinsi=c.id_provinsi";
        $query = $this->db->query($sql);
        return $query->result_array();
    } 

    public function get_data_prediksi_pilih($bln,$thn,$prov) {
        $date = $thn.'-'.$bln.'-01';
        $tgl = date("Y-m-d",strtotime($date)); 
        //echo $tgl;
        $sql = "select *,a.prediksi_harga as harga from beras_prediksi a JOIN waktu b on a.id_waktu=b.id_waktu JOIN provinsi c on a.id_provinsi=c.id_provinsi where a.id_waktu='$tgl' AND a.id_provinsi='$prov'";                
        $query = $this->db->query($sql);           
        return $query->result_array();
    }

    public function get_data_prediksi_setaun($tahun,$provinsi){
        $sql = "select * from beras_prediksi where year(id_waktu) = $tahun and id_provinsi=$provinsi ORDER BY id_waktu ASC";
        $query = $this->db->query($sql);            
        return $query->result_array();
    }

    public function findPrediksi($id_prediksi) {
        $sql = "SELECT * from beras_prediksi a JOIN waktu b on a.id_waktu=b.id_waktu JOIN provinsi c on a.id_provinsi=c.id_provinsi where id_prediksi=$id_prediksi";
        $query  = $this->db->query($sql);
        $result = $query->row();
        return $result;
    }

    public function rank_harga_prediksi($bulan,$tahun) {
        $sql = "SELECT c.id_provinsi,c.provinsi,a.prediksi_harga as harga,c.long,c.lat FROM beras_prediksi a JOIN waktu b ON a.id_waktu=b.id_waktu JOIN provinsi c ON a.id_provinsi=c.id_provinsi WHERE YEAR(b.id_waktu)=$tahun AND MONTH(b.id_waktu)=$bulan ORDER BY a.prediksi_harga DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function rank_produksi_prediksi($bulan,$tahun) {
        $sql = "SELECT c.id_provinsi,c.provinsi,a.prediksi_produksi as produksi,c.long,c.lat FROM beras_prediksi a JOIN waktu b ON a.id_waktu=b.id_waktu JOIN provinsi c ON a.id_provinsi=c.id_provinsi WHERE YEAR(b.id_waktu)=$tahun AND MONTH(b.id_waktu)=$bulan ORDER BY a.prediksi_produksi DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_stabilitas_prediksi($bulan,$tahun,$provinsi) {       
        $date = $tahun.'-'.$bulan.'-01';           
        $datenow = date("Y-m-d",strtotime($date));
        $dateago = date("Y-m-d",strtotime("-11 months",strtotime($datenow)));

        $sql = "select b.id_waktu,b.bulan,a.prediksi_harga from beras_prediksi a, waktu b, provinsi c where a.id_provinsi=c.id_provinsi and a.id_waktu=b.id_waktu and (b.id_waktu BETWEEN '$dateago' and '$datenow') and c.id_provinsi='$provinsi' order by b.id_waktu DESC";      
        $query = $this->db->query($sql);        
        return $query->result_array();
    }

    public function get_luastanam_aktual($bln,$thn,$prov) {
        $date = $thn.'-'.$bln.'-01';
        $tgl = date("Y-m-d",strtotime($date)); 
        //echo $tgl;
        $sql = "select aktual_luastanam as luastanam from beras_aktual a JOIN waktu b on a.id_waktu=b.id_waktu JOIN provinsi c on a.id_provinsi=c.id_provinsi where a.id_waktu='$tgl' AND a.id_provinsi='$prov'";                
        $query = $this->db->query($sql);           
        return $query->row()->luastanam;
    }

    public function get_luastanam_prediksi($bln,$thn,$prov) {
        $date = $thn.'-'.$bln.'-01';
        $tgl = date("Y-m-d",strtotime($date)); 
        //echo $tgl;
        $sql = "select prediksi_luastanam as luastanam from beras_prediksi a JOIN waktu b on a.id_waktu=b.id_waktu JOIN provinsi c on a.id_provinsi=c.id_provinsi where a.id_waktu='$tgl' AND a.id_provinsi='$prov'";                
        $query = $this->db->query($sql);           
        return $query->row()->luastanam;
    }

    

    //Reverse Data
    public function trans_bulan($bln){      
             if ($bln=='01') {$bulan = "Januari";}
        else if ($bln=='02') {$bulan = 'Februari';}
        else if ($bln=='03') {$bulan = 'Maret';}
        else if ($bln=='04') {$bulan = 'April';}
        else if ($bln=='05') {$bulan = 'Mei';}
        else if ($bln=='06') {$bulan = 'Juni';}
        else if ($bln=='07') {$bulan = 'Juli';}
        else if ($bln=='08') {$bulan = 'Agustus';}
        else if ($bln=='09') {$bulan = 'September';}
        else if ($bln=='10') {$bulan = 'Oktober';}
        else if ($bln=='11') {$bulan = 'November';}
        else if ($bln=='12') {$bulan = 'Desember';}
        else if ($bln=='13') {$bulan = 'Januari';}  
        return $bulan;      
        }       

        public function ubah_bulan($bln){      
             if ($bln=='Januari') {$bulan = "01";}
        else if ($bln=='Februari') {$bulan = '02';}
        else if ($bln=='Maret') {$bulan = '03';}
        else if ($bln=='April') {$bulan = '04';}
        else if ($bln=='Mei') {$bulan = '05';}
        else if ($bln=='Juni') {$bulan = '06';}
        else if ($bln=='Juli') {$bulan = '07';}
        else if ($bln=='Agustus') {$bulan = '08';}
        else if ($bln=='September') {$bulan = '09';}
        else if ($bln=='Oktober') {$bulan = '10';}
        else if ($bln=='November') {$bulan = '11';}
        else if ($bln=='Desember') {$bulan = '12';}
        return $bulan;
        }

        public function ubah_provinsi($provinsi){    
            if ($provinsi=='Jawa Barat') { $provinsi = "01";}
        else if ($provinsi=='Jawa Tengah') {$provinsi = '02';}
        else if ($provinsi=='Jawa Timur') {$provinsi = '03';}
        else if ($provinsi=='Sumatera Utara') {$provinsi = '04';}
        else if ($provinsi=='Sumatera Selatan') {$provinsi = '05';}
        else if ($provinsi=='Sulawesi Selatan') {$provinsi = '06';}
        else if ($provinsi=='Kalimantan Selatan') {$provinsi = '07';}
        else if ($provinsi=='Papua') {$provinsi = '08';}
        else if ($provinsi=='Kalimantan Tengah') {$provinsi = '09';}
        else if ($provinsi=='Maluku') {$provinsi = '10';}
        else if ($provinsi=='Sulawesi Utara') {$provinsi = '11';}
        return $provinsi;
        }

        //Waktu
        public function cek($bulan,$tahun) {
        $sql = "select * from waktu where year(id_waktu) = $tahun and MONTH(id_waktu) = $bulan";
        $query = $this->db->query($sql);            
        return $query->row_array();
        }

        public function cek_waktu_aktual($bulan,$tahun,$provinsi) {
        $sql = "select * from beras_aktual where year(id_waktu) = $tahun and MONTH(id_waktu) = $bulan and id_provinsi=$provinsi";
        $query = $this->db->query($sql);            
        return $query->row_array();
        }

        public function cek_waktu_prediksi($bulan,$tahun,$provinsi) {
        $sql = "select * from beras_prediksi where year(id_waktu) = $tahun and MONTH(id_waktu) = $bulan and id_provinsi=$provinsi";
        $query = $this->db->query($sql);            
        return $query->row_array();
        }

        public function cek_aktual($date,$provinsi) {
        $sql = "select * from beras_aktual where id_waktu='$date' and id_provinsi=$provinsi";
        $query = $this->db->query($sql);            
        return $query->row_array();
        }

        public function cek_prediksi($date,$provinsi) {
        $sql = "select * from beras_prediksi where id_waktu='$date' and id_provinsi=$provinsi";
        $query = $this->db->query($sql);            
        return $query->row_array();
        }

        public function get_aktual_pilih($date,$prov) {
        $date = $date;
        $tgl = date("Y-m-d",strtotime($date)); 
        //echo $tgl;
        $sql = "select *,a.aktual_harga as harga from beras_aktual a JOIN waktu b on a.id_waktu=b.id_waktu JOIN provinsi c on a.id_provinsi=c.id_provinsi where a.id_waktu='$tgl' AND a.id_provinsi='$prov'";                
        $query = $this->db->query($sql);           
        return $query->result_array();
        }

        public function get_prediksi_pilih($date,$prov) {
        $date = $date;
        $tgl = date("Y-m-d",strtotime($date)); 
        //echo $tgl;
        $sql = "select *,a.prediksi_harga as harga from beras_prediksi a JOIN waktu b on a.id_waktu=b.id_waktu JOIN provinsi c on a.id_provinsi=c.id_provinsi where a.id_waktu='$tgl' AND a.id_provinsi='$prov'";                
        $query = $this->db->query($sql);           
        return $query->result_array();
        }
    //Kondisi
    public function get_kondisi() {
        $sql = "select * from kondisi";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_rekomendasi() {
        $sql = "select * from rekomendasi order by no_rekomendasi";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_aturan() {
        $sql = "select * from aturan ORDER BY id_aturan";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_rule() {
        $sql = "SELECT id_aturan,stabilitas_harga,musim,bencana,hama,rekomendasi,i.* FROM (SELECT id_aturan,stabilitas_harga ,musim,bencana,nama_kondisi as hama, rekomendasi FROM (SELECT id_aturan,stabilitas_harga ,musim,nama_kondisi as bencana,kondisi_hama,rekomendasi FROM (SELECT id_aturan,stabilitas_harga ,nama_kondisi as musim,kondisi_bencana,kondisi_hama,rekomendasi FROM (SELECT id_aturan,nama_kondisi as stabilitas_harga ,kondisi_musim,kondisi_bencana,kondisi_hama,rekomendasi FROM aturan a LEFT JOIN kondisi b ON a.kondisi_harga=b.id_kondisi) b LEFT JOIN kondisi c ON b.kondisi_musim=c.id_kondisi) d LEFT JOIN kondisi e ON d.kondisi_bencana=e.id_kondisi) f LEFT JOIN kondisi g ON f.kondisi_hama=g.id_kondisi) h LEFT JOIN rekomendasi i ON h.rekomendasi=i.id_rekomendasi ORDER BY no_rekomendasi";
        $query = $this->db->query($sql);
        return $query->result();
    }
    


    public function get_HET($prov) {
        $sql = "select HET from provinsi WHERE id_provinsi='$prov'";                
        $query = $this->db->query($sql);           
        return $query->row()->HET;
    }

    public function get_setting1() {
        $sql = "select * from setting";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_setting2() {
        $sql = "select * from provinsi";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    public function insertData($table, $data) {
        $this->db->insert($table, $data);
    }
    
    public function updateData($param, $value, $table, $data) {
        $this->db->where($param, $value);
        $this->db->update($table, $data);
    }
    
    public function deleteData($param, $value, $table) {
        $this->db->where($param, $value);
        $this->db->delete($table);
    }
    
}