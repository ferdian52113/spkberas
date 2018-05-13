
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
                                    FORECASTING
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
                                                Forecasting
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
                                                Forecast 1 Tahun
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <!--begin::Form-->
                                <form class="m-form m-form--fit m-form--label-align-right" action="<?php echo base_url('user/generateData')?>" method="POST">
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
                                                        <option value="2019" <?php if($tahun_pilih=='2019') echo ' selected'?>>2019</option>
                                                        <option value="2020" <?php if($tahun_pilih=='2020') echo ' selected'?>>2020</option>
                                                        <option value="2021" <?php if($tahun_pilih=='2021') echo ' selected'?>>2021</option>
                                                        <option value="2022" <?php if($tahun_pilih=='2022') echo ' selected'?>>2022</option>
                                                        <option value="2023" <?php if($tahun_pilih=='2023') echo ' selected'?>>2023</option>
                                                        <option value="2024" <?php if($tahun_pilih=='2024') echo ' selected'?>>2024</option>
                                                        <option value="2025" <?php if($tahun_pilih=='2025') echo ' selected'?>>2025</option>         
                                                    </select> 
                                                </div>
                                            </div>
                                    </div>
                                    <div class="m-portlet__foot m-portlet__foot--fit">
                                        <div class="m-form__actions">
                                            <button type="action" class="btn m-btn--pill m-btn m-btn--gradient-from-primary m-btn--gradient-to-info tombolRekomendasi">
                                                Forecast
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!--end::Form-->
                            </div>
                            <!--end::Portlet-->
                            </div>
                         </div>
                    </div>
                </div>
            </div>
            <?php include(APPPATH.'views\footer.php'); ?>
