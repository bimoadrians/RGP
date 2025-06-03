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
                                <h1>Master Pengguna</h1>
                                <a class="btn btn-primary mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#tambah">+ Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    NIK</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Nama</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jenis Kelamin
                                                </th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No HP</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Email</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Alamat Rumah
                                                </th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Aksi</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Master Detail
                                                    Pengguna</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($pengguna as $p => $peng) {
                                                $id_pengguna = $peng['id_pengguna'];
                                                $detail_pengguna = site_url("detail_pengguna/$id_pengguna");
                                                $edit_pengguna = site_url("edit_pengguna/$id_pengguna");
                                                $hapus = site_url("pengguna/?aksi=hapus&id_pengguna=$id_pengguna");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69; word-wrap:break-word;"><?php echo $i++?></td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;"><?php echo $peng['nik_pengguna']?></td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;"><?php echo $peng['nama_pengguna']?></td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;">
                                                        <?php
                                                            if($peng['jenis_kelamin'] == 'l'){
                                                                $jenis_kelamin = 'Laki-laki';
                                                            } else if($peng['jenis_kelamin'] == 'p'){
                                                                $jenis_kelamin = 'Perempuan';
                                                            }
                                                            echo $jenis_kelamin;
                                                        ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;"><?php echo $peng['no_hp_pengguna']?></td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;"><?php echo $peng['email_pengguna']?></td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;"><?php echo $peng['alamat_rumah']?></td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-info btn-lg mb-3"
                                                            style="font-size:80%;" href="<?php echo $edit_pengguna?>">Edit</a>
                                                        <a class="btn btn-danger btn-lg mb-3" href="<?php echo $hapus?>" onclick="return confirm('Yakin akan menghapus data pengguna?')"
                                                            style="font-size:80%;">Hapus</a>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-success btn-lg mb-3" style="font-size:80%;" href="<?php echo $detail_pengguna?>">Lihat master detail pengguna</a>
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
                                <h1>Email Delegasi</h1>
                                <a class="btn btn-primary mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#tambah_delegasi">+ Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTables" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Username</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Email</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Tanggal Jam Mulai
                                                </th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Tanggal Jam Akhir
                                                </th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($email_delegasi as $ed => $delegasi) {
                                                $id_email_delegasi = $delegasi['id_email_delegasi'];
                                                $edit_email_delegasi = site_url("edit_email_delegasi/$id_email_delegasi");
                                                $hapus = site_url("pengguna/?aksi=hapus&id_email_delegasi=$id_email_delegasi");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69; word-wrap:break-word;"><?php echo $i++?></td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;"><?php echo $delegasi['username']?></td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;"><?php echo $delegasi['email_pengguna']?></td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;"><?php echo tanggal_jam_transaksi_kendaraan(date("Y-m-d H:i:s", substr((int)$delegasi['tanggal_jam_mulai'], 0, 10)));?></td>
                                                    <td class="text-center" style="color: #5a5c69; word-wrap:break-word;"><?php echo tanggal_jam_transaksi_kendaraan(date("Y-m-d H:i:s", substr((int)$delegasi['tanggal_jam_akhir'], 0, 10)));?></td> 
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-info btn-lg mb-3"
                                                            style="font-size:80%;" href="<?php echo $edit_email_delegasi?>">Edit</a>
                                                        <a class="btn btn-danger btn-lg mb-3" href="<?php echo $hapus?>" onclick="return confirm('Yakin akan menghapus data email delegasi?')"
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
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Data Pengguna</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" style="font-size:120%">
                                <div class="mb-1">
                                    NIK
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" type="number" class="form-control" name="nik_pengguna" id="nik_pengguna">
                                </div>

                                <div class="mb-1">
                                    Nama
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" type="text" class="form-control" name="nama_pengguna" id="nama_pengguna">
                                </div>

                                <div class="mb-1">
                                    Jenis Kelamin
                                </div>
                                <select class="select_kelamin" name="jenis_kelamin" style="width: 100%;"></select>
                                <script>
                                $(document).ready(function() {
                                    var kelamin = [
                                        'Laki-laki', 'Perempuan'
                                    ]

                                    $(".select_kelamin").select2({
                                        data: kelamin,
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
                                    Alamat Rumah<a style="color: #e74a3b">*</a>
                                </div>
                                <div class="mb-1">
                                    <textarea class="form-control" name="alamat_rumah" rows="3" placeholder=""></textarea>
                                </div>

                                <div class="mb-1 mt-3">
                                    No HP<a style="color: #e74a3b">*</a>
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" type="text" class="form-control" name="no_hp_pengguna" id="no_hp_pengguna">
                                </div>

                                <div class="mb-1">
                                    Email
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" type="email" class="form-control" name="email_pengguna" id="email_pengguna">
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
                        <div class="col-xl-12 col-lg-12 mb-3">
                            <a style="color: #e74a3b">&nbsp;*Silahkan beri tanda "-" jika tidak ada informasi</a>
                        </div>
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal"
                            style="font-size:120%">Batalkan</button>
                        <button class="btn btn-success" type="submit" name="save" style="font-size:120%">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal" id="tambah_delegasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="font-size:120%;">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Data Email Delegasi</h2>
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
                                <select class="select_username" name="username" style="width: 100%;"></select>
                                <script>
                                $(document).ready(function() {
                                    var data_select = [
                                        <?php foreach ($add_delegasi as $addel) : ?> "<?php echo $addel['username']?>",
                                        <?php endforeach ?>
                                    ]

                                    $(".select_username").select2({
                                        data: data_select,
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
                                    Personil Delegasi
                                </div>
                                <select class="select_personil_delegasi" name="personil_delegasi" style="width: 100%;"></select>
                                <script>
                                $(document).ready(function() {
                                    var data_select = [
                                        <?php foreach ($nik_nama as $nina) : ?> "<?php echo $nina['nama_pengguna']?><?php echo " - " ?><?php echo $nina['nik_pengguna'] ?>",
                                        <?php endforeach ?>
                                    ]

                                    $(".select_personil_delegasi").select2({
                                        data: data_select,
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
                                    Tanggal dan Jam Mulai
                                </div>

                                <input autocomplete="off" id='tanggal_jam_mulai' class="form-control" name="tanggal_jam_mulai">
                                
                                <script>
                                    $(function() {
                                        $.datetimepicker.setLocale('id');
                                        $('#tanggal_jam_mulai').datetimepicker({
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
                                <div class="mb-1 mt-3">
                                    Tanggal dan Jam Akhir
                                </div>

                                <input autocomplete="off" id='tanggal_jam_akhir' class="form-control" name="tanggal_jam_akhir">
                                
                                <script>
                                    $(function() {
                                        $.datetimepicker.setLocale('id');
                                        $('#tanggal_jam_akhir').datetimepicker({
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