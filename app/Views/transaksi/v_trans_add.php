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
                <a class="btn btn-secondary mb-3" href="trans" style="font-size:120%;"><i class="fa-solid fa-left-long"></i> Kembali</a>
                <button id="add_item_btn" class="btn btn-success add_item_btn mb-3" style="font-size:120%;"><i class="fa-solid fa-plus"></i> Tambah Personil</button>
                <button id="remove_item_btn" class="btn btn-danger mb-3" style="font-size:120%;"><i class="fa-solid fa-minus"></i> Hapus Semua Personil</button>
                <form action="" method="post" enctype="multipart/form-data" onsubmit="return check(this);">
                    <div class="card">
                        <div class="d-flex align-items-center row">
                            <div class="col-sm-12">
                                <div class="card-body">
                                    <h1>Transaksi</h1>
                                    <section id="features" class="features"  style="font-size:150%;">
                                        <ul class="nav nav-tabs row g-2 d-flex mb-3">
                                            <li class="nav-item col-3">
                                                <a class="nav-link active show" data-bs-toggle="tab"
                                                    data-bs-target="#tab_start">
                                                    <h3>Personil</h3>
                                                </a>
                                            </li>
                                            <!-- End tab nav item -->

                                            <li id="modal_tiket" style="display:none;" class="nav-item col-3">
                                                <a class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#tab_tiket">
                                                    <h3>Tiket</h3>
                                                </a>
                                            </li>
                                            <!-- End tab nav item -->

                                            <li id="modal_hotel" style="display:none;" class="nav-item col-3">
                                                <a class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#tab_hotel">
                                                    <h3>Hotel</h3>
                                                </a>
                                            </li>
                                            <!-- End tab nav item -->

                                            <li id="modal_mobil" style="display:none;" class="nav-item col-3">
                                                <a class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#tab_mobil">
                                                    <h3>Mobil</h3>
                                                </a>
                                            </li>
                                            <!-- End tab nav item -->
                                        </ul>
                                        <div class="modal-body">
                                            <div class="tab-content">
                                                <div class="tab-pane active show" id="tab_start" style="font-size:120%">
                                                    <div class="row">
                                                        <div class="col-lg-6 mb-3">
                                                            <div class="mb-1">
                                                                Pemesanan
                                                            </div>
                                                            <select id="test" class="test" name="pemesanan[]" onchange="showDiv(this)"
                                                                style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                <option value="pilih">-- pilih salah satu --</option>
                                                                <option value="0">Tiket</option>
                                                                <option value="1">Hotel</option>
                                                                <option value="2">Mobil</option>
                                                                <option value="3">Tiket + Hotel</option>
                                                                <option value="4">Tiket + Mobil</option>
                                                                <option value="5">Hotel + Mobil</option>
                                                                <option value="6">Tiket + Hotel + Mobil</option>
                                                            </select>
                                                            <script>
                                                                function showDiv(select) {
                                                                    if (select.value == 'pilih') {
                                                                        document.getElementById('modal_tiket').style
                                                                            .display = "none";
                                                                        document.getElementById('modal_hotel').style
                                                                            .display = "none";
                                                                        document.getElementById('modal_mobil').style
                                                                            .display = "none";
                                                                    } else if (select.value == 0) {
                                                                        document.getElementById('modal_tiket').style
                                                                            .display = "block";
                                                                        document.getElementById('modal_hotel').style
                                                                            .display = "none";
                                                                        document.getElementById('modal_mobil').style
                                                                            .display = "none";
                                                                    } else if (select.value == 1) {
                                                                        document.getElementById('modal_tiket').style
                                                                            .display = "none";
                                                                        document.getElementById('modal_hotel').style
                                                                            .display = "block";
                                                                        document.getElementById('modal_mobil').style
                                                                            .display = "none";
                                                                    } else if (select.value == 2) {
                                                                        document.getElementById('modal_tiket').style
                                                                            .display = "none";
                                                                        document.getElementById('modal_hotel').style
                                                                            .display = "none";
                                                                        document.getElementById('modal_mobil').style
                                                                            .display = "block";
                                                                    } else if (select.value == 3) {
                                                                        document.getElementById('modal_tiket').style
                                                                            .display = "block";
                                                                        document.getElementById('modal_hotel').style
                                                                            .display = "block";
                                                                        document.getElementById('modal_mobil').style
                                                                            .display = "none";
                                                                    } else if (select.value == 4) {
                                                                        document.getElementById('modal_tiket').style
                                                                            .display = "block";
                                                                        document.getElementById('modal_hotel').style
                                                                            .display = "none";
                                                                        document.getElementById('modal_mobil').style
                                                                            .display = "block";
                                                                    } else if (select.value == 5) {
                                                                        document.getElementById('modal_tiket').style
                                                                            .display = "none";
                                                                        document.getElementById('modal_hotel').style
                                                                            .display = "block";
                                                                        document.getElementById('modal_mobil').style
                                                                            .display = "block";
                                                                    } else if (select.value == 6) {
                                                                        document.getElementById('modal_tiket').style
                                                                            .display = "block";
                                                                        document.getElementById('modal_hotel').style
                                                                            .display = "block";
                                                                        document.getElementById('modal_mobil').style
                                                                            .display = "block";
                                                                    }
                                                                }
                                                            </script>
                                                        </div>

                                                        <div class="col-lg-6 mb-3">
                                                            <div class="mb-1">
                                                                Karyawan Konimex/Tamu
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <select class="select_tamu" id="tamu" name="tamu[]" style="width: 100%;" onchange="showTamu(this)">
                                                                        <option>-- pilih pemesanan terlebih dahulu --</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <script>
                                                                $(document).ready(function() {
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
                                                                $(".test").change(function() {
                                                                    var val = $(this).val();

                                                                    if (val == "0" || val == "1" || val == "3"|| val == "4" || val == "5" || val == "6") {
                                                                        $(".select_tamu").html(
                                                                            "<option>Karyawan Konimex</option><option>Tamu</option>"
                                                                        );
                                                                        document.getElementById('pilih_nama').style.display =
                                                                            "none";
                                                                        document.getElementById('nama_inputan').style.display =
                                                                            "none";
                                                                        document.getElementById('nama_select').style.display =
                                                                            "block";
                                                                        document.getElementById('lain-lain').style.display =
                                                                            "block";
                                                                        document.getElementById('footer').style.display =
                                                                            "none";
                                                                    } else if (val == "2") {
                                                                        $(".select_tamu").html(
                                                                            "<option>Karyawan Konimex</option><option>Tamu</option><option>MNJ</option>"
                                                                        );
                                                                        document.getElementById('pilih_nama').style.display =
                                                                            "none";
                                                                        document.getElementById('nama_inputan').style.display =
                                                                            "none";
                                                                        document.getElementById('nama_select').style.display =
                                                                            "block";
                                                                        document.getElementById('lain-lain').style.display =
                                                                            "block";
                                                                        document.getElementById('footer').style.display =
                                                                            "none";
                                                                    } else if (val == "pilih") {
                                                                        $(".select_tamu").html(
                                                                            "<option>-- pilih pemesanan terlebih dahulu --</option>"
                                                                        );
                                                                        document.getElementById('pilih_nama').style.display =
                                                                            "none";
                                                                        document.getElementById('nama_inputan').style.display =
                                                                            "none";
                                                                        document.getElementById('nama_select').style.display =
                                                                            "none";
                                                                        document.getElementById('lain-lain').style.display =
                                                                            "none";
                                                                        document.getElementById('footer').style.display =
                                                                            "none";
                                                                    }
                                                                });
                                                                function showTamu(select) {
                                                                    if (select.value == 'Karyawan Konimex') {
                                                                        document.getElementById('pilih_nama').style.display =
                                                                            "none";
                                                                        document.getElementById('nama_inputan').style.display =
                                                                            "none";
                                                                        document.getElementById('nama_select').style.display =
                                                                            "block";
                                                                        document.getElementById('lain-lain').style.display =
                                                                            "block";
                                                                    } else if (select.value == 'Tamu') {
                                                                        document.getElementById('pilih_nama').style.display =
                                                                            "none";
                                                                        document.getElementById('nama_inputan').style.display =
                                                                            "block";
                                                                        document.getElementById('nama_select').style.display =
                                                                            "none";
                                                                        document.getElementById('lain-lain').style.display =
                                                                            "block";
                                                                    } else if (select.value == 'MNJ') {
                                                                        document.getElementById('pilih_nama').style.display =
                                                                            "none";
                                                                        document.getElementById('nama_inputan').style.display =
                                                                            "block";
                                                                        document.getElementById('nama_select').style.display =
                                                                            "none";
                                                                        document.getElementById('lain-lain').style.display =
                                                                            "block";
                                                                    }
                                                                }
                                                            </script>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div id="pilih_nama" style="display:none;">
                                                            <div class="mb-1">
                                                                Personil
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <select class="select_pilih_nama" name="pilih_nama[]" onchange="showPilih(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                        <option value='pilih'>-- pilih pemesanan terlebih dahulu --</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            
                                                            <script>
                                                                function showPilih(select) {
                                                                    if (select.value == '0') {
                                                                        document.getElementById('nama_inputan').style.display =
                                                                            "none";
                                                                        document.getElementById('nama_select').style.display =
                                                                            "block";
                                                                        document.getElementById('lain-lain').style.display =
                                                                            "block";
                                                                    } else if (select.value == '1') {
                                                                        document.getElementById('nama_inputan').style.display =
                                                                            "block";
                                                                        document.getElementById('nama_select').style.display =
                                                                            "none";
                                                                        document.getElementById('lain-lain').style.display =
                                                                            "block";
                                                                    } else if (select.value == 'pilih') {
                                                                        document.getElementById('nama_inputan').style.display =
                                                                            "none";
                                                                        document.getElementById('nama_select').style.display =
                                                                            "none";
                                                                        document.getElementById('lain-lain').style.display =
                                                                            "none";
                                                                    }
                                                                }
                                                                $(".test").change(function() {
                                                                    var val = $(this).val();

                                                                    if (val == "pilih") {
                                                                        $(".select_pilih_nama").html(
                                                                            "<option value='pilih'>-- pilih pemesanan terlebih dahulu --</option>"
                                                                        );
                                                                    } else {
                                                                        $(".select_pilih_nama").html(
                                                                            "<option value='pilih'>-- pilih salah satu --</option><option value='0'>Hanya untuk 1 personil</option><option value='1'>Beberapa personil dengan 1 tujuan yang sama</option>"
                                                                        );
                                                                    }
                                                                });
                                                                
                                                                $(".select_tamu").change(function() {
                                                                    var val = $(this).val();

                                                                    if (val == "Karyawan Konimex") {
                                                                        $(".select_pilih_nama").html(
                                                                            "<option value='pilih'>-- pilih salah satu --</option><option value='0'>Hanya untuk 1 personil</option><option value='1'>Beberapa personil dengan 1 tujuan yang sama</option>"
                                                                        );
                                                                    } else if (val == "Tamu" || val == "MNJ") {
                                                                        $(".select_pilih_nama").html(
                                                                            "<option value='pilih'>-- pilih salah satu --</option><option value='0'>Hanya untuk 1 personil</option><option value='1'>Beberapa personil dengan 1 tujuan yang sama</option>"
                                                                        );
                                                                    } else if (val == "pilih") {
                                                                        $(".select_pilih_nama").html(
                                                                            "<option value='pilih'>-- pilih pemesanan terlebih dahulu --</option>"
                                                                        );
                                                                    }
                                                                });
                                                            </script>
                                                        </div>
                                                    </div>

                                                    <div id="nama_select" style="display:none;">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mt-3">
                                                                    Nama
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <select class="select_nama" id="nama_select0" name="nama_select0[]" multiple="multiple" style="width: 100%;"></select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <script>
                                                                    $(document).ready(function() {
                                                                        var data_select = [
                                                                            <?php foreach ($pengguna as $peng) : ?> "<?php echo $peng['nama_pengguna']?><?php echo " - "?><?php echo $peng['nama_jabatan']?><?php echo " - "?><?php echo $peng['jenis_kelamin']?>",
                                                                            <?php endforeach ?>
                                                                        ]

                                                                        $(".select_nama").select2({
                                                                            data: data_select,
                                                                            // tags: true,
                                                                            // tags:["Semua"],
                                                                            // tokenSeparators: [',', ' '],
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>
                                                            
                                                            <!-- <div class="col-lg-4">
                                                                <div class="mt-3">
                                                                    Jabatan
                                                                </div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <select class="select_jabatan" id="jabatan_select0" name="jabatan_select0[]" multiple="multiple" style="width: 100%;"></select>
                                                                    </div>
                                                                </div>
                                                                
                                                                <script>
                                                                    $(document).ready(function() {
                                                                        var data_select = [
                                                                            //     <php foreach ($bag as $bag) : ?> "<php echo $bag['strorgnm']?>",
                                                                            //     <php endforeach ?>
                                                                            'Direksi', 'Manager', 'Officer', 'Supervisor', 'Pelaksana'
                                                                        ]

                                                                        $(".select_jabatan").select2({
                                                                            // tags:["Semua"],
                                                                            data: data_select,
                                                                            // tags: true,
                                                                            // tokenSeparators: [',', ' '],
                                                                        });
                                                                    });
                                                                </script>
                                                            </div> -->

                                                            <div class="col-lg-6">
                                                                <div class="mt-3">
                                                                    Pembayaran
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <select class="select_pembayaran" name="pembayaran[]" style="width: 100%;"></select>
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
                                                                    });
                                                                </script>
                                                                <!-- <div class="mt-3">
                                                                    PIC
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <select class="select_pic" name="pic_select[]" multiple="multiple" style="width: 100%;"></select>
                                                                    </div>
                                                                </div> -->

                                                                <!-- <script>
                                                                $(document).ready(function() {
                                                                    var data_select = [
                                                                        <php foreach ($pengguna as $peng) : ?> "<php echo $peng['nama_pengguna']?>",
                                                                        <php endforeach ?>'Tamu'
                                                                    ]

                                                                    $(".select_pic").select2({
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
                                                                </script> -->
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="nama_inputan" style="display:none;">
                                                        <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="mt-3">
                                                                    Nama<a style="color: #e74a3b">*</a>
                                                                </div>
                                                                
                                                                <div class="col-lg-12 mt-1" style="height:80%;">
                                                                    <input autocomplete="off" type="text" class="form-control" id="nama_input" name="nama_inputan[]" style="text-transform:capitalize" placeholder="Nama 1, Nama 2">
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-lg-4">
                                                                <div class="mt-3">
                                                                    Jabatan<a style="color: #e74a3b">*</a>
                                                                </div>
                                                                
                                                                <div class="col-lg-12 mt-1" style="height:80%;">
                                                                    <input autocomplete="off" type="text" class="form-control" id="jabatan_input" name="jabatan_inputan[]" style="text-transform:capitalize" placeholder="Jabatan 1, Jabatan 2">
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-4">
                                                                <div class="mt-3">
                                                                    Pembayaran
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <select class="select_pembayaran" name="pembayaran_inputan[]" style="width: 100%;"></select>
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
                                                                    });
                                                                </script>
                                                                <!-- <div class="mt-3">
                                                                    PIC
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <select class="select_pic" name="pic_inputan[]" style="width: 100%;"></select>
                                                                    </div>
                                                                </div>

                                                                <script>
                                                                $(document).ready(function() {
                                                                    var data_select = [
                                                                        <php foreach ($pengguna as $peng) : ?> "<php echo $peng['nama_pengguna']?>",
                                                                        <php endforeach ?>'Tamu'//tambahin ? di depan tulisan php
                                                                    ]

                                                                    $(".select_pic").select2({
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
                                                                </script> -->
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="lain-lain" style="display:none;">
                                                        <!-- <div class="row">
                                                            <div class="col-lg-4">
                                                                <div class="mb-1">
                                                                    Email Informasi
                                                                </div>
                                                                
                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <select class="select_email_info" id="email_info" name="email_info[]" style="width: 100%;" data-select2-tags="false"></select>
                                                                    </div>
                                                                </div>

                                                                <script>
                                                                    $(document).ready(function() {
                                                                        var data_select = [
                                                                            <php foreach ($pengguna as $peng) : ?> "<php echo $peng['nama_pengguna']?>",
                                                                            <php endforeach ?>//tambahin ? di depan tulisan php
                                                                        ]

                                                                        $(".select_email_info").select2({
                                                                            // tags:["Semua"],
                                                                            data: data_select,
                                                                            // tags: true,
                                                                            // tokenSeparators: [',', ' '],
                                                                        });
                                                                    });
                                                                </script>
                                                            </div>
                                                                
                                                            <div class="col-lg-4">
                                                                <div class="mb-1">
                                                                    Email Evaluasi
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <select class="select_email_eval" id="email_eval" name="email_eval[]" style="width: 100%;"></select>
                                                                    </div>
                                                                </div>

                                                                <script>
                                                                $(document).ready(function() {
                                                                    var data_select = [
                                                                        <php foreach ($pengguna as $peng) : ?> "<php echo $peng['nama_pengguna']?>",
                                                                        <php endforeach ?>
                                                                    ]

                                                                    $(".select_email_eval").select2({
                                                                        // tags:["Semua"],
                                                                        data: data_select,
                                                                        // tags: true,
                                                                        // tokenSeparators: [',', ' '],
                                                                    });
                                                                });
                                                                </script>
                                                            </div>

                                                            <div class="col-lg-4">
                                                                <div class="mb-1">
                                                                    Pembayaran
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <select class="select_pembayaran" id="pembayaran" name="pembayaran[]" style="width: 100%;"></select>
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
                                                                });
                                                                </script>
                                                            </div>
                                                        </div> -->
                                                    </div>

                                                    <script>
                                                        $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                            if (event.which == '13') {
                                                                event.preventDefault();
                                                            }
                                                        });
                                                    </script>
                                                    
                                                    <div id="footer" style="display:none;">
                                                        <div class="modal-footer">
                                                            <div class="col-xl-12 col-lg-12 mb-3">
                                                                <a style="color: #e74a3b">&nbsp;*Note: Jika tamu lebih dari satu, silahkan menggunakan tanda baca koma ( , ) sebagai pemisah</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <script>
                                                        $(".select_tamu").change(function() {
                                                            var val = $(this).val();

                                                            if (val == "Karyawan Konimex") {
                                                                document.getElementById('footer').style.display =
                                                                    "none";
                                                            } else if (val == "Tamu" || val == "MNJ") {
                                                                document.getElementById('footer').style.display =
                                                                    "block";
                                                            } else if (val == "pilih") {
                                                                document.getElementById('footer').style.display =
                                                                    "none";
                                                            }
                                                        });
                                                    </script>
                                                </div>

                                                <div class="tab-pane" id="tab_tiket" style="font-size:120%">
                                                    <div class="mb-1">
                                                        GS
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <select class="select_gs_tiket" id="gs_tiket" name="gs_tiket[]" style="width: 100%;"></select>
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
                                                                    <select class="select_pilihan_tiket" id="pilihan_tiket" name="pilihan_tiket[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                        <option value="0">-- pilih salah satu --</option>
                                                                        <option>Bis</option>
                                                                        <option>Kereta Api</option>
                                                                        <option>Pesawat</option>
                                                                        <option>Travel</option>
                                                                        <option>Kapal Laut</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <div class="mb-1">
                                                                Tiket
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-12">
                                                                    <select class="select_tiket" id="tiket" name="tiket[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                        <option>-- pilih armada transportasi terlebih dahulu --</option>
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
                                                                    <select class="select_keberangkatan" id="keberangkatan" name="keberangkatan[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                        <option>-- pilih armada transportasi terlebih dahulu --</option>
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
                                                                    <select class="select_pemberhentian" id="pemberhentian" name="pemberhentian[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                        <option>-- pilih armada transportasi terlebih dahulu --</option>
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

                                                            <input autocomplete="off" type="number" min="0" class="form-control" name="jumlah_tiket[]" id="jumlah_tiket">
                                                        </div>

                                                        <div class="col-lg-4 mb-3">
                                                            <div class="mb-1">
                                                                Harga Total Tiket<a style="color: #e74a3b">*</a>
                                                            </div>

                                                            <input required type="text" autocomplete="off" class="form-control mb-3" name="harga_tiket[]" placeholder="Rp" id="currency-field_tiket" data-type="currency" value="<?php echo "0"; ?>">
                                                            
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

                                                            <input autocomplete="off" id='tanggal_jam_tiket' class="form-control" name="tanggal_jam_tiket[]">
                                                            
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
                                                    
                                                    <textarea class="form-control" id="keterangan_tiket" name="keterangan_tiket[]" rows="3" placeholder=""></textarea>

                                                    <script>
                                                        $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                            if (event.which == '13') {
                                                                event.preventDefault();
                                                            }
                                                        });
                                                    </script>

                                                    <div class="modal-footer">
                                                        <div class="col-xl-12 col-lg-12 mb-3">
                                                            <a style="color: #e74a3b">&nbsp;*Note: Masukkan nilai 0 jika belum mengetahui harga tiket</a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="tab-pane" id="tab_hotel" style="font-size:120%">
                                                    <div class="mb-1">
                                                        GS
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <select class="select_gs_hotel" id="gs_hotel" name="gs_hotel[]" onchange="showGshotel(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;"></select>
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

                                                    <div id="kota_kamar_jkt" style="display:none;">
                                                        <div id="mnj_pesan" style="display:none;">
                                                            <div class="mb-1">
                                                                Pesan Mess untuk MNJ?
                                                            </div>

                                                            <div class="row mb-3">
                                                                <div class="col-md-12">
                                                                    <select name="pesan_mnj[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                        <option>Tidak</option>
                                                                        <option>Iya</option>
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
                                                                        <select class="select_kota_jkt" id="kota_jkt" name="kota_jkt[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
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
                                                                        <select class="select_hotel_jkt" id="hotel_jkt" name="hotel_jkt[]" onchange="showKamarhoteljkt(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
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

                                                    <div id="tanggal_kamar_jkt" style="display:none;">
                                                        <div class="row">
                                                            <div class="col-lg-6 mb-2">
                                                                <div class="mb-1">
                                                                    Tanggal Masuk dan Jam Masuk
                                                                </div>

                                                                <input autocomplete="off" id='tanggal_jam_masuk_jkt' class="form-control mb-3" name="tanggal_jam_masuk_jkt[]" onchange="handler(event);">

                                                                <script>
                                                                    $(function() {
                                                                        $.datetimepicker.setLocale('id');
                                                                        $('#tanggal_jam_masuk_jkt').datetimepicker({
                                                                            format: 'Y-m-d H:i',
                                                                            formatDate: 'Y-m-d',
                                                                            formatTime: 'H:i',
                                                                            minDate:'0',
                                                                            step: 1,
                                                                            disabledDates: [
                                                                                <?php foreach ($merged as $me) : ?> "<?php echo $me?>",
                                                                                <?php endforeach ?>
                                                                            ],
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

                                                                <input autocomplete="off" id='tanggal_jam_keluar_jkt' class="form-control mb-3" name="tanggal_jam_keluar_jkt[]">
                                                                <script>
                                                                    $(function() {
                                                                        $.datetimepicker.setLocale('id');
                                                                        $('#tanggal_jam_keluar_jkt').datetimepicker({
                                                                            format: 'Y-m-d H:i',
                                                                            formatDate: 'Y-m-d',
                                                                            formatTime: 'H:i',
                                                                            minDate:'0',
                                                                            step: 1,
                                                                            disabledDates: [
                                                                                <?php foreach ($merged as $me) : ?> "<?php echo $me?>",
                                                                                <?php endforeach ?>
                                                                            ],
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
                                                                        <select class="select_sisa_mess_masuk" name="sisa_mess_masuk[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
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

                                                    <div id="kota_kamar" style="display:block;">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-1">
                                                                    Kota
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <select class="select_kota" id="kota" name="kota[]" onchange="showKotahotel(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                            <option value="pilih">-- pilih kota --</option>
                                                                            <?php foreach ($negara as $n => $neg) {?>
                                                                                <optgroup label=<?php echo $neg['nama_negara'] ?>>
                                                                                    <?php foreach ($kota as $k => $kot) {?>
                                                                                        <?php if ($neg['id_negara'] == $kot['id_negara']) { ?>
                                                                                            <option><?php echo $kot['nama_kota'] ?></option>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                </optgroup>
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
                                                                        <select class="select_hotel" id="hotel" name="hotel[]" onchange="showKamarhotel(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                            <option value="pilih1">-- pilih kota terlebih dahulu --</option>
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

                                                    <div id="tanggal_kamar" style="display:block;">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="mb-1">
                                                                    Tanggal Masuk dan Jam Masuk
                                                                </div>

                                                                <input autocomplete="off" id='tanggal_jam_masuk' class="form-control mb-3" name="tanggal_jam_masuk[]">

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

                                                                <input autocomplete="off" id='tanggal_jam_keluar' class="form-control mb-3" name="tanggal_jam_keluar[]">
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

                                                            <div class="row mb-3">
                                                                <div class="col-md-12">
                                                                    <select class="select_type" id="type" name="type[]" style="width: 100%;"></select>
                                                                </div>
                                                            </div>

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
                                                                <div id="jumlah_kamar" style="display:block;">
                                                                    <div class="mb-1">
                                                                        Jumlah Kamar
                                                                    </div>
                                                                </div>

                                                                <div id="jumlah_personil_mess" style="display:none;">
                                                                    <div class="mb-1">
                                                                        Jumlah Personil Menginap
                                                                    </div>
                                                                </div>
                                                                
                                                                <input autocomplete="off" type="number" min="0" class="form-control mb-3" name="jumlah_kamar[]" id="jumlah_kamar">
                                                                <input hidden type="text" class="form-control" name="count[]" value="0">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="sisa_mess" style="display:none;">
                                                        <div class="mb-1">
                                                            Ketersediaan Kamar Mess
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <select class="select_mess" id="mess" name="mess[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                    <option value='1'>Tersedia untuk : <?php foreach ($mess as $m => $mes) { $sisa_kamar = $mes['kapasitas_kamar'] - $mes['terpakai'];?><?php echo $sisa_kamar ?><?php } ?> orang</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div id="harga_hotel" style="display:block;">
                                                                <div class="mb-1">
                                                                    Harga Total Hotel<a style="color: #e74a3b">*</a>
                                                                </div>

                                                                <input required type="text" autocomplete="off" class="form-control mb-3" name="harga_hotel[]" style="display:block;" placeholder="Rp" id="currency-field_hotel" data-type="currency" value="<?php echo "0"; ?>">
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

                                                    <textarea class="form-control" id="keterangan_akomodasi" name="keterangan_akomodasi[]" rows="3" placeholder=""></textarea>

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

                                                <div class="tab-pane" id="tab_mobil" style="font-size:120%">
                                                    <div class="mb-1">
                                                        GS
                                                    </div>

                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <select class="select_gs_mobil" id="gs_mobil" name="gs_mobil[]" style="width: 100%;"></select>
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
                                                                    <select class="select_jenis_kendaraan" id="jenis_kendaraan" name="jenis_kendaraan[]" style="width: 100%;"></select>
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
                                                                    <select id="dalkot_lukot" name="dalkot_lukot[]" onchange="showD(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                        <option value="Dalam Kota">Dalam Kota</option>
                                                                        <option value="Luar Kota">Luar Kota</option>
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
                                                            <div class="mb-3" id="menginap" style="display:none;">
                                                                <div>
                                                                    Menginap
                                                                </div>

                                                                <select class="select_menginap" id="menginap_transport" name="menginap[]"
                                                                    style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                    <option>Iya</option>
                                                                    <option>Tidak</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-6 mb-3">
                                                            <div class="mb-1">
                                                                Jumlah Mobil
                                                            </div>

                                                            <input autocomplete="off" type="number" min="0" class="form-control" name="jumlah_mobil[]" id="jumlah_mobil">
                                                        </div>

                                                        <div class="col-lg-6 mb-3">
                                                            <div class="mb-1">
                                                                Kapasitas
                                                            </div>

                                                            <input autocomplete="off" type="number" min="0" class="form-control" name="kapasitas[]" id="kapasitas">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-3 mb-3">
                                                            <div class="mb-1">
                                                                Tujuan
                                                            </div>

                                                            <input autocomplete="off" type="text" class="form-control" name="tujuan_mobil[]" id="tujuan_mobil" style="text-transform:capitalize">
                                                        </div>

                                                        <div class="col-lg-3 mb-3">
                                                            <div class="mb-1">
                                                                Siap Di
                                                            </div>

                                                            <input autocomplete="off" type="text" class="form-control" name="siap_di[]" id="siap_di" style="text-transform:capitalize">
                                                        </div>

                                                        <div class="col-lg-3 mb-3">
                                                            <div class="mb-1">
                                                                Tanggal
                                                            </div>

                                                            <input autocomplete="off" id='tanggal_mobil' class="form-control" name="tanggal_mobil[]">

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

                                                            <input autocomplete="off" id='jam_siap' class="form-control" name="jam_siap[]">

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

                                                    <textarea class="form-control" id="keterangan_mobil" name="keterangan_mobil[]" rows="3" placeholder="Wajib Diisi"></textarea>

                                                    <script>
                                                        $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                            if (event.which == '13') {
                                                                event.preventDefault();
                                                            }
                                                        });
                                                    </script>

                                                    <div class="mb-3"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <script>
                        function check(form){
                            if(document.getElementById("test").value.trim() == 'pilih'){
                                alert("Silahkan pilih kategori pemesanan terlebih dahulu");
                                return false;
                            } else if(document.getElementById("nama_select0").value.trim() == '' && document.getElementById("nama_input").value.trim() == ''){
                                alert("Nama Personil harus diisi");
                                return false;
                            } else if(document.getElementById("keterangan_tiket").value.trim() == ''){
                                alert("Keterangan Tiket harus diisi");
                                return false;
                            } else if (check1(this)) {
                                return check1(this);
                            }
                        }
                    </script> -->

                    <div id="show_script_form">
                    </div>

                    <div id="show_transaksi">
                    </div>

                    <div id="show_script_pemesanan">
                    </div>

                    <div id="show_script_tamu">
                    </div>

                    <div id="show_script_personil">
                    </div>

                    <div id="show_script_tiket">
                    </div>

                    <div id="show_script_keberangkatan">
                    </div>

                    <div id="show_script_pemberhentian">
                    </div>

                    <div id="show_script_gs_hotel">
                    </div>

                    <div id="show_script_mnj_pesan">
                    </div>

                    <div id="show_script_kota_kamar_jkt">
                    </div>

                    <div id="show_script_kota_kamar">
                    </div>

                    <div id="show_script_harga_hotel">
                    </div>

                    <div id="show_script_dalkot_lukot">
                    </div>

                    <script>
                        var count=0;
                        var count_form=1;
                        $(document).ready(function() {
                            $("#add_item_btn").click(function (e) {
                                count++;
                                count_form++;

                                e.preventDefault();
                                $("#show_transaksi").append(`
                                    <div class="remove_all">
                                        <div class="card mt-3 remove`+count+`">
                                            <button id="remove_item_btn`+count+`" class="btn btn-danger mb-3" style="font-size:120%;"><i class="fa-solid fa-minus"></i> Hapus Personil</button>
                                            <div id="show_remove`+count+`">
                                            </div>
                                            <div class="d-flex align-items-center row">
                                                <div class="col-sm-12">
                                                    <div class="card-body">
                                                        <h1>Transaksi</h1>
                                                        <section id="features`+count+`" class="features"  style="font-size:150%;">
                                                            <ul class="nav nav-tabs row g-2 d-flex mb-3">
                                                                <li class="nav-item col-3">
                                                                    <a class="nav-link active show" data-bs-toggle="tab"
                                                                        data-bs-target="#tab_start`+count+`">
                                                                        <h3>Personil</h3>
                                                                    </a>
                                                                </li>
                                                                <!-- End tab nav item -->

                                                                <li id="modal_tiket`+count+`" style="display:none;" class="nav-item col-3">
                                                                    <a class="nav-link" data-bs-toggle="tab"
                                                                        data-bs-target="#tab_tiket`+count+`">
                                                                        <h3>Tiket</h3>
                                                                    </a>
                                                                </li>
                                                                <!-- End tab nav item -->

                                                                <li id="modal_hotel`+count+`" style="display:none;" class="nav-item col-3">
                                                                    <a class="nav-link" data-bs-toggle="tab"
                                                                        data-bs-target="#tab_hotel`+count+`">
                                                                        <h3>Hotel</h3>
                                                                    </a>
                                                                </li>
                                                                <!-- End tab nav item -->

                                                                <li id="modal_mobil`+count+`" style="display:none;" class="nav-item col-3">
                                                                    <a class="nav-link" data-bs-toggle="tab"
                                                                        data-bs-target="#tab_mobil`+count+`">
                                                                        <h3>Mobil</h3>
                                                                    </a>
                                                                </li>
                                                                <!-- End tab nav item -->
                                                            </ul>
                                                                <div class="modal-body">
                                                                    <div class="tab-content">
                                                                        <div class="tab-pane active show" id="tab_start`+count+`" style="font-size:120%">
                                                                            <div class="row">
                                                                                <div class="col-lg-6 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Pemesanan
                                                                                    </div>
                                                                                    <select id="test`+count+`" class="test`+count+`" name="pemesanan[]" onchange="showDiv`+count+`(this)"
                                                                                        style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                        <option value="pilih">-- pilih salah satu --</option>
                                                                                        <option value="0">Tiket</option>
                                                                                        <option value="1">Hotel</option>
                                                                                        <option value="2">Mobil</option>
                                                                                        <option value="3">Tiket + Hotel</option>
                                                                                        <option value="4">Tiket + Mobil</option>
                                                                                        <option value="5">Hotel + Mobil</option>
                                                                                        <option value="6">Tiket + Hotel + Mobil</option>
                                                                                    </select>
                                                                                </div>

                                                                                <div class="col-lg-6 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Karyawan Konimex/Tamu
                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <select class="select_tamu`+count+`" id="tamu`+count+`" name="tamu[]" style="width: 100%;" onchange="showTamu`+count+`(this)">
                                                                                                <option>-- pilih pemesanan terlebih dahulu --</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div id="pilih_nama`+count+`" style="display:none;">
                                                                                    <div class="mb-1">
                                                                                        Personil
                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <select class="select_pilih_nama`+count+`" name="pilih_nama[]" onchange="showPilih`+count+`(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                                <option value='pilih'>-- pilih pemesanan terlebih dahulu --</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div id="nama_select`+count+`" style="display:none;">
                                                                                <div class="row">
                                                                                    <div class="col-lg-6">
                                                                                        <div class="mt-3">
                                                                                            Nama
                                                                                        </div>
                                                                                        
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <select class="select_nama" id="nama_select`+count+`" name="nama_select`+count+`[]" multiple="multiple" style="width: 100%;"></select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <!-- <div class="col-lg-4">
                                                                                        <div class="mt-3">
                                                                                            Jabatan
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <select class="select_jabatan" id="jabatan_select`+count+`" name="jabatan_select`+count+`[]" multiple="multiple" style="width: 100%;"></select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div> -->

                                                                                    <div class="col-lg-6">
                                                                                        <div class="mt-3">
                                                                                            Pembayaran
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <select class="select_pembayaran" name="pembayaran[]" style="width: 100%;"></select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div id="nama_inputan`+count+`" style="display:none;">
                                                                                <div class="row">
                                                                                    <div class="col-lg-4">
                                                                                        <div class="mt-3">
                                                                                            Nama
                                                                                        </div>
                                                                                        
                                                                                        <div class="col-lg-12 mt-1" style="height:80%;">
                                                                                            <input autocomplete="off" type="text" class="form-control" id="nama_input`+count+`" name="nama_inputan[]" style="text-transform:capitalize">
                                                                                        </div>
                                                                                    </div>
                                                                                    
                                                                                    <div class="col-lg-4">
                                                                                        <div class="mt-3">
                                                                                            Jabatan
                                                                                        </div>
                                                                                        
                                                                                        <div class="col-lg-12 mt-1" style="height:80%;">
                                                                                            <input autocomplete="off" type="text" class="form-control" id="jabatan_input`+count+`" name="jabatan_inputan[]" style="text-transform:capitalize">
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="col-lg-4">
                                                                                        <div class="mt-3">
                                                                                            Pembayaran
                                                                                        </div>

                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <select class="select_pembayaran" name="pembayaran_inputan[]" style="width: 100%;"></select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div id="lain-lain`+count+`" style="display:none;">
                                                                            </div>
                                                                        </div>
                                                                        <div class="tab-pane" id="tab_tiket`+count+`" style="font-size:120%">
                                                                            <div class="mb-1">
                                                                                GS
                                                                            </div>

                                                                            <div class="row mb-3">
                                                                                <div class="col-md-12">
                                                                                    <select class="select_gs_tiket" id="gs_tiket`+count+`" name="gs_tiket[]" style="width: 100%;"></select>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-lg-6 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Pilihan Armada Transportasi
                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <select id="select_pilihan_tiket`+count+`" name="pilihan_tiket[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                                <option value="0">-- pilih salah satu --</option>
                                                                                                <option>Bis</option>
                                                                                                <option>Kereta Api</option>
                                                                                                <option>Pesawat</option>
                                                                                                <option>Travel</option>
                                                                                                <option>Kapal Laut</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-lg-6 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Tiket
                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <select id="select_tiket`+count+`" name="tiket[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                                <option>-- pilih armada transportasi terlebih dahulu --</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-lg-6 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Keberangkatan
                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <select id="select_keberangkatan`+count+`" name="keberangkatan[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                                <option>-- pilih armada transportasi terlebih dahulu --</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Pemberhentian
                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <select id="select_pemberhentian`+count+`" name="pemberhentian[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                                <option>-- pilih armada transportasi terlebih dahulu --</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-lg-4 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Jumlah Tiket
                                                                                    </div>

                                                                                    <input autocomplete="off" type="number" min="0" class="form-control" name="jumlah_tiket[]" id="jumlah_tiket`+count+`">
                                                                                </div>

                                                                                <div class="col-lg-4 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Harga Total Tiket<a style="color: #e74a3b">*</a>
                                                                                    </div>

                                                                                    <input required type="text" autocomplete="off" class="form-control" name="harga_tiket[]" placeholder="Rp" id="currency-field_tiket`+count+`" data-type="currency" value="<?php echo "0"; ?>">
                                                                                </div>

                                                                                <div class="col-lg-4 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Tanggal dan Jam
                                                                                    </div>

                                                                                    <input autocomplete="off" id='tanggal_jam_tiket`+count+`' class="form-control" name="tanggal_jam_tiket[]">
                                                                                </div>
                                                                            </div>

                                                                            <div class="mb-1">
                                                                                Keterangan
                                                                            </div>
                                                                            
                                                                            <textarea class="form-control" id="keterangan_tiket`+count+`" name="keterangan_tiket[]" rows="3" placeholder=""></textarea>

                                                                            <div class="modal-footer">
                                                                                <div class="col-xl-12 col-lg-12 mb-3">
                                                                                    <a style="color: #e74a3b">&nbsp;*Note: Masukkan nilai 0 jika belum mengetahui harga tiket</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="tab-pane" id="tab_hotel`+count+`" style="font-size:120%">
                                                                            <div class="mb-1">
                                                                                GS
                                                                            </div>

                                                                            <div class="row mb-3">
                                                                                <div class="col-md-12">
                                                                                    <select class="select_gs_hotel" id="gs_hotel`+count+`" name="gs_hotel[]" onchange="showGshotel`+count+`(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;"></select>
                                                                                </div>
                                                                            </div>

                                                                            <div id="kota_kamar_jkt`+count+`" style="display:none;">
                                                                                <div id="mnj_pesan`+count+`" style="display:none;">
                                                                                    <div class="mb-1">
                                                                                        Pesan Mess untuk MNJ?
                                                                                    </div>

                                                                                    <div class="row mb-3">
                                                                                        <div class="col-md-12">
                                                                                            <select name="pesan_mnj[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                                <option>Tidak</option>
                                                                                                <option>Iya</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="row">
                                                                                    <div class="col-lg-6">
                                                                                        <div class="mb-1">
                                                                                            Kota
                                                                                        </div>

                                                                                        <div class="row mb-3">
                                                                                            <div class="col-md-12">
                                                                                                <select id="select_kota_jkt`+count+`" name="kota_jkt[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                                    <option>Jakarta</option>
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
                                                                                                <select class="select_hotel_jkt" id="select_hotel_jkt`+count+`" name="hotel_jkt[]" onchange="showKamarhoteljkt`+count+`(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                                    <option>Mess Kx Jkt - Standar</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div id="tanggal_kamar_jkt`+count+`" style="display:none;">
                                                                                <div class="row">
                                                                                    <div class="col-lg-6">
                                                                                        <div class="mb-1">
                                                                                            Tanggal Masuk dan Jam Masuk
                                                                                        </div>

                                                                                        <input autocomplete="off" id='tanggal_jam_masuk_jkt`+count+`' class="form-control mb-3" name="tanggal_jam_masuk_jkt[]">
                                                                                    </div>

                                                                                    <div class="col-lg-6">
                                                                                        <div class="mb-1">
                                                                                            Tanggal Keluar dan Jam Keluar
                                                                                        </div>

                                                                                        <input autocomplete="off" id='tanggal_jam_keluar_jkt`+count+`' class="form-control mb-3" name="tanggal_jam_keluar_jkt[]">
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div id="kota_kamar`+count+`" style="display:block;">
                                                                                <div class="row">
                                                                                    <div class="col-lg-6">
                                                                                        <div class="mb-1">
                                                                                            Kota
                                                                                        </div>

                                                                                        <div class="row mb-3">
                                                                                            <div class="col-md-12">
                                                                                                <select id="select_kota`+count+`" name="kota[]" onchange="showKotahotel`+count+`(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                                    <option value="pilih">-- pilih kota --</option>
                                                                                                    <?php foreach ($negara as $n => $neg) {?>
                                                                                                        <optgroup label=<?php echo $neg['nama_negara'] ?>>
                                                                                                            <?php foreach ($kota as $k => $kot) {?>
                                                                                                                <?php if ($neg['id_negara'] == $kot['id_negara']) { ?>
                                                                                                                    <option><?php echo $kot['nama_kota'] ?></option>
                                                                                                                <?php } ?>
                                                                                                            <?php } ?>
                                                                                                        </optgroup>
                                                                                                    <?php } ?>
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
                                                                                                <select id="select_hotel`+count+`" name="hotel[]" onchange="showKamarhotel`+count+`(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                                    <option value="pilih1">-- pilih kota terlebih dahulu --</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div id="tanggal_kamar`+count+`" style="display:block;">
                                                                                <div class="row">
                                                                                    <div class="col-lg-6">
                                                                                        <div class="mb-1">
                                                                                            Tanggal Masuk dan Jam Masuk
                                                                                        </div>

                                                                                        <input autocomplete="off" id='tanggal_jam_masuk`+count+`' class="form-control mb-3" name="tanggal_jam_masuk[]">
                                                                                    </div>

                                                                                    <div class="col-lg-6">
                                                                                        <div class="mb-1">
                                                                                            Tanggal Keluar dan Jam Keluar
                                                                                        </div>

                                                                                        <input autocomplete="off" id='tanggal_jam_keluar`+count+`' class="form-control mb-3" name="tanggal_jam_keluar[]">
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-1">
                                                                                        Type
                                                                                    </div>

                                                                                    <div class="row mb-3">
                                                                                        <div class="col-md-12">
                                                                                            <select class="select_type" id="type`+count+`" name="type[]" style="width: 100%;"></select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6">
                                                                                    <div class="col-md-12" style="height:90%;">
                                                                                        <div id="jumlah_kamar`+count+`" style="display:block;">
                                                                                            <div class="mb-1">
                                                                                                Jumlah Kamar
                                                                                            </div>
                                                                                        </div>

                                                                                        <div id="jumlah_personil_mess`+count+`" style="display:none;">
                                                                                            <div class="mb-1">
                                                                                                Jumlah Personil Menginap
                                                                                            </div>
                                                                                        </div>
                                                                                        
                                                                                        <input autocomplete="off" type="number" min="0" class="form-control mb-3" name="jumlah_kamar[]" id="jumlah_kamar`+count+`">
                                                                                        <input hidden type="text" class="form-control" name="count[]" value="`+count+`">
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div id="sisa_mess`+count+`" style="display:none;">
                                                                                <div class="mb-1">
                                                                                    Ketersediaan Kamar Mess
                                                                                </div>

                                                                                <div class="row mb-3">
                                                                                    <div class="col-md-12">
                                                                                        <select id="select_mess`+count+`" name="mess[]" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                            <option value='1'>Tersedia untuk : <?php foreach ($mess as $m => $mes) { $sisa_kamar = $mes['kapasitas_kamar'] - $mes['terpakai'];?><?php echo $sisa_kamar ?><?php } ?> orang</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div id="harga_hotel`+count+`" style="display:block;">
                                                                                        <div class="mb-1">
                                                                                            Harga Total Hotel<a style="color: #e74a3b">*</a>
                                                                                        </div>

                                                                                        <input required type="text" autocomplete="off" class="form-control mb-3" name="harga_hotel[]" style="display:block;" placeholder="Rp" id="currency-field_hotel`+count+`" data-type="currency" value="<?php echo "0"; ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="mb-1">
                                                                                Keterangan
                                                                            </div>

                                                                            <textarea class="form-control" id="keterangan_akomodasi`+count+`" name="keterangan_akomodasi[]" rows="3" placeholder=""></textarea>
                                                                            <div class="modal-footer">
                                                                                <div class="col-xl-12 col-lg-12 mb-3">
                                                                                    <a style="color: #e74a3b">&nbsp;*Note: Masukkan nilai 0 jika belum mengetahui harga hotel</a>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="tab-pane" id="tab_mobil`+count+`" style="font-size:120%">
                                                                            <div class="mb-1">
                                                                                GS
                                                                            </div>

                                                                            <div class="row mb-3">
                                                                                <div class="col-md-12">
                                                                                    <select class="select_gs_mobil" id="gs_mobil`+count+`" name="gs_mobil[]" style="width: 100%;"></select>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-1">
                                                                                        Jenis Kendaraan
                                                                                    </div>

                                                                                    <div class="row mb-3">
                                                                                        <div class="col-md-12">
                                                                                            <select class="select_jenis_kendaraan" id="jenis_kendaraan`+count+`" name="jenis_kendaraan[]" style="width: 100%;"></select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-lg-6">
                                                                                    <div class="mb-1">
                                                                                        Dalam/Luar Kota
                                                                                    </div>

                                                                                    <div class="row mb-3">
                                                                                        <div class="col-md-12">
                                                                                            <select id="dalkot_lukot`+count+`" name="dalkot_lukot[]" onchange="showD`+count+`(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                                <option value="Dalam Kota">Dalam Kota</option>
                                                                                                <option value="Luar Kota">Luar Kota</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-md-12">
                                                                                    <div class="mb-3" id="menginap`+count+`" style="display:none;">
                                                                                        <div>
                                                                                            Menginap
                                                                                        </div>

                                                                                        <select class="select_menginap" id="menginap_transport`+count+`" name="menginap[]"
                                                                                            style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                                            <option>Iya</option>
                                                                                            <option>Tidak</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-lg-6 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Jumlah Mobil
                                                                                    </div>

                                                                                    <input autocomplete="off" type="number" min="0" class="form-control" name="jumlah_mobil[]" id="jumlah_mobil`+count+`">
                                                                                </div>

                                                                                <div class="col-lg-6 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Kapasitas
                                                                                    </div>

                                                                                    <input autocomplete="off" type="number" min="0" class="form-control" name="kapasitas[]" id="kapasitas`+count+`">
                                                                                </div>
                                                                            </div>

                                                                            <div class="row">
                                                                                <div class="col-lg-3 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Tujuan
                                                                                    </div>

                                                                                    <input autocomplete="off" type="text" class="form-control" name="tujuan_mobil[]" id="tujuan_mobil`+count+`" style="text-transform:capitalize">
                                                                                </div>

                                                                                <div class="col-lg-3 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Siap Di
                                                                                    </div>

                                                                                    <input autocomplete="off" type="text" class="form-control" name="siap_di[]" id="siap_di`+count+`" style="text-transform:capitalize">
                                                                                </div>

                                                                                <div class="col-lg-3 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Tanggal
                                                                                    </div>

                                                                                    <input autocomplete="off" id='tanggal_mobil`+count+`' class="form-control" name="tanggal_mobil[]">
                                                                                </div>

                                                                                <div class="col-lg-3 mb-3">
                                                                                    <div class="mb-1">
                                                                                        Jam Siap
                                                                                    </div>

                                                                                    <input autocomplete="off" id='jam_siap`+count+`' class="form-control" name="jam_siap[]">
                                                                                </div>
                                                                            </div>

                                                                            <div class="mb-1">
                                                                                Keterangan (Wajib Diisi)
                                                                            </div>

                                                                            <textarea class="form-control" id="keterangan_mobil`+count+`" name="keterangan_mobil[]" rows="3" placeholder="Wajib Diisi"></textarea>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        </section>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `);

                                e.preventDefault();
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.className = 'remove';
                                var code = `
                                    var button = document.getElementById("remove_item_btn`+count+`");
                                    button.addEventListener('click', (e) => {
                                        $('.remove`+count+`').remove();
                                        count--;
                                        e.preventDefault();
                                    });
                                `;
                                try {
                                s.appendChild(document.createTextNode(code));
                                $("#show_remove"+count+"").append(s);
                                } catch (e) {
                                s.text = code;
                                $("#show_remove"+count+"").append(s);
                                }

                                e.preventDefault();
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.className = 'remove';
                                var code = `
                                    function showDiv`+count+`(select) {
                                        if (select.value == 'pilih') {
                                            document.getElementById('modal_tiket`+count+`').style
                                                .display = "none";
                                            document.getElementById('modal_hotel`+count+`').style
                                                .display = "none";
                                            document.getElementById('modal_mobil`+count+`').style
                                                .display = "none";
                                        } else if (select.value == 0) {
                                            document.getElementById('modal_tiket`+count+`').style
                                                .display = "block";
                                            document.getElementById('modal_hotel`+count+`').style
                                                .display = "none";
                                            document.getElementById('modal_mobil`+count+`').style
                                                .display = "none";
                                        } else if (select.value == 1) {
                                            document.getElementById('modal_tiket`+count+`').style
                                                .display = "none";
                                            document.getElementById('modal_hotel`+count+`').style
                                                .display = "block";
                                            document.getElementById('modal_mobil`+count+`').style
                                                .display = "none";
                                        } else if (select.value == 2) {
                                            document.getElementById('modal_tiket`+count+`').style
                                                .display = "none";
                                            document.getElementById('modal_hotel`+count+`').style
                                                .display = "none";
                                            document.getElementById('modal_mobil`+count+`').style
                                                .display = "block";
                                        } else if (select.value == 3) {
                                            document.getElementById('modal_tiket`+count+`').style
                                                .display = "block";
                                            document.getElementById('modal_hotel`+count+`').style
                                                .display = "block";
                                            document.getElementById('modal_mobil`+count+`').style
                                                .display = "none";
                                        } else if (select.value == 4) {
                                            document.getElementById('modal_tiket`+count+`').style
                                                .display = "block";
                                            document.getElementById('modal_hotel`+count+`').style
                                                .display = "none";
                                            document.getElementById('modal_mobil`+count+`').style
                                                .display = "block";
                                        } else if (select.value == 5) {
                                            document.getElementById('modal_tiket`+count+`').style
                                                .display = "none";
                                            document.getElementById('modal_hotel`+count+`').style
                                                .display = "block";
                                            document.getElementById('modal_mobil`+count+`').style
                                                .display = "block";
                                        } else if (select.value == 6) {
                                            document.getElementById('modal_tiket`+count+`').style
                                                .display = "block";
                                            document.getElementById('modal_hotel`+count+`').style
                                                .display = "block";
                                            document.getElementById('modal_mobil`+count+`').style
                                                .display = "block";
                                        }
                                    }
                                `;
                                try {
                                s.appendChild(document.createTextNode(code));
                                $("#show_script_pemesanan").append(s);
                                } catch (e) {
                                s.text = code;
                                $("#show_script_pemesanan").append(s);
                                }

                                e.preventDefault();
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.className = 'remove';
                                var code = `
                                    $(".test`+count+`").change(function() {
                                        var val = $(this).val();

                                        if (val == "0" || val == "1" || val == "3"|| val == "4" || val == "5" || val == "6") {
                                            $(".select_tamu`+count+`").html(
                                                "<option>Karyawan Konimex</option><option>Tamu</option>"
                                            );
                                            document.getElementById('pilih_nama`+count+`').style.display =
                                                "none";
                                            document.getElementById('nama_inputan`+count+`').style.display =
                                                "none";
                                            document.getElementById('nama_select`+count+`').style.display =
                                                "block";
                                            document.getElementById('lain-lain`+count+`').style.display =
                                                "block";
                                        } else if (val == "2") {
                                            $(".select_tamu`+count+`").html(
                                                "<option>Karyawan Konimex</option><option>Tamu</option><option>MNJ</option>"
                                            );
                                            document.getElementById('pilih_nama`+count+`').style.display =
                                                "none";
                                            document.getElementById('nama_inputan`+count+`').style.display =
                                                "none";
                                            document.getElementById('nama_select`+count+`').style.display =
                                                "block";
                                            document.getElementById('lain-lain`+count+`').style.display =
                                                "block";
                                        } else if (val == "pilih") {
                                            $(".select_tamu`+count+`").html(
                                                "<option>-- pilih pemesanan terlebih dahulu --</option>"
                                            );
                                            document.getElementById('pilih_nama`+count+`').style.display =
                                                "none";
                                            document.getElementById('nama_inputan`+count+`').style.display =
                                                "none";
                                            document.getElementById('nama_select`+count+`').style.display =
                                                "none";
                                            document.getElementById('lain-lain`+count+`').style.display =
                                                "none";
                                        }
                                    });
                                    function showTamu`+count+`(select) {
                                        if (select.value == 'Karyawan Konimex') {
                                            document.getElementById('pilih_nama`+count+`').style.display =
                                                "none";
                                            document.getElementById('nama_inputan`+count+`').style.display =
                                                "none";
                                            document.getElementById('nama_select`+count+`').style.display =
                                                "block";
                                            document.getElementById('lain-lain`+count+`').style.display =
                                                "block";
                                        } else if (select.value == 'Tamu') {
                                            document.getElementById('pilih_nama`+count+`').style.display =
                                                "none";
                                            document.getElementById('nama_inputan`+count+`').style.display =
                                                "block";
                                            document.getElementById('nama_select`+count+`').style.display =
                                                "none";
                                            document.getElementById('lain-lain`+count+`').style.display =
                                                "block";
                                        } else if (select.value == 'MNJ') {
                                            document.getElementById('pilih_nama`+count+`').style.display =
                                                "none";
                                            document.getElementById('nama_inputan`+count+`').style.display =
                                                "block";
                                            document.getElementById('nama_select`+count+`').style.display =
                                                "none";
                                            document.getElementById('lain-lain`+count+`').style.display =
                                                "block";
                                        }
                                    }
                                `;
                                try {
                                s.appendChild(document.createTextNode(code));
                                $("#show_script_tamu").append(s);
                                } catch (e) {
                                s.text = code;
                                $("#show_script_tamu").append(s);
                                }

                                e.preventDefault();
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.className = 'remove';
                                var code = `
                                    function showPilih`+count+`(select) {
                                        if (select.value == '0') {
                                            document.getElementById('nama_inputan`+count+`').style.display =
                                                "none";
                                            document.getElementById('nama_select`+count+`').style.display =
                                                "block";
                                            document.getElementById('lain-lain`+count+`').style.display =
                                                "block";
                                        } else if (select.value == '1') {
                                            document.getElementById('nama_inputan`+count+`').style.display =
                                                "block";
                                            document.getElementById('nama_select`+count+`').style.display =
                                                "none";
                                            document.getElementById('lain-lain`+count+`').style.display =
                                                "block";
                                        } else if (select.value == 'pilih') {
                                            document.getElementById('nama_inputan`+count+`').style.display =
                                                "none";
                                            document.getElementById('nama_select`+count+`').style.display =
                                                "none";
                                            document.getElementById('lain-lain`+count+`').style.display =
                                                "none";
                                        }
                                    }
                                    $(".test`+count+`").change(function() {
                                        var val = $(this).val();

                                        if (val == "pilih") {
                                            $(".select_pilih_nama`+count+`").html(
                                                "<option value='pilih'>-- pilih pemesanan terlebih dahulu --</option>"
                                            );
                                        } else {
                                            $(".select_pilih_nama`+count+`").html(
                                                "<option value='pilih'>-- pilih salah satu --</option><option value='0'>Hanya untuk 1 personil</option><option value='1'>Beberapa personil dengan 1 tujuan yang sama</option>"
                                            );
                                        }
                                    });
                                                                
                                    $(".select_tamu`+count+`").change(function() {
                                        var val = $(this).val();

                                        if (val == "Karyawan Konimex") {
                                            $(".select_pilih_nama`+count+`").html(
                                                "<option value='pilih'>-- pilih salah satu --</option><option value='0'>Hanya untuk 1 personil</option><option value='1'>Beberapa personil dengan 1 tujuan yang sama</option>"
                                            );
                                        } else if (val == "Tamu" || val == "MNJ") {
                                            $(".select_pilih_nama`+count+`").html(
                                                "<option value='pilih'>-- pilih salah satu --</option><option value='0'>Hanya untuk 1 personil</option><option value='1'>Beberapa personil dengan 1 tujuan yang sama</option>"
                                            );
                                        } else if (val == "pilih") {
                                            $(".select_pilih_nama`+count+`").html(
                                                "<option value='pilih'>-- pilih pemesanan terlebih dahulu --</option>"
                                            );
                                        }
                                    });
                                `;
                                try {
                                s.appendChild(document.createTextNode(code));
                                $("#show_script_personil").append(s);
                                } catch (e) {
                                s.text = code;
                                $("#show_script_personil").append(s);
                                }

                                e.preventDefault();
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.className = 'remove';
                                var code = `
                                    $("#select_pilihan_tiket`+count+`").change(function() {
                                        var val = $(this).val();

                                        if (val == "Bis") {
                                            $("#select_tiket`+count+`").html(
                                                <?php if (empty($bus)) {?>
                                                    "<option>Tidak ada data</option>"
                                                <?php } else { ?>
                                                    "<?php foreach ($bus as $b => $bu) {?><option><?php echo $bu['nama_vendor']?></option><?php } ?>"
                                                <?php } ?>
                                            );
                                        } else if (val == "Kereta Api") {
                                            $("#select_tiket`+count+`").html(
                                                <?php if (empty($kereta)) {?>
                                                    "<option>Tidak ada data</option>"
                                                <?php } else { ?>
                                                    "<?php foreach ($kereta as $k => $ker) {?><option><?php echo $ker['nama_vendor']?></option><?php } ?>"
                                                <?php } ?>
                                            );
                                        } else if (val == "Pesawat") {
                                            $("#select_tiket`+count+`").html(
                                                <?php if (empty($pesawat)) {?>
                                                    "<option>Tidak ada data</option>"
                                                <?php } else { ?>
                                                    "<?php foreach ($pesawat as $p => $pes) {?><option><?php echo $pes['nama_vendor']?></option><?php } ?>"
                                                <?php } ?>
                                            );
                                        } else if (val == "Travel") {
                                            $("#select_tiket`+count+`").html(
                                                <?php if (empty($travel)) {?>
                                                    "<option>Tidak ada data</option>"
                                                <?php } else { ?>
                                                    "<?php foreach ($travel as $t => $tra) {?><option><?php echo $tra['nama_vendor']?></option><?php } ?>"
                                                <?php } ?>
                                            );
                                        } else if (val == "Kapal Laut") {
                                            $("#select_tiket`+count+`").html(
                                                <?php if (empty($kapal_laut)) {?>
                                                    "<option>Tidak ada data</option>"
                                                <?php } else { ?>
                                                    "<?php foreach ($kapal_laut as $ka => $kap) {?><option><?php echo $kap['nama_vendor']?></option><?php } ?>"
                                                <?php } ?>
                                            );
                                        } else if (val == "0") {
                                            $("#select_tiket`+count+`").html(
                                                "<option>-- pilih armada transportasi terlebih dahulu --</option>"
                                            );
                                        }
                                    });
                                `;
                                try {
                                s.appendChild(document.createTextNode(code));
                                $("#show_script_tiket").append(s);
                                } catch (e) {
                                s.text = code;
                                $("#show_script_tiket").append(s);
                                }

                                e.preventDefault();
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.className = 'remove';
                                var code = `
                                    $("#select_pilihan_tiket`+count+`").change(function() {
                                        var val = $(this).val();

                                        if (val == "Pesawat") {
                                            $("#select_keberangkatan`+count+`").html(
                                                <?php if (empty($bandara)) {?>
                                                    "<option>Tidak ada data</option>"
                                                <?php } else { ?>
                                                    "<?php foreach ($bandara as $b => $ba) {?><option><?php echo $ba['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $ba['nama_kota']?></option><?php } ?>"
                                                <?php } ?>
                                            );
                                        } else if (val == "Kapal Laut") {
                                            $("#select_keberangkatan`+count+`").html(
                                                <?php if (empty($pelabuhan)) {?>
                                                    "<option>Tidak ada data</option>"
                                                <?php } else { ?>
                                                    "<?php foreach ($pelabuhan as $p => $pe) {?><option><?php echo $pe['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $pe['nama_kota']?></option><?php } ?>"
                                                <?php } ?>
                                            );
                                        } else if (val == "Kereta Api") {
                                            $("#select_keberangkatan`+count+`").html(
                                                <?php if (empty($stasiun)) {?>
                                                    "<option>Tidak ada data</option>"
                                                <?php } else { ?>
                                                    "<?php foreach ($stasiun as $s => $st) {?><option><?php echo $st['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $st['nama_kota']?></option><?php } ?>"
                                                <?php } ?>
                                            );
                                        } else if (val == "Bis") {
                                            $("#select_keberangkatan`+count+`").html(
                                                <?php if (empty($terminal)) {?>
                                                    "<option>Tidak ada data</option>"
                                                <?php } else { ?>
                                                    "<?php foreach ($terminal as $t => $te) {?><option><?php echo $te['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $te['nama_kota']?></option><?php } ?>"
                                                <?php } ?>
                                            );
                                        } else if (val == "Travel") {
                                            $("#select_keberangkatan`+count+`").html(
                                                "<?php foreach ($kota as $ko => $kot) {?><option><?php echo $kot['nama_kota']?></option><?php } ?>"
                                            );
                                        } else if (val == "0") {
                                            $("#select_keberangkatan`+count+`").html(
                                                "<option>-- pilih armada transportasi terlebih dahulu --</option>"
                                            );
                                        }
                                    });
                                `;
                                try {
                                s.appendChild(document.createTextNode(code));
                                $("#show_script_keberangkatan").append(s);
                                } catch (e) {
                                s.text = code;
                                $("#show_script_keberangkatan").append(s);
                                }

                                e.preventDefault();
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.className = 'remove';
                                var code = `
                                    $("#select_pilihan_tiket`+count+`").change(function() {
                                        var val = $(this).val();

                                        if (val == "Pesawat") {
                                            $("#select_pemberhentian`+count+`").html(
                                                <?php if (empty($bandara)) {?>
                                                    "<option>Tidak ada data</option>"
                                                <?php } else { ?>
                                                    "<?php foreach ($bandara as $b => $ba) {?><option><?php echo $ba['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $ba['nama_kota']?></option><?php } ?>"
                                                <?php } ?>
                                            );
                                        } else if (val == "Kapal Laut") {
                                            $("#select_pemberhentian`+count+`").html(
                                                <?php if (empty($pelabuhan)) {?>
                                                    "<option>Tidak ada data</option>"
                                                <?php } else { ?>
                                                    "<?php foreach ($pelabuhan as $p => $pe) {?><option><?php echo $pe['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $pe['nama_kota']?></option><?php } ?>"
                                                <?php } ?>
                                            );
                                        } else if (val == "Kereta Api") {
                                            $("#select_pemberhentian`+count+`").html(
                                                <?php if (empty($stasiun)) {?>
                                                    "<option>Tidak ada data</option>"
                                                <?php } else { ?>
                                                    "<?php foreach ($stasiun as $s => $st) {?><option><?php echo $st['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $st['nama_kota']?></option><?php } ?>"
                                                <?php } ?>
                                            );
                                        } else if (val == "Bis") {
                                            $("#select_pemberhentian`+count+`").html(
                                                <?php if (empty($terminal)) {?>
                                                    "<option>Tidak ada data</option>"
                                                <?php } else { ?>
                                                    "<?php foreach ($terminal as $t => $te) {?><option><?php echo $te['nama_pemberhentian']?> <?php echo "-" ?> <?php echo $te['nama_kota']?></option><?php } ?>"
                                                <?php } ?>
                                            );
                                        } else if (val == "Travel") {
                                            $("#select_pemberhentian`+count+`").html(
                                                "<?php foreach ($kota as $ko => $kot) {?><option><?php echo $kot['nama_kota']?></option><?php } ?>"
                                            );
                                        } else if (val == "0") {
                                            $("#select_pemberhentian`+count+`").html(
                                                "<option>-- pilih armada transportasi terlebih dahulu --</option>"
                                            );
                                        }
                                    });
                                `;
                                try {
                                s.appendChild(document.createTextNode(code));
                                $("#show_script_pemberhentian").append(s);
                                } catch (e) {
                                s.text = code;
                                $("#show_script_pemberhentian").append(s);
                                }

                                e.preventDefault();
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.className = 'remove';
                                var code = `
                                    function showGshotel`+count+`(select) {
                                        if (select.value == '2. Pool Jakarta') {
                                            document.getElementById('kota_kamar`+count+`').style
                                                .display = "none";
                                            document.getElementById('tanggal_kamar`+count+`').style
                                                .display = "none";
                                            document.getElementById('jumlah_kamar`+count+`').style
                                                .display = "none";
                                            document.getElementById('kota_kamar_jkt`+count+`').style
                                                .display = "block";
                                            document.getElementById('tanggal_kamar_jkt`+count+`').style
                                                .display = "block";
                                            document.getElementById('jumlah_personil_mess`+count+`').style
                                                .display = "block";
                                            document.getElementById('sisa_mess`+count+`').style
                                                .display = "none";
                                            document.getElementById('harga_hotel`+count+`').style
                                                .display = "none";
                                        } else {
                                            document.getElementById('kota_kamar`+count+`').style
                                                .display = "block";
                                            document.getElementById('tanggal_kamar`+count+`').style
                                                .display = "block";
                                            document.getElementById('jumlah_kamar`+count+`').style
                                                .display = "block";
                                            document.getElementById('kota_kamar_jkt`+count+`').style
                                                .display = "none";
                                            document.getElementById('tanggal_kamar_jkt`+count+`').style
                                                .display = "none";
                                            document.getElementById('jumlah_personil_mess`+count+`').style
                                                .display = "none";
                                            document.getElementById('sisa_mess`+count+`').style
                                                .display = "none";
                                            document.getElementById('harga_hotel`+count+`').style
                                                .display = "block";
                                        }
                                    }
                                `;
                                try {
                                s.appendChild(document.createTextNode(code));
                                $("#show_script_gs_hotel").append(s);
                                } catch (e) {
                                s.text = code;
                                $("#show_script_gs_hotel").append(s);
                                }

                                e.preventDefault();
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.className = 'remove';
                                var code = `
                                    $(".select_tamu`+count+`").change(function() {
                                        var val = $(this).val();

                                        if (val == "Karyawan Konimex") {
                                            document.getElementById('mnj_pesan`+count+`').style
                                                .display = "none";
                                        } else if (val == "Tamu") {
                                            document.getElementById('mnj_pesan`+count+`').style
                                                .display = "block";
                                        }
                                    });
                                `;
                                try {
                                s.appendChild(document.createTextNode(code));
                                $("#show_script_mnj_pesan").append(s);
                                } catch (e) {
                                s.text = code;
                                $("#show_script_mnj_pesan").append(s);
                                }

                                e.preventDefault();
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.className = 'remove';
                                var code = `
                                    function showKamarhoteljkt`+count+`(select) {
                                        if (select.value == 'Mess Kx Jkt - Standar') {
                                            document.getElementById('sisa_mess`+count+`').style
                                                .display = "block";
                                        } else {
                                            document.getElementById('sisa_mess`+count+`').style
                                                .display = "none";
                                        }
                                    }
                                `;
                                try {
                                s.appendChild(document.createTextNode(code));
                                $("#show_script_kota_kamar_jkt").append(s);
                                } catch (e) {
                                s.text = code;
                                $("#show_script_kota_kamar_jkt").append(s);
                                }

                                e.preventDefault();
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.className = 'remove';
                                var code = `
                                    function showKotahotel`+count+`(select) {
                                        if (select.value == 'Jakarta') {
                                            
                                        } else {
                                            document.getElementById('sisa_mess`+count+`').style
                                                .display = "none";
                                        }
                                    }

                                    $("#select_kota`+count+`").change(function() {
                                        var val = $(this).val();

                                        <?php foreach ($kota as $k => $kot) {?>
                                            if (val == "<?php echo $kot['nama_kota'] ?>") {
                                                $("#select_hotel`+count+`").html(
                                                    "<option value=''>-- Daftar hotel --</option><?php foreach ($hotel as $h => $hote) {?><?php if ($kot['nama_kota'] == $hote['nama_kota']) { ?><option><?php echo $hote['nama_hotel'] ?><?php echo " - " ?><?php echo $hote['jenis_kamar'] ?></option><?php } ?><?php } ?>"
                                                );
                                            } else if (val == "pilih") {
                                                $("#select_hotel`+count+`").html(
                                                    "<option value='pilih1'>-- pilih kota terlebih dahulu --</option>"
                                                );
                                            }
                                        <?php } ?>
                                    });

                                    function showKamarhotel`+count+`(select) {
                                        if (select.value == 'Mess Kx Jkt - Standar') {
                                            document.getElementById('sisa_mess`+count+`').style
                                                .display = "block";
                                        } else {
                                            document.getElementById('sisa_mess`+count+`').style
                                                .display = "none";
                                        }
                                    }
                                `;
                                try {
                                s.appendChild(document.createTextNode(code));
                                $("#show_script_kota_kamar").append(s);
                                } catch (e) {
                                s.text = code;
                                $("#show_script_kota_kamar").append(s);
                                }

                                e.preventDefault();
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.className = 'remove';
                                var code = `
                                    $("#select_hotel`+count+`").change(function() {
                                        var val = $(this).val();

                                        if (val == "Mess Kx Jkt - Standar") {
                                            document.getElementById('harga_hotel`+count+`').style.display = "none";
                                        } else {
                                            document.getElementById('harga_hotel`+count+`').style.display = "block";
                                        }
                                    });

                                    $("#select_hotel_jkt`+count+`").change(function() {
                                        var val = $(this).val();

                                        if (val == "Mess Kx Jkt - Standar") {
                                            document.getElementById('harga_hotel`+count+`').style.display = "none";
                                        } else {
                                            document.getElementById('harga_hotel`+count+`').style.display = "block";
                                        }
                                    });
                                `;
                                try {
                                s.appendChild(document.createTextNode(code));
                                $("#show_script_harga_hotel").append(s);
                                } catch (e) {
                                s.text = code;
                                $("#show_script_harga_hotel").append(s);
                                }

                                e.preventDefault();
                                var s = document.createElement('script');
                                s.type = 'text/javascript';
                                s.className = 'remove';
                                var code = `
                                    function showD`+count+`(select) {
                                        if (select.value == 'Dalam Kota') {
                                            document.getElementById('menginap`+count+`').style.display =
                                                "none";
                                        } else if (select.value == 'Luar Kota') {
                                            document.getElementById('menginap`+count+`').style.display =
                                                "block";
                                        }
                                    }
                                `;
                                try {
                                s.appendChild(document.createTextNode(code));
                                $("#show_script_dalkot_lukot").append(s);
                                } catch (e) {
                                s.text = code;
                                $("#show_script_dalkot_lukot").append(s);
                                }

                                // e.preventDefault();
                                // var s = document.createElement('script');
                                // s.className = 'remove';
                                // var code = `
                                //     function check`+count+`(){
                                //         if(document.getElementById("test`+count+`").value.trim() == 'pilih'){
                                //             alert("Silahkan pilih kategori pemesanan terlebih dahulu");
                                //             return false;
                                //         } else if(document.getElementById("nama_select`+count+`").value.trim() == '' && document.getElementById("nama_input`+count+`").value.trim() == ''){
                                //             alert("Nama Personil harus diisi");
                                //             return false;
                                //         } else if(document.getElementById("keterangan_tiket`+count+`").value.trim() == ''){
                                //             alert("Keterangan Tiket harus diisi");
                                //             return false;
                                //         } else if (check`+count+`()) {
                                //             return check`+count+`();
                                //         }
                                //     }
                                // `;
                                // try {
                                // s.appendChild(document.createTextNode(code));
                                // $("#show_script_form").append(s);
                                // } catch (e) {
                                // s.text = code;
                                // $("#show_script_form").append(s);
                                // }

                                var data_select = [
                                    <?php foreach ($pengguna as $peng) : ?> "<?php echo $peng['nama_pengguna']?><?php echo " - "?><?php echo $peng['nama_jabatan']?><?php echo " - "?><?php echo $peng['jenis_kelamin']?>",
                                    <?php endforeach ?>
                                ]

                                $(".select_nama").select2({
                                    // tags:["Semua"],
                                    data: data_select,
                                    // tags: true,
                                    // tokenSeparators: [',', ' '],
                                });

                                var data_select = [
                                    //     <php foreach ($bag as $bag) : ?> "<php echo $bag['strorgnm']?>",
                                    //     <php endforeach ?>
                                    'Direksi', 'Manager', 'Officer', 'Supervisor', 'Pelaksana'
                                ]

                                $(".select_jabatan").select2({
                                    // tags:["Semua"],
                                    data: data_select,
                                    // tags: true,
                                    // tokenSeparators: [',', ' '],
                                });

                                var data_select = [
                                    <?php foreach ($pengguna as $peng) : ?> "<?php echo $peng['nama_pengguna']?>",
                                    <?php endforeach ?>
                                ]

                                $(".select_email_info").select2({
                                    // tags:["Semua"],
                                    data: data_select,
                                    // tags: true,
                                    // tokenSeparators: [',', ' '],
                                });

                                var data_select = [
                                    <?php foreach ($pengguna as $peng) : ?> "<?php echo $peng['nama_pengguna']?>",
                                    <?php endforeach ?>
                                ]

                                $(".select_email_eval").select2({
                                    // tags:["Semua"],
                                    data: data_select,
                                    // tags: true,
                                    // tokenSeparators: [',', ' '],
                                });

                                var data_select = ['Company Acc', 'Personal Acc']

                                $(".select_pembayaran").select2({
                                    data: data_select,
                                    // tags: true,
                                    // tokenSeparators: [',', ' '],
                                });

                                // var data_select = ['Non Tamu', 'Tamu', 'MNJ']

                                // $(".select_tamu").select2({
                                //     data: data_select,
                                //     // tags: true,
                                //     // tokenSeparators: [',', ' '],
                                // });

                                $('select:not(.normal)').each(function() {
                                    $(this).select2({
                                        // tags: true,
                                        dropdownParent: $(this)
                                            .parent()
                                    });
                                });

                                var data_select = [
                                    <?php foreach ($pengguna as $peng) : ?> "<?php echo $peng['nama_pengguna']?>",
                                    <?php endforeach ?>'Tamu'
                                ]

                                $(".select_pic").select2({
                                    data: data_select,
                                    // tags: true,
                                    // tokenSeparators: [',', ' '],
                                });

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

                                $.datetimepicker.setLocale('id');
                                $('#tanggal_jam_tiket'+count+'').datetimepicker({
                                    format: 'Y-m-d H:i',
                                    formatDate: 'Y-m-d',
                                    formatTime: 'H:i',
                                    minDate:'0',
                                    step: 1,
                                    closeOnTimeSelect : true,
                                    scrollMonth : false,
                                    scrollInput : false,
                                });

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

                                var data_select = [
                                    <?php foreach ($hotel_jkt as $ho) : ?> "<?php echo $ho['nama_hotel']?><?php echo " - " ?><?php echo $ho['jenis_kamar'] ?>",
                                    <?php endforeach ?>
                                ]

                                $(".select_hotel_jkt").select2({
                                    data: data_select,
                                    // tags: true,
                                    // tokenSeparators: [',', ' '],
                                });

                                var data_select = ['Single', 'Twin / Double']

                                $(".select_type").select2({
                                    data: data_select,
                                    // tags: true,
                                    // tokenSeparators: [',', ' '],
                                });

                                $.datetimepicker.setLocale('id');
                                $('#tanggal_jam_masuk_jkt'+count+'').datetimepicker({
                                    format: 'Y-m-d H:i',
                                    formatDate: 'Y-m-d',
                                    formatTime: 'H:i',
                                    minDate:'0',
                                    step: 1,
                                    disabledDates: [
                                        <?php foreach ($merged as $me) : ?> "<?php echo $me?>",
                                        <?php endforeach ?>
                                    ],
                                    closeOnTimeSelect : true,
                                    scrollMonth : false,
                                    scrollInput : false,
                                });

                                $.datetimepicker.setLocale('id');
                                $('#tanggal_jam_keluar_jkt'+count+'').datetimepicker({
                                    format: 'Y-m-d H:i',
                                    formatDate: 'Y-m-d',
                                    formatTime: 'H:i',
                                    minDate:'0',
                                    step: 1,
                                    disabledDates: [
                                        <?php foreach ($merged as $me) : ?> "<?php echo $me?>",
                                        <?php endforeach ?>
                                    ],
                                    closeOnTimeSelect : true,
                                    scrollMonth : false,
                                    scrollInput : false,
                                });

                                $.datetimepicker.setLocale('id');
                                $('#tanggal_jam_masuk'+count+'').datetimepicker({
                                    format: 'Y-m-d H:i',
                                    formatDate: 'Y-m-d',
                                    formatTime: 'H:i',
                                    minDate:'0',
                                    step: 1,
                                    closeOnTimeSelect : true,
                                    scrollMonth : false,
                                    scrollInput : false,
                                });

                                $.datetimepicker.setLocale('id');
                                $('#tanggal_jam_keluar'+count+'').datetimepicker({
                                    format: 'Y-m-d H:i',
                                    formatDate: 'Y-m-d',
                                    formatTime: 'H:i',
                                    minDate:'0',
                                    step: 1,
                                    closeOnTimeSelect : true,
                                    scrollMonth : false,
                                    scrollInput : false,
                                });

                                var data_select = [
                                    <?php foreach ($pool as $poo) : ?> "<?php echo $poo['nama_pool']?>",
                                    <?php endforeach ?>
                                ]

                                $(".select_gs_mobil").select2({
                                    data: data_select,
                                    // tags: true,
                                    // tokenSeparators: [',', ' '],
                                });

                                var data_select = [
                                    'Sedan', 'Station', 'Pickup', 'Box', 'Truck'
                                ]

                                $(".select_jenis_kendaraan").select2({
                                    data: data_select,
                                    // tags: true,
                                    // tokenSeparators: [',', ' '],
                                });

                                $.datetimepicker.setLocale('id');
                                $('#tanggal_mobil'+count+'').datetimepicker({
                                    format: 'Y-m-d',
                                    formatDate: 'Y-m-d',
                                    minDate:'0',
                                    step: 1,
                                    timepicker : false,
                                    closeOnDateSelect : true,
                                    scrollMonth : false,
                                    scrollInput : false,
                                });

                                $.datetimepicker.setLocale('id');
                                $('#jam_siap'+count+'').datetimepicker({
                                    format: 'H:i',
                                    formatTime: 'H:i',
                                    step: 1,
                                    datepicker : false,
                                });

                                var data_select = [
                                    <?php foreach ($pool as $poo) : ?> "<?php echo $poo['nama_pool']?>",
                                    <?php endforeach ?>
                                ]

                                $('select:not(.normal)').each(function() {
                                    $(this).select2({
                                        // tags: true,
                                        dropdownParent: $(this)
                                            .parent()
                                    });
                                });
                            });

                            $('#remove_item_btn').click(function () {
                                $('.remove_all').remove();
                                count--;
                            });
                        });
                    </script>

                    <div class="card mt-1">
                        <div class="d-flex align-items-center row">
                            <div class="col-sm-12">
                                <div class="card-body" style="text-align:right;">
                                    <a class="btn btn-success btn-lg" href="javascript:void(0)" style="font-size:120%" data-bs-toggle="modal" data-bs-target="#konfirm"><i class="fa-solid fa-check"></i> Konfirmasi</a>

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
                                                    <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal" style="font-size:150%;">
                                                        <i class="fa-solid fa-xmark"></i> Batalkan
                                                    </button>
                                                    <button id="add_btn" class="btn btn-success btn-lg" type="submit" name="save" style="font-size:150%">
                                                        <i class="fa-solid fa-check"></i> Konfirmasi
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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