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
                                <h1>Evaluasi Akomodasi</h1>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69;">No</th>
                                                <th class="text-center" style="color: #5a5c69;">Nama/Jabatan</th>
                                                <th class="text-center" style="color: #5a5c69;">Hotel</th>
                                                <th class="text-center" style="color: #5a5c69;">Tanggal Masuk</th>
                                                <th class="text-center" style="color: #5a5c69;">Tanggal Keluar</th>
                                                <th class="text-center" style="color: #5a5c69;">Evaluasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($e_akomodasi as $a => $ako) {
                                                $id_akomodasi = $ako['id_akomodasi'];
                                                $id_trans = $ako['id_trans'];
                                                $detail_evaluasi_akomodasi = site_url("detail_evaluasi_akomodasi/$id_trans/$id_akomodasi");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ako['atas_nama'] ?> <?php echo "(" ?><?php echo $ako['jabatan'] ?><?php echo ")" ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ako['nama_hotel'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo tanggal_jam_transaksi_kendaraan($ako['tanggal_jam_masuk']) ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo tanggal_jam_transaksi_kendaraan($ako['tanggal_jam_keluar']) ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><a class="btn btn-primary btn-lg mb-3" style="font-size:80%;" href="<?php echo $detail_evaluasi_akomodasi ?>">Evaluasi</a></td>
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