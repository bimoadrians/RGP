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
                                <h1>Edit Data Mobil</h1>
                                <div style="font-size: 24px">
                                    <?php foreach ($mobil as $m => $mob) {
                                        $id_mobil = $mob['id_mobil'];
                                        $mobil = site_url("mobil");
                                    ?>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="mb-1">
                                                Pool
                                            </div>
                                            <div class="mb-3">
                                                <select class="select_pool" name="nama_pool" style="width: 100%;">
                                                    <?php foreach ($pool_mobil as $pm => $pmob) { ?>
                                                        <?php if ($pmob['id_mobil'] == $id_mobil) { ?>
                                                            <option>
                                                                <?php echo(isset($mobil0)) ? $mobil0 : $pmob['nama_pool']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <script>
                                                    $(document).ready(function() {
                                                        var pool = [
                                                            <?php foreach ($pool as $p) : ?>"<?php echo $p['nama_pool']?>",<?php endforeach ?>
                                                        ]

                                                        $(".select_pool").select2({
                                                            data: pool,
                                                            // tags: true,
                                                            // tokenSeparators: [',', ' '],
                                                        });

                                                        $('select:not(.normal)').each(function() {
                                                            $(this).select2({
                                                                dropdownParent: $(this)
                                                                    .parent()
                                                            });
                                                        });
                                                    });
                                                </script>
                                            </div>

                                            <div class="mb-1 mt-3">
                                                Kendaraan
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="nama_mobil"
                                                    id="nama_mobil" value="<?php echo(isset($mobil1)) ? $mobil1 : $mob['nama_mobil'];?>">
                                            </div>

                                            <div class="form-check mb-1 mt-3">
                                                <input class="form-check-input" type="checkbox" value="1" id="non_mobil" name="non_mobil"
                                                    style="border-style: solid; border-color: black;"
                                                    <?php if ($mob['non_mobil'] == "0") { ?>
                                                        
                                                    <?php } else { ?>
                                                        checked
                                                    <?php } ?>>
                                                Default
                                            </div>

                                            <div class="mb-1 mt-3">
                                                Nopol
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="nopol" id="nopol" value="<?php echo(isset($mobil2)) ? $mobil2 : $mob['nopol'];?>">
                                            </div>

                                            <div class="mb-1">
                                                Jenis BBM
                                            </div>
                                            <select class="select_jenis_bbm" name="jenis_bbm" style="width: 100%;">
                                                <?php foreach ($jenis_bbm_mobil as $jb => $jbbmm) { ?>
                                                    <?php if ($jbbmm['id_mobil'] == $id_mobil) { ?>
                                                        <option>
                                                            <?php echo(isset($mobil3)) ? $mobil3 : $jbbmm['jenis_bbm']; ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            $(document).ready(function() {
                                                var jenis_bbm = [
                                                    <?php foreach ($jenis_bbm as $jbbm) : ?>"<?php echo $jbbm['jenis_bbm']?>",<?php endforeach ?>
                                                ]

                                                $(".select_jenis_bbm").select2({
                                                    data: jenis_bbm,
                                                    // tags: true,
                                                    // tokenSeparators: [',', ' '],
                                                });

                                                $('select:not(.normal)').each(function() {
                                                    $(this).select2({
                                                        dropdownParent: $(this)
                                                            .parent()
                                                    });
                                                });
                                            });
                                            </script>

                                            <div class="mb-1 mt-3">
                                                Jenis Kendaraan
                                            </div>
                                            <select class="select_jenis_kendaraan" name="jenis_kendaraan" style="width: 100%;">
                                                <?php foreach ($jenis_kendaraan_mobil as $jb => $jkendd) { ?>
                                                    <?php if ($jkendd['id_mobil'] == $id_mobil) { ?>
                                                        <option>
                                                            <?php echo(isset($mobil4)) ? $mobil4 : $jkendd['jenis_kendaraan']; ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            $(document).ready(function() {
                                                var jenis_kendaraan = [
                                                    <?php foreach ($jenis_kendaraan as $jkend) : ?>"<?php echo $jkend['jenis_kendaraan']?>",<?php endforeach ?>
                                                ]

                                                $(".select_jenis_kendaraan").select2({
                                                    data: jenis_kendaraan,
                                                    // tags: true,
                                                    // tokenSeparators: [',', ' '],
                                                });
                                            });
                                            </script>

                                            <div class="mb-1 mt-3">
                                                Tanggal STNK
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" id='tgl_stnk' class="form-control" name="tgl_stnk" value="<?php echo(isset($mobil5)) ? $mobil5 : tanggal_kendaraan($mob['tgl_stnk']);?>">

                                                <script>
                                                    $(function() {
                                                        $.datetimepicker.setLocale('id');
                                                        $('#tgl_stnk').datetimepicker({
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

                                            <div class="mb-1">
                                                Tanggal KEUR
                                            </div>
                                            <div class="mb-3">
                                            <input required autocomplete="off" id='tgl_keur' class="form-control" name="tgl_keur" value="<?php echo(isset($mobil6)) ? $mobil6 : tanggal_kendaraan($mob['tgl_keur']);?>">

                                                <script>
                                                    $(function() {
                                                        $.datetimepicker.setLocale('id');
                                                        $('#tgl_keur').datetimepicker({
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

                                            <div class="mb-1">
                                                KM Mesin
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" min="0" type="number" class="form-control" name="km_mesin" id="km_mesin" value="<?php echo(isset($mobil7)) ? $mobil7 : $mob['km_mesin'];?>">
                                            </div>

                                            <div class="mb-1">
                                                KM Awal Mesin
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" min="0" type="number" class="form-control" name="km_awal_mesin" id="km_awal_mesin" value="<?php echo(isset($mobil8)) ? $mobil8 : $mob['km_awal_mesin'];?>">
                                            </div>

                                            <div class="mb-1">
                                                KM Oli
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" min="0" type="number" class="form-control" name="km_oli" id="km_oli" value="<?php echo(isset($mobil9)) ? $mobil9 : $mob['km_oli'];?>">
                                            </div>

                                            <div class="mb-1">
                                                KM Awal Oli
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" min="0" type="number" class="form-control" name="km_awal_oli" id="km_awal_oli" value="<?php echo(isset($mobil10)) ? $mobil10 : $mob['km_awal_oli'];?>">
                                            </div>

                                            <div class="mb-1">
                                                KM BBM
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" min="0" type="number" class="form-control" name="km_bbm" id="km_bbm" value="<?php echo(isset($mobil11)) ? $mobil11 : $mob['km_bbm'];?>">
                                            </div>

                                            <div class="mb-1">
                                                KM Awal BBM
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" min="0" type="number" class="form-control" name="km_awal_bbm" id="km_awal_bbm" value="<?php echo(isset($mobil12)) ? $mobil12 : $mob['km_awal_bbm'];?>">
                                            </div>

                                            <div class="mb-1">
                                                KM Udara
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" min="0" type="number" class="form-control" name="km_udara" id="km_udara" value="<?php echo(isset($mobil13)) ? $mobil13 : $mob['km_udara'];?>">
                                            </div>

                                            <div class="mb-1">
                                                KM Awal Udara
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" min="0" type="number" class="form-control" name="km_awal_udara" id="km_awal_udara" value="<?php echo(isset($mobil14)) ? $mobil14 : $mob['km_awal_udara'];?>">
                                            </div>

                                            <script>
                                                $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                    if (event.which == '13') {
                                                        event.preventDefault();
                                                    }
                                                });
                                            </script>

                                            <a class="btn btn-secondary" type="button" href="<?php echo $mobil?>" style="font-size:100%">Batalkan</a>
                                            <button class="btn btn-success" type="submit" name="save" style="font-size:100%">Submit</button>
                                        </form>
                                    <?php } ?>
                                </div>
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