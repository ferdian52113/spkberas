<?php
/* 
 * Multiplicative Implementation 
 * http://en.wikipedia.org/wiki/Exponential_smoothing#Triple_exponential_smoothing
 *
 * Holt-Winter is a triple exponential smoothing algorithm 
 * involving the following factors / parameters:
 *  alpha: data smoothing factor 0 < alpha < 1
 *  beta: trend smoothing factor 0 < beta < 1
 *  gamma: seasonal smoothing factor 0 < gamma < 1
 *  L: period length
 * */
class Hw extends CI_Controller {

    private $alpha;
    private $beta;
    private $gamma;
    private $L;
    private $series;
    private $levels;
    private $trends;
    private $seasonals;
    function __construct()
    {
        parent::__construct();
        $this->load->helper('text');
        $this->load->helper('form');
        $this->load->library('image_lib');
        $this->load->library('form_validation');
        $this->load->library('session');
        date_default_timezone_set("Asia/Jakarta");
        
    }

    public function index()
    {   

        $date1 = '2019-01-01';
        $tahun_sekarang = date('Y', strtotime($date1));
        $tahun_akhir_forecast = 2016;
        $length_forecast = ($tahun_sekarang-$tahun_akhir_forecast)*12;

        $alpha = 1;
        $beta = 1;
        $gamma = 1;
        $L = 12;
        $series = array (138188,58127,41523,84453,159789,86222,41171,26981,27042,16475,67644,178295,114388,45446,36846,58971,157963,112509,48643,36253,20753,6412,29410,106346,164555,74459,39804,83174,146628,120229,52785,39040,35561,58784,91251,151577,98866,42002,37541,55857,184949,117006,44780,22456,21909,23191,70821,211486,124940,42919,31796,96877,205064,96594,38202,29232,24149,24295,69520,169902,143328,43026,33406,55088,243582,129967,35354,29615,37853,21705,73046,190144,137088,58716,36649,50148,267073,114139,34339,36714,30398,11323,59995,147830,180749,82621,60369,47194,279697,125886,30714,37593,48871,6316,14322,123254,184294,83781,61167,48012,279200,128186,31350,36090,48520,5985,8627,121482);
        $loc = 06;
        $this->holt_winters($series, $season_length = 12, $alpha = 0.001664835, $beta = 1, $gamma = 0.948530965,$length_forecast,$loc='06',$var='Luas Tanam');
        //$this->build_model();
    }

    function holt_winters($data, $season_length, $alpha, $beta, $gamma,$length_forecast,$loc,$var) {
    

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

                if($var='Luas Tanam') {
                    if($loc=='01') {
                        $regression[$i] = -78990.3064005526 + 1.5202869917021*$alpha_level + 0*$beta_trend + 1.01128062797808 * $season[$i];
                    } else if($loc=='02') {
                        $regression[$i] = -157663.557244406 + 2.37814702176707*$alpha_level + 42.7692417201128*$beta_trend + 1.02761909746456* $season[$i];
                    } else if($loc=='03') {
                        $regression[$i] = -24406.6292092276 + 1.25174874219277*$alpha_level + 4.41452932029465*$beta_trend + 1.02323446514704 * $season[$i];
                    } else if($loc=='04') {
                        $regression[$i] =  67949419.2020113 + 1.12783457524249*$alpha_level + 215959.462573575*$beta_trend + 1.02867318606618 * $season[$i];
                    } else if($loc=='05') {
                        $regression[$i] = -3.27418092638254E-11 + 1*$alpha_level + -3.10036691064369E-15*$beta_trend + 1 * $season[$i];
                    } else if($loc=='06') {
                        $regression[$i] = -478.17186156489 + 1.01184610400827*$alpha_level + -0.0804756783555209*$beta_trend + 1.00594416094877 * $season[$i];
                    } else if($loc=='07') {
                        $regression[$i] = 73198.6660710314 + -0.763106500885188*$alpha_level + 24.9495351940806*$beta_trend + 0.951496584215959 * $season[$i];
                    } else if($loc=='08') {
                        $regression[$i] = -2166.86018826386 + 0.732384663813179*$alpha_level + -29.4173666932935*$beta_trend + 1.13384611870156 * $season[$i];
                    } else if($loc=='09') {
                        $regression[$i] = -6.13908923696727E-11 + 1*$alpha_level + 8.38708377611989E-15*$beta_trend + 1 * $season[$i];
                    } else if($loc=='10') {
                        $regression[$i] = -11067.3232420229 + 9.05444880796968*$alpha_level + 260.948287783574*$beta_trend + 1.22858168206032 * $season[$i];
                    } else if($loc=='11') {
                        $regression[$i] = 10135.5522619964 + 0.0374029780864185*$alpha_level + 0.85900464816731*$beta_trend + 0.0996083133906275 * $season[$i];
                    } 
                }
                $m++;
            } else {
                $temp_level = $level;
                $temp_trend = $trend;
                $level = $alpha * ($data[$i] - $season[$i-$season_length]) + (1.0 - $alpha) * ($temp_level + $temp_trend);
                $trend = $beta * ($level - $temp_level) + ( 1.0 - $beta ) * $temp_trend;
                $season[$i] = $gamma * ($data[$i] - $level) + (1.0 - $gamma) * $season[$i-$season_length];
                $holt_winters[$i] = $temp_level + $temp_trend + $season[$i-$season_length];

                if($var='Luas Tanam') {
                    if($loc=='01') {
                        $regression[$i] = -78990.3064005526 + 1.5202869917021*$level + 0*$trend + 1.01128062797808 * $season[$i];
                    } else if($loc=='02') {
                        $regression[$i] = -157663.557244406 + 2.37814702176707*$level + 42.7692417201128*$trend + 1.02761909746456* $season[$i];
                    } else if($loc=='03') {
                        $regression[$i] = -24406.6292092276 + 1.25174874219277*$level + 4.41452932029465*$trend + 1.02323446514704 * $season[$i];
                    } else if($loc=='04') {
                        $regression[$i] =  67949419.2020113 + 1.12783457524249*$level + 215959.462573575*$trend + 1.02867318606618 * $season[$i];
                    } else if($loc=='05') {
                        $regression[$i] = -3.27418092638254E-11 + 1*$level + -3.10036691064369E-15*$trend + 1 * $season[$i];
                    } else if($loc=='06') {
                        $regression[$i] = -478.17186156489 + 1.01184610400827*$level + -0.0804756783555209*$trend + 1.00594416094877 * $season[$i];
                    } else if($loc=='07') {
                        $regression[$i] = 73198.6660710314 + -0.763106500885188*$level + 24.9495351940806*$trend + 0.951496584215959 * $season[$i];
                    } else if($loc=='08') {
                        $regression[$i] = -2166.86018826386 + 0.732384663813179*$level + -29.4173666932935*$trend + 1.13384611870156 * $season[$i];
                    } else if($loc=='09') {
                        $regression[$i] = -6.13908923696727E-11 + 1*$level + 8.38708377611989E-15*$trend + 1 * $season[$i];
                    } else if($loc=='10') {
                        $regression[$i] = -11067.3232420229 + 9.05444880796968*$level + 260.948287783574*$trend + 1.22858168206032 * $season[$i];
                    } else if($loc=='11') {
                        $regression[$i] = 10135.5522619964 + 0.0374029780864185*$level + 0.85900464816731*$trend + 0.0996083133906275 * $season[$i];
                    } 
                }        
            }
        }
    //print_r($regression);
        $a=1;
        $k = count($regression)-$season_length;
        for ($i=1; $i <= 12; $i++) { 
                $tanggal = '2019'.'-'.$a.'-01';
                $date_now = date("Y-m-d",strtotime($tanggal));

                $data_prediksi = array(                
                            'id_waktu'  => $date_now,
                            'prediksi_luas_tanam'  => round($regression[$k])          
                );
                print_r($data_prediksi); echo "<br>";
                $a++;$k++;

        }
    }

    /*private function build_model()
    {

        $this->initialize_levels();
        $this->initialize_trends();
        $this->initialize_seasonals();

        print_r($this->levels);echo "<br>";
        print_r($this->trends);echo "<br>";
        print_r($this->seasonals);exit();
        for ($i = $this->L; $i < count($this->series); $i++) {
            $x = $this->series[$i];
            $s0 = $this->seasonals[$i-$this->L];
            $l0 = $this->levels[$i-1];
            $t0 = $this->trends[$i-1];
            $l = $this->alpha * $x / $s0 + (1 - $this->alpha) * ($l0 + $t0);
            $t = $this->beta * ($l - $l0) + (1 - $this->beta) * $t0;
            $s = $this->gamma * ($x / $l) + (1 - $this->gamma) * $s0;
            $this->levels[$i] = $l;
            $this->trends[$i] = $t;
            $this->seasonals[$i] = $s;
        }
    }
    private function initialize_levels()
    {
        $this->levels = array();
        $sum = 0;
        for ($i = 0; $i < $this->L - 1; $i++) {
            $this->levels[] = null;
            $sum += $this->series[$i];
        }
        $sum += $this->series[$this->L-1];
        $this->levels[] = $sum / $this->L;
        return $this->levels;
    }
    private function initialize_trends()
    {
        $this->trends = array();
        for ($i = 0; $i < $this->L - 1; $i++) {
            $this->trends[] = null;
        }
        $this->trends[] = 0;
    }
    private function initialize_seasonals()
    {
        $this->seasonals = array();
        for ($i = 0; $i < $this->L; $i++) {
            $this->seasonals[] = $this->series[$i] / $this->levels[$this->L-1];
        }
        $this->seasonals;        
    }
    public function forecast($k)
    {
        $m = $k - count($this->series) + 1;
        if ($m <= 0) {
            throw new Exception("Supposed to forecast future series");
        }
        $i = count($this->series)-1;
        $j = $i - $this->L + (($m-1) % $this->L) + 1;
        $forecast = ($this->levels[$i] + $m * $this->trends[$i]) * $this->seasonals[$j];
        return $forecast;
    }*/
}