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
                                    <?php foreach ($akomodasi as $ak => $ako) {?>
                                        <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                            <?php if ($ako['status_akomodasi'] == 0) { ?>
                                                <h1>Edit Transaksi Akomodasi</h1>
                                            <?php } else if ($ako['status_akomodasi'] == 1) { ?>
                                                <h1>Detail Transaksi Akomodasi</h1>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <a class="btn btn-secondary mb-3" href="<?php echo session()->get('url_kend')?>" style="font-size:120%;"><i class="fa-solid fa-left-long"></i> Kembali</a>
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
                                                data-bs-target="#tab_hotel">
                                                <h3>Hotel</h3>
                                            </a>
                                        </li>
                                        <!-- End tab nav item -->

                                        <?php foreach ($trans as $t => $tra) {
                                            $id_trans = $tra['id_trans'];
                                        ?>
                                            <?php foreach ($akomodasi as $ak => $ako) {?>
                                                <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                    <?php if ($ako['status_akomodasi'] == 0) { ?>
                                                        <!-- <li id="modal_confirm" style="display:block;" class="nav-item col-3">
                                                            <a class="nav-link" data-bs-toggle="tab"
                                                                data-bs-target="#tab_confirm">
                                                                <h3>Confirm</h3>
                                                            </a>
                                                        </li> -->
                                                        <!-- End tab nav item -->
                                                    <?php } else if ($ako['status_akomodasi'] == 1) { ?>
                                                        
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
                                                        <?php foreach ($akomodasi as $ak => $ako) {?>
                                                            <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                                <div class="mb-1 mt-3">
                                                                    Karyawan Konimex/Tamu
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <select class="select_tamu" id="tamu" name="tamu" style="width: 100%;" onchange="showTamu(this)">
                                                                            <option>
                                                                                <?php echo(isset($tamu1)) ? $tamu1 : $ako['tamu']; ?>
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

                                                                                        <?php if ($ako['tamu'] == 'Karyawan Konimex') { ?>
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
                                                                                                if($ako['pembayaran'] == 'k'){
                                                                                                    $pembayaran_tiket = 'Company Acc';
                                                                                                } else if($ako['pembayaran'] == 'p'){
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

                                                                                <?php if ($ako['tamu'] == 'Karyawan Konimex') { ?>
                                                                                    <div class="col-md-12 mt-1" style="height:70%;">
                                                                                        <input autocomplete="off" type="text" class="form-control" name="nama_inputan" style="text-transform:capitalize">
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <div class="col-md-12 mt-1" style="height:70%;">
                                                                                        <input autocomplete="off" type="text" class="form-control" name="nama_inputan" style="text-transform:capitalize" value="<?php echo(isset($nama_tamu1)) ? $nama_tamu1 : $ako['atas_nama']; ?>">
                                                                                    </div>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-4">
                                                                            <div class="mt-3">
                                                                                <div>
                                                                                    Jabatan
                                                                                </div>

                                                                                <?php if ($ako['tamu'] == 'Karyawan Konimex') { ?>
                                                                                    <div class="col-md-12 mt-1" style="height:70%;">
                                                                                        <input autocomplete="off" type="text" class="form-control" name="jabatan_inputan" style="text-transform:capitalize">
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <div class="col-md-12 mt-1" style="height:70%;">
                                                                                        <input autocomplete="off" type="text" class="form-control" name="jabatan_inputan" style="text-transform:capitalize" value="<?php echo(isset($nama_jabatan1)) ? $nama_jabatan1 : $ako['jabatan']; ?>">
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
                                                                                                if($ako['pembayaran'] == 'k'){
                                                                                                    $pembayaran_tiket = 'Company Acc';
                                                                                                } else if($ako['pembayaran'] == 'p'){
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
                                                                    <?php if ($ako['tamu'] == 'Karyawan Konimex') { ?>
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

                                                    <div class="tab-pane" id="tab_hotel" style="font-size:120%">
                                                        <div class="mb-1">
                                                            GS
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <select class="select_gs_hotel" id="gs_hotel" name="gs_hotel" onchange="showGshotel(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                    <option>
                                                                        <?php echo(isset($gs_hotel1)) ? $gs_hotel1 : $ako['nama_pool']; ?>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <script>
                                                            $(document).ready(function() {
                                                                <?php foreach ($mess as $m => $mes) {?>
                                                                    <?php if ($mes['terpakai'] == 18) { ?>
                                                                        var data_select = [
                                                                            "1. Pool Solo"
                                                                        ]
                                                                    <?php } else { ?>
                                                                        var data_select = [
                                                                            <?php foreach ($pool as $poo) : ?> "<?php echo $poo['nama_pool']?>",
                                                                            <?php endforeach ?>
                                                                        ]
                                                                    <?php } ?>
                                                                <?php } ?>

                                                                $(".select_gs_hotel").select2({
                                                                    data: data_select,
                                                                    // tags: true,
                                                                    // tokenSeparators: [',', ' '],
                                                                });
                                                            });
                                                        </script>

                                                        <script>
                                                            function showGshotel(select) {
                                                                if (select.value == '2. Pool Jakarta') {
                                                                    document.getElementById('kota_kamar').style
                                                                        .display = "none";
                                                                    document.getElementById('tanggal_kamar').style
                                                                        .display = "none";
                                                                    document.getElementById('jumlah_kamar').style
                                                                        .display = "none";
                                                                    document.getElementById('kota_kamar_jkt').style
                                                                        .display = "block";
                                                                    document.getElementById('tanggal_kamar_jkt').style
                                                                        .display = "block";
                                                                    document.getElementById('jumlah_personil_mess').style
                                                                        .display = "block";
                                                                    document.getElementById('sisa_mess').style
                                                                        .display = "none";
                                                                    document.getElementById('harga_hotel').style
                                                                        .display = "none";
                                                                } else {
                                                                    document.getElementById('kota_kamar').style
                                                                        .display = "block";
                                                                    document.getElementById('tanggal_kamar').style
                                                                        .display = "block";
                                                                    document.getElementById('jumlah_kamar').style
                                                                        .display = "block";
                                                                    document.getElementById('kota_kamar_jkt').style
                                                                        .display = "none";
                                                                    document.getElementById('tanggal_kamar_jkt').style
                                                                        .display = "none";
                                                                    document.getElementById('jumlah_personil_mess').style
                                                                        .display = "none";
                                                                    document.getElementById('sisa_mess').style
                                                                        .display = "none";
                                                                    document.getElementById('harga_hotel').style
                                                                        .display = "block";
                                                                }
                                                            }
                                                        </script>

                                                        <?php if ($ako['nama_pool'] == '2. Pool Jakarta') { ?>
                                                            <div id="kota_kamar_jkt" style="display:block;">
                                                        <?php } else { ?>
                                                            <div id="kota_kamar_jkt" style="display:none;">
                                                        <?php } ?>
                                                            <?php if ($ako['tamu'] == 'MNJ') { ?>
                                                                <div id="mnj_pesan" style="display:block;">
                                                            <?php } else { ?>
                                                                <div id="mnj_pesan" style="display:none;">
                                                            <?php } ?>
                                                                <div class="mb-1">
                                                                    Pesan Mess untuk MNJ?
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <select name="pesan_mnj" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                            <option>
                                                                                <?php
                                                                                    if($ako['tamu'] == 'MNJ'){
                                                                                        $pesan_mnj2 = 'Iya';
                                                                                    } else {
                                                                                        $pesan_mnj2 = 'Tidak';
                                                                                    }
                                                                                ?>
                                                                                <?php echo(isset($pesan_mnj1)) ? $pesan_mnj1 : $pesan_mnj2; ?>
                                                                            </option>
                                                                            <option>
                                                                                <?php
                                                                                    if($ako['tamu'] != 'MNJ'){
                                                                                        $pesan_mnj4 = 'Tidak';
                                                                                    } else {
                                                                                        $pesan_mnj4 = 'Iya';
                                                                                    }
                                                                                ?>
                                                                                <?php echo(isset($pesan_mnj3)) ? $pesan_mnj3 : $pesan_mnj4; ?>
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <script>
                                                                $(".select_tamu").change(function() {
                                                                    var val = $(this).val();

                                                                    if (val == "Karyawan Konimex") {
                                                                        document.getElementById('mnj_pesan').style
                                                                            .display = "none";
                                                                    } else if (val == "Tamu") {
                                                                        document.getElementById('mnj_pesan').style
                                                                            .display = "block";
                                                                    }
                                                                });
                                                            </script>

                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="mb-1">
                                                                        Kota
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <select class="select_kota_jkt" id="kota_jkt" name="kota_jkt" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                <optgroup label = "Indonesia">
                                                                                    <option>
                                                                                        Jakarta
                                                                                    </option>
                                                                                </optgroup>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-lg-6">
                                                                    <div class="mb-1">
                                                                        Hotel/Kamar
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <select class="select_hotel_jkt" id="hotel_jkt" name="hotel_jkt" onchange="showKamarhoteljkt(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                <option>Mess Kx Jkt - Standar</option>
                                                                            </select>
                                                                        </div>

                                                                        <script>
                                                                            $(document).ready(function() {
                                                                                var data_select = [
                                                                                    <?php foreach ($hotel_jkt as $ho) : ?> "<?php echo $ho['nama_hotel']?><?php echo " - " ?><?php echo $ho['jenis_kamar'] ?>",
                                                                                    <?php endforeach ?>
                                                                                ]

                                                                                $(".select_hotel_jkt").select2({
                                                                                    data: data_select,
                                                                                    // tags: true,
                                                                                    // tokenSeparators: [',', ' '],
                                                                                });
                                                                            });
                                                                        </script>
                                                                    </div>
                                                                    
                                                                    <script>
                                                                        function showKamarhoteljkt(select) {
                                                                            if (select.value == 'Mess Kx Jkt - Standar') {
                                                                                document.getElementById('sisa_mess').style
                                                                                    .display = "block";
                                                                            } else {
                                                                                document.getElementById('sisa_mess').style
                                                                                    .display = "none";
                                                                            }
                                                                        }
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php if ($ako['nama_pool'] == '2. Pool Jakarta') { ?>
                                                            <div id="tanggal_kamar_jkt" style="display:block;">
                                                        <?php } else { ?>
                                                            <div id="tanggal_kamar_jkt" style="display:none;">
                                                        <?php } ?>
                                                            <div class="row">
                                                                <div class="col-lg-6 mb-2">
                                                                    <div class="mb-1">
                                                                        Tanggal Masuk dan Jam Masuk
                                                                    </div>

                                                                    <input autocomplete="off" id='tanggal_jam_masuk_jkt' class="form-control mb-3" name="tanggal_jam_masuk_jkt" onchange="handler(event);" value="<?php echo(isset($tanggal_jam_masuk_jkt1)) ? $tanggal_jam_masuk_jkt1 : $ako['tanggal_jam_masuk'];?>">

                                                                    <script>
                                                                        $(function() {
                                                                            $.datetimepicker.setLocale('id');
                                                                            $('#tanggal_jam_masuk_jkt').datetimepicker({
                                                                                format: 'Y-m-d H:i',
                                                                                formatDate: 'Y-m-d',
                                                                                formatTime: 'H:i',
                                                                                minDate:'0',
                                                                                step: 1,
                                                                                // disabledDates: [
                                                                                //     <php foreach ($merged as $me) : ?> "<php echo $me?>",
                                                                                //     <php endforeach ?>
                                                                                // ],
                                                                                closeOnTimeSelect : true,
                                                                                scrollMonth : false,
                                                                                scrollInput : false,
                                                                            });
                                                                        });
                                                                    </script>
                                                                </div>

                                                                <div class="col-lg-6 mb-2">
                                                                    <div class="mb-1">
                                                                        Tanggal Keluar dan Jam Keluar
                                                                    </div>

                                                                    <input autocomplete="off" id='tanggal_jam_keluar_jkt' class="form-control mb-3" name="tanggal_jam_keluar_jkt" value="<?php echo(isset($tanggal_jam_keluar_jkt1)) ? $tanggal_jam_keluar_jkt1 : $ako['tanggal_jam_keluar'];?>">

                                                                    <script>
                                                                        $(function() {
                                                                            $.datetimepicker.setLocale('id');
                                                                            $('#tanggal_jam_keluar_jkt').datetimepicker({
                                                                                format: 'Y-m-d H:i',
                                                                                formatDate: 'Y-m-d',
                                                                                formatTime: 'H:i',
                                                                                minDate:'0',
                                                                                step: 1,
                                                                                // disabledDates: [
                                                                                //     <php foreach ($merged as $me) : ?> "<php echo $me?>",
                                                                                //     <php endforeach ?>
                                                                                // ],
                                                                                closeOnTimeSelect : true,
                                                                                scrollMonth : false,
                                                                                scrollInput : false,
                                                                            });
                                                                        });
                                                                    </script>
                                                                </div>
                                                            </div>

                                                            <!-- <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="mb-1">
                                                                        Ketersediaan Kamar Mess
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <select class="select_sisa_mess_masuk" name="sisa_mess_masuk" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                <option value="pilih1">-- pilih tanggal masuk dan jam masuk terlebih dahulu --</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <script>
                                                                        function formatDate(date) {
                                                                            var d = new Date(date),
                                                                                month = '' + (d.getMonth() + 1),
                                                                                day = '' + d.getDate(),
                                                                                year = d.getFullYear();

                                                                            if (month.length < 2) 
                                                                                month = '0' + month;
                                                                            if (day.length < 2) 
                                                                                day = '0' + day;

                                                                            return [year, month, day].join('-');
                                                                        }

                                                                        function handler(e){
                                                                            // alert(formatDate(e.target.value));
                                                                            <php foreach ($tanggal_mess as $ta => $tam) {?>
                                                                                if (formatDate(e.target.value) == "<php echo $tam['tanggal_mess'] ?>") {
                                                                                    $(".select_sisa_mess_masuk").html(
                                                                                        "<option>Tersedia untuk: <php echo $tam['sum'] ?> orang</option>"
                                                                                    );
                                                                                } else if (formatDate(e.target.value) != "<php echo $tam['tanggal_mess'] ?>") {
                                                                                    $(".select_sisa_mess_masuk").html(
                                                                                        "<option>Tersedia untuk: 18 orang</option>"
                                                                                    );
                                                                                }
                                                                            <php } ?>
                                                                        }
                                                                    </script>
                                                                </div>
                                                            </div> -->
                                                        </div>

                                                        <?php if ($ako['nama_pool'] == '2. Pool Jakarta') { ?>
                                                            <div id="kota_kamar" style="display:none;">
                                                        <?php } else { ?>
                                                            <div id="kota_kamar" style="display:block;">
                                                        <?php } ?>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="mb-1">
                                                                        Kota
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <select class="select_kota" id="kota" name="kota" onchange="showKotahotel(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                <?php if ($ako['nama_pool'] == '2. Pool Jakarta') { ?>
                                                                                    <?php foreach ($negara_hotel as $nh => $neg_hot) {?>
                                                                                        <optgroup label = "<?php echo $neg_hot['nama_negara']?>">
                                                                                            <option>
                                                                                                <?php echo(isset($kota1)) ? $kota1 : $ako['nama_kota']; ?>
                                                                                            </option>
                                                                                        </optgroup>
                                                                                    <?php } ?>
                                                                                    <?php foreach ($negara as $n => $neg) {?>
                                                                                        <optgroup label=<?php echo $neg['nama_negara'] ?>>
                                                                                            <?php foreach ($kota_hide as $k => $kot_hid) {?>
                                                                                                <?php if ($neg['id_negara'] == $kot_hid['id_negara']) { ?>
                                                                                                    <option><?php echo $kot_hid['nama_kota'] ?></option>
                                                                                                <?php } ?>
                                                                                            <?php } ?>
                                                                                        </optgroup>
                                                                                    <?php } ?>
                                                                                    <!-- <option value="pilih">-- pilih kota --</option> -->
                                                                                <?php } else { ?>
                                                                                    <?php foreach ($negara_hotel as $nh => $neg_hot) {?>
                                                                                        <optgroup label = "<?php echo $neg_hot['nama_negara']?>">
                                                                                            <option>
                                                                                                <?php echo(isset($kota1)) ? $kota1 : $ako['nama_kota']; ?>
                                                                                            </option>
                                                                                        </optgroup>
                                                                                    <?php } ?>
                                                                                    <?php foreach ($negara as $n => $neg) {?>
                                                                                        <optgroup label=<?php echo $neg['nama_negara'] ?>>
                                                                                            <?php foreach ($kota_hide as $k => $kot_hid) {?>
                                                                                                <?php if ($neg['id_negara'] == $kot_hid['id_negara']) { ?>
                                                                                                    <option><?php echo $kot_hid['nama_kota'] ?></option>
                                                                                                <?php } ?>
                                                                                            <?php } ?>
                                                                                        </optgroup>
                                                                                    <?php } ?>
                                                                                    <!-- <option value="pilih">-- pilih kota --</option> -->
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <script>
                                                                        function showKotahotel(select) {
                                                                            if (select.value == 'Jakarta') {
                                                                                
                                                                            } else {
                                                                                document.getElementById('sisa_mess').style
                                                                                    .display = "none";
                                                                            }
                                                                        }
                                                                    </script>
                                                                </div>

                                                                <div class="col-lg-6">
                                                                    <div class="mb-1">
                                                                        Hotel/Kamar
                                                                    </div>

                                                                    <div class="row mb-3">
                                                                        <div class="col-md-12">
                                                                            <select class="select_hotel" id="hotel" name="hotel" onchange="showKamarhotel(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                <?php if ($ako['nama_pool'] == '2. Pool Jakarta') { ?>
                                                                                    <?php foreach ($kota_hotel as $k => $kot) {?><?php foreach ($hotel as $h => $hote) {?><?php if ($kot['nama_kota'] == $hote['nama_kota']) { ?><option><?php echo $hote['nama_hotel'] ?><?php echo " - " ?><?php echo $hote['jenis_kamar'] ?><?php } ?><?php } ?><?php } ?>
                                                                                    <!-- <option value="pilih1">-- pilih kota terlebih dahulu --</option> -->
                                                                                <?php } else { ?>
                                                                                    <option>
                                                                                        <?php echo(isset($hotel1)) ? $hotel1 : $ako['nama_hotel']; ?><?php echo " - " ?><?php echo $ako['jenis_kamar'] ?>
                                                                                    </option>
                                                                                    <?php foreach ($kota_hotel as $k => $kot) {?><?php foreach ($hotel as $h => $hote) {?><?php if ($kot['nama_kota'] == $hote['nama_kota']) { ?><option><?php echo $hote['nama_hotel'] ?><?php echo " - " ?><?php echo $hote['jenis_kamar'] ?><?php } ?><?php } ?><?php } ?>
                                                                                    <!-- <option value="pilih1">-- pilih kota terlebih dahulu --</option> -->
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                    <script>
                                                                        $(".select_kota").change(function() {
                                                                            var val = $(this).val();

                                                                            <?php foreach ($kota as $k => $kot) {?>
                                                                                if (val == "<?php echo $kot['nama_kota'] ?>") {
                                                                                    $(".select_hotel").html(
                                                                                        "<option value=''>-- Daftar hotel --</option><?php foreach ($hotel as $h => $hote) {?><?php if ($kot['nama_kota'] == $hote['nama_kota']) { ?><option><?php echo $hote['nama_hotel'] ?><?php echo " - " ?><?php echo $hote['jenis_kamar'] ?></option><?php } ?><?php } ?>"
                                                                                    );
                                                                                } else if (val == "pilih") {
                                                                                    $(".select_hotel").html(
                                                                                        "<option value='pilih1'>-- pilih kota terlebih dahulu --</option>"
                                                                                    );
                                                                                }
                                                                            <?php } ?>
                                                                        });
                                                                    </script>
                                                                    
                                                                    <script>
                                                                        function showKamarhotel(select) {
                                                                            if (select.value == 'Mess Kx Jkt - Standar') {
                                                                                document.getElementById('sisa_mess').style
                                                                                    .display = "block";
                                                                            } else {
                                                                                document.getElementById('sisa_mess').style
                                                                                    .display = "none";
                                                                            }
                                                                        }
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <?php if ($ako['nama_pool'] == '2. Pool Jakarta') { ?>
                                                            <div id="tanggal_kamar" style="display:none;">
                                                        <?php } else { ?>
                                                            <div id="tanggal_kamar" style="display:block;">
                                                        <?php } ?>
                                                            <div class="row">
                                                                <div class="col-lg-6">
                                                                    <div class="mb-1">
                                                                        Tanggal Masuk dan Jam Masuk
                                                                    </div>

                                                                    <input autocomplete="off" id='tanggal_jam_masuk' class="form-control mb-3" name="tanggal_jam_masuk" value="<?php echo(isset($tanggal_jam_masuk1)) ? $tanggal_jam_masuk1 : $ako['tanggal_jam_masuk'];?>">

                                                                    <script>
                                                                        $(function() {
                                                                            $.datetimepicker.setLocale('id');
                                                                            $('#tanggal_jam_masuk').datetimepicker({
                                                                                format: 'Y-m-d H:i',
                                                                                formatDate: 'Y-m-d',
                                                                                formatTime: 'H:i',
                                                                                minDate:'0',
                                                                                step: 1,
                                                                                closeOnTimeSelect : true,
                                                                                scrollMonth : false,
                                                                                scrollInput : false,
                                                                            });
                                                                        });
                                                                    </script>
                                                                </div>

                                                                <div class="col-lg-6">
                                                                    <div class="mb-1">
                                                                        Tanggal Keluar dan Jam Keluar
                                                                    </div>

                                                                    <input autocomplete="off" id='tanggal_jam_keluar' class="form-control mb-3" name="tanggal_jam_keluar" value="<?php echo(isset($tanggal_jam_keluar1)) ? $tanggal_jam_keluar1 : $ako['tanggal_jam_keluar'];?>">

                                                                    <script>
                                                                        $(function() {
                                                                            $.datetimepicker.setLocale('id');
                                                                            $('#tanggal_jam_keluar').datetimepicker({
                                                                                format: 'Y-m-d H:i',
                                                                                formatDate: 'Y-m-d',
                                                                                formatTime: 'H:i',
                                                                                minDate:'0',
                                                                                step: 1,
                                                                                closeOnTimeSelect : true,
                                                                                scrollMonth : false,
                                                                                scrollInput : false,
                                                                            });
                                                                        });
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-1">
                                                                    Type
                                                                </div>

                                                                <select class="select_type" id="type" name="type" style="width: 100%;">
                                                                    <option>
                                                                        <?php echo(isset($type1)) ? $type1 : $ako['type']; ?>
                                                                    </option>
                                                                </select>

                                                                <script>
                                                                    $(document).ready(function() {
                                                                        var data_select = ['Single', 'Twin / Double']

                                                                        $(".select_type").select2({
                                                                            data: data_select,
                                                                            // tags: true,
                                                                            // tokenSeparators: [',', ' '],
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>

                                                            <div class="col-lg-6">
                                                                <div class="col-md-12" style="height:90%;">
                                                                    
                                                                <?php if ($ako['nama_pool'] == '2. Pool Jakarta') { ?>
                                                                    <div id="jumlah_kamar" style="display:none;">
                                                                <?php } else { ?>
                                                                    <div id="jumlah_kamar" style="display:block;">
                                                                <?php } ?>
                                                                        <div class="mb-1">
                                                                            Jumlah Kamar
                                                                        </div>
                                                                    </div>

                                                                <?php if ($ako['nama_pool'] == '2. Pool Jakarta') { ?>
                                                                    <div id="jumlah_personil_mess" style="display:block;">
                                                                <?php } else { ?>
                                                                    <div id="jumlah_personil_mess" style="display:none;">
                                                                <?php } ?>
                                                                        <div class="mb-1">
                                                                            Jumlah Personil Menginap
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <input autocomplete="off" type="number" min="0" class="form-control mb-3" name="jumlah_kamar" id="jumlah_kamar" value="<?php echo(isset($jumlah_kamar1)) ? $jumlah_kamar1 : $ako['jumlah_kamar'];?>">
                                                                    <input hidden type="text" class="form-control" name="count" value="0">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="sisa_mess" style="display:none;">
                                                            <div class="mb-1">
                                                                Ketersediaan Kamar Mess
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-12">
                                                                    <select class="select_mess" id="mess" name="mess" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                        <option value='1'>Tersedia untuk : <?php foreach ($mess as $m => $mes) { $sisa_kamar = $mes['kapasitas_kamar'] - $mes['terpakai'];?><?php echo $sisa_kamar ?><?php } ?> orang</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <?php if ($ako['nama_pool'] == '2. Pool Jakarta') { ?>
                                                                    <div id="harga_hotel" style="display:none;">
                                                                <?php } else { ?>
                                                                    <div id="harga_hotel" style="display:block;">
                                                                <?php } ?>
                                                                    <div class="mb-1">
                                                                        Harga Total Hotel<a style="color: #e74a3b">*</a>
                                                                    </div>

                                                                    <input required type="text" autocomplete="off" class="form-control mb-3" name="harga_hotel" style="display:block;" placeholder="Rp" id="currency-field_hotel" data-type="currency" value="<?php echo "Rp"; echo(isset($harga_hotel1)) ? $harga_hotel1 : number_format($ako['harga_akomodasi'], 2, ',', '.'); ?>">

                                                                    <script>
                                                                        $(".select_hotel").change(function() {
                                                                            var val = $(this).val();

                                                                            if (val == "Mess Kx Jkt - Standar") {
                                                                                document.getElementById('harga_hotel').style.display = "none";
                                                                            } else {
                                                                                document.getElementById('harga_hotel').style.display = "block";
                                                                            }
                                                                        });

                                                                        $(".select_hotel_jkt").change(function() {
                                                                            var val = $(this).val();

                                                                            if (val == "Mess Kx Jkt - Standar") {
                                                                                document.getElementById('harga_hotel').style.display = "none";
                                                                            } else {
                                                                                document.getElementById('harga_hotel').style.display = "block";
                                                                            }
                                                                        });

                                                                        $("input[data-type='currency']").on({
                                                                            keyup: function() {
                                                                            formatCurrency($(this));
                                                                            },
                                                                            blur: function() { 
                                                                            formatCurrency($(this), "blur");
                                                                            }
                                                                        });

                                                                        function formatNumber(n) {
                                                                        // format number 1000000 to 1,234,567
                                                                        return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                                                                        }

                                                                        function formatCurrency(input, blur) {
                                                                        // appends $ to value, validates decimal side
                                                                        // and puts cursor back in right position.
                                                                        
                                                                        // get input value
                                                                        var input_val = input.val();
                                                                        
                                                                        // don't validate empty input
                                                                        if (input_val === "") { return; }
                                                                        
                                                                        // original length
                                                                        var original_len = input_val.length;

                                                                        // initial caret position 
                                                                        var caret_pos = input.prop("selectionStart");
                                                                            
                                                                        // check for decimal
                                                                        if (input_val.indexOf(",") >= 0) {

                                                                            // get position of first decimal
                                                                            // this prevents multiple decimals from
                                                                            // being entered
                                                                            var decimal_pos = input_val.indexOf(",");

                                                                            // split number by decimal point
                                                                            var left_side = input_val.substring(0, decimal_pos);
                                                                            var right_side = input_val.substring(decimal_pos);

                                                                            // add commas to left side of number
                                                                            left_side = formatNumber(left_side);

                                                                            // validate right side
                                                                            right_side = formatNumber(right_side);
                                                                            
                                                                            // On blur make sure 2 numbers after decimal
                                                                            if (blur === "blur") {
                                                                            right_side += "00";
                                                                            }
                                                                            
                                                                            // Limit decimal to only 2 digits
                                                                            right_side = right_side.substring(0, 2);

                                                                            // join number by .
                                                                            input_val = "Rp" + left_side + "," + right_side;

                                                                        } 
                                                                        else {
                                                                            // no decimal entered
                                                                            // add commas to number
                                                                            // remove all non-digits
                                                                            input_val = formatNumber(input_val);
                                                                            input_val = "Rp" + input_val;
                                                                            
                                                                            // final formatting
                                                                            if (blur === "blur") {
                                                                            input_val += "";
                                                                            }
                                                                        }
                                                                        
                                                                        // send updated string to input
                                                                        input.val(input_val);

                                                                        // put caret back in the right position
                                                                        var updated_len = input_val.length;
                                                                        caret_pos = updated_len - original_len + caret_pos;
                                                                        input[0].setSelectionRange(caret_pos, caret_pos);
                                                                        }
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mb-1">
                                                            Keterangan
                                                        </div>

                                                        <textarea class="form-control" id="keterangan_akomodasi" name="keterangan_akomodasi" rows="3" placeholder=""><?php echo(isset($keterangan_akomodasi1)) ? $keterangan_akomodasi1 : $ako['keterangan_akomodasi'];?></textarea>

                                                        <script>
                                                            $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                                if (event.which == '13') {
                                                                    event.preventDefault();
                                                                }
                                                            });
                                                        </script>
                                                        <div class="modal-footer">
                                                            <div class="col-xl-12 col-lg-12 mb-3">
                                                                <a style="color: #e74a3b">&nbsp;*Note: Masukkan nilai 0 jika belum mengetahui harga hotel</a>
                                                            </div>
                                                        </div>
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
                                            <?php foreach ($akomodasi as $ak => $ako) {?>
                                                <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                    <?php if ($ako['status_akomodasi'] == 0) { ?>
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
                                                    <?php } else if ($ako['status_akomodasi'] == 1) { ?>
                                                        
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