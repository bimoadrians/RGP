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
                                <h1>Master Kota</h1>
                                <?php $negara = site_url("negara");?>
                                <a class="btn btn-secondary mb-3" href="<?php echo $negara?>" style="font-size:120%;">Kembali</a>
                                <a class="btn btn-success mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#tambah">+ Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69;">No</th>
                                                <th class="text-center" style="color: #5a5c69;">Kota</th>
                                                <th class="text-center" style="color: #5a5c69;">Pool</th>
                                                <td class="text-center" style="color: #5a5c69;">Aksi</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($kota as $k => $kot) {
                                                $id_kota = $kot['id_kota'];
                                                $edit_kota = site_url("edit_kota/$id_negara/$id_kota");
                                                $hapus = site_url("kota/$id_negara/?aksi=hapus&id_kota=$id_kota");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $kot['nama_kota']?></td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <?php foreach ($pool_kota as $p => $po) { ?>
                                                            <?php if ($po['id_kota'] == $id_kota) { ?>
                                                                <?php echo(isset($kota0)) ? $kota0 : $po['nama_pool']; ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-info btn-lg mb-3"
                                                            style="font-size:80%;" href="<?php echo $edit_kota?>">Edit</a>
                                                        <a class="btn btn-danger btn-lg mb-3" href="<?php echo $hapus?>" onclick="return confirm('Yakin akan menghapus data kota?')"
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
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Data Kota</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" style="font-size:120%">
                                <div class="mb-1">
                                    Kota
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" type="text" class="form-control" name="nama_kota" id="nama_kota">
                                </div>

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