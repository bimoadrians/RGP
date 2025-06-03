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
                                    <?php foreach ($tiket as $ti => $tik) {?>
                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                            <?php if ($tik['status_tiket'] == 0) { ?>
                                                <h1>Edit Transaksi Tiket</h1>
                                            <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                <h1>Detail Transaksi Tiket</h1>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <a class="btn btn-secondary mb-3" href="<?php echo site_url("tiket_admin"); ?>" style="font-size:120%;"><i class="fa-solid fa-left-long"></i> Kembali</a>
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
                                                data-bs-target="#tab_tiket">
                                                <h3>Tiket</h3>
                                            </a>
                                        </li>
                                        <!-- End tab nav item -->

                                        <?php foreach ($trans as $t => $tra) {
                                            $id_trans = $tra['id_trans'];
                                        ?>
                                            <?php foreach ($tiket as $ti => $tik) {?>
                                                <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                    <?php if ($tik['status_tiket'] == 0) { ?>
                                                        <!-- <li id="modal_confirm" style="display:block;" class="nav-item col-3">
                                                            <a class="nav-link" data-bs-toggle="tab"
                                                                data-bs-target="#tab_confirm">
                                                                <h3>Confirm</h3>
                                                            </a>
                                                        </li> -->
                                                        <!-- End tab nav item -->
                                                    <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                        
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
                                                        <?php foreach ($tiket as $ti => $tik) {?>
                                                            <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                                <div class="mb-1 mt-3">
                                                                    Karyawan Konimex/Tamu
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <select class="select_tamu" id="tamu" name="tamu" style="width: 100%;" onchange="showTamu(this)">
                                                                            <option>
                                                                                <?php echo(isset($tamu1)) ? $tamu1 : $tik['tamu']; ?>
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

                                                                                        <?php if ($tik['tamu'] == 'Karyawan Konimex') { ?>
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
                                                                                                if($tik['pembayaran'] == 'k'){
                                                                                                    $pembayaran_tiket = 'Company Acc';
                                                                                                } else if($tik['pembayaran'] == 'p'){
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

                                                                                <?php if ($tik['tamu'] == 'Karyawan Konimex') { ?>
                                                                                    <div class="col-md-12 mt-1" style="height:70%;">
                                                                                        <input autocomplete="off" type="text" class="form-control" name="nama_inputan" style="text-transform:capitalize">
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <div class="col-md-12 mt-1" style="height:70%;">
                                                                                        <input autocomplete="off" type="text" class="form-control" name="nama_inputan" style="text-transform:capitalize" value="<?php echo(isset($nama_tamu1)) ? $nama_tamu1 : $tik['atas_nama']; ?>">
                                                                                    </div>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-lg-4">
                                                                            <div class="mt-3">
                                                                                <div>
                                                                                    Jabatan
                                                                                </div>

                                                                                <?php if ($tik['tamu'] == 'Karyawan Konimex') { ?>
                                                                                    <div class="col-md-12 mt-1" style="height:70%;">
                                                                                        <input autocomplete="off" type="text" class="form-control" name="jabatan_inputan" style="text-transform:capitalize">
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                    <div class="col-md-12 mt-1" style="height:70%;">
                                                                                        <input autocomplete="off" type="text" class="form-control" name="jabatan_inputan" style="text-transform:capitalize" value="<?php echo(isset($nama_jabatan1)) ? $nama_jabatan1 : $tik['jabatan']; ?>">
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
                                                                                                if($tik['pembayaran'] == 'k'){
                                                                                                    $pembayaran_tiket = 'Company Acc';
                                                                                                } else if($tik['pembayaran'] == 'p'){
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
                                                                    <?php if ($tik['tamu'] == 'Karyawan Konimex') { ?>
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

                                                    <div class="tab-pane" id="tab_tiket" style="font-size:120%">
                                                        <?php foreach ($tiket as $ti => $tik) {?>
                                                            <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                                <div class="mb-1">
                                                                    GS
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <select class="select_gs_tiket" id="gs_tiket" name="gs_tiket" style="width: 100%;">
                                                                            <option>
                                                                                <?php echo(isset($gs_tiket1)) ? $gs_tiket1 : $tik['nama_pool']; ?>
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <script>
                                                                    $(document).ready(function() {
                                                                        // var data_select = [
                                                                        //     <php foreach ($pool as $poo) : ?> "<php echo $poo['nama_pool']?>",
                                                                        //     <php endforeach ?>
                                                                        // ]
                                                                        var data_select = [
                                                                            "1. Pool Solo"
                                                                        ]

                                                                        $(".select_gs_tiket").select2({
                                                                            data: data_select,
                                                                            // tags: true,
                                                                            // tokenSeparators: [',', ' '],
                                                                        });
                                                                    });
                                                                </script>

                                                                <div class="row">
                                                                    <div class="col-lg-6 mb-3">
                                                                        <div class="mb-1">
                                                                            Pilihan Armada Transportasi
                                                                        </div>

                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <select class="select_pilihan_tiket" id="pilihan_tiket" name="pilihan_tiket" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                    <option>
                                                                                        <?php
                                                                                            if($tik['jenis_vendor'] == 'B'){
                                                                                                $jenis_vendor = 'Bis';
                                                                                            } else if($tik['jenis_vendor'] == 'K'){
                                                                                                $jenis_vendor = 'Kereta Api';
                                                                                            } else if($tik['jenis_vendor'] == 'P'){
                                                                                                $jenis_vendor = 'Pesawat';
                                                                                            } else if($tik['jenis_vendor'] == 'T'){
                                                                                                $jenis_vendor = 'Travel';
                                                                                            } else if($tik['jenis_vendor'] == 'Ka'){
                                                                                                $jenis_vendor = 'Kapal Laut';
                                                                                            }
                                                                                            echo(isset($pilihan_tiket1)) ? $pilihan_tiket1 : $jenis_vendor
                                                                                        ?>
                                                                                    </option>
                                                                                </select>

                                                                                <script>
                                                                                    $(document).ready(function() {
                                                                                        var data_select = [
                                                                                            //     <php foreach ($bag as $bag) : ?> "<php echo $bag['strorgnm']?>",
                                                                                            //     <php endforeach ?>
                                                                                            'Bis', 'Kereta Api', 'Pesawat', 'Travel', 'Kapal Laut'
                                                                                        ]

                                                                                        $(".select_pilihan_tiket").select2({
                                                                                            // tags:["Semua"],
                                                                                            data: data_select,
                                                                                            // tags: true,
                                                                                            // tokenSeparators: [',', ' '],
                                                                                        });
                                                                                    });
                                                                                </script>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-6">
                                                                        <div class="mb-1">
                                                                            Tiket
                                                                        </div>

                                                                        <div class="row mb-3">
                                                                            <div class="col-md-12">
                                                                                <select class="select_tiket" id="tiket" name="tiket" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                    <option>
                                                                                        <?php echo(isset($tiket1)) ? $tiket1 : $tik['nama_vendor'] ?>
                                                                                    </option>
                                                                                    <?php foreach ($vendor as $vt => $ven) {?>
                                                                                        <option>
                                                                                            <?php echo $ven['nama_vendor'] ?>
                                                                                        </option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <script>
                                                                            $(".select_pilihan_tiket").change(function() {
                                                                                var val = $(this).val();

                                                                                if (val == "Bis") {
                                                                                    $(".select_tiket").html(
                                                                                        <?php if (empty($bus)) {?>
                                                                                            "<option>Tidak ada data</option>"
                                                                                        <?php } else { ?>
                                                                                            "<?php foreach ($bus as $b => $bu) {?><option><?php echo $bu['nama_vendor']?></option><?php } ?>"
                                                                                        <?php } ?>
                                                                                    );
                                                                                } else if (val == "Kereta Api") {
                                                                                    $(".select_tiket").html(
                                                                                        <?php if (empty($kereta)) {?>
                                                                                            "<option>Tidak ada data</option>"
                                                                                        <?php } else { ?>
                                                                                            "<?php foreach ($kereta as $k => $ker) {?><option><?php echo $ker['nama_vendor']?></option><?php } ?>"
                                                                                        <?php } ?>
                                                                                    );
                                                                                } else if (val == "Pesawat") {
                                                                                    $(".select_tiket").html(
                                                                                        <?php if (empty($pesawat)) {?>
                                                                                            "<option>Tidak ada data</option>"
                                                                                        <?php } else { ?>
                                                                                            "<?php foreach ($pesawat as $p => $pes) {?><option><?php echo $pes['nama_vendor']?></option><?php } ?>"
                                                                                        <?php } ?>
                                                                                    );
                                                                                } else if (val == "Travel") {
                                                                                    $(".select_tiket").html(
                                                                                        <?php if (empty($travel)) {?>
                                                                                            "<option>Tidak ada data</option>"
                                                                                        <?php } else { ?>
                                                                                            "<?php foreach ($travel as $t => $tra) {?><option><?php echo $tra['nama_vendor']?></option><?php } ?>"
                                                                                        <?php } ?>
                                                                                    );
                                                                                } else if (val == "Kapal Laut") {
                                                                                    $(".select_tiket").html(
                                                                                        <?php if (empty($kapal_laut)) {?>
                                                                                            "<option>Tidak ada data</option>"
                                                                                        <?php } else { ?>
                                                                                            "<?php foreach ($kapal_laut as $ka => $kap) {?><option><?php echo $kap['nama_vendor']?></option><?php } ?>"
                                                                                        <?php } ?>
                                                                                    );
                                                                                } else if (val == "0") {
                                                                                    $(".select_tiket").html(
                                                                                        "<option>-- pilih armada transportasi terlebih dahulu --</option>"
                                                                                    );
                                                                                }
                                                                            });
                                                                        </script>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <div class="mb-1">
                                                                            Keberangkatan
                                                                        </div>

                                                                        <div class="row mb-3">
                                                                            <div class="col-md-12">
                                                                                <select class="select_keberangkatan" id="keberangkatan" name="keberangkatan" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                    <option>
                                                                                        <?php echo(isset($keberangkatan1)) ? $keberangkatan1 : $berangkat_tiket['nama_pemberhentian'] ?> <?php echo "-" ?> <?php echo $berangkat_tiket['nama_kota']?>
                                                                                    </option>
                                                                                    <?php foreach ($berangkat as $bt => $berang) {?>
                                                                                        <option>
                                                                                            <?php echo $berang['nama_pemberhentian'] ?> <?php echo "-" ?> <?php echo $berang['nama_kota']?>
                                                                                        </option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <script>
                                                                            $(".select_pilihan_tiket").change(function() {
                                                                                var val = $(this).val();

                                                                                if (val == "Pesawat") {
                                                                                    $(".select_keberangkatan").html(
                                                                                        <?php if (empty($bandara)) {?>
                                                                                            "<option>Tidak ada data</option>"
                                                                                        <?php } else { ?>
                                                                                            "<?php foreach ($bandara as $b => $ba) {?><option><?php echo $ba['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $ba['nama_kota']?></option><?php } ?>"
                                                                                        <?php } ?>
                                                                                    );
                                                                                } else if (val == "Kapal Laut") {
                                                                                    $(".select_keberangkatan").html(
                                                                                        <?php if (empty($pelabuhan)) {?>
                                                                                            "<option>Tidak ada data</option>"
                                                                                        <?php } else { ?>
                                                                                            "<?php foreach ($pelabuhan as $p => $pe) {?><option><?php echo $pe['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $pe['nama_kota']?></option><?php } ?>"
                                                                                        <?php } ?>
                                                                                    );
                                                                                } else if (val == "Kereta Api") {
                                                                                    $(".select_keberangkatan").html(
                                                                                        <?php if (empty($stasiun)) {?>
                                                                                            "<option>Tidak ada data</option>"
                                                                                        <?php } else { ?>
                                                                                            "<?php foreach ($stasiun as $s => $st) {?><option><?php echo $st['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $st['nama_kota']?></option><?php } ?>"
                                                                                        <?php } ?>
                                                                                    );
                                                                                } else if (val == "Bis") {
                                                                                    $(".select_keberangkatan").html(
                                                                                        <?php if (empty($terminal)) {?>
                                                                                            "<option>Tidak ada data</option>"
                                                                                        <?php } else { ?>
                                                                                            "<?php foreach ($terminal as $t => $te) {?><option><?php echo $te['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $te['nama_kota']?></option><?php } ?>"
                                                                                        <?php } ?>
                                                                                    );
                                                                                } else if (val == "Travel") {
                                                                                    $(".select_keberangkatan").html(
                                                                                        "<?php foreach ($kota as $ko => $kot) {?><option><?php echo $kot['nama_kota']?></option><?php } ?>"
                                                                                    );
                                                                                } else if (val == "0") {
                                                                                    $(".select_keberangkatan").html(
                                                                                        "<option>-- pilih armada transportasi terlebih dahulu --</option>"
                                                                                    );
                                                                                }
                                                                            });
                                                                        </script>
                                                                    </div>

                                                                    <div class="col-lg-6">
                                                                        <div class="mb-1">
                                                                            Pemberhentian
                                                                        </div>

                                                                        <div class="row mb-3">
                                                                            <div class="col-md-12">
                                                                                <select class="select_pemberhentian" id="pemberhentian" name="pemberhentian" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                    <option>
                                                                                        <?php echo(isset($pemberhentian1)) ? $pemberhentian1 : $berhenti_tiket['nama_pemberhentian'] ?> <?php echo "-" ?> <?php echo $berhenti_tiket['nama_kota']?>
                                                                                    </option>
                                                                                    <?php foreach ($berhenti as $be => $berhe) {?>
                                                                                        <option>
                                                                                            <?php echo $berhe['nama_pemberhentian'] ?> <?php echo "-" ?> <?php echo $berhe['nama_kota']?>
                                                                                        </option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                        </div>

                                                                        <script>
                                                                            $(".select_pilihan_tiket").change(function() {
                                                                                var val = $(this).val();

                                                                                if (val == "Pesawat") {
                                                                                    $(".select_pemberhentian").html(
                                                                                        <?php if (empty($bandara)) {?>
                                                                                            "<option>Tidak ada data</option>"
                                                                                        <?php } else { ?>
                                                                                            "<?php foreach ($bandara as $b => $ba) {?><option><?php echo $ba['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $ba['nama_kota']?></option><?php } ?>"
                                                                                        <?php } ?>
                                                                                    );
                                                                                } else if (val == "Kapal Laut") {
                                                                                    $(".select_pemberhentian").html(
                                                                                        <?php if (empty($pelabuhan)) {?>
                                                                                            "<option>Tidak ada data</option>"
                                                                                        <?php } else { ?>
                                                                                            "<?php foreach ($pelabuhan as $p => $pe) {?><option><?php echo $pe['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $pe['nama_kota']?></option><?php } ?>"
                                                                                        <?php } ?>
                                                                                    );
                                                                                } else if (val == "Kereta Api") {
                                                                                    $(".select_pemberhentian").html(
                                                                                        <?php if (empty($stasiun)) {?>
                                                                                            "<option>Tidak ada data</option>"
                                                                                        <?php } else { ?>
                                                                                            "<?php foreach ($stasiun as $s => $st) {?><option><?php echo $st['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $st['nama_kota']?></option><?php } ?>"
                                                                                        <?php } ?>
                                                                                    );
                                                                                } else if (val == "Bis") {
                                                                                    $(".select_pemberhentian").html(
                                                                                        <?php if (empty($terminal)) {?>
                                                                                            "<option>Tidak ada data</option>"
                                                                                        <?php } else { ?>
                                                                                            "<?php foreach ($terminal as $t => $te) {?><option><?php echo $te['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $te['nama_kota']?></option><?php } ?>"
                                                                                        <?php } ?>
                                                                                    );
                                                                                } else if (val == "Travel") {
                                                                                    $(".select_pemberhentian").html(
                                                                                        "<?php foreach ($kota as $ko => $kot) {?><option><?php echo $kot['nama_kota']?></option><?php } ?>"
                                                                                    );
                                                                                } else if (val == "0") {
                                                                                    $(".select_pemberhentian").html(
                                                                                        "<option>-- pilih armada transportasi terlebih dahulu --</option>"
                                                                                    );
                                                                                }
                                                                            });
                                                                        </script>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-lg-4 mb-3">
                                                                        <div class="mb-1">
                                                                            Jumlah Tiket
                                                                        </div>

                                                                        <input autocomplete="off" type="number" min="0" class="form-control" name="jumlah_tiket" id="jumlah_tiket" value="<?php echo(isset($jumlah_tiket1)) ? $jumlah_tiket1 : $tik['jumlah_tiket'];?>">
                                                                    </div>

                                                                    <div class="col-lg-4 mb-3">
                                                                        <div class="mb-1">
                                                                            Harga Total Tiket<a style="color: #e74a3b">*</a>
                                                                        </div>

                                                                        <input required type="text" autocomplete="off" class="form-control mb-3" name="harga_tiket" placeholder="Rp" id="currency-field_tiket" data-type="currency" value="<?php echo "Rp"; echo(isset($harga_tiket1)) ? $harga_tiket1 : number_format($tik['harga_tiket'], 2, ',', '.'); ?>">
                                                                        
                                                                        <script>
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

                                                                    <div class="col-lg-4 mb-3">
                                                                        <div class="mb-1">
                                                                            Tanggal dan Jam
                                                                        </div>

                                                                        <input autocomplete="off" id='tanggal_jam_tiket' class="form-control" name="tanggal_jam_tiket" value="<?php echo(isset($tanggal_jam_tiket1)) ? $tanggal_jam_tiket1 : $tik['tanggal_jam_tiket'];?>">
                                                                        
                                                                        <script>
                                                                            $(function() {
                                                                                $.datetimepicker.setLocale('id');
                                                                                $('#tanggal_jam_tiket').datetimepicker({
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

                                                                <div class="mb-1">
                                                                    Keterangan
                                                                </div>
                                                                
                                                                <textarea class="form-control" name="keterangan_tiket" rows="3" placeholder=""><?php echo(isset($keterangan_tiket1)) ? $keterangan_tiket1 : $tik['keterangan_tiket'];?></textarea>

                                                                <script>
                                                                    $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                                        if (event.which == '13') {
                                                                            event.preventDefault();
                                                                        }
                                                                    });
                                                                </script>
                                                            <?php } ?>
                                                        <?php } ?>
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
                                            <?php foreach ($tiket as $ti => $tik) {?>
                                                <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                    <?php if ($tik['status_tiket'] == 0) { ?>
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
                                                    <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                        
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