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
                      Data User
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
                      <button data-toggle="modal" data-target="#tambahUser" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                          <span>
                          <i class="la la-user-plus"></i>
                          <span>
                            Tambah User
                          </span>
                        </span>
                      </button>
                      <div class="m-separator m-separator--dashed d-xl-none"></div>
                    </div>
                  </div>
                </div>
                <!--end: Search Form -->
    <!--begin: Datatable -->
                <table class="m-datatable" id="html_table" width="100%">
                  <thead>
                    <tr>
                      <th title="Field #2">
                        Lembaga
                      </th>
                      <th title="Field #3">
                        Username
                      </th>
                      <th title="Field #4">
                        Password
                      </th>
                      <th title="Field #5">
                        Role
                      </th>
                      <th title="Field #6">
                        Provinsi
                      </th>
                      <th title="Field #7">
                        Aksi
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                        $no = 0;
                        foreach ($user as $data) {
                        $id_user=$data['id_user']; 
                        if($this->input->post('is_submitted')){
                            $lembaga = $set_value['lembaga'];
                            $username      = $set_value('username');
                            $password   = $set_value('password');
                            $role   = $set_value('role');
                            $loc   = $set_value('provinsi');
                        }
                        else {
                            $lembaga=$data['lembaga'];
                            $username=$data['username'];
                            $password = $data['password'];
                            $role = $data['role'];
                            $loc = $data['provinsi'];
                        }
                    ?>
                        <tr>
                          <td>
                            <?php echo $data['lembaga']; ?>
                          </td>
                          <td>
                            <?php echo $data['username'];?>
                          </td>
                          <td>
                            <?php echo $data['password'];?>
                          </td>
                          <td>
                            <?php if($data['role']==0) { echo "Admin Pusat"; } else { echo "Admin Provinsi";}?>    
                          </td>
                          <td>
                            <?php echo $data['provinsi'];?>
                          </td>
                          <td>
                             <button data-toggle="modal" data-target="#editUser<?php echo $id_user?>" class="btn m-btn m-btn--pill m-btn--air m-btn--gradient-from-danger m-btn--gradient-to-warning">Edit</button>
                            <?=anchor('user/hapusUser/'.$data['id_user'], 'Hapus', [
                              'class' => 'btn m-btn m-btn--pill m-btn--air m-btn--gradient-from-accent m-btn--gradient-to-success',
                              'role'  => 'button',
                              'onclick'=>'return confirm(\'Apakah anda yakin akan menghapus user?\')'
                            ])?>
                          </td>
                        </tr>
                      <div class="modal inmodal fade" id="editUser<?php echo $id_user?>" tabindex="-1" role="dialog"  aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                    <h3 class="modal-title">Edit User</h3><br>
                                </div>
                                <div class="modal-body">                
                                  <div class="row">
                                          <div class="col-xl-12">
                                            <form action="<?php echo base_url('user/editUser/'.$id_user);?>" method="post">
                                            <div class="form-group m-form__group row">
                                                <div class="col-md-12">
                                                    <label>Lembaga</label>
                                                    <input type="text" name="lembaga" value="<?= $lembaga?>" class="form-control" required="">
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Nama Kondisi</label>
                                                    <input type="text" name="username" value="<?= $username?>" class="form-control" required="">
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Password</label>
                                                    <input type="password" name="password" value="<?= $password?>" class="form-control" required="">
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Role</label>
                                                    <select name="role" required="true" class="form-control m-input m-input--air">
                                                        <option value="0" <?php if($role==0) echo "selected";?>>Admin Pusat</option>
                                                        <option value="1" <?php if($role==1) echo "selected";?>>Admin Provinsi</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Provinsi</label>
                                                    <select name="provinsi" required="true" class="form-control m-input m-input--air">
                                                        <?php foreach ($provinsi as $prov) {
                                                          if($prov['provinsi'] == $loc){ 
                                                              echo '<option selected="selected" value="'.$prov['id_provinsi'].'">'.$prov['provinsi'].'</option>';
                                                            
                                                          }
                                                          else {
                                                            echo '<option value="'.$prov['id_provinsi'].'">'.$prov['provinsi'].'</option>';
                                                          }

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
                    <?php }?>
                  </tbody>
                </table>
                <!--end: Datatable -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal inmodal fade" id="tambahUser" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h3 class="modal-title">Tambah User</h3><br>
                </div>
                <div class="modal-body">                
                  <div class="row">
                          <div class="col-xl-12">
                            <form action="<?php echo base_url('user/tambahUser');?>" method="post">
                            <div class="form-group m-form__group row">
                                <div class="col-md-12">
                                    <label>Nama Lembaga</label>
                                    <input type="text" name="nama_lembaga" class="form-control" required="">
                                </div>
                                <div class="col-md-12">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" required="">
                                </div>
                                <div class="col-md-12">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" required="">
                                </div>
                                <div class="col-md-12">
                                    <label>Role</label>
                                    <select name="role" required="true" class="form-control m-input m-input--air">
                                        <option value="0">Admin Pusat</option>
                                        <option value="1">Admin Provinsi</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label>Provinsi</label>
                                    <select name="prov" class="form-control  m-input m-input--air">
                                        <option value="">-Pilih Provinsi-</option>
                                        <?php 
                                            foreach ($provinsi as $prov) {                   
                                            echo '<option value="'.$prov['id_provinsi'].'">'.$prov['provinsi'].'</option>';
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
