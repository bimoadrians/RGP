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
                                <h1>Kirim Pesan via Telegram</h1>
                                <a class="btn btn-primary mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#tambah">+ Tambah Pesan</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Pesan</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No HP</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Nama</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Status Pesan</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Waktu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center" style="color: #5a5c69;">1</td>
                                                <td class="text-center" style="color: #5a5c69;">Siap di Pos Satpam Siap
                                                    di Pos Satpam Siap di Pos Satpam</td>
                                                <td class="text-center" style="color: #5a5c69;">0957463284756</td>
                                                <td class="text-center" style="color: #5a5c69;">Bimo Adrian Septianto
                                                </td>
                                                <td class="text-center" style="color: #5a5c69;">Terkirim</td>
                                                <td class="text-center" style="color: #5a5c69;">1 Januari 2024 17:00:00
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
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Pesan</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" style="font-size:120%">
                                <div class="mb-1">
                                    Pesan
                                </div>
                                <div class="mb-3">
                                    <textarea class="form-control" name="pesan" rows="3" placeholder=""></textarea>
                                </div>

                                <div class="mb-1">
                                    Penerima Pesan
                                </div>
                                <select class="select_penerima" name="penerima" style="width: 100%;"></select>
                                <script>
                                $(document).ready(function() {
                                    var penerima = [
                                        //     <php foreach ($bag as $bag) : ?> "<php echo $bag['strorgnm']?>",
                                        //     <php endforeach ?>
                                        'Bimo Adrian Septianto'
                                    ]

                                    $(".select_penerima").select2({
                                        data: penerima,
                                        // tags: true,
                                        // tokenSeparators: [',', ' '],
                                    });
                                });
                                </script>

                                <div class="mb-1 mt-3">
                                    No HP
                                </div>
                                <div class="mb-1">
                                    <input autocomplete="off" type="number" class="form-control" name="nohp" id="nohp">
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