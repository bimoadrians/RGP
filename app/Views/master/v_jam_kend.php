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
                                <h1>Master Jam Tersedia Kendaraan</h1>
                                <a class="btn btn-primary mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#tambah">+ Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69;">No</th>
                                                <th class="text-center" style="color: #5a5c69;">Kendaraan</th>
                                                <th class="text-center" style="color: #5a5c69;">Tahun/Bulan</th>
                                                <th class="text-center" style="color: #5a5c69;">Jam Tersedia</th>
                                                <th class="text-center" style="color: #5a5c69;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center col-1" style="color: #5a5c69;"></td>
                                                <td class="text-center" style="color: #5a5c69;"></td>
                                                <td class="text-center" style="color: #5a5c69;"></td>
                                                <td class="text-center" style="color: #5a5c69;"></td>
                                                <td class="text-center" style="color: #5a5c69;">
                                                    <a class="btn btn-info btn-lg mb-3" style="font-size:80%"
                                                        href="jam_kend">Edit</a>
                                                    <a class="btn btn-danger btn-lg mb-3" style="font-size:80%"
                                                        href="jam_kend">Hapus</a>
                                                </td>
                                            </tr>
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
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Data Jam Tersedia Kendaraan</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" style="font-size:120%">
                                <div class="mb-1">
                                    Mobil
                                </div>
                                <select class="select_mobil" name="mobil" style="width: 100%;"></select>
                                <script>
                                $(document).ready(function() {
                                    var mobil = [
                                        //     <php foreach ($bag as $bag) : ?> "<php echo $bag['strorgnm']?>",
                                        //     <php endforeach ?>
                                        'Ertiga Putih AD 88831 CP'
                                    ]

                                    $(".select_mobil").select2({
                                        data: mobil,
                                        // tags: true,
                                        // tokenSeparators: [',', ' '],
                                    });
                                });
                                </script>

                                <div class="mb-1 mt-3">
                                    Bulan/Tahun
                                </div>
                                <div class="mb-3">
                                    <input autocomplete="off" type="month" class="form-control" name="bulan" id="bulan">
                                </div>

                                <div class="mb-1 mt-3">
                                    Jam Tersedia
                                </div>
                                <div class="mb-3">
                                    <input autocomplete="off" type="time" class="form-control" name="jam" id="jam">
                                </div>
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