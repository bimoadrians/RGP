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
                                <h1>Master Vendor</h1>
                                <a class="btn btn-primary mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#tambah">+ Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69;">No</th>
                                                <th class="text-center" style="color: #5a5c69;">Jenis</th>
                                                <th class="text-center" style="color: #5a5c69;">Vendor</th>
                                                <th class="text-center" style="color: #5a5c69;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($vendor as $v => $ven) {
                                                $id_vendor = $ven['id_vendor'];
                                                $edit_vendor = site_url("edit_vendor/$id_vendor");
                                                $hapus = site_url("vendo/?aksi=hapus&id_vendor=$id_vendor");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++?></td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <?php
                                                            if($ven['jenis_vendor'] == 'B'){
                                                                $jenis_vendor = 'Bus';
                                                            } else if($ven['jenis_vendor'] == 'K'){
                                                                $jenis_vendor = 'Kereta Api';
                                                            } else if($ven['jenis_vendor'] == 'P'){
                                                                $jenis_vendor = 'Pesawat';
                                                            } else if($ven['jenis_vendor'] == 'T'){
                                                                $jenis_vendor = 'Travel';
                                                            } else if($ven['jenis_vendor'] == 'Ka'){
                                                                $jenis_vendor = 'Kapal Laut';
                                                            }
                                                            echo $jenis_vendor
                                                        ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ven['nama_vendor']?></a></td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-info btn-lg mb-3"
                                                            style="font-size:80%;" href="<?php echo $edit_vendor?>">Edit</a>
                                                        <a class="btn btn-danger btn-lg mb-3" href="<?php echo $hapus?>" onclick="return confirm('Yakin akan menghapus data vendor?')"
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

        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <h1>Master Pemberhentian</h1>
                                <a class="btn btn-primary mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#pemberhentian">+ Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTables" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69;">No</th>
                                                <th class="text-center" style="color: #5a5c69;">Jenis</th>
                                                <th class="text-center" style="color: #5a5c69;">Pemberhentian</th>
                                                <th class="text-center" style="color: #5a5c69;">Kota</th>
                                                <th class="text-center" style="color: #5a5c69;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($pemberhentian as $p => $pem) {
                                                $id_pemberhentian = $pem['id_pemberhentian'];
                                                $edit_pemberhentian = site_url("edit_pemberhentian/$id_pemberhentian");
                                                $hapus = site_url("vendo/?aksi=hapus&id_pemberhentian=$id_pemberhentian");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++?></td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <?php
                                                            if($pem['jenis_pemberhentian'] == 'B'){
                                                                $jenis_pemberhentian = 'Bandara';
                                                            } else if($pem['jenis_pemberhentian'] == 'P'){
                                                                $jenis_pemberhentian = 'Pelabuhan';
                                                            } else if($pem['jenis_pemberhentian'] == 'S'){
                                                                $jenis_pemberhentian = 'Stasiun';
                                                            } else if($pem['jenis_pemberhentian'] == 'T'){
                                                                $jenis_pemberhentian = 'Terminal';
                                                            }
                                                            echo $jenis_pemberhentian
                                                        ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $pem['nama_pemberhentian']?></a></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $pem['nama_kota']?></a></td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-info btn-lg mb-3"
                                                            style="font-size:80%;" href="<?php echo $edit_pemberhentian?>">Edit</a>
                                                        <a class="btn btn-danger btn-lg mb-3" href="<?php echo $hapus?>" onclick="return confirm('Yakin akan menghapus data pemberhentian?')"
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
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Data Vendor</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active show">
                                <div class="mb-1">
                                    Jenis
                                </div>
                                <select class="select_jenis_vendor" name="jenis_vendor" style="width: 100%;"></select>
                                <script>
                                $(document).ready(function() {
                                    var jenis = [
                                        //     <php foreach ($bag as $bag) : ?> "<php echo $bag['strorgnm']?>",
                                        //     <php endforeach ?>
                                        'Pesawat', 'Kereta Api', 'Bus', 'Travel', 'Kapal Laut'
                                    ]

                                    $(".select_jenis_vendor").select2({
                                        data: jenis,
                                        // tags: true,
                                        // tokenSeparators: [',', ' '],
                                    });
                                });
                                </script>

                                <div class="mt-3 mb-1">
                                    Vendor
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" style="font-size:120%" type="text" class="form-control"
                                        name="nama_vendor" id="nama_vendor">
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

    <div class="modal" id="pemberhentian" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="font-size:120%;">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Data Pemberhentian</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active show">
                                <div class="mb-1">
                                    Jenis
                                </div>
                                <select class="select_jenis_pemberhentian" name="jenis_pemberhentian" style="width: 100%;"></select>
                                <script>
                                    $(document).ready(function() {
                                        var jenis = [
                                            //     <php foreach ($bag as $bag) : ?> "<php echo $bag['strorgnm']?>",
                                            //     <php endforeach ?>
                                            'Bandara', 'Pelabuhan', 'Stasiun', 'Terminal'
                                        ]

                                        $(".select_jenis_pemberhentian").select2({
                                            data: jenis,
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

                                <div class="mt-3 mb-1">
                                    Pemberhentian
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" style="font-size:130%" type="text" class="form-control"
                                        name="nama_pemberhentian" id="nama_pemberhentian">
                                </div>

                                <div class="mb-1">
                                    Kota
                                </div>
                                <select class="select_nama_kota" name="nama_kota" style="width: 100%;"></select>
                                <script>
                                    $(document).ready(function() {
                                        var jenis = [
                                                <?php foreach ($kota as $kot) : ?> "<?php echo $kot['nama_kota']?>",
                                                <?php endforeach ?>
                                        ]

                                        $(".select_nama_kota").select2({
                                            data: jenis,
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