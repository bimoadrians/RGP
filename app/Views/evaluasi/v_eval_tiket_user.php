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
                                <h1>Evaluasi Tiket</h1>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69;">No</th>
                                                <th class="text-center" style="color: #5a5c69;">Nama/Jabatan</th>
                                                <th class="text-center" style="color: #5a5c69;">Tiket</th>
                                                <th class="text-center" style="color: #5a5c69;">Tanggal</th>
                                                <th class="text-center" style="color: #5a5c69;">Evaluasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($e_tiket as $t => $tik) {
                                                $id_tiket = $tik['id_tiket'];
                                                $id_trans = $tik['id_trans'];
                                                $detail_evaluasi_tiket = site_url("detail_evaluasi_tiket/$id_trans/$id_tiket");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $tik['atas_nama'] ?> <?php echo "(" ?><?php echo $tik['jabatan'] ?><?php echo ")" ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $tik['nama_vendor'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo tanggal_jam_transaksi_kendaraan($tik['tanggal_jam_tiket']) ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><a class="btn btn-primary btn-lg mb-3" style="font-size:80%;" href="<?php echo $detail_evaluasi_tiket ?>">Evaluasi</a></td>
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