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
                                <h1>Master Hotel</h1>
                                <a class="btn btn-primary mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#tambah">+ Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Hotel</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Kota</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Alamat</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Telp</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Email</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Bintang</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Aksi</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Master Kamar Hotel</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($hotel as $h => $ho) {
                                                $id_hotel = $ho['id_hotel'];
                                                $edit_hotel = site_url("edit_hotel/$id_hotel");
                                                $detail_hotel = site_url("detail_hotel/$id_hotel");
                                                $hapus = site_url("hotel/?aksi=hapus&id_hotel=$id_hotel");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ho['nama_hotel'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ho['nama_kota'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ho['alamat_hotel'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ho['telp_hotel'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ho['email_hotel'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ho['bintang_hotel'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-info btn-lg mb-3"
                                                            style="font-size:80%;" href="<?php echo $edit_hotel?>">Edit</a>
                                                        <a class="btn btn-danger btn-lg mb-3" href="<?php echo $hapus?>" onclick="return confirm('Yakin akan menghapus data hotel?')"
                                                            style="font-size:80%;">Hapus</a>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;"><a
                                                            class="btn btn-success btn-lg mb-3" style="font-size:80%;"
                                                            href="<?php echo $detail_hotel ?>">Lihat detail kamar hotel</a></td>
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
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Data Hotel</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" style="font-size:120%">
                                <div class="mb-1">
                                    Hotel
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" type="text" class="form-control" name="nama_hotel" id="hotel">
                                </div>

                                <div class="mb-1">
                                    Kota
                                </div>
                                <select class="select_nama_kota" name="nama_kota" style="width: 100%;"></select>
                                <script>
                                $(document).ready(function() {
                                    var nama_kota = [
                                            <?php foreach ($kota as $kot) : ?> "<?php echo $kot['nama_kota']?>",
                                            <?php endforeach ?>
                                    ]

                                    $(".select_nama_kota").select2({
                                        data: nama_kota,
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
                                    Alamat<a style="color: #e74a3b">*</a>
                                </div>
                                <div class="mb-1">
                                    <textarea class="form-control" name="alamat_hotel" rows="3" placeholder=""></textarea>
                                </div>

                                <div class="mb-1 mt-3">
                                    Telp
                                </div>
                                <div class="mb-3">
                                    <input required min="0" autocomplete="off" type="number" class="form-control" name="telp_hotel" id="telp_hotel">
                                </div>

                                <div class="mb-1 mt-3">
                                    Email<a style="color: #e74a3b">*</a>
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" type="text" class="form-control" name="email_hotel" id="email_hotel">
                                </div>

                                <div class="mb-1">
                                    Bintang
                                </div>
                                <select class="select_bintang_hotel" name="bintang_hotel" style="width: 100%;"></select>
                                <script>
                                $(document).ready(function() {
                                    var bintang_hotel = [
                                        //     <php foreach ($bag as $bag) : ?> "<php echo $bag['strorgnm']?>",
                                        //     <php endforeach ?>
                                        '5', '4', '3', '2', '1'
                                    ]

                                    $(".select_bintang_hotel").select2({
                                        data: bintang_hotel,
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