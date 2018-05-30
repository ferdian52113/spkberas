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
                        Lihat Data
                      </span>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <!-- END: Subheader -->
          <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
              <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                  <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                      Data Aturan
                    </h3>
                  </div>
                </div>
              </div>
              <div class="m-portlet__body">
                <!--begin: Search Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                  <div class="row align-items-center">
                    <div class="col-xl-8 order-2 order-xl-1">
                      <div class="form-group m-form__group row align-items-center">
                        <div class="col-md-4">
                          <div class="m-input-icon m-input-icon--left">
                            <input type="text" class="form-control m-input" placeholder="Search..." id="generalSearch">
                            <span class="m-input-icon__icon m-input-icon__icon--left">
                              <span>
                                <i class="la la-search"></i>
                              </span>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                      <button data-toggle="modal" data-target="#tambahAturan" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                          <span>
                          <i class="la la-user-plus"></i>
                          <span>
                            Tambah Aturan
                          </span>
                        </span>
                      </button>
                      <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                  </div>
                </div>
                <table class="m-datatable" id="html_table" width="100%">
                  <thead>
                    <tr>
                      <th title="Field #1">
                        Kode
                      </th>
                      <th title="Field #3">
                        Kondisi Harga
                      </th>
                      <th title="Field #3">
                        Kondisi Musim
                      </th>
                      <th title="Field #3">
                        Kondisi Bencana Alam
                      </th>
                       <th title="Field #3">
                        Kondisi Hama
                      </th>
                      <th title="Field #3">
                        Aturan
                      </th>
                      <th title="Field #4">
                        Aksi
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                        $no = 0;
                        foreach ($aturan as $data) { 
                          if($this->input->post('is_submitted')){
                              $kode_aturan = $set_value['id_aturan'];
                              $kondisi_harga   = $set_value('kondisi_harga');
                              $kondisi_musim   = $set_value('kondisi_musim');
                              $kondisi_bencana   = $set_value('kondisi_bencana');
                              $kondisi_hama   = $set_value('kondisi_hama');    
                              $rekomen      = $set_value('rekomendasi');
                          }
                          else {
                              $kode_aturan=$data['id_aturan'];
                              $kondisi_harga = $data['kondisi_harga'];
                              $kondisi_musim = $data['kondisi_musim'];
                              $kondisi_bencana = $data['kondisi_bencana'];
                              $kondisi_hama = $data['kondisi_hama'];
                              $rekomen=$data['rekomendasi'];
                          }
                        ?>
                        <tr>
                          <td>
                            <?php echo $rule_based_system[$no]->id_aturan;?>
                          </td>
                          <td>
                            <?php echo $rule_based_system[$no]->kondisi_harga.' - '.$rule_based_system[$no]->stabilitas_harga;?>
                          </td>
                          <td>
                            <?php echo $rule_based_system[$no]->kondisi_musim.' - '.$rule_based_system[$no]->musim;?>
                          </td>
                          <td>
                            <?php echo $rule_based_system[$no]->kondisi_bencana.' - '.$rule_based_system[$no]->bencana;?>
                          </td>
                          <td>
                            <?php echo $rule_based_system[$no]->kondisi_hama.' - '.$rule_based_system[$no]->hama;?>
                          </td>
                          <td>
                            <?php echo $rule_based_system[$no]->nama_rekomendasi; ?>
                          </td>
                          <td>
                              <button data-toggle="modal" data-target="#editAturan<?php echo $kode_aturan?>" class="btn m-btn m-btn--pill m-btn--air m-btn--gradient-from-danger m-btn--gradient-to-warning">Edit</button>
                              <?=anchor('user/hapusAturan/'.$data['id_aturan'], 'Hapus', [
                              'class' => 'btn m-btn m-btn--pill m-btn--air m-btn--gradient-from-accent m-btn--gradient-to-success',
                              'role'  => 'button',
                              'onclick'=>'return confirm(\'Apakah anda yakin akan menghapus aturan?\')'
                            ])?>
                          </td>
                        </tr>
                        <div class="modal inmodal fade" id="editAturan<?php echo $kode_aturan?>" tabindex="-1" role="dialog"  aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                    <h3 class="modal-title">Edit Aturan</h3><br>
                                </div>
                                <div class="modal-body">                
                                  <div class="row">
                                          <div class="col-xl-12">
                                            <form action="<?php echo base_url('user/editAturan/'.$kode_aturan);?>" method="post">
                                            <div class="form-group m-form__group row">
                                                <div class="col-md-12">
                                                    <label>Kode Aturan</label>
                                                    <input type="text" name="id_aturan" value="<?= $kode_aturan?>" class="form-control" required="">
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Kondisi Harga</label>
                                                    <select name="kondisi_harga" class="form-control m-input m-input--air">
                                                      <option>--Pilih Kondisi--</option>
                                                      <?php foreach ($kondisi as $k) {
                                                        if($k['kategori_kondisi']=='Harga')
                                                        echo "<option value=".$k['id_kondisi'];
                                                        if($k['id_kondisi']==$kondisi_harga) echo " selected";
                                                        echo " >".$k['id_kondisi']."-".$k['nama_kondisi']."</option>";
                                                      } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Kondisi Musim</label>
                                                    <select name="kondisi_musim" class="form-control m-input m-input--air">
                                                      <option>--Pilih Kondisi--</option>
                                                      <?php foreach ($kondisi as $k) {
                                                        if($k['kategori_kondisi']=='Musim')
                                                        echo "<option value=".$k['id_kondisi'];
                                                        if($k['id_kondisi']==$kondisi_musim) echo " selected";
                                                        echo " >".$k['id_kondisi']."-".$k['nama_kondisi']."</option>";
                                                      } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Kondisi Bencana</label>
                                                    <select name="kondisi_bencana" class="form-control m-input m-input--air">
                                                      <option>--Pilih Kondisi--</option>
                                                      <?php foreach ($kondisi as $k) {
                                                        if($k['kategori_kondisi']=='Bencana Alam')
                                                        echo "<option value=".$k['id_kondisi'];
                                                        if($k['id_kondisi']==$kondisi_bencana) echo " selected";
                                                        echo " >".$k['id_kondisi']."-".$k['nama_kondisi']."</option>";
                                                      } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Kondisi Hama</label>
                                                    <select name="kondisi_bencana" class="form-control m-input m-input--air">
                                                      <option>--Pilih Kondisi--</option>
                                                      <?php foreach ($kondisi as $k) {
                                                        if($k['kategori_kondisi']=='Hama')
                                                        echo "<option value=".$k['id_kondisi'];
                                                        if($k['id_kondisi']==$kondisi_hama) echo " selected";
                                                        echo " >".$k['id_kondisi']."-".$k['nama_kondisi']."</option>";
                                                      } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Rekomendasi</label>
                                                    <select name="rekomendasi" class="form-control m-input m-input--air">
                                                      <option>--Pilih Rekomendasi--</option>
                                                      <?php foreach ($rekomendasi as $rek) {
                                                        echo "<option value=".$rek['id_rekomendasi'];
                                                        if($rek['id_rekomendasi']==$rekomen) echo " selected";
                                                        echo " >".$rek['id_rekomendasi']."-".$rek['nama_rekomendasi']."</option>";
                                                      } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="m-portlet__foot m-portlet__foot--fit">
                                                <div class="m-form__actions">
                                                    <button type="button" data-dismiss="modal" class="btn m-btn--pill m-btn m-btn--gradient-from-danger m-btn--gradient-to-warning" value="batal">Batal</button>
                                                <button class="btn m-btn--pill m-btn m-btn--gradient-from-primary m-btn--gradient-to-info" type="submit">Simpan</button>
                                                </div>
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
                <!--end: Datatable -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal inmodal fade" id="tambahAturan" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h3 class="modal-title">Tambah Aturan</h3><br>
                </div>
                <div class="modal-body">                
                  <div class="row">
                          <div class="col-xl-12">
                            <form action="<?php echo base_url('user/tambahAturan');?>" method="post">
                            <div class="form-group m-form__group row">
                                <div class="col-md-12">
                                    <label>Kode Aturan</label>
                                    <input type="text" name="id_aturan" class="form-control" required="">
                                </div>
                                <div class="col-md-12">
                                    <label>Kondisi Harga</label>
                                    <select name="kondisi_harga" class="form-control m-input m-input--air">
                                      <option>--Pilih Kondisi--</option>
                                      <?php foreach ($kondisi as $k) {
                                        if($k['kategori_kondisi']=='Harga')
                                        echo "<option value=".$k['id_kondisi'].">".$k['id_kondisi']."-".$k['nama_kondisi']."</option>";
                                      } ?>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label>Kondisi Musim</label>
                                    <select name="kondisi_musim" class="form-control m-input m-input--air">
                                      <option>--Pilih Kondisi--</option>
                                      <?php foreach ($kondisi as $k) {
                                        if($k['kategori_kondisi']=='Musim')
                                        echo "<option value=".$k['id_kondisi'].">".$k['id_kondisi']."-".$k['nama_kondisi']."</option>";
                                      } ?>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label>Kondisi Bencana Alam</label>
                                    <select name="kondisi_bencana" class="form-control m-input m-input--air">
                                      <option>--Pilih Kondisi--</option>
                                      <?php foreach ($kondisi as $k) {
                                        if($k['kategori_kondisi']=='Bencana Alam')
                                        echo "<option value=".$k['id_kondisi'].">".$k['id_kondisi']."-".$k['nama_kondisi']."</option>";
                                      } ?>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label>Kondisi Hama</label>
                                    <select name="kondisi_hama" class="form-control m-input m-input--air">
                                      <option>--Pilih Kondisi--</option>
                                      <?php foreach ($kondisi as $k) {
                                        if($k['kategori_kondisi']=='Hama')
                                        echo "<option value=".$k['id_kondisi'].">".$k['id_kondisi']."-".$k['nama_kondisi']."</option>";
                                      } ?>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label>Rekomendasi</label>
                                    <select name="rekomendasi" class="form-control m-input m-input--air">
                                      <option>--Pilih Rekomendasi--</option>
                                      <?php foreach ($rekomendasi as $rek) {
                                        echo "<option value=".$rek['id_rekomendasi'].">".$rek['id_rekomendasi']."-".$rek['nama_rekomendasi']."</option>";
                                      } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="m-portlet__foot m-portlet__foot--fit">
                                <div class="m-form__actions">
                                    <button type="button" data-dismiss="modal" class="btn m-btn--pill m-btn m-btn--gradient-from-danger m-btn--gradient-to-warning" value="batal">Batal</button>
                                <button class="btn m-btn--pill m-btn m-btn--gradient-from-primary m-btn--gradient-to-info" type="submit">Simpan</button>
                                </div>
                            </div>
                            </form>  
                          </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
<!-- end:: Body -->
            <?php include(APPPATH.'views\footer.php'); ?>
