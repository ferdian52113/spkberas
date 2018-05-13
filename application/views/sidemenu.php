<div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark m-aside-menu--dropdown " data-menu-vertical="true" data-menu-dropdown="true" data-menu-scrollable="true" data-menu-dropdown-timeout="500">
    <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
        <li class="m-menu__item  m-menu__item--active" aria-haspopup="true" >
            <a  href="<?php echo base_url('user');?>" class="m-menu__link ">
                <?php if ($halaman=='Dashboard') { ?>
                <span class="m-menu__item-here"></span>
                <?php } ?>
                <i class="m-menu__link-icon flaticon-dashboard"></i>
                <span class="m-menu__link-text">
                    Dashboard
                </span>
            </a>
        </li>
        <li class="m-menu__item  m-menu__item--active" aria-haspopup="true" >
            <a  href="<?php echo base_url('user/rekomendasi');?>" class="m-menu__link ">
                <?php if ($halaman=='Rekomendasi') { ?>
                <span class="m-menu__item-here"></span>
                <?php } ?>
                <i class="m-menu__link-icon flaticon-information"></i>
                <span class="m-menu__link-text">
                    Rekomendasi
                </span>
            </a>
        </li>
        <li class="m-menu__item  m-menu__item--active" aria-haspopup="true" >
            <a  href="<?php echo base_url('user/lihatData');?>" class="m-menu__link ">
                <?php if ($halaman=='Lihat Data') { ?>
                <span class="m-menu__item-here"></span>
                <?php } ?>
                <i class="m-menu__link-icon flaticon-file"></i>
                <span class="m-menu__link-text">
                    Lihat Data
                </span>
            </a>
        </li>
        <li class="m-menu__item  m-menu__item--active" aria-haspopup="true" >
            <a  href="<?php echo base_url('user/inputData');?>" class="m-menu__link ">
                <?php if ($halaman=='Entri Data') { ?>
                <span class="m-menu__item-here"></span>
                <?php } ?>
                <i class="m-menu__link-icon flaticon-add"></i>
                <span class="m-menu__link-text">
                    Entri Data
                </span>
            </a>
        </li>
        <li class="m-menu__item  m-menu__item--active" aria-haspopup="true" >
            <a  href="<?php echo base_url('user/generateData');?>" class="m-menu__link ">
                <?php if ($halaman=='Generate') { ?>
                <span class="m-menu__item-here"></span>
                <?php } ?>
                <i class="m-menu__link-icon flaticon-line-graph"></i>
                <span class="m-menu__link-text">
                    Forecast Data
                </span>
            </a>
        </li>
        <?php if($this->session->userdata('role')==0) {?>
        <li class="m-menu__item  m-menu__item--active" aria-haspopup="true"  data-menu-submenu-toggle="hover">
            <a  href="#" class="m-menu__link "   style="background: #282a3a">
                <?php if ($halaman=='Pengetahuan') { ?>
                <span class="m-menu__item-here"></span>
                <?php } ?>
                <i class="m-menu__link-icon flaticon-pie-chart" style="color:#bf2d46 !important"></i>
                <span class="m-menu__link-text"  style="color: white;">
                    Basis Pengetahuan
                </span>
            </a>
            <div class="m-menu__submenu">
                <span class="m-menu__arrow"></span>
                <ul class="m-menu__subnav">
                    <li class="m-menu__item " aria-haspopup="true" >
                        <a  href="<?php echo base_url('user/dataKondisi')?>" class="m-menu__link ">
                            <i class="m-menu__link-icon flaticon-pie-chart" style="color:#bf2d46 !important"></i>
                            <span class="m-menu__link-text" style="color: white;">
                                Kondisi
                            </span>
                        </a>
                    </li>
                    <li class="m-menu__item " aria-haspopup="true"  data-redirect="true">
                        <a  href="<?php echo base_url('user/dataRekomendasi')?>" class="m-menu__link ">
                            <i class="m-menu__link-icon flaticon-line-graph" style="color:#bf2d46 !important"></i>
                            <span class="m-menu__link-text" style="color: white;">
                                Rekomendasi
                            </span>
                        </a>
                    </li>
                    <li class="m-menu__item " aria-haspopup="true"  data-redirect="true">
                        <a  href="<?php echo base_url('user/dataAturan')?>" class="m-menu__link ">
                            <i class="m-menu__link-icon flaticon-statistics" style="color:#bf2d46 !important"></i>
                            <span class="m-menu__link-text" style="color: white;">
                                Aturan
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <?php } ?>
        <?php if($this->session->userdata('role')==0) {?>
        <li class="m-menu__item  m-menu__item--active" aria-haspopup="true" >
            <a  href="<?php echo base_url('user/manajemenUser');?>" class="m-menu__link ">
                <?php if ($halaman=='User') { ?>
                <span class="m-menu__item-here"></span>
                <?php } ?>
                <i class="m-menu__link-icon flaticon-user"></i>
                <span class="m-menu__link-text">
                    Manage User
                </span>
            </a>
        </li>
        <?php } ?>
        <li class="m-menu__item  m-menu__item--active" aria-haspopup="true" >
            <a  href="<?php echo base_url('user/setting');?>" class="m-menu__link ">
                <?php if ($halaman=='Setting') { ?>
                <span class="m-menu__item-here"></span>
                <?php } ?>
                <i class="m-menu__link-icon flaticon-settings"></i>
                <span class="m-menu__link-text">
                    Pengaturan
                </span>
            </a>
        </li>
        <li class="m-menu__item  m-menu__item--active" aria-haspopup="true" >
            <a  href="<?php echo base_url('user/logout');?>" class="m-menu__link ">
                <i class="m-menu__link-icon flaticon-logout"></i>
                <span class="m-menu__link-text">
                    Log Out
                </span>
            </a>
        </li>
    </ul>
    </div>