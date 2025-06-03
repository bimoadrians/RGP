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
                                <h1>Master Detail Pengguna</h1>
                                <?php $pengguna = site_url("pengguna");?>
                                <a class="btn btn-secondary mb-3" href="<?php echo $pengguna?>" style="font-size:120%;">Kembali</a>
                                <a class="btn btn-primary mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#tambah">+ Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Username</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Bagian</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jabatan</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Level User</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Pool</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($detail_pengguna as $d => $detp) {
                                                $id_detail_pengguna = $detp['id_detail_pengguna'];
                                                $edit_detail_pengguna = site_url("edit_detail_pengguna/$id_pengguna/$id_detail_pengguna");
                                                $hapus = site_url("detail_pengguna/$id_pengguna/?aksi=hapus&id_detail_pengguna=$id_detail_pengguna");
                                            ?>
                                                <tr>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;"><?php echo $i++; ?></td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;"><?php echo $detp['username']; ?></td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;">
                                                        <?php foreach ($bagian_detail_pengguna as $bd => $bdp) { ?>
                                                            <?php if ($bdp['id_detail_pengguna'] == $id_detail_pengguna) { ?>
                                                                <?php echo(isset($detail_pengguna1)) ? $detail_pengguna1 : $bdp['nama_bagian']; ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;">
                                                        <?php foreach ($jabatan_detail_pengguna as $jd => $jdp) { ?>
                                                            <?php if ($jdp['id_detail_pengguna'] == $id_detail_pengguna) { ?>
                                                                <?php echo(isset($detail_pengguna2)) ? $detail_pengguna2 : $jdp['nama_jabatan']; ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;">
                                                        <?php
                                                            if($detp['admin_gs'] == '0'){
                                                                $admin_gs = 'User';
                                                            } else if($detp['admin_gs'] == '1'){
                                                                $admin_gs = 'Admin GS';
                                                            } else if($detp['admin_gs'] == '2'){
                                                                $admin_gs = 'Petugas Pool';
                                                            }
                                                            echo $admin_gs;
                                                        ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;">
                                                        <?php foreach ($pool_detail_pengguna as $pd => $pdp) { ?>
                                                            <?php if ($pdp['id_detail_pengguna'] == $id_detail_pengguna) { ?>
                                                                <?php echo(isset($detail_pengguna3)) ? $detail_pengguna3 : $pdp['nama_pool']; ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-info btn-lg mb-3"
                                                            style="font-size:80%;" href="<?php echo $edit_detail_pengguna?>">Edit</a>
                                                        <a class="btn btn-danger btn-lg mb-3" href="<?php echo $hapus?>" onclick="return confirm('Yakin akan menghapus data detail pengguna?')"
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
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Data Detail Pengguna</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" style="font-size:120%">
                                <div class="mb-1">
                                    Username
                                </div>
                                <div class="mb-3">
                                    <input autocomplete="off" type="text" class="form-control" name="username"
                                        id="username">
                                </div>

                                <div class="mb-1">
                                    Bagian
                                </div>
                                <select class="select_bagian" name="nama_bagian" style="width: 100%;"></select>
                                <script>
                                $(document).ready(function() {
                                    var bagian = [
                                        <?php foreach ($bagian as $b) : ?>"<?php echo $b['nama_bagian']?>",<?php endforeach ?>
                                    ]

                                    $(".select_bagian").select2({
                                        data: bagian,
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
                                    Jabatan
                                </div>
                                <select class="select_jabatan" name="nama_jabatan" style="width: 100%;"></select>
                                <script>
                                $(document).ready(function() {
                                    var jabatan = [
                                        <?php foreach ($jabatan as $j) : ?>"<?php echo $j['nama_jabatan']?>",<?php endforeach ?>
                                    ]

                                    $(".select_jabatan").select2({
                                        data: jabatan,
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
                                    Level User
                                </div>
                                <select class="select_level" name="admin_gs" style="width: 100%;"></select>
                                <script>
                                $(document).ready(function() {
                                    var level = [
                                        'User', 'Admin GS', 'Petugas Pool'
                                    ]

                                    $(".select_level").select2({
                                        data: level,
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