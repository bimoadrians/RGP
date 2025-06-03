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
                                <h1>Evaluasi Transport</h1>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69;">No</th>
                                                <th class="text-center" style="color: #5a5c69;">Nama/Jabatan</th>
                                                <th class="text-center" style="color: #5a5c69;">Driver</th>
                                                <th class="text-center" style="color: #5a5c69;">Tanggal</th>
                                                <th class="text-center" style="color: #5a5c69;">Evaluasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($trans as $t => $tra) {?>
                                                <?php foreach ($transportasi_antar as $tr => $transpo) {
                                                    $id_transportasi = $transpo['id_transportasi'];
                                                    $id_trans = $transpo['id_trans'];
                                                    $detail_evaluasi_transport_antar = site_url("detail_evaluasi_transport_antar/$id_trans/$id_transportasi");
                                                ?>
                                                    <?php if ($transpo['id_trans'] == $tra['id_trans']) { ?>
                                                        <?php if ($transpo['jemput'] == 0) { ?>
                                                            <tr>
                                                                <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                                <td class="text-center" style="color: #5a5c69;"><?php echo $transpo['atas_nama'] ?> <?php echo "(" ?><?php echo $transpo['jabatan'] ?><?php echo ")" ?></td>
                                                                <td class="text-center" style="color: #5a5c69;"><?php echo $transpo['nama_pengemudi'] ?></td>
                                                                <td class="text-center" style="color: #5a5c69;"><?php echo date_transaksi_kendaraan($transpo['tanggal_mobil']) ?> <?php echo $transpo['jam_siap'] ?></td>
                                                                <td class="text-center" style="color: #5a5c69;"><a class="btn btn-primary btn-lg mb-3" style="font-size:80%;" href="<?php echo $detail_evaluasi_transport_antar ?>">Evaluasi</a></td>
                                                            </tr>
                                                        <?php } else if ($transpo['jemput'] == 1) { ?>
                                                            <?php foreach ($transportasi_jemput as $traj => $transpoaj) {
                                                                $id_transportasi_jemput = $transpoaj['id_transportasi_jemput'];
                                                                $id_trans = $transpoaj['id_trans'];
                                                                $detail_evaluasi_transport_jemput = site_url("detail_evaluasi_transport_jemput/$id_trans/$id_transportasi_jemput");
                                                            ?>
                                                                <?php if ($transpoaj['id_trans'] == $transpo['id_trans']) { ?>
                                                                    <tr>
                                                                        <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                                        <td class="text-center" style="color: #5a5c69;"><?php echo $transpoaj['atas_nama'] ?> <?php echo "(" ?><?php echo $transpoaj['jabatan'] ?><?php echo ")" ?></td>
                                                                        <td class="text-center" style="color: #5a5c69;"><?php echo $transpoaj['nama_pengemudi'] ?></td>
                                                                        <td class="text-center" style="color: #5a5c69;"><?php echo date_transaksi_kendaraan($transpoaj['tanggal_mobil']) ?> <?php echo $transpoaj['jam_siap'] ?></td>
                                                                        <td class="text-center" style="color: #5a5c69;"><a class="btn btn-primary btn-lg mb-3" style="font-size:80%;" href="<?php echo $detail_evaluasi_transport_jemput ?>">Evaluasi</a></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } else if ($transpo['jemput'] == 2) { ?>
                                                            <?php foreach ($transportasi_antar_jemput1 as $trj => $transpoj) {
                                                                $id_transportasi = $transpoj['id_transportasi'];
                                                                $id_trans = $transpoj['id_trans'];
                                                                $detail_evaluasi_transport_antar = site_url("detail_evaluasi_transport_antar/$id_trans/$id_transportasi");
                                                            ?>
                                                                <?php if ($transpoj['id_trans'] == $transpo['id_trans']) { ?>
                                                                    <tr>
                                                                        <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                                        <td class="text-center" style="color: #5a5c69;"><?php echo $transpoj['atas_nama'] ?> <?php echo "(" ?><?php echo $transpoj['jabatan'] ?><?php echo ")" ?></td>
                                                                        <td class="text-center" style="color: #5a5c69;"><?php echo $transpoj['nama_pengemudi'] ?></td>
                                                                        <td class="text-center" style="color: #5a5c69;"><?php echo date_transaksi_kendaraan($transpoj['tanggal_mobil']) ?> <?php echo $transpoj['jam_siap'] ?></td>
                                                                        <td class="text-center" style="color: #5a5c69;"><a class="btn btn-primary btn-lg mb-3" style="font-size:80%;" href="<?php echo $detail_evaluasi_transport_antar ?>">Evaluasi</a></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } ?>
                                                            <?php foreach ($transportasi_antar_jemput2 as $trj2 => $transpoj2) {
                                                                $id_transportasi_jemput = $transpoj2['id_transportasi_jemput'];
                                                                $id_trans = $transpoj2['id_trans'];
                                                                $detail_evaluasi_transport_jemput = site_url("detail_evaluasi_transport_jemput/$id_trans/$id_transportasi_jemput");
                                                            ?>
                                                                <?php if ($transpoj2['id_trans'] == $transpo['id_trans']) { ?>
                                                                    <tr>
                                                                        <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                                        <td class="text-center" style="color: #5a5c69;"><?php echo $transpoj2['atas_nama'] ?> <?php echo "(" ?><?php echo $transpoj2['jabatan'] ?><?php echo ")" ?></td>
                                                                        <td class="text-center" style="color: #5a5c69;"><?php echo $transpoj2['nama_pengemudi'] ?></td>
                                                                        <td class="text-center" style="color: #5a5c69;"><?php echo date_transaksi_kendaraan($transpoj2['tanggal_mobil']) ?> <?php echo $transpoj2['jam_siap'] ?></td>
                                                                        <td class="text-center" style="color: #5a5c69;"><a class="btn btn-primary btn-lg mb-3" style="font-size:80%;" href="<?php echo $detail_evaluasi_transport_jemput ?>">Evaluasi</a></td>
                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
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