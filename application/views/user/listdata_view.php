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
        <!-- END: Left Aside -->
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
          <!-- BEGIN: Subheader -->
          <div class="m-subheader ">
            <div class="d-flex align-items-center">
              <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                  DATA
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
                        Lihat Data Aktual dan Prediksi
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
                  <div class="col-xl-12">
                    <form action="<?php echo base_url('user/lihatData');?>" method="post">
                    <div class="form-group m-form__group row">
                        <div class="col-md-4">
                            <label>Provinsi</label><br>
                            <select class="form-control" name="prov">              
                                <option class="select-extra" value="">--Pilih Provinsi--</option>
                                <?php if ($this->session->userdata('role')!=0) { 
                                    echo '<option selected="selected" value="'.$this->session->userdata('prov').'">'.$this->session->userdata('prov').'</option>';
                                } else { ?>
                                <?php foreach ($pilihan_provinsi as $prov) {
                                  if($prov['provinsi'] == $provinsi_pilih){ 
                                      echo '<option selected="selected" value="'.$prov['provinsi'].'">'.$prov['provinsi'].'</option>';
                                    
                                  }
                                  else {
                                    echo '<option value="'.$prov['provinsi'].'">'.$prov['provinsi'].'</option>';
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
                        <div class="col-md-2" style="margin-top: auto;">
                          <button class="btn m-btn--pill m-btn m-btn--gradient-from-primary m-btn--gradient-to-info"  type="submit">Lihat Data</button>
                        </div>
                    </div>
                    </form>  
                  </div>
            </div>
            <div class="row">
              <div class="col-xl-12">
                    <div class="m-portlet m-portlet--mobile">
                      <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                          <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                              Data Aktual
                            </h3>
                          </div>
                        </div>
                      </div>
                      <div class="m-portlet__body">
                              <div class="table-responsive">
                                    <table class="table table-hover dataTables-example widht:40px" >
                                          <thead>
                                              <tr>      
                                                  <th>No</th>  
                                                  <th>Waktu</th>  
                                                  <th>Provinsi</th>    
                                                  <th>Harga</th>
                                                  <th>Produksi</th>
                                                  <th>Luas Tanam</th>
                                                  <th>Curah Hujan</th>
                                                  <th>Banjir</th>
                                                  <th>Luas Terkena Hama</th>
                                                  <th>Aksi</th>                   
                                              </tr>      
                                          </thead>

                                          <tbody>                         
                                                <?php 
                                                    $no = 0;
                                                    foreach ($data_aktual as $data) {  
                                                    if ($this->session->userdata('role')==0) {       
                                                      $waktu = new DateTime($data['id_waktu']);
                                                      $waktu = $waktu->format("M/y");
                                                      ?>
                                                      <tr>      
                                                      <td><span><?php $no++; echo $no;?></span></td>
                                                      <td><span><?php echo $waktu; ?></span></td>
                                                      <td><span><?php echo $data['provinsi'];?></span></td>
                                                      <td><span><?php echo "Rp. ".$data['aktual_harga'];?></span></td>      
                                                      <td><span><?php echo $data['aktual_produksi']." Ton";?></span></td>
                                                      <td><span><?php echo $data['aktual_luastanam']. " Ha";?></span></td>
                                                      <td><span><?php echo $data['aktual_curahhujan']." mm";?></span></td>
                                                      <td><span><?php if($data['aktual_banjir']>=1) {echo "Banjir";} else {echo "Tidak";};?></span></td>
                                                      <td><span><?php echo $data['aktual_hama']. " Ha";?></span></td>
                                                      <td><a href="<?php echo base_url('user/editData/'.$kategori1.'/'.$data['id_aktual'])?>" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-primary"/>
                                                      Edit
                                                      </td>                      
                                                      </tr>
                                                    <?php } else {
                                                      if($this->session->userdata('prov')==$data['provinsi']) {
                                                          $waktu = new DateTime($data['id_waktu']);
                                                          $waktu = $waktu->format("M/y");
                                                          ?>
                                                          <tr>      
                                                          <td><span><?php $no++; echo $no;?></span></td>
                                                          <td><span><?php echo $waktu; ?></span></td>
                                                          <td><span><?php echo "Rp. ".$data['aktual_harga'];?></span></td>      
                                                          <td><span><?php echo $data['aktual_produksi']." Ton";?></span></td>
                                                          <td><span><?php echo $data['aktual_luastanam']. " Ha";?></span></td>
                                                          <td><span><?php echo $data['aktual_curahhujan']." mm";?></span></td>
                                                          <td><span><?php if($data['aktual_banjir']>=1) {echo "Banjir";} else {echo "Tidak";};?></span></td>
                                                          <td><span><?php echo $data['aktual_hama']. " Ha";?></span></td>
                                                          <td><a href="<?php echo base_url('user/editData/'.$kategori1.'/'.$data['id_aktual'])?>" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-primary"/>
                                                          Edit
                                                          </td>                      
                                                          </tr>

                                                      <?php }
                                                    }
                                                }?>
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
              </div>
              <div class="col-xl-12">
                <div class="m-portlet m-portlet--mobile">
                  <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                      <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                          Data Prediksi
                        </h3>
                      </div>
                    </div>
                  </div>
                  <div class="m-portlet__body">
                          <div class="table-responsive">
                                <table class="table table-hover dataTables-example widht:40px" >
                                      <thead>
                                          <tr>      
                                              <th>No</th>  
                                              <th>Waktu</th>  
                                              <th>Provinsi</th>    
                                              <th>Harga</th>
                                              <th>Produksi</th>
                                              <th>Luas Tanam</th>
                                              <th>Curah Hujan</th>
                                              <th>Banjir</th>
                                              <th>Luas Terkena Hama</th>
                                              <th>Aksi</th>                   
                                          </tr>      
                                      </thead>

                                      <tbody>                         
                                            <?php 
                                                  $no = 0;
                                                  foreach ($data_prediksi as $data) {    
                                                  if ($this->session->userdata('role')==0) {     
                                                    $waktu = new DateTime($data['id_waktu']);
                                                    $waktu = $waktu->format("M/y");
                                                    ?>
                                                    <tr>      
                                                    <td><span><?php $no++; echo $no;?></span></td>
                                                    <td><span><?php echo $waktu; ?></span></td>
                                                    <td><span><?php echo $data['provinsi'];?></span></td>
                                                    <td><span><?php echo "Rp. ".$data['prediksi_harga'];?></span></td>      
                                                    <td><span><?php echo $data['prediksi_produksi']." Ton";?></span></td>
                                                    <td><span><?php echo $data['prediksi_luastanam']. " Ha";?></span></td>
                                                    <td><span><?php echo $data['prediksi_curahhujan']." mm";?></span></td>
                                                    <td><span><?php if($data['prediksi_banjir']>=1) {echo "Banjir";} else {echo "Tidak";};?></span></td>
                                                    <td><span><?php echo $data['prediksi_hama']. " Ha";?></span></td>
                                                    <td><a href="<?php echo base_url('user/editData/'.$kategori2.'/'.$data['id_prediksi'])?>" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-primary"/>
                                                    Edit
                                                    </td>                      
                                                    </tr>
                                                  <?php } else {
                                                    if($this->session->userdata('prov')==$data['provinsi']) {
                                                          $waktu = new DateTime($data['id_waktu']);
                                                          $waktu = $waktu->format("M/y");
                                                          ?>
                                                    <tr>      
                                                    <td><span><?php $no++; echo $no;?></span></td>
                                                    <td><span><?php echo $waktu; ?></span></td>
                                                    <td><span><?php echo "Rp. ".$data['prediksi_harga'];?></span></td>      
                                                    <td><span><?php echo $data['prediksi_produksi']." Ton";?></span></td>
                                                    <td><span><?php echo $data['prediksi_luastanam']. " Ha";?></span></td>
                                                    <td><span><?php echo $data['prediksi_curahhujan']." mm";?></span></td>
                                                    <td><span><?php if($data['prediksi_banjir']>=1) {echo "Banjir";} else {echo "Tidak";};?></span></td>
                                                    <td><span><?php echo $data['prediksi_hama']. " Ha";?></span></td>
                                                    <td><a href="<?php echo base_url('user/editData/'.$kategori2.'/'.$data['id_prediksi'])?>" class="m-btn m-btn--pill m-btn--hover-brand btn btn-sm btn-primary"/>
                                                    Edit
                                                    </td>                      
                                                    </tr>      
                                            <?php }
                                                    }
                                                }?>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- end:: Body -->
<!-- end:: Body -->
            <?php include(APPPATH.'views\footer.php'); ?>

            <script>
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                dom: '<"html5buttons"B>lTfgitp',
                "ordering": true,
                buttons: [
                    
                    
                ]

            });
        });
    </script>
