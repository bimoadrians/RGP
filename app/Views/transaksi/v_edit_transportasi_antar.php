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
            <div class="col-lg-12 order-0">
                <div class="card">
                    <div class="d-flex align-items-center row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <?php foreach ($trans as $t => $tra) {
                                    $id_trans = $tra['id_trans'];
                                ?>
                                    <?php foreach ($transportasi as $tr => $transpo) {?>
                                        <?php if ($transpo['id_trans'] == $tra['id_trans']) { ?>
                                            <?php if ($transpo['status_mobil'] == 0) { ?>
                                                <h1>Edit Transaksi</h1>
                                            <?php } else if ($transpo['status_mobil'] == 1) { ?>
                                                <h1>Detail Transaksi</h1>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <a class="btn btn-secondary mb-3" href="<?php echo site_url("transport_admin"); ?>" style="font-size:120%;"><i class="fa-solid fa-left-long"></i> Kembali</a>
                                <section id="features" class="features"  style="font-size:150%;">
                                    <ul class="nav nav-tabs row g-2 d-flex mb-3">
                                        <li class="nav-item col-3">
                                            <a class="nav-link active show" data-bs-toggle="tab"
                                                data-bs-target="#tab_start">
                                                <h3>Personil</h3>
                                            </a>
                                        </li>
                                        <!-- End tab nav item -->

                                        <li id="modal_tiket" style="display:block;" class="nav-item col-3">
                                            <a class="nav-link" data-bs-toggle="tab"
                                                data-bs-target="#tab_mobil">
                                                <h3>Mobil</h3>
                                            </a>
                                        </li>
                                        <!-- End tab nav item -->

                                        <?php foreach ($trans as $t => $tra) {
                                            $id_trans = $tra['id_trans'];
                                        ?>
                                            <?php foreach ($transportasi as $tr => $transpo) {?>
                                                <?php if ($transpo['id_trans'] == $tra['id_trans']) { ?>
                                                    <?php if ($transpo['status_mobil'] == 0) { ?>
                                                        <!-- <li id="modal_confirm" style="display:block;" class="nav-item col-3">
                                                            <a class="nav-link" data-bs-toggle="tab"
                                                                data-bs-target="#tab_confirm">
                                                                <h3>Confirm</h3>
                                                            </a>
                                                        </li> -->
                                                        <!-- End tab nav item -->
                                                    <?php } else if ($transpo['status_mobil'] == 1) { ?>
                                                        
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="modal-body">
                                            <div class="tab-content">
                                                <?php foreach ($trans as $t => $tra) {
                                                    $id_trans = $tra['id_trans'];
                                                ?>
                                                    <div class="tab-pane active show" id="tab_start" style="font-size:120%">
                                                        <?php foreach ($transportasi as $tr => $transpo) {?>
                                                            <?php if ($transpo['id_trans'] == $tra['id_trans']) { ?>
                                                                <div class="mb-1 mt-3">
                                                                    Karyawan Konimex/Tamu
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <select class="select_tamu" id="tamu" name="tamu" style="width: 100%;" onchange="showTamu(this)">
                                                                            <option>
                                                                                <?php echo(isset($tamu1)) ? $tamu1 : $transpo['tamu']; ?>
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <script>
                                                                $(document).ready(function() {
                                                                    var data_select = ['Karyawan Konimex', 'Tamu']

                                                                    $(".select_tamu").select2({
                                                                        data: data_select,
                                                                        // tags: true,
                                                                        // tokenSeparators: [',', ' '],
                                                                    });

                                                                    $('select:not(.normal)').each(function() {
                                                                        $(this).select2({
                                                                            // tags: true,
                                                                            dropdownParent: $(this)
                                                                                .parent()
                                                                        });
                                                                    });
                                                                });
                                                                </script>

                                                                <script>
                                                                    function showTamu(select) {
                                                                        if (select.value == 'Karyawan Konimex') {
                                                                            document.getElementById('select').style.display =
                                                                                "block";
                                                                            document.getElementById('inputan').style.display =
                                                                                "none";
                                                                        } else if (select.value == 'Tamu') {
                                                                            document.getElementById('select').style.display =
                                                                                "none";
                                                                            document.getElementById('inputan').style.display =
                                                                                "block";
                                                                        }
                                                                    }
                                                                </script>
                                                                
                                                                <div id="select" style="display:block;">
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <div class="mt-3">
                                                                                <div>
                                                                                    Nama
                                                                                </div>
                                                                                
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <select class="select_nama" id="nama" name="nama_select[]" multiple="multiple" style="width: 100%;">
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <script>
                                                                                    $(document).ready(function() {
                                                                                        var data_select = [
                                                                                            <?php foreach ($pengguna as $peng) : ?> "<?php echo $peng['nama_pengguna']?><?php echo " - "?><?php echo $peng['nama_jabatan']?><?php echo " - "?><?php echo $peng['jenis_kelamin']?>",
                                                                                            <?php endforeach ?>
                                                                                        ]

                                                                                        var s2 = $(".select_nama").select2({
                                                                                            data: data_select,
                                                                                            // tags: true,
                                                                                            // tags:["Semua"],
                                                                                            // tokenSeparators: [',', ' '],
                                                                                        });

                                                                                        <?php if ($transpo['tamu'] == 'Karyawan Konimex') { ?>
                                                                                            var vals = [<?php foreach ($result as $res) : ?> "<?php echo $res['atas_nama']?><?php echo " - "?><?php echo $res['jabatan']?><?php echo " - "?><?php echo $res['jenis_kelamin']?>",
                                                                                            <?php endforeach ?>];

                                                                                            vals.forEach(function(e){
                                                                                            if(!s2.find('option:contains(' + e + ')').length) 
                                                                                            s2.append($('<option>').text(e));
                                                                                            });

                                                                                            s2.val(vals).trigger("change");
                                                                                        <?php } else { ?>
                                                                                            
                                                                                        <?php } ?>
                                                                                    });
                                                                                </script>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-6">
                                                                            <div class="mt-3">
                                                                                Pembayaran
                                                                            </div>

                                                                            <div class="row mb-3">
                                                                                <div class="col-md-12">
                                                                                    <select class="select_pembayaran" name="pembayaran" style="width: 100%;">
                                                                                        <option>
                                                                                            <?php 
                                                                                                if($transpo['pembayaran'] == 'k'){
                                                                                                    $pembayaran_tiket = 'Company Acc';
                                                                                                } else if($transpo['pembayaran'] == 'p'){
                                                                                                    $pembayaran_tiket = 'Personal Acc';
                                                                                                }
                                                                                            ?>
                                                                                            <?php echo(isset($pembayaran1)) ? $pembayaran1 : $pembayaran_tiket; ?>
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                            <script>
                                                                            $(document).ready(function() {
                                                                                var data_select = ['Company Acc', 'Personal Acc']

                                                                                $(".select_pembayaran").select2({
                                                                                    data: data_select,
                                                                                    // tags: true,
                                                                                    // tokenSeparators: [',', ' '],
                                                                                });

                                                                                $('select:not(.normal)').each(function() {
                                                                                    $(this).select2({
                                                                                        // tags: true,
                                                                                        dropdownParent: $(this)
                                                                                            .parent()
                                                                                    });
                                                                                });
                                                                            });
                                                                            </script>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div id="inputan" style="display:none;">
                                                                    <div class="row">
                                                                        <div class="col-lg-4">
                                                                            <div class="mt-3">
                                                                                <div>
                                                                                    Nama
                                                                                </div>

                                                                                <?php if ($transpo['tamu'] == 'Karyawan Konimex') { ?>
                                                                                    <div class="col-md-12 mt-1" style="height:70%;">
                                                                                        <input autocomplete="off" type="text" class="form-control" name="nama_inputan" style="text-transform:capitalize">
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <div class="col-md-12 mt-1" style="height:70%;">
                                                                                        <input autocomplete="off" type="text" class="form-control" name="nama_inputan" style="text-transform:capitalize" value="<?php echo(isset($nama_tamu1)) ? $nama_tamu1 : $transpo['atas_nama']; ?>">
                                                                                    </div>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-4">
                                                                            <div class="mt-3">
                                                                                <div>
                                                                                    Jabatan
                                                                                </div>

                                                                                <?php if ($transpo['tamu'] == 'Karyawan Konimex') { ?>
                                                                                    <div class="col-md-12 mt-1" style="height:70%;">
                                                                                        <input autocomplete="off" type="text" class="form-control" name="jabatan_inputan" style="text-transform:capitalize">
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <div class="col-md-12 mt-1" style="height:70%;">
                                                                                        <input autocomplete="off" type="text" class="form-control" name="jabatan_inputan" style="text-transform:capitalize" value="<?php echo(isset($nama_jabatan1)) ? $nama_jabatan1 : $transpo['jabatan']; ?>">
                                                                                    </div>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-4">
                                                                            <div class="mt-3">
                                                                                Pembayaran
                                                                            </div>

                                                                            <div class="row mb-3">
                                                                                <div class="col-md-12">
                                                                                    <select class="select_pembayaran" name="pembayaran_inputan" style="width: 100%;">
                                                                                        <option>
                                                                                            <?php 
                                                                                                if($transpo['pembayaran'] == 'k'){
                                                                                                    $pembayaran_tiket = 'Company Acc';
                                                                                                } else if($transpo['pembayaran'] == 'p'){
                                                                                                    $pembayaran_tiket = 'Personal Acc';
                                                                                                }
                                                                                            ?>
                                                                                            <?php echo(isset($pembayaran1)) ? $pembayaran1 : $pembayaran_tiket; ?>
                                                                                        </option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>

                                                                            <script>
                                                                            $(document).ready(function() {
                                                                                var data_select = ['Company Acc', 'Personal Acc']

                                                                                $(".select_pembayaran").select2({
                                                                                    data: data_select,
                                                                                    // tags: true,
                                                                                    // tokenSeparators: [',', ' '],
                                                                                });

                                                                                $('select:not(.normal)').each(function() {
                                                                                    $(this).select2({
                                                                                        // tags: true,
                                                                                        dropdownParent: $(this)
                                                                                            .parent()
                                                                                    });
                                                                                });
                                                                            });
                                                                            </script>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <script>
                                                                    <?php if ($transpo['tamu'] == 'Karyawan Konimex') { ?>
                                                                        document.getElementById('select').style.display =
                                                                                "block";
                                                                        document.getElementById('inputan').style.display =
                                                                                "none";
                                                                    <?php } else { ?>
                                                                        document.getElementById('select').style.display =
                                                                                "none";
                                                                        document.getElementById('inputan').style.display =
                                                                                "block";
                                                                    <?php } ?>
                                                                </script>

                                                                <div class="modal-footer">
                                                                </div>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="tab-pane" id="tab_mobil" style="font-size:120%">
                                                        <div class="mb-1">
                                                            GS
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <select class="select_gs_mobil" name="gs_mobil" style="width: 100%;">
                                                                    <option>
                                                                        <?php echo(isset($gs_mobil1)) ? $gs_mobil1 : $transpo['nama_pool']; ?>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <script>
                                                            $(document).ready(function() {
                                                                var data_select = [
                                                                    <?php foreach ($pool as $poo) : ?> "<?php echo $poo['nama_pool']?>",
                                                                    <?php endforeach ?>
                                                                ]

                                                                $(".select_gs_mobil").select2({
                                                                    data: data_select,
                                                                    // tags: true,
                                                                    // tokenSeparators: [',', ' '],
                                                                });
                                                            });
                                                        </script>

                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-1">
                                                                    Jenis Kendaraan
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <select class="select_jenis_kendaraan" name="jenis_kendaraan" style="width: 100%;">
                                                                            <option>
                                                                                <?php
                                                                                    if($transpo['jenis_kendaraan'] == 's'){
                                                                                        $jenis_kendaraan2 = 'Sedan';
                                                                                    } else if($transpo['jenis_kendaraan'] == 'a'){
                                                                                        $jenis_kendaraan2 = 'Station';
                                                                                    } else if($transpo['jenis_kendaraan'] == 'p'){
                                                                                        $jenis_kendaraan2 = 'Pick Up';
                                                                                    } else if($transpo['jenis_kendaraan'] == 'b'){
                                                                                        $jenis_kendaraan2 = 'Box';
                                                                                    } else if($transpo['jenis_kendaraan'] == 't'){
                                                                                        $jenis_kendaraan2 = 'Truck';
                                                                                    }
                                                                                ?>
                                                                                <?php echo(isset($jenis_kendaraan1)) ? $jenis_kendaraan1 : $jenis_kendaraan2; ?>
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <script>
                                                                    $(document).ready(function() {
                                                                        var data_select = [
                                                                            'Sedan', 'Station', 'Pickup', 'Box', 'Truck'
                                                                        ]

                                                                        $(".select_jenis_kendaraan").select2({
                                                                            data: data_select,
                                                                            // tags: true,
                                                                            // tokenSeparators: [',', ' '],
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>

                                                            <div class="col-lg-6">
                                                                <div class="mb-1">
                                                                    Dalam/Luar Kota
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <select id="dalkot_lukot" name="dalkot_lukot" onchange="showD(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                            <option>
                                                                                <?php
                                                                                    if($transpo['dalkot_lukot'] == 'd'){
                                                                                        $dalkot_lukot2 = 'Dalam Kota';
                                                                                    } else {
                                                                                        $dalkot_lukot2 = 'Luar Kota';
                                                                                    }
                                                                                ?>
                                                                                <?php echo(isset($dalkot_lukot1)) ? $dalkot_lukot1 : $dalkot_lukot2; ?>
                                                                            </option>
                                                                            <option>
                                                                                <?php
                                                                                    if($transpo['dalkot_lukot'] == 'd'){
                                                                                        $dalkot_lukot4 = 'Luar Kota';
                                                                                    } else {
                                                                                        $dalkot_lukot4 = 'Dalam Kota';
                                                                                    }
                                                                                ?>
                                                                                <?php echo(isset($dalkot_lukot3)) ? $dalkot_lukot3 : $dalkot_lukot4; ?>
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <script>
                                                                    function showD(select) {
                                                                        if (select.value == 'Dalam Kota') {
                                                                            document.getElementById('menginap').style.display =
                                                                                "none";
                                                                        } else if (select.value == 'Luar Kota') {
                                                                            document.getElementById('menginap').style.display =
                                                                                "block";
                                                                        }
                                                                    }
                                                                </script>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <?php if($transpo['dalkot_lukot'] == 'd'){ ?>
                                                                        <div class="mb-1 mb-3" id="menginap" style="display:none;">
                                                                    <?php } else { ?>
                                                                        <div class="mb-1 mb-3" id="menginap" style="display:block;">
                                                                    <?php } ?>
                                                                    <div class="mb-1">
                                                                        Menginap
                                                                    </div>

                                                                    <select class="select_menginap" name="menginap" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                        <option>
                                                                            <?php
                                                                                if($transpo['menginap'] == '1'){
                                                                                    $menginap2 = 'Iya';
                                                                                } else {
                                                                                    $menginap2 = 'Tidak';
                                                                                }
                                                                            ?>
                                                                            <?php echo(isset($menginap1)) ? $menginap1 : $menginap2; ?>
                                                                        </option>
                                                                        <option>
                                                                            <?php
                                                                                if($transpo['menginap'] == '1'){
                                                                                    $menginap4 = 'Tidak';
                                                                                } else {
                                                                                    $menginap4 = 'Iya';
                                                                                }
                                                                            ?>
                                                                            <?php echo(isset($menginap3)) ? $menginap3 : $menginap4; ?>
                                                                        </option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-6 mb-3">
                                                                <div class="mb-1">
                                                                    Jumlah Mobil
                                                                </div>

                                                                <input autocomplete="off" type="number" class="form-control" name="jumlah_mobil" id="jumlah_mobil" value="<?php echo(isset($jumlah_mobil1)) ? $jumlah_mobil1 : $transpo['jumlah_mobil'];?>">
                                                            </div>

                                                            <div class="col-lg-6 mb-3">
                                                                <div class="mb-1">
                                                                    Kapasitas
                                                                </div>

                                                                <input autocomplete="off" type="number" class="form-control" name="kapasitas" id="kapasitas" value="<?php echo(isset($kapasitas1)) ? $kapasitas1 : $transpo['kapasitas'];?>">
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-3 mb-3">
                                                                <div class="mb-1">
                                                                    Tujuan
                                                                </div>

                                                                <input autocomplete="off" type="text" class="form-control" name="tujuan_mobil" id="tujuan_mobil" style="text-transform:capitalize" value="<?php echo(isset($tujuan_mobil1)) ? $tujuan_mobil1 : $transpo['tujuan_mobil'];?>">
                                                            </div>

                                                            <div class="col-lg-3 mb-3">
                                                                <div class="mb-1">
                                                                    Siap Di
                                                                </div>

                                                                <input autocomplete="off" type="text" class="form-control" name="siap_di" id="siap_di" style="text-transform:capitalize" value="<?php echo(isset($siap_di1)) ? $siap_di1 : $transpo['siap_di'];?>">
                                                            </div>

                                                            <div class="col-lg-3 mb-3">
                                                                <div class="mb-1">
                                                                    Tanggal
                                                                </div>

                                                                <input autocomplete="off" id='tanggal_mobil' class="form-control" name="tanggal_mobil" value="<?php echo(isset($tanggal_mobil1)) ? $tanggal_mobil1 : $transpo['tanggal_mobil'];?>">

                                                                <script>
                                                                    $(function() {
                                                                        $.datetimepicker.setLocale('id');
                                                                        $('#tanggal_mobil').datetimepicker({
                                                                            format: 'Y-m-d',
                                                                            formatDate: 'Y-m-d',
                                                                            minDate:'0',
                                                                            step: 1,
                                                                            timepicker : false,
                                                                            closeOnDateSelect : true,
                                                                            scrollMonth : false,
                                                                            scrollInput : false,
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>

                                                            <div class="col-lg-3 mb-3">
                                                                <div class="mb-1">
                                                                    Jam Siap
                                                                </div>

                                                                <input autocomplete="off" id='jam_siap' class="form-control" name="jam_siap" value="<?php echo(isset($jam_siap1)) ? $jam_siap1 : $transpo['jam_siap'];?>">

                                                                <script>
                                                                    $(function() {
                                                                        $.datetimepicker.setLocale('id');
                                                                        $('#jam_siap').datetimepicker({
                                                                            format: 'H:i',
                                                                            formatTime: 'H:i',
                                                                            step: 1,
                                                                            datepicker : false,
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>
                                                        </div>

                                                        <div class="mb-1">
                                                            Keterangan (Wajib diisi)
                                                        </div>

                                                        <textarea class="form-control mb-3" name="keterangan_mobil" rows="3" placeholder=""><?php echo(isset($keterangan_mobil1)) ? $keterangan_mobil1 : $transpo['keterangan_mobil']; ?></textarea>

                                                        <script>
                                                            $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                                if (event.which == '13') {
                                                                    event.preventDefault();
                                                                }
                                                            });
                                                        </script>

                                                        <div class="mb-1">
                                                            Keterangan Tambahan dari GS
                                                        </div>

                                                        <textarea class="form-control" name="keterangan_gs" rows="3" placeholder=""><?php echo(isset($keterangan_gs1)) ? $keterangan_gs1 : $transpo['keterangan_gs']; ?></textarea>

                                                        <script>
                                                            $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                                if (event.which == '13') {
                                                                    event.preventDefault();
                                                                }
                                                            });
                                                        </script>

                                                        <div class="mb-3"></div>
                                                    </div>
                                                    
                                                    <!-- <div class="tab-pane" id="tab_confirm" style="font-size:100%">
                                                        <div class="modal-footer">
                                                            <a class="btn btn-success btn-lg" href="javascript:void(0)" style="font-size:85%" data-bs-toggle="modal" data-bs-target="#konfirm"><i class="fa-solid fa-check"></i> Konfirmasi</a>

                                                            <div class="modal" id="konfirm" tabindex="-1" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h2 class="modal-title" id="modalTopTitle">Konfirmasi?</h2>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <div class="tab-content">
                                                                                <div class="tab-pane active show" style="font-size:120%; text-align: center;">
                                                                                    <div class="mt-4 mb-1">
                                                                                        Mohon sebelum melakukan konfirmasi, pastikan sekali lagi apakah data yang diinput sudah benar dan tepat. Setelah melakukan konfirmasi, data tidak dapat diubah kembali.
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="font-size:85%;">
                                                                                <i class="fa-solid fa-xmark"></i> Batalkan
                                                                            </button>
                                                                            <button id="add_btn" class="btn btn-success btn-lg" type="submit" name="save" style="font-size:85%">
                                                                                <i class="fa-solid fa-check"></i> Konfirmasi
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php foreach ($trans as $t => $tra) {
                                            $id_trans = $tra['id_trans'];
                                        ?>
                                            <?php foreach ($transportasi as $tr => $transpo) {?>
                                                <?php if ($transpo['id_trans'] == $tra['id_trans']) { ?>
                                                    <?php if ($transpo['status_mobil'] == 0) { ?>
                                                        <div class="d-flex align-items-center row">
                                                            <div class="col-sm-12">
                                                                <div class="card-body" style="text-align:right;">
                                                                    <a class="btn btn-success btn-lg" href="javascript:void(0)" style="font-size:100%" data-bs-toggle="modal" data-bs-target="#konfirm"><i class="fa-solid fa-check"></i> Konfirmasi</a>

                                                                    <div class="modal" id="konfirm" tabindex="-1" aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h2 class="modal-title" id="modalTopTitle">Konfirmasi?</h2>
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="tab-content">
                                                                                        <div class="tab-pane active show" style="font-size:26px; text-align: center;">
                                                                                            <div class="mt-4 mb-1">
                                                                                                Mohon sebelum melakukan konfirmasi, pastikan sekali lagi apakah data yang diinput sudah benar dan tepat. Setelah melakukan konfirmasi, data tidak dapat diubah kembali.
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal" style="font-size:120%;">
                                                                                        <i class="fa-solid fa-xmark"></i> Batalkan
                                                                                    </button>
                                                                                    <button id="add_btn" class="btn btn-success btn-lg" type="submit" name="save" style="font-size:120%">
                                                                                        <i class="fa-solid fa-check"></i> Konfirmasi
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } else if ($transpo['status_mobil'] == 1) { ?>
                                                        
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </form>
                                </section>
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