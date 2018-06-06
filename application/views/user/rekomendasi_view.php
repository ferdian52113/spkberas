
<?php include(APPPATH.'views\header.php'); ?>        
        <!-- begin::Body -->
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
                <!-- BEGIN: Left Aside -->
                <button class="m-aside-left-close m-aside-left-close--skin-dark" id="m_aside_left_close_btn">
                    <i class="la la-close"></i>
                </button>
                <div id="m_aside_left" class="m-grid__item  m-aside-left  m-aside-left--skin-dark ">
                    <!-- BEGIN: Aside Menu -->
                        <?php include(APPPATH.'views\sidemenu.php'); ?>
                    <!-- END: Aside Menu -->
                </div>
                <!-- END: Left Aside -->
                <div class="m-grid__item m-grid__item--fluid m-wrapper">
                    <!-- BEGIN: Subheader -->
                    <div class="m-subheader ">
                        <div class="d-flex align-items-center">
                            <div class="mr-auto">
                                <h3 class="m-subheader__title m-subheader__title--separator">
                                    REKOMENDASI
                                </h3>
                                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                                    <li class="m-nav__item m-nav__item--home">
                                        <a href="<?php echo base_url()?>user" class="m-nav__link m-nav__link--icon">
                                            <i class="m-nav__link-icon la la-home"></i>
                                        </a>
                                    </li>
                                    <li class="m-nav__separator">
                                        -
                                    </li>
                                    <li class="m-nav__item">
                                        <a href="" class="m-nav__link">
                                            <span class="m-nav__link-text">
                                                Rekomendasi
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- END: Subheader -->
                    <div class="m-content">
                        <div class="row">
                            <div class="col-md-12">
                            <!--begin::Portlet-->
                            <div class="m-portlet m-portlet--tab">
                                <div class="m-portlet__head">
                                    <div class="m-portlet__head-caption">
                                        <div class="m-portlet__head-title">
                                            <span class="m-portlet__head-icon m--hide">
                                                <i class="la la-gear"></i>
                                            </span>
                                            <h3 class="m-portlet__head-text">
                                                Rekomendasi Keputusan
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <!--begin::Form-->
                                <form class="m-form m-form--fit m-form--label-align-right" action="<?php echo base_url('user/rekomendasi')?>" method="POST">
                                    <div class="m-portlet__body">
                                        <div class="form-group m-form__group">
                                            <?php echo $this->session->flashdata('msg2'); ?>
                                        </div>
                                        <div class="form-group m-form__group row">
                                                <div class="col-md-4">
                                                    <label>Provinsi</label><br>
                                                    <select class="form-control" name="prov" onchange="scrollDown();">              
                                                        <option class="select-extra" value="">--Pilih Provinsi--</option>
                                                        <?php if ($this->session->userdata('role')!=0) { 
                                                            echo '<option selected="selected" value="'.$this->session->userdata('prov').'">'.$this->session->userdata('prov').'</option>';
                                                        } else { ?>
                                                        <?php foreach ($pilihan_provinsi as $prodov) {
                                                          if($prodov['provinsi'] == $provinsi_pilih){ 
                                                              echo '<option selected="selected" value="'.$prodov['provinsi'].'">'.$prodov['provinsi'].'</option>';
                                                            
                                                          }
                                                          else {
                                                            echo '<option value="'.$prodov['provinsi'].'">'.$prodov['provinsi'].'</option>';
                                                          }

                                                          } } ?>                
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Tahun</label><br>
                                                    <select class="form-control" name="tahun">
                                                        <option class="select-extra" value="">--Pilih Tahun--</option>
                                                        <?php foreach ($pilihan_tahun as $thn) { 
                                                          if($thn['tahun'] == $tahun_pilih){ 
                                                            echo '<option selected="selected" value="'.$thn['tahun'].'">'.$thn['tahun'].'</option>';
                                                          } else {
                                                            echo '<option value="'.$thn['tahun'].'">'.$thn['tahun'].'</option>';
                                                          }
                                                          } ?>                
                                                    </select> 
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Bulan</label><br>
                                                    <select class="form-control" name="bulan">
                                                        <option class="select-extra" value="">--Pilih Bulan--</option>
                                                        <?php foreach ($pilihan_bulan as $bln) { 
                                                          if($bln['bulan'] == $bulan_pilih){ 
                                                            echo '<option selected="selected" value="'.$bln['bulan'].'">'.$bln['bulan'].'</option>';
                                                          } else {
                                                            echo '<option value="'.$bln['bulan'].'">'.$bln['bulan'].'</option>';
                                                          }
                                                          } ?>                
                                                    </select>
                                                </div>
                                            </div>
                                    </div>
                                    <div class="m-portlet__foot m-portlet__foot--fit">
                                        <div class="m-form__actions">
                                            <button type="action" class="btn m-btn--pill m-btn m-btn--gradient-from-primary m-btn--gradient-to-info tombolRekomendasi">
                                                Lihat Rekomendasi
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end::Portlet-->
                            </div>
                         </div>
                         <?php if ($status_rekomendasi==TRUE) {?>
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>   
                            <script>
                                $(function() {
                                    $('html, body').animate({
                                        scrollTop: $("#scoll").offset().top-85
                                    }, 1000);
                                 });
                            </script>
                            <?php 

                                $sta = 5;  
                                $statusHET = $status_HET;
                                $HET = $HET;
                                if($aktual) {
                                    $harga= $rekomendasi[0]['aktual_harga'];
                                    $luas = $rekomendasi[0]['aktual_luastanam'];
                                    $prod = $rekomendasi[0]['aktual_produksi'];
                                    $curahhujan= $rekomendasi[0]['aktual_curahhujan'];
                                    $banjir= $rekomendasi[0]['aktual_banjir'];
                                    $hama=$rekomendasi[0]['aktual_hama'];
                                    $luastanam4bulansebelum = $luas_tanam_empat_bulan_sebelum;
                                } else {
                                    $harga= $rekomendasi[0]['prediksi_harga'];
                                    $luas = $rekomendasi[0]['prediksi_luastanam'];
                                    $prod = $rekomendasi[0]['prediksi_produksi'];
                                    $curahhujan= $rekomendasi[0]['prediksi_curahhujan'];
                                    $banjir= $rekomendasi[0]['prediksi_banjir'];
                                    $hama= $rekomendasi[0]['prediksi_hama'];
                                    $luastanam4bulansebelum = $luas_tanam_empat_bulan_sebelum;
                                }

                                //Menentukan stabilitas harga dengan pendekatan HET atau Standar Deviasi 12 Bulan
                                if ($status_HET=='YA') {
                                    //Menggunakan pendekatan HET
                                    if ($harga>$HET) {
                                        $stabilitas_harga = 'Tidak Stabil';
                                    } else {
                                        $stabilitas_harga = 'Stabil';
                                    }
                                } else {
                                    //Menggunakan pendekatan Standar Deviasi 12 Bulan
                                    foreach ($stabilitas as $r) {            
                                          if($aktual){          
                                            $harga = $r['aktual_harga'];
                                          } else {
                                            $harga = $r['prediksi_harga'];
                                          }  
                                          $log_stab[] = log10(intval($harga)); 
                                    }

                                    $avg_stab = array_sum($log_stab) / count($log_stab);    

                                      $a = 0; 
                                      $i = 0;    
                                      foreach ($log_stab as $row) {      
                                        $a += pow(($log_stab[$i] - $avg_stab), 2);
                                        $i++;
                                      }
                                      
                                      $sta = sqrt($a/11)*100;
                                      /*print_r($sta);*/ 
                                      if ($sta <= 5) {
                                        $stabilitas_harga = 'Stabil';                    
                                      } else {
                                        $stabilitas_harga = 'Tidak Stabil';                    
                                      }
                                }

                                //Menentukan musim
                                if ($curahhujan<=150) {
                                    $musim = 'Kemarau';
                                } else if ($curahhujan>150){
                                    $musim = 'Penghujan';
                                }

                                //Menentukan bencanaa alam
                                if($banjir == 1) {
                                    $bencana = 'Ada Bencana';
                                } else {
                                    $bencana = 'Tidak Ada Bencana';
                                }

                                //Menentukan hamaa
                                $prosentaseHama = $hama/$luastanam4bulansebelum * 100;
                                if($prosentaseHama > 10) {
                                    $hama = 'Ada Hama';
                                } else {
                                    $hama = 'Tidak Ada Hama';
                                }

                                /*if ($prod<($produktivitas*$luastanam4bulansebelum)) {
                                    $bencana = 'Ada Bencana';
                                } else if ($prod>=($produktivitas*$luastanam4bulansebelum)){
                                    $bencana = 'Tidak Ada Bencana';
                                }*/

                                //Rule Based System
                                /*foreach ($rule_based_system as $rule) {
                                    if($stabilitas_harga==$rule->stabilitas_harga AND $musim==$rule->musim AND $bencana==$rule->bencana AND $hama==$rule->hama){
                                        $namarekomendasi = $rule->id_rekomendasi;
                                        $rekomendasi1 = $rule->rekomendasi_1;
                                        $rekomendasi2 = $rule->rekomendasi_2;
                                        $rekomendasi3 = $rule->rekomendasi_3; 
                                    }
                                    echo "if ".$rule->stabilitas_harga." and ".$rule->musim." and ".$rule->bencana." and ".$rule->hama." then ".$rule->id_rekomendasi;
                                    echo '<br>';
                                }*/

                                if($stabilitas_harga=='Stabil') {
                                    if($musim=='Penghujan') {
                                        if($bencana=='Tidak Ada Bencana') {
                                            if($hama=='Tidak Ada Hama') {
                                                $namarekomendasi = 'R1';
                                            } else if ($hama=='Ada Hama') {
                                                $namarekomendasi = 'R2';
                                            }
                                        } else if ($bencana=='Ada Bencana') {
                                            if($hama=='Tidak Ada Hama') {
                                                $namarekomendasi = 'R3';
                                            } else if ($hama=='Ada Hama') {
                                                $namarekomendasi = 'R4';
                                            }
                                        }
                                    } else if ($musim=='Kemarau') {
                                        if($bencana=='Tidak Ada Bencana') {
                                            if($hama=='Tidak Ada Hama') {
                                                $namarekomendasi = 'R5';
                                            } else if ($hama=='Ada Hama') {
                                                $namarekomendasi = 'R6';
                                            }
                                        } else if ($bencana=='Ada Bencana') {
                                            if($hama=='Tidak Ada Hama') {
                                                $namarekomendasi = 'R7';
                                            } else if ($hama=='Ada Hama') {
                                                $namarekomendasi = 'R8';
                                            }
                                        }
                                    }
                                } else if ($stabilitas_harga=='Tidak Stabil') {
                                    if($musim=='Penghujan') {
                                        if($bencana=='Tidak Ada Bencana') {
                                            if($hama=='Tidak Ada Hama') {
                                                $namarekomendasi = 'R9';
                                            } else if ($hama=='Ada Hama') {
                                                $namarekomendasi = 'R10';
                                            }
                                        } else if ($bencana=='Ada Bencana') {
                                            if($hama=='Tidak Ada Hama') {
                                                $namarekomendasi = 'R11';
                                            } else if ($hama=='Ada Hama') {
                                                $namarekomendasi = 'R12';
                                            }
                                        }
                                    } else if ($musim=='Kemarau') {
                                        if($bencana=='Tidak Ada Bencana') {
                                            if($hama=='Tidak Ada Hama') {
                                                $namarekomendasi = 'R13';
                                            } else if ($hama=='Ada Hama') {
                                                $namarekomendasi = 'R14';
                                            }
                                        } else if ($bencana=='Ada Bencana') {
                                            if($hama=='Tidak Ada Hama') {
                                                $namarekomendasi = 'R15';
                                            } else if ($hama=='Ada Hama') {
                                                $namarekomendasi = 'R16';
                                            }
                                        }
                                    }
                                }

                                foreach ($rule_based_system as $rule) {
                                    if($rule->id_rekomendasi==$namarekomendasi){
                                        $rekomendasi1 = $rule->rekomendasi_1;
                                        $rekomendasi2 = $rule->rekomendasi_2;
                                        $rekomendasi3 = $rule->rekomendasi_3; 
                                    }
                                }
                                print_r('status HET : '.$status_HET.'<br>');
                                print_r('HET : '.$HET.'<br>');
                                print_r('harga : '.$harga.'<br>');
                                print_r('luastanam : '.$luas.'<br>');
                                print_r('luastanam4bulansebelum : '.$luastanam4bulansebelum.'<br>');
                                print_r('produksi : '.$prod.'<br>');
                                print_r('curahhujan : '.$curahhujan.'<br>');
                                print_r('stabilitas_harga : '.$sta.'<br>');
                                print_r('stabilitas_harga : '.$stabilitas_harga.'<br>');
                                print_r('musim : '.$musim.'<br>');
                                print_r('bencana : '.$bencana.'<br>');
                                print_r('hama : '.$hama.'<br>');
                                print_r('prosentaseHama : '.$prosentaseHama.'<br>');
                                print_r('banjir : '.$banjir.'<br>');
                                print_r('namarekomendasi : '.$namarekomendasi.'<br>');

                                

                            ?>
                            <div class="row" id="scoll">
                                <div class="col-md-3">
                                    <div class="m-portlet m-portlet--tab">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="la la-gear"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        <b>Stabilitas Harga</b>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <?php if($stabilitas_harga=='Stabil') { ?>
                                        <div class=" btn-success btn-lg m-btn m-btn--square" style="height: 75px">
                                                <h3 style="font-size: 35px"><?php echo $stabilitas_harga?></h3>  
                                        </div>
                                        <?php } else {?>
                                        <div class=" btn-danger btn-lg m-btn m-btn--square" style="height: 75px">
                                                <h3 style="font-size: 35px"><?php echo $stabilitas_harga?></h3>  
                                        </div>
                                        <?php } ?>
                                    </div>
                                 </div>
                                <div class="col-md-3">
                                    <div class="m-portlet m-portlet--tab">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="la la-gear"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        <b>Musim</b>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" btn-primary btn-lg m-btn m-btn--square" style="height: 75px;">
                                                <h3 style="font-size: 35px"><?php echo $musim; ?></h3>
                                        </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="m-portlet m-portlet--tab">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="la la-gear"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        <b>Bencana</b>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" btn-warning btn-lg m-btn m-btn--square" style="height: 75px;">
                                                <h3 style="font-size: 23px"><?php echo $bencana ?></h3>
                                        </div>
                                    </div>
                                 </div>
                                 <div class="col-md-3">
                                    <div class="m-portlet m-portlet--tab">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="la la-gear"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        <b>Hama</b>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" btn-warning btn-lg m-btn m-btn--square" style="height: 75px;">
                                                <h3 style="font-size: 23px"><?php echo $hama ?></h3>
                                        </div>
                                    </div>
                                 </div>
                             </div> 
                             <div class="row">
                                <div class="col-md-4">
                                    <div class="m-portlet m-portlet--tab">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="la la-gear"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        <b>Prediksi Harga Bulan 
                                                            <?php 
                                                                if($bulan_pilih=='Desember') {
                                                                echo $bulan_depan." ". ($tahun_pilih+1);
                                                                }
                                                                else {
                                                                echo $bulan_depan." ". ($tahun_pilih);    
                                                                }
                                                            ?></b>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" btn-primary btn-lg m-btn m-btn--square" style="height: 75px;background-color: brown">
                                            <?php 
                                                if($prediksi){
                                                        if($data_prediksi_bulan_depan) {
                                                        $prediksi_harga_bulan_depan = "Rp. ". number_format($data_prediksi_bulan_depan[0]['prediksi_harga'],2);
                                                        }
                                                        else {
                                                            $prediksi_harga_bulan_depan = "-";
                                                        }
                                                } else { 
                                                    $prediksi_harga_bulan_depan = "-"; }
                                            ?>
                                                <h3 style="font-size: 35px"><?php echo $prediksi_harga_bulan_depan; ?></h3>
                                        </div>
                                    </div>
                                 </div>
                                 <div class="col-md-8">
                                    <div class="m-portlet m-portlet--tab">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="la la-gear"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        <b>Harga Aktual / Harga Prediksi Bulan <?php echo $bulan_pilih." ". ($tahun_pilih);?> </b>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class=" btn-warning btn-lg m-btn m-btn--square" style="height: 75px;background-color: yellow">
                                            <?php 
                                              if($prediksi){
                                                 $prediksi_harga = "Rp. ". number_format($data_prediksi[0]['prediksi_harga'],2);
                                               } else { 
                                                $prediksi_harga = "-"; }

                                              if ($aktual) {
                                                $aktual_harga = "Rp. ". number_format($data_aktual[0]['aktual_harga'],2);
                                               } else { 
                                                $aktual_harga = "-"; }
                                            ?>
                                                <h3 style="font-size: 35px"><?php echo $aktual_harga.' / '.$prediksi_harga; ?></h3>
                                        </div>
                                    </div>
                                 </div>
                             </div> 
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="m-portlet m-portlet--tab">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="la la-gear"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        <b>PREDIKSI KONDISI SAAT INI</b>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-portlet__body ">
                                            <?php 
                                              $no = 0;
                                              if($prediksi){
                                                  foreach ($data_prediksi as $hasil) {
                                                    $harga = "Rp ".number_format((float)$hasil['prediksi_harga'],2); 
                                                    $produksi = number_format((float)$hasil['prediksi_produksi'],2) . " Ton";
                                                    $luastanam = number_format((float)$hasil['prediksi_luastanam'],2) . " Ha"; 
                                                    if($data_prediksi_bulan_depan) {        
                                                            $harga_bulan_depan = "Rp ".number_format($data_prediksi_bulan_depan[0]['prediksi_harga'],2);
                                                    }
                                                    else {
                                                            $harga_bulan_depan = "-";
                                                    }
                                                }   
                                              } else {
                                                $harga_bulan_depan = "-";
                                                $produksi = "-";
                                                $luastanam = "-";
                                                $harga = "-";
                                              }
                                              ?>
                                            <b>
                                                <table class="table table-hover dataTables-example widht:40px" style=" font-size:15px">
                                                    <tbody>
                                                        <tr>
                                                            <th>
                                                                Periode
                                                            </th>
                                                            <td>
                                                                :
                                                            </td>
                                                            <td>
                                                                <?php echo $bulan_pilih; echo ' '; echo $tahun_pilih;?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                Harga
                                                            </th>
                                                            <td>
                                                                :
                                                            </td>
                                                            <td>
                                                                <?php echo $harga;?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                Produksi
                                                            </th>
                                                            <td>
                                                                :
                                                            </td>
                                                            <td>
                                                                <?php echo $produksi;?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                Luas Tanam
                                                            </th>
                                                            <td>
                                                                :
                                                            </td>
                                                            <td>
                                                                <?php echo $luastanam;?>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                Harga Bulan Depan
                                                            </th>
                                                            <td>
                                                                :
                                                            </td>
                                                            <td>
                                                                <?php echo $harga_bulan_depan;?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </b>
                                                <!--end: Datatable -->
                                        </div>
                                    </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="m-portlet m-portlet--tab">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="la la-gear"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        <b>REKOMENDASI KEPUTUSAN</b>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-portlet__body ">
                                            <div class="table-responsive">
                                                <b>
                                                  <table class="table table-hover" style="font-size: 15px">      
                                                        <tbody>  
                                                            <?php if($rekomendasi3) {?>
                                                            <tr>
                                                                <td>
                                                                    <?php echo $last_four_month; echo '-'; echo $last_three_month; ?>
                                                                </td>
                                                                <td>
                                                                    :
                                                                </td>
                                                                <td>
                                                                    <?php echo $rekomendasi3;?>
                                                                    <?php echo ''?>
                                                                </td>
                                                            </tr>
                                                            <?php } ?>
                                                            <?php if($rekomendasi2) {?>
                                                            <tr>
                                                                <td>
                                                                    <?php echo $last_two_month; echo '-'; echo $last_one_month; ?>
                                                                </td>
                                                                <td>
                                                                    :
                                                                </td>
                                                                <td>
                                                                    <?php echo $rekomendasi2; ?>
                                                                </td>
                                                            </tr>
                                                            <?php } ?>
                                                            <tr>
                                                                <td width="33">
                                                                    <?php echo $bulan_pilih; ?>
                                                                </td>
                                                                <td width="33">
                                                                    :
                                                                </td>
                                                                <td width="33">
                                                                    <?php echo $rekomendasi1; ?>
                                                                </td>
                                                            </tr>
                                                        </tbody> 
                                                  </table>
                                              </b>
                                            </div>
                                        </div>
                                    </div>
                                 </div>


                                 <!-- <div class="col-md-6">
                                    <div class="m-portlet m-portlet--tab">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="la la-gear"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        <b>REKOMENDASI WAKTU TANAM</b>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-portlet__body">
                                                <?php print_r($sta);?>
                                        </div>
                                    </div>
                                 </div> -->
                             </div> 

                             <!-- <div class="row">
                                 <div class="col-md-12">
                                    <div class="m-portlet m-portlet--tab">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="la la-gear"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        <b>PREDIKSI KONDISI SAAT INI</b>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-portlet__body ">
                                            <?php 
                                              $no = 0;
                                              if($prediksi){
                                                  foreach ($data_prediksi as $hasil) {
                                                    $harga = "Rp ".number_format((float)$hasil['prediksi_harga'],2); 
                                                    $produksi = number_format((float)$hasil['prediksi_produksi'],2) . " Ton";
                                                    $luastanam = number_format((float)$hasil['prediksi_luastanam'],2) . " Ha"; 
                                                    if($data_prediksi_bulan_depan) {        
                                                            $harga_bulan_depan = "Rp ".number_format($data_prediksi_bulan_depan[0]['prediksi_harga'],2);
                                                    }
                                                    else {
                                                            $harga_bulan_depan = "-";
                                                    }
                                                }   
                                              } else {
                                                $harga_bulan_depan = "-";
                                                $produksi = "-";
                                                $luastanam = "-";
                                                $harga = "-";
                                              }
                                              ?>
                                            <table class="table table-hover dataTables-example widht:40px" style=" font-size:15px">
                                                    <thead >
                                                        <tr>
                                                            <th style="width:20%">
                                                            Periode
                                                            </th>
                                                            <th style="width:20%">
                                                            Harga
                                                            </th>
                                                            <th style="width:20%">
                                                            Produksi
                                                            </th>
                                                            <th style="width:20%">
                                                            Luas Tanam
                                                            </th>
                                                            <th style="width:20%">
                                                            Harga Bulan Depan
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td style="width:20%">
                                                            <?php echo $bulan_pilih; echo ' '; echo $tahun_pilih;?>
                                                            </td>
                                                            <td style="width:20%">
                                                            <?php echo $harga?>
                                                            </td>
                                                            <td style="width:20%">
                                                            <?php echo $produksi?>
                                                            </td>
                                                            <td style="width:20%">
                                                            <?php echo $luastanam?>
                                                            </td>
                                                            <td style="width:20%">
                                                            <?php echo $harga_bulan_depan?>
                                                          </td>
                                                        </tr>
                                                  </tbody>
                                                </table>
                                        </div>
                                    </div>
                                 </div>
                             </div>  -->

                             <div class="row" id="myDiv">
                                 <div class="col-md-12">
                                    <div class="m-portlet m-portlet--tab">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="la la-gear"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        <b>GRAFIK PERBANDINGAN HARGA AKTUAL DAN PREDIKSI</b>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-portlet__body ">
                                            <?php
                                                if($aktual){ 
                                                    $data_aktual1 = '';
                                                    $data_aktual2 = '';
                                                    foreach ($data_aktual_setaun as $row) {
                                                        $data_aktual1 .= $row['aktual_harga']. ", ";
                                                        $data_aktual2 .= $row['aktual_produksi']. ", ";
                                                    }
                                                }
                                                if($prediksi){ 
                                                    $data_prediksi1 = '';
                                                    $data_prediksi2 = '';
                                                    foreach ($data_prediksi_setaun as $row) {
                                                        $data_prediksi1 .= $row['prediksi_harga']. ", ";
                                                        $data_prediksi2 .= $row['prediksi_produksi']. ", ";
                                                    }
                                                }
                                            ?>

                                            <canvas id="myChart" style="width: 500px; height: 50%"></canvas>
                                        </div>
                                    </div>
                                 </div>
                             </div> 
                             <!-- <div class="row">
                                 <div class="col-md-12">
                                    <div class="m-portlet m-portlet--tab">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="la la-gear"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        <b>GRAFIK PERBANDINGAN PRODUKSI AKTUAL DAN PREDIKSI</b>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-portlet__body ">
                                            <?php
                                                if($aktual){ 
                                                    $data_aktual1 = '';
                                                    $data_aktual2 = '';
                                                    foreach ($data_aktual_setaun as $row) {
                                                        $data_aktual1 .= $row['aktual_harga']. ", ";
                                                        $data_aktual2 .= $row['aktual_produksi']. ", ";
                                                    }
                                                }
                                                if($prediksi){ 
                                                    $data_prediksi1 = '';
                                                    $data_prediksi2 = '';
                                                    foreach ($data_prediksi_setaun as $row) {
                                                        $data_prediksi1 .= $row['prediksi_harga']. ", ";
                                                        $data_prediksi2 .= $row['prediksi_produksi']. ", ";
                                                    }
                                                }
                                            ?>

                                            <canvas id="myChart2" style="width: 500px; height: 50%"></canvas>
                                        </div>
                                    </div>
                                 </div>
                             </div>  -->
                        <?php } ?>
                    </div>
                </div>
            </div>
            <?php include(APPPATH.'views\footer.php'); ?>
            <!-- google map js -->
            <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
            <!-- google chartjs api -->
            <script type="text/javascript" src="https://www.google.com/jsapi"></script>
            <!-- end:: Body -->
            <script>
            var ctx = document.getElementById("myChart");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [
                    <?php if($prediksi) { ?> 
                    {
                        label: 'Prediksi',
                        data: [
                        <?php
                        echo $data_prediksi1;
                        ?>
                        ],
                        borderWidth: 3,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 1)',
                        fill: false,
                    },
                    <?php } ?> 
                    <?php if($aktual) { ?> 
                    {
                        label: 'Aktual',
                        data: [
                        <?php
                        echo $data_aktual1;
                        ?>
                        ],
                        borderWidth: 3,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 1)',
                        fill: false,
                    }<?php } ?> ]
                },
                options: {
                    elements: {
                        line: {
                            tension: 0.000001
                        }
                    },
                    responsive: true,
                    title:{
                        display:false,
                        text:'Chart.js Line Chart'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'Month'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'Value'
                            }/*,
                            ticks: {
                                suggestedMin: 0,
                                suggestedMax: 5000000
                            }*/
                        }]
                    }
                }
            });
            </script>
            <script>
            var ctx = document.getElementById("myChart2");
            var myChart2 = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [
                    <?php if($prediksi) { ?> 
                    {
                        label: 'Prediksi',
                        data: [
                        <?php
                        echo $data_prediksi2;
                        ?>
                        ],
                        borderWidth: 3,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 1)',
                        fill: false,
                    },
                    <?php } ?> 
                    <?php if($aktual) { ?> 
                    {
                        label: 'Aktual',
                        data: [
                        <?php
                        echo $data_aktual2;
                        ?>
                        ],
                        borderWidth: 3,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 1)',
                        fill: false,
                    }<?php } ?> ]
                },
                options: {
                    elements: {
                        line: {
                            tension: 0.000001
                        }
                    },
                    responsive: true,
                    title:{
                        display:false,
                        text:'Chart.js Line Chart'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'Month'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'Value'
                            }/*,
                            ticks: {
                                suggestedMin: 0,
                                suggestedMax: 5000000
                            }*/
                        }]
                    }
                }
            });
            </script>
