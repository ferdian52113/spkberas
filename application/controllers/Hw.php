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

        $alpha = 1;
        $beta = 1;
        $gamma = 1;
        $L = 12;
        $series = array (4177,5473,32072,38542,16619,4751,984,104,7508,43987,38934,16735,8819,7260,25611,35936,26612,9070,2296,263,19223,64860,36046,17664,6629,12037,43808,24448,17207,8414,3106,3603,10614,28893,35063,14362,15398,10852,28048,37983,24561,4700,1559,1863,21476,49223,35396,14374,7515,11117,28586,57988,25385,3633,2441,1175,15206,66127,49790,17154,6600,15761,32679,35333,23303,6787,801,2866,15279,47214,36440,23486,7592,11317,24277,42293,25072,10864,3173,915,22811,39928,37085,22638,14377,11681,38949,35524,19783,15273,2181,327,13054,32745,55970,36566,14843,12041,39433,34733,19328,15287,2312,83,11209,31109,55949,37126);
        $loc = 09;
        $this->holt_winters($series, $season_length = 12, $alpha = 0.004370336, $beta = 1, $gamma = 1,$length_forecast=12,$loc='09',$var='Luas Tanam');
        //$this->build_model();
    }
    private function build_model()
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
    for($i=0;$i<count($data)+$length_forecast;$i++){
        if($i<$season_length) {
            $holt_winters[$i]=NULL;
        } else if ($i>=count($data)){
            $holt_winters[$i]=1;
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
    return array($holt_winters);
}
}