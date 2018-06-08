<?php
if($this->input->post('is_submitted')){
    $harga      = $set_value('harga');
    $produksi   = $set_value('produksi');
    $luastanam   = $set_value('luastanam');
    $curahhujan   = $set_value('curahhujan');
    $banjir   = $set_value('banjir');
    $hama   = $set_value('hama');
}
else {
    if ($kategori=='Prediksi') {
    $id=$data->id_prediksi;
    $harga = $data->prediksi_harga;
    $produksi = $data->prediksi_produksi;
    $luastanam = $data->prediksi_luastanam;
    $curahhujan = $data->prediksi_curahhujan;
    $banjir = $data->prediksi_banjir;
    $hama = $data->prediksi_hama;
    }
    else {
        $id=$data->id_aktual;
        $harga = $data->aktual_harga;
        $produksi = $data->aktual_produksi;
        $luastanam = $data->aktual_luastanam;
        $curahhujan = $data->aktual_curahhujan;
        $banjir = $data->aktual_banjir;
        $hama = $data->aktual_hama;
    }
    $bulan = $data->bulan;
    $tahun = $data->tahun;
    $provinsi = $data->provinsi;
}
?>
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
                                                Edit Data
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
                                                Edit Data Beras
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <!--begin::Form-->
                                <form class="m-form m-form--fit m-form--label-align-right" action="<?php echo base_url("user/editData/$kategori/$id")?>" method="POST">
                                    <div class="m-portlet__body">
                                        <div class="form-group m-form__group row">
                                                <div class="col-md-3">
                                                    <label>Kategori</label><br>
                                                    <select name="kategori" id="kategori" required="true" class="form-control m-input m-input--air" disabled="">
                                                        <option value="<?php echo $kategori?>"><?php echo $kategori?></option>                  
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Provinsi</label><br>
                                                    <select name="prov" required="true" class="form-control  m-input m-input--air" disabled="">
                                                        <option value="<?php echo $provinsi?>"><?php echo $provinsi?></option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Tahun</label><br>
                                                    <select name="tahun" required="true" class="form-control m-input m-input--air" disabled="">
                                                        <option value="<?php echo $tahun?>"><?php echo $tahun?></option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label>Bulan</label><br>
                                                    <select name="bulan" required="true" class="form-control m-input m-input--air" disabled="">
                                                        <option value="<?php echo $bulan?>"><?php echo $bulan?></option>
                                                    </select>
                                                </div>
                                        </div>
                                        <hr>
                                        <div class="form-group m-form__group row">
                                                <div class="col-md-4">
                                                    <label>Harga (Rp)</label><br>
                                                    <input type="text" name="harga" class="form-control good" value="<?= $harga?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Produksi (Ton)</label><br>
                                                    <input type="number" step="any" name="produksi" class="form-control" value="<?= $produksi?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Luas Tanam (Ha)</label><br>
                                                    <input type="number" step="any" name="luastanam" class="form-control" value="<?= $luastanam?>">
                                                </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                                <div class="col-md-4">
                                                    <label>Curah Hujan (mm)</label><br>
                                                    <input type="number" step="any" name="curahhujan" class="form-control" value="<?= $curahhujan?>">
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Terdapat Bencana Banjir</label><br>
                                                    <select name="banjir" required="true" class="form-control m-input m-input--air">
                                                        <option <?php if($banjir=='1') {echo " selected ";} ?>value="1">Ya</option>
                                                        <option <?php if($banjir=='0') {echo " selected ";} ?>value="0">Tidak</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label>Luas Terkena Hama (Ha)</label><br>
                                                    <input type="number" step="any" name="hama" class="form-control" value="<?= $hama?>">
                                                </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__foot m-portlet__foot--fit">
                                        <div class="m-form__actions">
                                            <a href="<?php echo base_url()?>user/lihatData"><button type="button" name="submit" class="btn m-btn--pill m-btn m-btn--gradient-from-danger m-btn--gradient-to-warning" value="batal">Batal</button></a>
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

