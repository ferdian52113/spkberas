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
                                                Input Data Beras
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <!--begin::Form-->
                                <form class="m-form m-form--fit m-form--label-align-right" action="<?php echo base_url('user/inputData')?>" method="POST">
                                    <div class="m-portlet__body">
                                        <div class="form-group m-form__group row">
                                                <div class="col-md-4">
                                                    <label>Provinsi</label><br>
                                                    <select name="prov" required="true" class="form-control  m-input m-input--air">
                                                        <option value="">-Pilih Provinsi-</option>
                                                        <?php foreach ($provinsi as $prov) {                   
                                                            echo '<option value="'.$prov['provinsi'].'">'.$prov['provinsi'].'</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
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
                                        <div class="form-group m-form__group">
                                            <label for="exampleInputEmail1">
                                                Email address
                                            </label>
                                            <input type="email" class="form-control m-input m-input--air" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                                            <span class="m-form__help">
                                                We'll never share your email with anyone else.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="m-portlet__foot m-portlet__foot--fit">
                                        <div class="m-form__actions">
                                            <button type="reset" class="btn btn-accent">
                                                Submit
                                            </button>
                                            <button type="reset" class="btn btn-secondary">
                                                Cancel
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
            <!-- end:: Body -->
            <?php include(APPPATH.'views\footer.php'); ?>

