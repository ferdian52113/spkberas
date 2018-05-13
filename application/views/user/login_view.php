<!DOCTYPE html>

<html lang="en" >
    <!-- begin::Head -->
    <head>
        <meta charset="utf-8" />
        <title>
            DSS | BERAS
        </title>
        <meta name="description" content="Latest updates and statistic charts">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!--begin::Web font -->
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        <script>
          WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>
        <!--end::Web font -->
        <!--begin::Base Styles -->
        <link href="<?php echo base_url()?>assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url()?>assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />
        <!--end::Base Styles -->
        <link rel="shortcut icon" href="<?php echo base_url()?>assets/demo/default/media/img/logo/favicon.ico" />
    </head>
    <!-- end::Head -->
    <!-- end::Body -->
    <body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
        <!-- begin:: Page -->
        <div class="m-grid m-grid--hor m-grid--root m-page">
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--singin m-login--2 m-login-2--skin-2" id="m_login" style="background-image: url(<?php echo base_url()?>assets/app/media/img//bg/bg-3.jpg);">
                <div class="m-grid__item m-grid__item--fluid    m-login__wrapper">
                    <div class="m-login__container">
                        <div class="m-login__logo">
                            <a href="#">
                                <img src="<?php echo base_url()?>assets/logo.png" class="img-responsive text-center" style="margin: 0 10px auto;height: 100px; display: inline;">
                                <img src="<?php echo base_url()?>assets/logo_rdib.png" class="img-responsive text-center" style="margin: 0 10px auto;height: 100px; display: inline;">
                            </a>
                        </div>
                        <div class="m-login__signin">
                            <div class="m-login__head">
                                <h3 class="m-login__title">
                                    LABORATORIUM REKAYASA DATA DAN INTELEGENSI BISNIS
                                </h3>
                            </div>
                            <form class="m-login__form m-form" action="<?php echo base_url('login/proses')?>" method="POST">
                                <div class="form-group m-login__form-sub">
                                    <?php if(isset($err)) { ?>
                                        <div class="text-center fadeIn animated m-alert m-alert--outline alert alert-danger alert-dismissible" role="alert">            
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>           
                                            <span>Username atau password salah. Silahkan coba lagi</span>
                                        </div>
                                <?php } ?>
                                </div>
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input"   type="text" placeholder="Username" name="username" autocomplete="off">
                                </div>
                                <div class="form-group m-form__group">
                                    <input class="form-control m-input m-login__form-input--last" type="password" placeholder="Password" name="password">
                                </div>
                                
                                <div class="m-login__form-action">
                                    <button class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary" type="submit">
                                        Masuk
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end:: Page -->
        <!--begin::Base Scripts -->
        <script src="<?php echo base_url()?>assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
        <script src="<?php echo base_url()?>assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>
        <!--end::Base Scripts -->   
        <!--begin::Page Snippets -->
        <script src="<?php echo base_url()?>assets/snippets/pages/user/login.js" type="text/javascript"></script>
        <!--end::Page Snippets -->
    </body>
    <!-- end::Body -->
</html>
