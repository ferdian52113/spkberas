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
                                                Input Data
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
                                                Input Data Beras
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <!--begin::Form-->
                                <form class="m-form m-form--fit m-form--label-align-right" action="<?php echo base_url('user/inputData')?>" method="POST">
                                    <div class="m-portlet__body">
                                        <div class="form-group m-form__group row">
                                        <?php echo $this->session->flashdata('msg'); ?>
                                        </div>
                                        <div class="form-group m-form__group row">
                                                <div class="col-md-3">
                                                    <label>Kategori</label><br>
                                                    <select name="kategori" id="kategori" required="true" class="form-control m-input m-input--air">
                                                        <option value="">-Pilih Kategori-</option>
                                                        <option value="Aktual">Aktual</option>
                                                        <option value="Prediksi">Prediksi</option>                  
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Provinsi</label><br>
                                                    <select name="prov" required="true" class="form-control  m-input m-input--air">
                                                        <option value="">-Pilih Provinsi-</option>
                                                        <?php if($this->session->userdata('role')==0)
                                                            foreach ($provinsi as $prov) {                   
                                                            echo '<option value="'.$prov['provinsi'].'">'.$prov['provinsi'].'</option>';
                                                        } else { ?>
                                                        <option value="<?php echo $this->session->userdata('prov')?>"><?php echo $this->session->userdata('prov')?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Tahun</label><br>
                                                    <select name="tahun" required="true" class="form-control m-input m-input--air">
                                                        <option value="">-Pilih Tahun-</option>
                                                        <option value="2017">2017</option>
                                                        <option value="2018">2018</option>
                                                        <option value="2019">2019</option>
                                                        <option value="2020">2020</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Bulan</label><br>
                                                    <select name="bulan" required="true" class="form-control m-input m-input--air">
                                                        <option value="">-Pilih Bulan-</option>
                                                        <?php foreach ($bulan as $bulan) {                   
                                                            echo '<option value="'.$bulan['bulan'].'">'.$bulan['bulan'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                                <div class="col-md-6">
                                                    <label>Harga (Rp)</label><br>
                                                    <input type="text" name="harga" class="form-control good">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Produksi (Ton)</label><br>
                                                    <input type="text" name="produksi" class="form-control good2">
                                                </div>
                                            </div>
                                    </div>
                                    <div class="m-portlet__foot m-portlet__foot--fit">
                                        <div class="m-form__actions">
                                            <a href="<?php echo base_url()?>user"><button type="button" name="submit" class="btn m-btn--pill m-btn m-btn--gradient-from-danger m-btn--gradient-to-warning" value="batal">Batal</button></a>
                                        <button class="btn m-btn--pill m-btn m-btn--gradient-from-primary m-btn--gradient-to-info" type="submit">Simpan</button>
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
            <!-- end:: Body -->
            <?php include(APPPATH.'views\footer.php'); ?>

            <script type='text/javascript' src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>

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
                    digits: 2,
                    autoGroup: true,
                    suffix: ' Ton', //Space after $, this will not truncate the first character.
                    rightAlign: false,
                    oncleared: function () { self.Value(''); }
                });
            </script>

