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
                                <h1>Master Mobil</h1>
                                <a class="btn btn-primary mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#tambah">+ Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69;">No</th>
                                                <th class="text-center" style="color: #5a5c69;">Pool</th>
                                                <th class="text-center" style="color: #5a5c69;">Kendaraan</th>
                                                <th class="text-center" style="color: #5a5c69;">Nopol</th>
                                                <th class="text-center" style="color: #5a5c69;">Jenis BBM</th>
                                                <th class="text-center" style="color: #5a5c69;">Jenis Kendaraan</th>
                                                <th class="text-center" style="color: #5a5c69;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($mobil as $m => $mob) {
                                                $id_mobil = $mob['id_mobil'];
                                                $edit_mobil = site_url("edit_mobil/$id_mobil");
                                                $hapus = site_url("mobil/?aksi=hapus&id_mobil=$id_mobil");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++?></td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                    <?php foreach ($pool_mobil as $pm => $pmob) { ?>
                                                        <?php if ($pmob['id_mobil'] == $id_mobil) { ?>
                                                            <?php echo(isset($mobil0)) ? $mobil0 : $pmob['nama_pool']; ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $mob['nama_mobil']?></a></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $mob['nopol']?></a></td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                    <?php foreach ($jenis_bbm_mobil as $jb => $jbbm) { ?>
                                                        <?php if ($jbbm['id_mobil'] == $id_mobil) { ?>
                                                            <?php echo(isset($mobil1)) ? $mobil1 : $jbbm['jenis_bbm']; ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <?php foreach ($jenis_kendaraan_mobil as $jk => $jkend) { ?>
                                                            <?php if ($jkend['id_mobil'] == $id_mobil) { ?>
                                                                <?php echo(isset($mobil2)) ? $mobil2 : $jkend['jenis_kendaraan']; ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-info btn-lg mb-3"
                                                            style="font-size:80%;" href="<?php echo $edit_mobil?>">Edit</a>
                                                        <a class="btn btn-danger btn-lg mb-3" href="<?php echo $hapus?>" onclick="return confirm('Yakin akan menghapus data mobil?')"
                                                            style="font-size:80%;">Hapus</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="tambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="font-size:120%;">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Data Mobil</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" style="font-size:120%">
                                <div class="mb-1">
                                    Pool
                                </div>
                                <select class="select_pool" name="nama_pool" style="width: 100%;"></select>
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

                                <div class="mb-1 mt-3">
                                    Kendaraan
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" type="text" class="form-control" name="nama_mobil"
                                        id="nama_mobil">
                                </div>

                                <div class="form-check mb-1 mt-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="non_mobil" name="non_mobil"
                                        style="border-style: solid; border-color: black;">
                                    Default
                                </div>

                                <div class="mb-1 mt-3">
                                    Nopol
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" type="text" class="form-control" name="nopol" id="nopol">
                                </div>

                                <div class="mb-1">
                                    Jenis BBM
                                </div>
                                <select class="select_jenis_bbm" name="jenis_bbm" style="width: 100%;"></select>
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
                                <select class="select_jenis_kendaraan" name="jenis_kendaraan" style="width: 100%;"></select>
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
                                    <input required autocomplete="off" id='tgl_stnk' class="form-control" name="tgl_stnk">

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
                                <input required autocomplete="off" id='tgl_keur' class="form-control" name="tgl_keur">

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
                                    <input required autocomplete="off" min="0" type="number" class="form-control" name="km_mesin"
                                        id="km_mesin">
                                </div>

                                <div class="mb-1">
                                    KM Awal Mesin
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" min="0" type="number" class="form-control" name="km_awal_mesin"
                                        id="km_awal_mesin">
                                </div>

                                <div class="mb-1">
                                    KM Oli
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" min="0" type="number" class="form-control" name="km_oli"
                                        id="km_oli">
                                </div>

                                <div class="mb-1">
                                    KM Awal Oli
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" min="0" type="number" class="form-control" name="km_awal_oli"
                                        id="km_awal_oli">
                                </div>

                                <div class="mb-1">
                                    KM BBM
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" min="0" type="number" class="form-control" name="km_bbm"
                                        id="km_bbm">
                                </div>

                                <div class="mb-1">
                                    KM Awal BBM
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" min="0" type="number" class="form-control" name="km_awal_bbm"
                                        id="km_awal_bbm">
                                </div>

                                <div class="mb-1">
                                    KM Udara
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" min="0" type="number" class="form-control" name="km_udara"
                                        id="km_udara">
                                </div>

                                <div class="mb-1">
                                    KM Awal Udara
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" min="0" type="number" class="form-control" name="km_awal_udara"
                                        id="km_awal_udara">
                                </div>
                                <script>
                                    $(document).on("keypress", ":input:not(textarea)", function(event) {
                                        if (event.which == '13') {
                                            event.preventDefault();
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal"
                            style="font-size:120%">Batalkan</button>
                        <button class="btn btn-success" type="submit" name="save" style="font-size:120%">Submit</button>
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