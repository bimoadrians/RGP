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
                                <h1>Master Pengemudi</h1>
                                <a class="btn btn-primary mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#tambah">+ Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Pool</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Nama</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Email</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No HP</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jenis Pengemudi
                                                </th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Mobil</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i=1;?>
                                            <?php foreach ($pengemudi as $p => $penge) {
                                                $id_pengemudi = $penge['id_pengemudi'];
                                                $edit_pengemudi = site_url("edit_pengemudi/$id_pengemudi");
                                                $hapus = site_url("pengemudi/?aksi=hapus&id_pengemudi=$id_pengemudi");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++?></td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <?php foreach ($pool_pengemudi as $pp => $pool_penge) { ?>
                                                            <?php if ($pool_penge['id_pengemudi'] == $id_pengemudi) { ?>
                                                                <?php echo(isset($pengemudi1)) ? $pengemudi1 : $pool_penge['nama_pool']; ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $penge['nama_pengemudi'];?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $penge['email'];?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $penge['nomor_hp'];?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $penge['jenis_sopir'];?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <?php foreach ($mobil_pengemudi as $mp => $mobil_penge) { ?>
                                                            <?php if ($mobil_penge['id_pengemudi'] == $id_pengemudi) { ?>
                                                                <?php echo(isset($pengemudi2)) ? $pengemudi2 : $mobil_penge['nama_mobil']; ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-info btn-lg mb-3"
                                                            style="font-size:80%;" href="<?php echo $edit_pengemudi?>">Edit</a>
                                                        <a class="btn btn-danger btn-lg mb-3" href="<?php echo $hapus?>" onclick="return confirm('Yakin akan menghapus data pengemudi?')"
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
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Data Driver</h2>
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
                                    Nama
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" type="text" class="form-control" name="nama_pengemudi" id="nama_pengemudi">
                                </div>

                                <div class="mb-1 mt-3">
                                    Email<a style="color: #e74a3b">*</a>
                                </div>
                                <div class="mb-3">
                                    <input autocomplete="off" type="text" class="form-control" name="email" id="email">
                                </div>

                                <div class="mb-1 mt-3">
                                    No HP
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" type="text" class="form-control" name="nomor_hp" id="nomor_hp">
                                </div>

                                <div class="mb-1">
                                    Jenis Driver
                                </div>
                                <select class="select_jenis_sopir" name="jenis_sopir" style="width: 100%;"></select>
                                <script>
                                    $(document).ready(function() {
                                        var jenis_sopir = [
                                            <?php foreach ($jenis_sopir as $js) : ?>"<?php echo $js['jenis_sopir']?>",<?php endforeach ?>
                                        ]

                                        $(".select_jenis_sopir").select2({
                                            data: jenis_sopir,
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
                                    Mobil
                                </div>
                                <select class="select_mobil" name="nama_mobil" style="width: 100%;"></select>
                                <script>
                                    $(document).ready(function() {
                                        var mobil = [
                                            <?php foreach ($mobil as $mb) : ?>"<?php echo $mb['nama_mobil']?>",<?php endforeach ?>
                                        ]

                                        $(".select_mobil").select2({
                                            data: mobil,
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