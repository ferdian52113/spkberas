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
                        Pengaturan
                      </span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- END: Subheader -->
          <div class="m-content">
              <div class="col-xl-12">
                <div class="form-group m-form__group">
                  <?php echo $this->session->flashdata('msg'); ?>
              </div>
                  <div class="m-portlet m-portlet--mobile">
                      <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                          <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                              Umum
                            </h3>
                          </div>
                        </div>
                      </div>
                      <div class="m-portlet__body">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group m-form__group row">
                                  <?php 
                                    foreach ($pengaturan1 as $data) { 
                                    $id=$data['id_setting'];
                                    if($this->input->post('is_submitted')){
                                        $status = $set_value['status_HET']; 
                                        $prod_jawa = $set_value['prod_jawa'];
                                        $prod_luarjawa = $set_value['prod_luarjawa'];

                                    }
                                    else {
                                        $status=$data['status_HET'];
                                        $prod_jawa = $data['produktivitas_jawa'];
                                        $prod_luarjawa = $data['produktivitas_luarjawa'];

                                        if($status=='YA') {
                                          $pend_stab='Harga Eceran Tertinggi';
                                        } else  {
                                          $pend_stab = 'Standar Deviasi 12 Bulan';
                                        }
                                    }
                                    }
                                  ?>
                                  <div class="col-md-4">
                                      <label>Pendekatan Stabilitas</label>
                                      <input type="text" value="<?php echo $pend_stab?>" class="form-control" disabled="" ">
                                  </div>
                                  <div class="col-md-4">
                                      <label>Produktivitas Produksi Jawa</label>
                                      <input type="text" class="form-control" value ="<?php echo $prod_jawa;?> Ton" disabled="" ">
                                  </div>
                                  <div class="col-md-4">
                                      <label>Produktivitas Produksi Luar Jawa</label>
                                      <input type="text" class="form-control" value ="<?php echo $prod_luarjawa;?> Ton" disabled="" ">
                                  </div>
                              </div>
                              <div class="m-form__actions">
                                  <button data-toggle="modal" data-target="#edit" class="btn m-btn m-btn--pill m-btn--air m-btn--gradient-from-danger m-btn--gradient-to-warning pull-right">Edit</button>
                              </div>
                              <div class="modal inmodal fade" id="edit" tabindex="-1" role="dialog"  aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                              <h3 class="modal-title">Edit Pengaturan</h3><br>
                                          </div>
                                          <div class="modal-body">                
                                            <div class="row">
                                                    <div class="col-md-12">
                                                      <form action="<?php echo base_url('user/editSetting/'.$id);?>" method="post">
                                                      <div class="form-group m-form__group row">
                                                          <div class="col-md-12">
                                                              <label>Pendekatan Stabilitas</label>
                                                              <select name="status_HET" required="true" class="form-control m-input m-input--air">
                                                                  <option value="YA" <?php if($pend_stab=='Harga Eceran Tertinggi') echo "selected";?>>Harga Eceran Tertinggi</option>
                                                                  <option value="TIDAK" <?php if($pend_stab=='Standar Deviasi 12 Bulan') echo "selected";?>>Standar Deviasi 12 Bulan</option>
                                                              </select>
                                                          </div>
                                                      </div>
                                                      <div class="form-group m-form__group row">
                                                        <div class="col-md-12">
                                                              <label>Produktivitas Produksi Jawa (Ton)</label>
                                                              <input type="number" step="any" name="prod_jawa" class="form-control" value ="<?php echo $prod_jawa;?>">
                                                          </div>
                                                      </div>
                                                      <div class="form-group m-form__group row">
                                                          <div class="col-md-12">
                                                              <label>Produktivitas Produksi Luar Jawa (Ton)</label>
                                                              <input type="number" step="any" name="prod_luarjawa" class="form-control" value ="<?php echo $prod_luarjawa;?>">
                                                          </div>
                                                      </div>
                                                      <div class="m-form__actions">
                                                              <button type="button" data-dismiss="modal" class="btn m-btn--pill m-btn m-btn--gradient-from-danger m-btn--gradient-to-warning" value="batal">Batal</button>
                                                          <button class="btn m-btn--pill m-btn m-btn--gradient-from-primary m-btn--gradient-to-info" type="submit">Simpan</button>
                                                          </div>
                                                      </form>  
                                                    </div>
                                                  </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </div>
                  </div>
                    <div class="m-portlet m-portlet--mobile">
                      <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                          <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                              Harga Eceran Tertinggi
                            </h3>
                          </div>
                        </div>
                      </div>
                      <div class="m-portlet__body">
                              <div class="table-responsive">
                                    <table class="table table-hover widht:40px" >
                                          <thead>
                                              <tr>      
                                                  <th>No</th>  
                                                  <th>Provinsi</th>      
                                                  <th>Harga Eceran Tertinggi</th>
                                                  <th>Aksi</th>                   
                                              </tr>      
                                          </thead>

                                          <tbody>                         
                                                <?php 
                                                  $no = 1; 
                                                  foreach ($pengaturan2 as $data) { 
                                                    $idProv=$data['id_provinsi'];
                                                    if($this->input->post('is_submitted')){
                                                        $HET = $set_value['HET'];
                                                    }
                                                    else {
                                                        $HET=$data['HET'];
                                                    }
                                                  ?>
                                                  <tr>
                                                    <td>
                                                      <?php echo $no;?>
                                                    </td>
                                                    <td>
                                                      <?php echo $data['provinsi']; ?>
                                                    </td>
                                                    <td>
                                                      <?php echo "Rp ". number_format($data['HET'],0,".",".");?>
                                                    </td>
                                                    <td>
                                                        <button data-toggle="modal" data-target="#editHET<?php echo $idProv?>" class="btn m-btn m-btn--pill m-btn--air m-btn--gradient-from-danger m-btn--gradient-to-warning">Edit</button>
                                                    </td>
                                                  </tr>
                                                  <div class="modal inmodal fade" id="editHET<?php echo $idProv?>" tabindex="-1" role="dialog"  aria-hidden="true">
                                                  <div class="modal-dialog">
                                                      <div class="modal-content">
                                                          <div class="modal-header">
                                                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                                              <h3 class="modal-title">Edit HET</h3><br>
                                                          </div>
                                                          <div class="modal-body">                
                                                            <div class="row">
                                                                    <div class="col-md-12">
                                                                      <form action="<?php echo base_url('user/editHET/'.$idProv);?>" method="post">
                                                                      <div class="form-group m-form__group row">
                                                                          <div class="col-md-6">
                                                                              <label>Provinsi</label>
                                                                              <input type="text" class="form-control" value="<?php echo $data['provinsi']?>" readonly disabled>
                                                                          </div>
                                                                          <div class="col-md-6">
                                                                              <label>HET</label>
                                                                              <input type="text" name="HET" value="<?= $HET?>" class="form-control good" required="">
                                                                          </div>
                                                                      </div>
                                                                      <div class="m-form__actions">
                                                                              <button type="button" data-dismiss="modal" class="btn m-btn--pill m-btn m-btn--gradient-from-danger m-btn--gradient-to-warning" value="batal">Batal</button>
                                                                          <button class="btn m-btn--pill m-btn m-btn--gradient-from-primary m-btn--gradient-to-info" type="submit">Simpan</button>
                                                                          </div>
                                                                      </form>  
                                                                    </div>
                                                                  </div>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>
                                              <?php $no++; }?>
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
              </div>
            </div>
        </div>
      </div>
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
          <script type="text/javascript">
                $('.good').inputmask("numeric", {
                    radixPoint: ".",
                    groupSeparator: ",",
                    digits: 2,
                    autoGroup: true,
                    prefix: 'Rp ', //Space after $, this will not truncate the first character.
                    rightAlign: false,
                    oncleared: function () { self.Value(''); }
                });
            </script>
          <script type="text/javascript">
                $('.good2').inputmask("numeric", {
                    radixPoint: ".",
                    groupSeparator: ",",
                    digits: 4,
                    autoGroup: true,
                    suffix: ' Ton', //Space after $, this will not truncate the first character.
                    rightAlign: false,
                    oncleared: function () { self.Value(''); }
                });
            </script>
