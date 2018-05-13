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
                    <div class="m-content">
                        <div class="row">
                          <div class="col-xl-12">
                            <form action="<?php echo base_url('user');?>" method="post">
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
                            <div class="col-md-12">
                                <div class="m-portlet m-portlet--tab">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <span class="m-portlet__head-icon m--hide">
                                                    <i class="la la-gear"></i>
                                                </span>
                                                <h3 class="m-portlet__head-text">
                                                    Grafik Perbandingan <b>Data Prediksi</b> dan <b>Data Aktual</b> Harga Beras
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <?php
                                            if($aktual){ 
                                                $data_aktual1 = '';
                                                foreach ($data_aktual_setaun as $row) {
                                                    $data_aktual1 .= $row['aktual_harga']. ", ";
                                                }
                                            }
                                            if($prediksi){ 
                                                $data_prediksi1 = '';
                                                foreach ($data_prediksi_setaun as $row) {
                                                    $data_prediksi1 .= $row['prediksi_harga']. ", ";
                                                }
                                            }
                                        ?>
                                        <canvas id="line-chart" style="width:100%; height: 250px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-md-6">
                                <div class="m-portlet m-portlet--tab">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <span class="m-portlet__head-icon m--hide">
                                                    <i class="la la-gear"></i>
                                                </span>
                                                <h3 class="m-portlet__head-text">
                                                    Peringkat <b>Harga</b> Nasional
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body ">
                                    <!--begin: Datatable -->
                                        <table class="table table-hover dataTables-example widht:40px" style=" font-size:15px">
                                          <thead style="background-color: #d8fe01;" >
                                            <tr>
                                              <th style="width:20%">
                                                No
                                              </th>
                                              <th style="width:50%">
                                                Provinsi
                                              </th>
                                              <th style="width:30%">
                                                Harga
                                              </th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <?php 
                                                $no = 0;
                                                if($rank_harga) {
                                                foreach ($rank_harga as $data) { ?>
                                                <tr <?php if($data['provinsi']==$provinsi_pilih) { echo 'style="background-color: orange"';}?>>
                                                  <td style="width:20%">
                                                    <?php $no++; echo $no;?>
                                                  </td>
                                                  <td style="width:50%">
                                                    <?php echo $data['provinsi']; ?>
                                                  </td>
                                                  <td style="width:30%">
                                                    <?php echo "Rp " . number_format($data['harga'],0,".","."); ?>
                                                  </td>
                                                </tr>
                                            <?php } }?>
                                          </tbody>
                                        </table>
                                        <!--end: Datatable -->
                                    </div>
                                </div>
                             </div>

                             <div class="col-md-6">
                                <div class="m-portlet m-portlet--tab">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <span class="m-portlet__head-icon m--hide">
                                                    <i class="la la-gear"></i>
                                                </span>
                                                <h3 class="m-portlet__head-text">
                                                    Peringkat <b>Produksi</b> Nasional
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <!--begin: Datatable -->
                                        <table class="table table-hover dataTables-example widht:40px" style=" font-size:15px">
                                          <thead style="background-color: #d8fe01;" >
                                            <tr>
                                              <th style="width:20%">
                                                No
                                              </th>
                                              <th style="width:50%">
                                                Provinsi
                                              </th>
                                              <th style="width:30%">
                                                Harga
                                              </th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <?php 
                                                $no = 0;
                                                if($rank_produksi) {
                                                foreach ($rank_produksi as $data) { ?>
                                                <tr <?php if($data['provinsi']==$provinsi_pilih) { echo 'style="background-color: orange"';}?>>
                                                  <td style="width:20%">
                                                    <?php $no++; echo $no;?>
                                                  </td>
                                                  <td style="width:50%">
                                                    <?php echo $data['provinsi']; ?>
                                                  </td>
                                                  <td style="width:30%">
                                                    <?php echo number_format($data['produksi'],0,".","."); ?> Ton
                                                  </td>
                                                </tr>
                                            <?php } }?>
                                          </tbody>
                                        </table>
                                        <!--end: Datatable -->
                                    </div>
                                </div>
                             </div>
                         </div>
                          <div class="row">
                                 <div class="col-md-12">
                                    <div class="m-portlet m-portlet--tab">
                                        <div class="m-portlet__head">
                                            <div class="m-portlet__head-caption">
                                                <div class="m-portlet__head-title">
                                                    <span class="m-portlet__head-icon m--hide">
                                                        <i class="la la-gear"></i>
                                                    </span>
                                                    <h3 class="m-portlet__head-text">
                                                        <b>GRAFIK PERBANDINGAN PRODUKSI AKTUAL DAN PREDIKSI</b>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="m-portlet__body ">
                                            <?php
                                                if($aktual){ 
                                                    $data_aktual1 = '';
                                                    $data_aktual2 = '';
                                                    foreach ($data_aktual_setaun as $row) {
                                                        $data_aktual1 .= $row['aktual_harga']. ", ";
                                                        $data_aktual2 .= $row['aktual_produksi']. ", ";
                                                    }
                                                }
                                                if($prediksi){ 
                                                    $data_prediksi1 = '';
                                                    $data_prediksi2 = '';
                                                    foreach ($data_prediksi_setaun as $row) {
                                                        $data_prediksi1 .= $row['prediksi_harga']. ", ";
                                                        $data_prediksi2 .= $row['prediksi_produksi']. ", ";
                                                    }
                                                }
                                            ?>

                                            <canvas id="myChart2" style="width: 500px; height: 50%"></canvas>
                                        </div>
                                    </div>
                                 </div>
                             </div>
                         <div class="row">
                            <div class="col-md-6">
                                <div class="m-portlet m-portlet--tab">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <span class="m-portlet__head-icon m--hide">
                                                    <i class="la la-gear"></i>
                                                </span>
                                                <h3 class="m-portlet__head-text">
                                                    Peta Grafik <b>Pergerakan Harga</b> per Provinsi di Indonesia
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <div id="grafik_harga" style="width: 500px; height: 50%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="m-portlet m-portlet--tab">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <span class="m-portlet__head-icon m--hide">
                                                    <i class="la la-gear"></i>
                                                </span>
                                                <h3 class="m-portlet__head-text">
                                                    Peta Grafik <b>Pergerakan Produksi</b> per Provinsi di Indonesia
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <div id="grafik_produksi" style="width: 500px; height: 50%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php include(APPPATH.'views\footer.php'); ?>
            <!-- google map js -->
            <script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
            <!-- google chartjs api -->
            <script type="text/javascript" src="https://www.google.com/jsapi"></script>
            <!-- end:: Body -->
            <script>
            var ctx = document.getElementById("line-chart");
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [
                    <?php if($prediksi) { ?> 
                    {
                        label: 'Prediksi',
                        data: [
                        <?php
                        echo $data_prediksi1;
                        ?>
                        ],
                        borderWidth: 3,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 1)',
                        fill: false,
                    },
                    <?php } ?> 
                    <?php if($aktual) { ?> 
                    {
                        label: 'Aktual',
                        data: [
                        <?php
                        echo $data_aktual1;
                        ?>
                        ],
                        borderWidth: 3,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 1)',
                        fill: false,
                    }<?php } ?> ]
                },
                options: {
                    elements: {
                        line: {
                            tension: 0.000001
                        }
                    },
                    responsive: true,
                    title:{
                        display:false,
                        text:'Chart.js Line Chart'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'Month'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'Value'
                            }/*,
                            ticks: {
                                suggestedMin: 0,
                                suggestedMax: 5000000
                            }*/
                        }]
                    }
                }
            });
        </script>

        <script type='text/javascript'>
            google.charts.load('current', {'packages': ['geochart']});
            google.charts.setOnLoadCallback(drawMarkersMap);

            function drawMarkersMap() {
                var data = google.visualization.arrayToDataTable([
                    ['Long', 'Lat' , 'Name', 'Harga'],
                    <?php if($rank_harga) { ?> 
                    <?php
                    foreach ($rank_harga as $row) {
                        echo "[".$row['long'].", ".$row['lat'].",'".$row['provinsi']."', ".$row['harga']."],";
                    }
                    ?>
                    <?php } ?> 
                    ]);

                var options = {
                    sizeAxis: { minValue: 0, maxValue: 100 },
                region: 'ID', // Western Europe
                displayMode: 'markers',
                colorAxis: {colors: ['#3ea33f', '#f8ff35', '#ff002a']} // green to red
            };

            var chart = new google.visualization.GeoChart(document.getElementById('grafik_harga'));
            chart.draw(data, options);
        };
        </script>

        <script type='text/javascript'>
            google.charts.load('current', {'packages': ['geochart']});
            google.charts.setOnLoadCallback(drawMarkersMap);

            function drawMarkersMap() {
                var data = google.visualization.arrayToDataTable([
                    ['Long', 'Lat' , 'Name', 'Produksi'],
                    <?php if($rank_produksi) { ?> 
                    <?php
                    foreach ($rank_produksi as $row) {
                        echo "[".$row['long'].", ".$row['lat'].",'".$row['provinsi']."', ".$row['produksi']."],";
                    }
                    ?>
                    <?php } ?> 
                    ]);

                var options = {
                    sizeAxis: { minValue: 0, maxValue: 100 },
                region: 'ID', // Western Europe
                displayMode: 'markers',
                colorAxis: {colors: ['#ff002a', '#f8ff35', '#3ea33f']} // green to red
            };

            var chart = new google.visualization.GeoChart(document.getElementById('grafik_produksi'));
            chart.draw(data, options);
        };
        </script>
        <script>
            var ctx = document.getElementById("myChart2");
            var myChart2 = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [
                    <?php if($prediksi) { ?> 
                    {
                        label: 'Prediksi',
                        data: [
                        <?php
                        echo $data_prediksi2;
                        ?>
                        ],
                        borderWidth: 3,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 1)',
                        fill: false,
                    },
                    <?php } ?> 
                    <?php if($aktual) { ?> 
                    {
                        label: 'Aktual',
                        data: [
                        <?php
                        echo $data_aktual2;
                        ?>
                        ],
                        borderWidth: 3,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 1)',
                        fill: false,
                    }<?php } ?> ]
                },
                options: {
                    elements: {
                        line: {
                            tension: 0.000001
                        }
                    },
                    responsive: true,
                    title:{
                        display:false,
                        text:'Chart.js Line Chart'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'Month'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'Value'
                            }/*,
                            ticks: {
                                suggestedMin: 0,
                                suggestedMax: 5000000
                            }*/
                        }]
                    }
                }
            });
            </script>
