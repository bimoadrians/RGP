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
                                <h1>Arsip Tiket</h1>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Peminta</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Tiket</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Dari</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Tujuan</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Nama/Jabatan</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    PIC</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Tanggal</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Status</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Tanggal Input</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center" style="color: #5a5c69;">1</td>
                                                <td class="text-center" style="color: #5a5c69;">Bimo Adrian Septianto
                                                </td>
                                                <td class="text-center" style="color: #5a5c69;">Argo Bromo Anggrek</td>
                                                <td class="text-center" style="color: #5a5c69;">Solo</td>
                                                <td class="text-center" style="color: #5a5c69;">Surabaya</td>
                                                <td class="text-center" style="color: #5a5c69;">Adrian Bimo Septianto
                                                    (Software Development Officer)</td>
                                                <td class="text-center" style="color: #5a5c69;">Bimo Adrian Septianto
                                                </td>
                                                <td class="text-center" style="color: #5a5c69;">5 Januari 2024</td>
                                                <td class="text-center" style="color: #5a5c69;">Disetujui GS</td>
                                                <td class="text-center" style="color: #5a5c69;">1 Januari 2024 17:00:00
                                                </td>
                                                <td class="text-center" style="color: #5a5c69;">
                                                    <a class="btn btn-warning btn-lg mb-3" style="font-size:80%;"
                                                        href="arsip_tiket">Evaluasi</a>
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