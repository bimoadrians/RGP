<!-- Content wrapper -->
<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <?php
        $session = \Config\Services::session();
        if($session->getFlashdata('warning')) {
        ?>
        <div class="alert alert-warning">
            <ul>
                <?php
                    foreach($session->getFlashdata('warning') as $val) {
                    ?>
                <li><?php echo $val ?></li>
                <?php
                    }
                    ?>
            </ul>
        </div>
        <?php
        }
        if($session->getFlashdata('success')) {
        ?>
        <div class="alert alert-success"><?php echo $session->getFlashdata('success')?></div>
        <?php
        }
        ?>
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <h1>Detail Evaluasi Transport Jemput</h1>
                                <?php $eval_transport_user = site_url("eval_transport_user");?>
                                <a class="btn btn-secondary mb-3" href="<?php echo $eval_transport_user?>" style="font-size:120%;">Kembali</a>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="table-responsive text-nowrap">
                                        <table class="text-center" style="width: 100%; font-size:200%;">
                                            <?php foreach ($e_transportasi_jemput as $tj => $tjemput) {?>
                                                <tbody>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Nama Driver</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;"><?php echo $tjemput['nama_pengemudi'] ?></th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Mobil</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;"><?php echo $tjemput['nama_mobil'] ?></th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Tanggal</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;"><?php echo date_transaksi_kendaraan($tjemput['tanggal_mobil']) ?></th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">No</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">Faktor</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">Nilai</th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">1.</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Sikap Pengemudi</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">a. Kesopanan</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                            <input class="form-check-input" type="checkbox" value="0" id="1_nilai" name="1_nilai[1][]" style="border-style: solid; border-color: black;"> N/A
                                                            <input class="form-check-input" type="checkbox" value="1" id="1_nilai" name="1_nilai[1][]" style="border-style: solid; border-color: black;"> Kurang
                                                            <input class="form-check-input" type="checkbox" value="2" id="1_nilai" name="1_nilai[1][]" style="border-style: solid; border-color: black;"> Sedang
                                                            <input class="form-check-input" type="checkbox" value="3" id="1_nilai" name="1_nilai[1][]" style="border-style: solid; border-color: black;" checked> Baik
                                                            <input class="form-check-input" type="checkbox" value="4" id="1_nilai" name="1_nilai[1][]" style="border-style: solid; border-color: black;"> Baik Sekali
                                                            <script>
                                                                $("input:checkbox").on('click', function() {
                                                                // in the handler, 'this' refers to the box clicked on
                                                                var $box = $(this);
                                                                if ($box.is(":checked")) {
                                                                    // the name of the box is retrieved using the .attr() method
                                                                    // as it is assumed and expected to be immutable
                                                                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                                                                    // the checked state of the group/box on the other hand will change
                                                                    // and the current value is retrieved using .prop() method
                                                                    $(group).prop("checked", false);
                                                                    $box.prop("checked", true);
                                                                } else {
                                                                    $box.prop("checked", false);
                                                                }
                                                                });
                                                            </script>
                                                        </th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">b. Keramahan</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                            <input class="form-check-input" type="checkbox" value="0" id="2_nilai" name="2_nilai[2][]" style="border-style: solid; border-color: black;"> N/A
                                                            <input class="form-check-input" type="checkbox" value="1" id="2_nilai" name="2_nilai[2][]" style="border-style: solid; border-color: black;"> Kurang
                                                            <input class="form-check-input" type="checkbox" value="2" id="2_nilai" name="2_nilai[2][]" style="border-style: solid; border-color: black;"> Sedang
                                                            <input class="form-check-input" type="checkbox" value="3" id="2_nilai" name="2_nilai[2][]" style="border-style: solid; border-color: black;" checked> Baik
                                                            <input class="form-check-input" type="checkbox" value="4" id="2_nilai" name="2_nilai[2][]" style="border-style: solid; border-color: black;"> Baik Sekali
                                                            <script>
                                                                $("input:checkbox").on('click', function() {
                                                                // in the handler, 'this' refers to the box clicked on
                                                                var $box = $(this);
                                                                if ($box.is(":checked")) {
                                                                    // the name of the box is retrieved using the .attr() method
                                                                    // as it is assumed and expected to be immutable
                                                                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                                                                    // the checked state of the group/box on the other hand will change
                                                                    // and the current value is retrieved using .prop() method
                                                                    $(group).prop("checked", false);
                                                                    $box.prop("checked", true);
                                                                } else {
                                                                    $box.prop("checked", false);
                                                                }
                                                                });
                                                            </script>
                                                        </th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">c. Kerapihan</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                            <input class="form-check-input" type="checkbox" value="0" id="3_nilai" name="3_nilai[3][]" style="border-style: solid; border-color: black;"> N/A
                                                            <input class="form-check-input" type="checkbox" value="1" id="3_nilai" name="3_nilai[3][]" style="border-style: solid; border-color: black;"> Kurang
                                                            <input class="form-check-input" type="checkbox" value="2" id="3_nilai" name="3_nilai[3][]" style="border-style: solid; border-color: black;"> Sedang
                                                            <input class="form-check-input" type="checkbox" value="3" id="3_nilai" name="3_nilai[3][]" style="border-style: solid; border-color: black;" checked> Baik
                                                            <input class="form-check-input" type="checkbox" value="4" id="3_nilai" name="3_nilai[3][]" style="border-style: solid; border-color: black;"> Baik Sekali
                                                            <script>
                                                                $("input:checkbox").on('click', function() {
                                                                // in the handler, 'this' refers to the box clicked on
                                                                var $box = $(this);
                                                                if ($box.is(":checked")) {
                                                                    // the name of the box is retrieved using the .attr() method
                                                                    // as it is assumed and expected to be immutable
                                                                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                                                                    // the checked state of the group/box on the other hand will change
                                                                    // and the current value is retrieved using .prop() method
                                                                    $(group).prop("checked", false);
                                                                    $box.prop("checked", true);
                                                                } else {
                                                                    $box.prop("checked", false);
                                                                }
                                                                });
                                                            </script>
                                                        </th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">d. Ringan Tangan</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                            <input class="form-check-input" type="checkbox" value="0" id="4_nilai" name="4_nilai[4][]" style="border-style: solid; border-color: black;"> N/A
                                                            <input class="form-check-input" type="checkbox" value="1" id="4_nilai" name="4_nilai[4][]" style="border-style: solid; border-color: black;"> Kurang
                                                            <input class="form-check-input" type="checkbox" value="2" id="4_nilai" name="4_nilai[4][]" style="border-style: solid; border-color: black;"> Sedang
                                                            <input class="form-check-input" type="checkbox" value="3" id="4_nilai" name="4_nilai[4][]" style="border-style: solid; border-color: black;" checked> Baik
                                                            <input class="form-check-input" type="checkbox" value="4" id="4_nilai" name="4_nilai[4][]" style="border-style: solid; border-color: black;"> Baik Sekali
                                                            <script>
                                                                $("input:checkbox").on('click', function() {
                                                                // in the handler, 'this' refers to the box clicked on
                                                                var $box = $(this);
                                                                if ($box.is(":checked")) {
                                                                    // the name of the box is retrieved using the .attr() method
                                                                    // as it is assumed and expected to be immutable
                                                                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                                                                    // the checked state of the group/box on the other hand will change
                                                                    // and the current value is retrieved using .prop() method
                                                                    $(group).prop("checked", false);
                                                                    $box.prop("checked", true);
                                                                } else {
                                                                    $box.prop("checked", false);
                                                                }
                                                                });
                                                            </script>
                                                        </th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">2.</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Kondisi Mobil</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">a. Kebersihan</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                            <input class="form-check-input" type="checkbox" value="0" id="5_nilai" name="5_nilai[5][]" style="border-style: solid; border-color: black;"> N/A
                                                            <input class="form-check-input" type="checkbox" value="1" id="5_nilai" name="5_nilai[5][]" style="border-style: solid; border-color: black;"> Kurang
                                                            <input class="form-check-input" type="checkbox" value="2" id="5_nilai" name="5_nilai[5][]" style="border-style: solid; border-color: black;"> Sedang
                                                            <input class="form-check-input" type="checkbox" value="3" id="5_nilai" name="5_nilai[5][]" style="border-style: solid; border-color: black;" checked> Baik
                                                            <input class="form-check-input" type="checkbox" value="4" id="5_nilai" name="5_nilai[5][]" style="border-style: solid; border-color: black;"> Baik Sekali
                                                            <script>
                                                                $("input:checkbox").on('click', function() {
                                                                // in the handler, 'this' refers to the box clicked on
                                                                var $box = $(this);
                                                                if ($box.is(":checked")) {
                                                                    // the name of the box is retrieved using the .attr() method
                                                                    // as it is assumed and expected to be immutable
                                                                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                                                                    // the checked state of the group/box on the other hand will change
                                                                    // and the current value is retrieved using .prop() method
                                                                    $(group).prop("checked", false);
                                                                    $box.prop("checked", true);
                                                                } else {
                                                                    $box.prop("checked", false);
                                                                }
                                                                });
                                                            </script>
                                                        </th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">b. Interior</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                            <input class="form-check-input" type="checkbox" value="0" id="6_nilai" name="6_nilai[6][]" style="border-style: solid; border-color: black;"> N/A
                                                            <input class="form-check-input" type="checkbox" value="1" id="6_nilai" name="6_nilai[6][]" style="border-style: solid; border-color: black;"> Kurang
                                                            <input class="form-check-input" type="checkbox" value="2" id="6_nilai" name="6_nilai[6][]" style="border-style: solid; border-color: black;"> Sedang
                                                            <input class="form-check-input" type="checkbox" value="3" id="6_nilai" name="6_nilai[6][]" style="border-style: solid; border-color: black;" checked> Baik
                                                            <input class="form-check-input" type="checkbox" value="4" id="6_nilai" name="6_nilai[6][]" style="border-style: solid; border-color: black;"> Baik Sekali
                                                            <script>
                                                                $("input:checkbox").on('click', function() {
                                                                // in the handler, 'this' refers to the box clicked on
                                                                var $box = $(this);
                                                                if ($box.is(":checked")) {
                                                                    // the name of the box is retrieved using the .attr() method
                                                                    // as it is assumed and expected to be immutable
                                                                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                                                                    // the checked state of the group/box on the other hand will change
                                                                    // and the current value is retrieved using .prop() method
                                                                    $(group).prop("checked", false);
                                                                    $box.prop("checked", true);
                                                                } else {
                                                                    $box.prop("checked", false);
                                                                }
                                                                });
                                                            </script>
                                                        </th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">c. AC</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                            <input class="form-check-input" type="checkbox" value="0" id="7_nilai" name="7_nilai[7][]" style="border-style: solid; border-color: black;"> N/A
                                                            <input class="form-check-input" type="checkbox" value="1" id="7_nilai" name="7_nilai[7][]" style="border-style: solid; border-color: black;"> Kurang
                                                            <input class="form-check-input" type="checkbox" value="2" id="7_nilai" name="7_nilai[7][]" style="border-style: solid; border-color: black;"> Sedang
                                                            <input class="form-check-input" type="checkbox" value="3" id="7_nilai" name="7_nilai[7][]" style="border-style: solid; border-color: black;" checked> Baik
                                                            <input class="form-check-input" type="checkbox" value="4" id="7_nilai" name="7_nilai[7][]" style="border-style: solid; border-color: black;"> Baik Sekali
                                                            <script>
                                                                $("input:checkbox").on('click', function() {
                                                                // in the handler, 'this' refers to the box clicked on
                                                                var $box = $(this);
                                                                if ($box.is(":checked")) {
                                                                    // the name of the box is retrieved using the .attr() method
                                                                    // as it is assumed and expected to be immutable
                                                                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                                                                    // the checked state of the group/box on the other hand will change
                                                                    // and the current value is retrieved using .prop() method
                                                                    $(group).prop("checked", false);
                                                                    $box.prop("checked", true);
                                                                } else {
                                                                    $box.prop("checked", false);
                                                                }
                                                                });
                                                            </script>
                                                        </th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">d. Pengharum</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                            <input class="form-check-input" type="checkbox" value="0" id="8_nilai" name="8_nilai[8][]" style="border-style: solid; border-color: black;"> N/A
                                                            <input class="form-check-input" type="checkbox" value="1" id="8_nilai" name="8_nilai[8][]" style="border-style: solid; border-color: black;"> Kurang
                                                            <input class="form-check-input" type="checkbox" value="2" id="8_nilai" name="8_nilai[8][]" style="border-style: solid; border-color: black;"> Sedang
                                                            <input class="form-check-input" type="checkbox" value="3" id="8_nilai" name="8_nilai[8][]" style="border-style: solid; border-color: black;" checked> Baik
                                                            <input class="form-check-input" type="checkbox" value="4" id="8_nilai" name="8_nilai[8][]" style="border-style: solid; border-color: black;"> Baik Sekali
                                                            <script>
                                                                $("input:checkbox").on('click', function() {
                                                                // in the handler, 'this' refers to the box clicked on
                                                                var $box = $(this);
                                                                if ($box.is(":checked")) {
                                                                    // the name of the box is retrieved using the .attr() method
                                                                    // as it is assumed and expected to be immutable
                                                                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                                                                    // the checked state of the group/box on the other hand will change
                                                                    // and the current value is retrieved using .prop() method
                                                                    $(group).prop("checked", false);
                                                                    $box.prop("checked", true);
                                                                } else {
                                                                    $box.prop("checked", false);
                                                                }
                                                                });
                                                            </script>
                                                        </th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">e. Tape/Radio</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                            <input class="form-check-input" type="checkbox" value="0" id="9_nilai" name="9_nilai[9][]" style="border-style: solid; border-color: black;"> N/A
                                                            <input class="form-check-input" type="checkbox" value="1" id="9_nilai" name="9_nilai[9][]" style="border-style: solid; border-color: black;"> Kurang
                                                            <input class="form-check-input" type="checkbox" value="2" id="9_nilai" name="9_nilai[9][]" style="border-style: solid; border-color: black;"> Sedang
                                                            <input class="form-check-input" type="checkbox" value="3" id="9_nilai" name="9_nilai[9][]" style="border-style: solid; border-color: black;" checked> Baik
                                                            <input class="form-check-input" type="checkbox" value="4" id="9_nilai" name="9_nilai[9][]" style="border-style: solid; border-color: black;"> Baik Sekali
                                                            <script>
                                                                $("input:checkbox").on('click', function() {
                                                                // in the handler, 'this' refers to the box clicked on
                                                                var $box = $(this);
                                                                if ($box.is(":checked")) {
                                                                    // the name of the box is retrieved using the .attr() method
                                                                    // as it is assumed and expected to be immutable
                                                                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                                                                    // the checked state of the group/box on the other hand will change
                                                                    // and the current value is retrieved using .prop() method
                                                                    $(group).prop("checked", false);
                                                                    $box.prop("checked", true);
                                                                } else {
                                                                    $box.prop("checked", false);
                                                                }
                                                                });
                                                            </script>
                                                        </th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">3.</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Ketepatan Penjemputan</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                            <input class="form-check-input" type="checkbox" value="0" id="10_nilai" name="10_nilai[10][]" style="border-style: solid; border-color: black;"> N/A
                                                            <input class="form-check-input" type="checkbox" value="1" id="10_nilai" name="10_nilai[10][]" style="border-style: solid; border-color: black;"> Kurang
                                                            <input class="form-check-input" type="checkbox" value="2" id="10_nilai" name="10_nilai[10][]" style="border-style: solid; border-color: black;"> Sedang
                                                            <input class="form-check-input" type="checkbox" value="3" id="10_nilai" name="10_nilai[10][]" style="border-style: solid; border-color: black;" checked> Baik
                                                            <input class="form-check-input" type="checkbox" value="4" id="10_nilai" name="10_nilai[10][]" style="border-style: solid; border-color: black;"> Baik Sekali
                                                            <script>
                                                                $("input:checkbox").on('click', function() {
                                                                // in the handler, 'this' refers to the box clicked on
                                                                var $box = $(this);
                                                                if ($box.is(":checked")) {
                                                                    // the name of the box is retrieved using the .attr() method
                                                                    // as it is assumed and expected to be immutable
                                                                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                                                                    // the checked state of the group/box on the other hand will change
                                                                    // and the current value is retrieved using .prop() method
                                                                    $(group).prop("checked", false);
                                                                    $box.prop("checked", true);
                                                                } else {
                                                                    $box.prop("checked", false);
                                                                }
                                                                });
                                                            </script>
                                                        </th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">4.</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Inisiatif / bisa memberikan solusi terhadap permasalahan yang terjadi di lapangan</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                            <input class="form-check-input" type="checkbox" value="0" id="11_nilai" name="11_nilai[11][]" style="border-style: solid; border-color: black;"> N/A
                                                            <input class="form-check-input" type="checkbox" value="1" id="11_nilai" name="11_nilai[11][]" style="border-style: solid; border-color: black;"> Kurang
                                                            <input class="form-check-input" type="checkbox" value="2" id="11_nilai" name="11_nilai[11][]" style="border-style: solid; border-color: black;"> Sedang
                                                            <input class="form-check-input" type="checkbox" value="3" id="11_nilai" name="11_nilai[11][]" style="border-style: solid; border-color: black;" checked> Baik
                                                            <input class="form-check-input" type="checkbox" value="4" id="11_nilai" name="11_nilai[11][]" style="border-style: solid; border-color: black;"> Baik Sekali
                                                            <script>
                                                                $("input:checkbox").on('click', function() {
                                                                // in the handler, 'this' refers to the box clicked on
                                                                var $box = $(this);
                                                                if ($box.is(":checked")) {
                                                                    // the name of the box is retrieved using the .attr() method
                                                                    // as it is assumed and expected to be immutable
                                                                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                                                                    // the checked state of the group/box on the other hand will change
                                                                    // and the current value is retrieved using .prop() method
                                                                    $(group).prop("checked", false);
                                                                    $box.prop("checked", true);
                                                                } else {
                                                                    $box.prop("checked", false);
                                                                }
                                                                });
                                                            </script>
                                                        </th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                    <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">5.</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Sikap Petugas Adm. Transportasi</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">a. Kesopanan</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                            <input class="form-check-input" type="checkbox" value="0" id="12_nilai" name="12_nilai[12][]" style="border-style: solid; border-color: black;"> N/A
                                                            <input class="form-check-input" type="checkbox" value="1" id="12_nilai" name="12_nilai[12][]" style="border-style: solid; border-color: black;"> Kurang
                                                            <input class="form-check-input" type="checkbox" value="2" id="12_nilai" name="12_nilai[12][]" style="border-style: solid; border-color: black;"> Sedang
                                                            <input class="form-check-input" type="checkbox" value="3" id="12_nilai" name="12_nilai[12][]" style="border-style: solid; border-color: black;" checked> Baik
                                                            <input class="form-check-input" type="checkbox" value="4" id="12_nilai" name="12_nilai[12][]" style="border-style: solid; border-color: black;"> Baik Sekali
                                                            <script>
                                                                $("input:checkbox").on('click', function() {
                                                                // in the handler, 'this' refers to the box clicked on
                                                                var $box = $(this);
                                                                if ($box.is(":checked")) {
                                                                    // the name of the box is retrieved using the .attr() method
                                                                    // as it is assumed and expected to be immutable
                                                                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                                                                    // the checked state of the group/box on the other hand will change
                                                                    // and the current value is retrieved using .prop() method
                                                                    $(group).prop("checked", false);
                                                                    $box.prop("checked", true);
                                                                } else {
                                                                    $box.prop("checked", false);
                                                                }
                                                                });
                                                            </script>
                                                        </th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;"></th>
                                                        <th style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">b. Keramahan</th>
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                            <input class="form-check-input" type="checkbox" value="0" id="13_nilai" name="13_nilai[13][]" style="border-style: solid; border-color: black;"> N/A
                                                            <input class="form-check-input" type="checkbox" value="1" id="13_nilai" name="13_nilai[13][]" style="border-style: solid; border-color: black;"> Kurang
                                                            <input class="form-check-input" type="checkbox" value="2" id="13_nilai" name="13_nilai[13][]" style="border-style: solid; border-color: black;"> Sedang
                                                            <input class="form-check-input" type="checkbox" value="3" id="13_nilai" name="13_nilai[13][]" style="border-style: solid; border-color: black;" checked> Baik
                                                            <input class="form-check-input" type="checkbox" value="4" id="13_nilai" name="13_nilai[13][]" style="border-style: solid; border-color: black;"> Baik Sekali
                                                            <script>
                                                                $("input:checkbox").on('click', function() {
                                                                // in the handler, 'this' refers to the box clicked on
                                                                var $box = $(this);
                                                                if ($box.is(":checked")) {
                                                                    // the name of the box is retrieved using the .attr() method
                                                                    // as it is assumed and expected to be immutable
                                                                    var group = "input:checkbox[name='" + $box.attr("name") + "']";
                                                                    // the checked state of the group/box on the other hand will change
                                                                    // and the current value is retrieved using .prop() method
                                                                    $(group).prop("checked", false);
                                                                    $box.prop("checked", true);
                                                                } else {
                                                                    $box.prop("checked", false);
                                                                }
                                                                });
                                                            </script>
                                                        </th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                        <th colspan ="3" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Nilai: 1 (Kurang), 2 (Sedang), 3 (Baik), 4 (Baik Sekali)</th>
                                                    </tr>
                                                    <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                        <th style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">Komentar Lainnya</th>
                                                        <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">
                                                            <textarea class="form-control" name="komentar" rows="3" placeholder=""></textarea>
                                                        </th>
                                                    </tr>
                                                </tbody>
                                            <?php } ?>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-success" type="submit" name="save" style="font-size:200%">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="content-footer footer bg-footer-theme">
        <div class="container-xxl d-flex flex-wrap justify-content-center">
            <div class="mb-2 mb-md-0">
                Copyright &copy; <strong><span>MIS 2024</span></strong>.
            </div>
        </div>
    </footer>
    <!-- / Footer -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->