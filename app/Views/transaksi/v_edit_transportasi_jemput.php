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
                                    <?php foreach ($transportasi_jemput as $tr => $transpo) {?>
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
                                                data-bs-target="#tab_mobil_pulang">
                                                <h3>Mobil Jemput</h3>
                                            </a>
                                        </li>
                                        <!-- End tab nav item -->

                                        <?php foreach ($trans as $t => $tra) {
                                            $id_trans = $tra['id_trans'];
                                        ?>
                                            <?php foreach ($transportasi_jemput as $tr => $transpo) {?>
                                                <?php if ($transpo['id_trans'] == $tra['id_trans']) { ?>
                                                    <?php if ($transpo['status_mobil'] == 0) { ?>
                                                        <li id="modal_confirm" style="display:block;" class="nav-item col-3">
                                                            <a class="nav-link" data-bs-toggle="tab"
                                                                data-bs-target="#tab_confirm">
                                                                <h3>Confirm</h3>
                                                            </a>
                                                        </li>
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
                                                        <?php foreach ($transportasi_jemput as $tr => $transpo) {?>
                                                            <?php if ($transpo['id_trans'] == $tra['id_trans']) { ?>
                                                                <div class="mb-1 mt-3">
                                                                    Nama
                                                                </div>

                                                                <input autocomplete="off" type="text" class="form-control" name="nama" id="nama" style="text-transform:capitalize" value="<?php echo(isset($nama1)) ? $nama1 : $transpo['atas_nama'];?>">

                                                                <div class="mb-1 mt-3">
                                                                    Jabatan
                                                                </div>

                                                                <input autocomplete="off" type="text" class="form-control" name="jabatan" id="jabatan" style="text-transform:capitalize" value="<?php echo(isset($jabatan1)) ? $jabatan1 : $transpo['jabatan'];?>">
                                                                
                                                                <div class="mb-1 mt-3">
                                                                    Pembayaran
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <select class="select_pembayaran" name="pembayaran" style="width: 100%;">
                                                                            <option>
                                                                                <?php 
                                                                                    if($transpo['pembayaran'] == 'k'){
                                                                                        $pembayaran_transport = 'Company Acc';
                                                                                    } else if($transpo['pembayaran'] == 'p'){
                                                                                        $pembayaran_transport = 'Personal Acc';
                                                                                    }
                                                                                ?>
                                                                                <?php echo(isset($pembayaran1)) ? $pembayaran1 : $pembayaran_transport; ?>
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
                                                                });
                                                                </script>

                                                                <div class="mb-1 mt-3">
                                                                    Tamu/Non Tamu
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <select class="select_tamu" name="tamu" style="width: 100%;">
                                                                            <option>
                                                                                <?php echo(isset($tamu1)) ? $tamu1 : $transpo['tamu']; ?>
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <script>
                                                                $(document).ready(function() {
                                                                    var data_select = ['Non Tamu', 'Tamu']

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
                                                                
                                                                <div class="mb-1 mt-3">
                                                                    PIC
                                                                </div>

                                                                <input autocomplete="off" type="text" class="form-control" name="pic" id="pic" style="text-transform:capitalize" value="<?php echo(isset($pic1)) ? $pic1 : $transpo['pic'];?>">

                                                                <div class="modal-footer">
                                                                </div>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="tab-pane" id="tab_mobil_pulang" style="font-size:120%">
                                                        <h2>Mobil Jemput</h2>

                                                        <div class="mb-1">
                                                            GS
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <select class="select_gs_mobil_pulang" name="gs_mobil_pulang" style="width: 100%;">
                                                                    <option>
                                                                        <?php echo(isset($gs_mobil_pulang1)) ? $gs_mobil_pulang1 : $transpo['nama_pool']; ?>
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

                                                            $(".select_gs_mobil_pulang").select2({
                                                                data: data_select,
                                                                // tags: true,
                                                                // tokenSeparators: [',', ' '],
                                                            });
                                                        });
                                                        </script>

                                                        <div class="mb-1 mt-3">
                                                            Jenis Kendaraan
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <select class="select_jenis_kendaraan_pulang" name="jenis_kendaraan_pulang" style="width: 100%;">
                                                                    <option>
                                                                        <?php 
                                                                            if($transpo['jenis_kendaraan'] == 's'){
                                                                                $jenis_kendaraan_pulang2 = 'Sedan';
                                                                            } else if($transpo['jenis_kendaraan'] == 'a'){
                                                                                $jenis_kendaraan_pulang2 = 'Station';
                                                                            } else if($transpo['jenis_kendaraan'] == 'p'){
                                                                                $jenis_kendaraan_pulang2 = 'Pick Up';
                                                                            } else if($transpo['jenis_kendaraan'] == 'b'){
                                                                                $jenis_kendaraan_pulang2 = 'Box';
                                                                            } else if($transpo['jenis_kendaraan'] == 't'){
                                                                                $jenis_kendaraan_pulang2 = 'Truck';
                                                                            }
                                                                        ?>
                                                                        <?php echo(isset($jenis_kendaraan_pulang1)) ? $jenis_kendaraan_pulang1 : $jenis_kendaraan_pulang2; ?>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <script>
                                                        $(document).ready(function() {
                                                            var data_select = [
                                                                'Sedan', 'Station', 'Pickup', 'Box', 'Truck'
                                                            ]

                                                            $(".select_jenis_kendaraan_pulang").select2({
                                                                data: data_select,
                                                                // tags: true,
                                                                // tokenSeparators: [',', ' '],
                                                            });
                                                        });
                                                        </script>
                                                        
                                                        <div class="mb-1 mt-3">
                                                            Butuh Tambahan Tenaga Angkut?
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <select class="select_tenaga_angkut_pulang" name="tenaga_angkut_pulang" style="width: 100%;">
                                                                    <option>
                                                                        <?php
                                                                            if($transpo['tenaga_angkut'] == '1'){
                                                                                $tenaga_angkut_pulang2 = 'Iya';
                                                                            } else {
                                                                                $tenaga_angkut_pulang2 = 'Tidak';
                                                                            }
                                                                        ?>
                                                                        <?php echo(isset($tenaga_angkut_pulang1)) ? $tenaga_angkut_pulang1 : $tenaga_angkut_pulang2; ?>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <script>
                                                        $(document).ready(function() {
                                                            var data_select = [
                                                                //     <php foreach ($bag as $bag) : ?> "<php echo $bag['strorgnm']?>",
                                                                //     <php endforeach ?>
                                                                'Tidak', 'Iya'
                                                            ]

                                                            $(".select_tenaga_angkut_pulang").select2({
                                                                data: data_select,
                                                                // tags: true,
                                                                // tokenSeparators: [',', ' '],
                                                            });
                                                        });
                                                        </script>

                                                        <div class="mb-1 mt-3">
                                                            Dalam/Luar Kota
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <select id="dalkot_lukot_pulang" name="dalkot_lukot_pulang" onchange="showD(this)" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                    <option>
                                                                        <?php
                                                                            if($transpo['dalkot_lukot'] == 'd'){
                                                                                $dalkot_lukot_pulang2 = 'Dalam Kota';
                                                                            } else {
                                                                                $dalkot_lukot_pulang2 = 'Luar Kota';
                                                                            }
                                                                        ?>
                                                                        <?php echo(isset($dalkot_lukot_pulang1)) ? $dalkot_lukot_pulang1 : $dalkot_lukot_pulang2; ?>
                                                                    </option>
                                                                    <option>
                                                                        <?php
                                                                            if($transpo['dalkot_lukot'] == 'd'){
                                                                                $dalkot_lukot_pulang4 = 'Luar Kota';
                                                                            } else {
                                                                                $dalkot_lukot_pulang4 = 'Dalam Kota';
                                                                            }
                                                                        ?>
                                                                        <?php echo(isset($dalkot_lukot_pulang3)) ? $dalkot_lukot_pulang3 : $dalkot_lukot_pulang4; ?>
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <script>
                                                            function showD(select) {
                                                                if (select.value == 'Dalam Kota') {
                                                                    document.getElementById('menginap_pulang').style.display =
                                                                        "none";
                                                                } else {
                                                                    document.getElementById('menginap_pulang').style.display =
                                                                        "block";
                                                                }
                                                            }
                                                        </script>

                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <?php if($transpo['dalkot_lukot'] == 'd'){ ?>
                                                                    <div class="mb-1 mt-3" id="menginap_pulang" style="display:none;">
                                                                <?php } else { ?>
                                                                    <div class="mb-1 mt-3" id="menginap_pulang" style="display:block;">
                                                                <?php } ?>
                                                                    <div class="mb-1">
                                                                        Menginap
                                                                    </div>

                                                                    <select class="select_menginap_pulang" name="menginap_pulang"
                                                                        style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                                        <option>Iya</option>
                                                                        <option>Tidak</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mb-1">
                                                            Jumlah Mobil
                                                        </div>

                                                        <input autocomplete="off" type="number" class="form-control" name="jumlah_mobil_pulang" id="jumlah_mobil_pulang" value="<?php echo(isset($jumlah_mobil_pulang1)) ? $jumlah_mobil_pulang1 : $transpo['jumlah_mobil'];?>">

                                                        <div class="mb-1 mt-3">
                                                            Kapasitas
                                                        </div>

                                                        <input autocomplete="off" type="number" class="form-control" name="kapasitas_pulang" id="kapasitas_pulang" value="<?php echo(isset($kapasitas_pulang1)) ? $kapasitas_pulang1 : $transpo['kapasitas'];?>">

                                                        <div class="mb-1 mt-3">
                                                            Tujuan
                                                        </div>

                                                        <input autocomplete="off" type="text" class="form-control" name="tujuan_mobil_pulang" id="tujuan_mobil_pulang" style="text-transform:capitalize" value="<?php echo(isset($tujuan_mobil_pulang1)) ? $tujuan_mobil_pulang1 : $transpo['tujuan_mobil'];?>">

                                                        <div class="mb-1 mt-3">
                                                            Siap Di
                                                        </div>

                                                        <input autocomplete="off" type="text" class="form-control" name="siap_di_pulang" id="siap_di_pulang" style="text-transform:capitalize" value="<?php echo(isset($siap_di_pulang1)) ? $siap_di_pulang1 : $transpo['siap_di'];?>">

                                                        <div class="mb-1 mt-3">
                                                            Tanggal
                                                        </div>

                                                        <input autocomplete="off" id='tanggal_mobil_pulang' class="form-control" name="tanggal_mobil_pulang" value="<?php echo(isset($tanggal_mobil_pulang1)) ? $tanggal_mobil_pulang1 : $transpo['tanggal_mobil'];?>">

                                                        <script>
                                                            $(function() {
                                                                $.datetimepicker.setLocale('id');
                                                                $('#tanggal_mobil_pulang').datetimepicker({
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

                                                        <div class="mb-1 mt-3">
                                                            Jam Siap
                                                        </div>

                                                        <input autocomplete="off" id='jam_siap_pulang' class="form-control" name="jam_siap_pulang" value="<?php echo(isset($jam_siap_pulang1)) ? $jam_siap_pulang1 : $transpo['jam_siap'];?>">

                                                        <script>
                                                            $(function() {
                                                                $.datetimepicker.setLocale('id');
                                                                $('#jam_siap_pulang').datetimepicker({
                                                                    format: 'H:i',
                                                                    formatTime: 'H:i',
                                                                    step: 1,
                                                                    datepicker : false,
                                                                });
                                                            });
                                                        </script>

                                                        <div class="mb-1 mt-3">
                                                            Keterangan
                                                        </div>

                                                        <textarea class="form-control" name="keterangan_mobil_pulang" rows="3" placeholder=""><?php echo(isset($keterangan_mobil_pulang1)) ? $keterangan_mobil_pulang1 : $transpo['keterangan_mobil']; ?></textarea>

                                                        <div class="mb-1 mt-3">
                                                            Keterangan Tambahan dari GS
                                                        </div>

                                                        <textarea class="form-control" name="keterangan_gs_pulang" rows="3" placeholder=""></textarea>

                                                        <div class="mb-3"></div>
                                                    </div>
                                                    <div class="tab-pane" id="tab_confirm" style="font-size:100%">
                                                        <div class="modal-footer">
                                                            <!-- <button class="btn btn-success btn-lg" type="submit"
                                                                name="save" style="font-size:120%">Konfirmasi</button> -->
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
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
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