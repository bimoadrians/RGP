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
                                <h1>Master Bagian</h1>
                                <a class="btn btn-primary mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#tambah">+ Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69;">No</th>
                                                <th class="text-center" style="color: #5a5c69;">Bagian</th>
                                                <th class="text-center" style="color: #5a5c69;">Aksi</th>
                                                <th class="text-center" style="color: #5a5c69;">Master Jabatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($bagian as $b => $bag) {
                                                $id_bagian = $bag['id_bagian'];
                                                $jabt = site_url("jabt/$id_bagian");
                                                $edit_dept = site_url("edit_dept/$id_bagian");
                                                $hapus = site_url("dept/?aksi=hapus&id_bagian=$id_bagian");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $bag['nama_bagian']?></td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-info btn-lg mb-3"
                                                            style="font-size:80%;" href="<?php echo $edit_dept?>">Edit</a>
                                                        <a class="btn btn-danger btn-lg mb-3" href="<?php echo $hapus?>" onclick="return confirm('Yakin akan menghapus data bagian?')"
                                                            style="font-size:80%;">Hapus</a>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-success btn-lg mb-3" style="font-size:80%;" href="<?php echo $jabt?>">Lihat master jabatan</a>
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

        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <h1>Master Persetujuan</h1>
                                <a class="btn btn-primary mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#tambah_persetujuan">+ Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTables" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69;">No</th>
                                                <th class="text-center" style="color: #5a5c69;">Jabatan Atasan</th>
                                                <th class="text-center" style="color: #5a5c69;">Jabatan Bawahan</th>
                                                <th class="text-center" style="color: #5a5c69;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($persetujuan as $p => $perse) {
                                                $id_persetujuan = $perse['id_persetujuan'];
                                                $persetujuan = site_url("persetujuan/$id_persetujuan");
                                                $edit_persetujuan = site_url("edit_persetujuan/$id_persetujuan");
                                                $hapus = site_url("dept/?aksi=hapus&id_persetujuan=$id_persetujuan");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++?></td>
                                                    <?php foreach ($jabatan_atasan as $at => $atasan) { ?>
                                                        <?php if ($atasan['id_persetujuan'] == $id_persetujuan) { ?>
                                                            <td class="text-center" style="color: #5a5c69;"><?php echo $atasan['jabatan_atasan']?></td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($jabatan_bawahan as $ba => $bawahan) { ?>
                                                        <?php if ($bawahan['id_persetujuan'] == $id_persetujuan) { ?>
                                                            <td class="text-center" style="color: #5a5c69;"><?php echo $bawahan['jabatan_bawahan']?></td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-info btn-lg mb-3"
                                                            style="font-size:80%;" href="<?php echo $edit_persetujuan?>">Edit</a>
                                                        <a class="btn btn-danger btn-lg mb-3" href="<?php echo $hapus?>" onclick="return confirm('Yakin akan menghapus data persetujuan?')"
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
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Data Bagian</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" style="font-size:120%">
                                <div class="mb-1">
                                    Bagian
                                </div>
                                <div class="mb-1">
                                    <input required autocomplete="off" style="font-size:100%" type="text" class="form-control" name="bagian" id="bagian">
                                </div>
                                <script>
                                    $(document).keypress(function(event){
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

    <div class="modal" id="tambah_persetujuan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="font-size:120%;">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Data Persetujuan</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" style="font-size:120%">
                                <div class="mb-1">
                                    Jabatan Atasan
                                </div>
                                <select class="select_persetujuan" name="jabatan_atasan" style="width: 100%;"></select>
                                <script>
                                    $(document).ready(function() {
                                        var pool = [
                                            <?php foreach ($jabatan as $p) : ?>"<?php echo $p['nama_jabatan']?>",<?php endforeach ?>
                                        ]

                                        $(".select_persetujuan").select2({
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
                                    Jabatan Bawahan
                                </div>
                                <select class="select_perse" name="jabatan_bawahan" style="width: 100%;"></select>
                                <script>
                                    $(document).ready(function() {
                                        var pool = [
                                            <?php foreach ($jabatan as $p) : ?>"<?php echo $p['nama_jabatan']?>",<?php endforeach ?>
                                                //<php echo $p['id_jabatan']?> - <php echo $p['nama_jabatan']?>
                                        ]

                                        $(".select_perse").select2({
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
                                <script>
                                    $(document).keypress(function(event){
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