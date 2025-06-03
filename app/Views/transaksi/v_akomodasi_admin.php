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
                                <h1>Akomodasi</h1>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Id</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Peminta</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Hotel</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Harga Tiket Hotel</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jumlah Kamar</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Tanggal Masuk</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Tanggal Keluar</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Nama</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jabatan</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    PIC</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Status</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Tanggal Input</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($trans as $t => $tra) {
                                                $id_trans = $tra['id_trans'];
                                            ?>
                                                <tr>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $tra['id_trans'] ?></td>
                                                    <?php foreach ($akomodasi as $ak => $ako) {?>
                                                        <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($ako['status_akomodasi'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $ako['peminta'] ?>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo $ako['peminta'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi as $ak => $ako) {?>
                                                        <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($ako['status_akomodasi'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $ako['nama_hotel'] ?>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo $ako['nama_hotel'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi as $ak => $ako) {?>
                                                        <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($ako['status_akomodasi'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo "Rp"; echo number_format($ako['harga_akomodasi'], 2, ',', '.'); ?>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo "Rp"; echo number_format($ako['harga_akomodasi'], 2, ',', '.'); ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi as $ak => $ako) {?>
                                                        <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($ako['status_akomodasi'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $ako['jumlah_kamar'] ?>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo $ako['jumlah_kamar'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi as $ak => $ako) {?>
                                                        <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($ako['status_akomodasi'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo tanggal_jam_transaksi_kendaraan($ako['tanggal_jam_masuk']) ?>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo tanggal_jam_transaksi_kendaraan($ako['tanggal_jam_masuk']) ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi as $ak => $ako) {?>
                                                        <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($ako['status_akomodasi'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo tanggal_jam_transaksi_kendaraan($ako['tanggal_jam_keluar']) ?>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo tanggal_jam_transaksi_kendaraan($ako['tanggal_jam_keluar']) ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi as $ak => $ako) {?>
                                                        <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($ako['status_akomodasi'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $ako['atas_nama'] ?>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo $ako['atas_nama'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi as $ak => $ako) {?>
                                                        <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($ako['status_akomodasi'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $ako['jabatan'] ?>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo $ako['jabatan'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi as $ak => $ako) {?>
                                                        <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($ako['status_akomodasi'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $ako['pic'] ?>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo $ako['pic'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi as $ak => $ako) {?>
                                                        <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($ako['status_akomodasi'] == 0 && $ako['batal_akomodasi'] == '0') { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php 
                                                                        $status_akomodasi = 'Belum Disetujui GS';
                                                                    ?>
                                                                    <?php echo $status_akomodasi ?>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1 && $ako['batal_akomodasi'] == '0') { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php 
                                                                        $status_akomodasi = 'Disetujui GS';
                                                                    ?>
                                                                    <?php echo $status_akomodasi ?>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 0 && $ako['batal_akomodasi'] == '1') { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php 
                                                                        $status_akomodasi = 'User mengajukan permintaan batal akomodasi';
                                                                    ?>
                                                                    <?php echo $status_akomodasi ?>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1 && $ako['batal_akomodasi'] == '1') { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php 
                                                                        $status_akomodasi = 'User mengajukan permintaan batal akomodasi';
                                                                    ?>
                                                                    <?php echo $status_akomodasi ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi as $ak => $ako) {?>
                                                        <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($ako['status_akomodasi'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo tanggal_jam_transaksi_kendaraan($ako['created_at']) ?>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo tanggal_jam_transaksi_kendaraan($ako['created_at']) ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi as $ak => $ako) {
                                                        $id_akomodasi = $ako['id_akomodasi'];
                                                        $edit_akomodasi = site_url("edit_akomodasi/$id_trans/$id_akomodasi");
                                                        $confirm_akomodasi = site_url("akomodasi_admin/?aksi=confirm&id_trans=$id_trans&id_akomodasi=$id_akomodasi");
                                                        $batal = site_url("akomodasi_admin/?aksi=batal&id_trans=$id_trans&id_akomodasi=$id_akomodasi");
                                                        $tolak_batal = site_url("akomodasi_admin/?aksi=tolak_permintaan_batal&id_trans=$id_trans&id_akomodasi=$id_akomodasi");
                                                        $batal_confirm = site_url("akomodasi_admin/?aksi=batal_confirm&id_trans=$id_trans&id_akomodasi=$id_akomodasi");
                                                        $mess_jkt = site_url("akomodasi_admin/?aksi=mess_jkt");
                                                    ?>
                                                        <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($ako['status_akomodasi'] == 0 && $ako['batal_akomodasi'] == 0) { ?>
                                                                <td class="text-center" style="color: #5a5c69;">
                                                                <a class="btn btn-info btn-lg mb-3"
                                                                    style="font-size:80%;" href="<?php echo $edit_akomodasi?>">Edit</a>
                                                                <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                    style="font-size:80%;">Batal</a>
                                                                <a class="btn btn-success btn-lg mb-3"
                                                                    style="font-size:80%;" href="<?php echo $confirm_akomodasi?>" onclick="return confirm('Yakin akan melakukan konfirmasi untuk transaksi ini?')">Confirm</a>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1 && $ako['batal_akomodasi'] == 0) { ?>
                                                                <td class="text-center" style="color: #5a5c69;">
                                                                <a class="btn btn-info btn-lg mb-3"
                                                                    style="font-size:80%;" href="<?php echo $edit_akomodasi?>">Detail</a>
                                                                <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal_confirm?>" onclick="return confirm('Yakin akan membatalkan transaksi yang telah disetujui ini?')"
                                                                    style="font-size:80%;">Batal</a>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 0 && $ako['batal_akomodasi'] == 1) { ?>
                                                                <td class="text-center" style="color: #5a5c69;">
                                                                <a class="btn btn-danger btn-lg mb-3" href="<?php echo $tolak_batal?>" onclick="return confirm('Yakin akan menolak pembatalan transaksi ini?')"
                                                                    style="font-size:80%;">Tolak Pembatalan Akomodasi</a>
                                                                <a class="btn btn-success btn-lg mb-3" href="<?php echo $batal?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                    style="font-size:80%;">Confirm Pembatalan Akomodasi</a>
                                                                </td>
                                                            <?php } else if ($ako['status_akomodasi'] == 1 && $ako['batal_akomodasi'] == 1) { ?>
                                                                <td class="text-center" style="color: #5a5c69;">
                                                                <a class="btn btn-danger btn-lg mb-3" href="<?php echo $tolak_batal?>" onclick="return confirm('Yakin akan menolak pembatalan transaksi ini?')"
                                                                    style="font-size:80%;">Tolak Pembatalan Akomodasi</a>
                                                                <a class="btn btn-success btn-lg mb-3" href="<?php echo $batal_confirm?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                    style="font-size:80%;">Confirm Pembatalan Akomodasi</a>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
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
                                <h1>Batal Akomodasi</h1>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTables" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Id</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Peminta</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Hotel</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Harga Tiket Hotel</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jumlah Kamar</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Nilai Refund Hotel</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Tanggal Masuk</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Tanggal Keluar</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Nama</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jabatan</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    PIC</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Status</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($trans_batal as $tb => $traba) {
                                                $id_trans = $traba['id_trans'];
                                            ?>
                                                <tr>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $traba['id_trans'] ?></td>
                                                    <?php foreach ($akomodasi_batal as $akb => $akoba) {?>
                                                        <?php if ($akoba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo $akoba['peminta'] ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi_batal as $akb => $akoba) {?>
                                                        <?php if ($akoba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo $akoba['nama_hotel'] ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi_batal as $akb => $akoba) {?>
                                                        <?php if ($akoba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo "Rp"; echo number_format($akoba['harga_akomodasi'], 2, ',', '.'); ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi_batal as $akb => $akoba) {?>
                                                        <?php if ($akoba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo "Rp"; echo number_format($akoba['refund_akomodasi'], 2, ',', '.'); ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi_batal as $akb => $akoba) {?>
                                                        <?php if ($akoba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo $akoba['jumlah_kamar'] ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi_batal as $akb => $akoba) {?>
                                                        <?php if ($akoba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo tanggal_jam_transaksi_kendaraan($akoba['tanggal_jam_masuk']) ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi_batal as $akb => $akoba) {?>
                                                        <?php if ($akoba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo tanggal_jam_transaksi_kendaraan($akoba['tanggal_jam_keluar']) ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi_batal as $akb => $akoba) {?>
                                                        <?php if ($akoba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo $akoba['atas_nama'] ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi_batal as $akb => $akoba) {?>
                                                        <?php if ($akoba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo $akoba['jabatan'] ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi_batal as $akb => $akoba) {?>
                                                        <?php if ($akoba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo $akoba['pic'] ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi_batal as $akb => $akoba) {?>
                                                        <?php if ($akoba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">Transaksi telah dibatalkan</td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($akomodasi_batal as $akb => $akoba) {
                                                        $id_akomodasi = $akoba['id_akomodasi'];
                                                        $refund_akomodasi = site_url("akomodasi_admin/?aksi=refund&id_trans=$id_trans&id_akomodasi=$id_akomodasi");
                                                    ?>
                                                        <?php if ($akoba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #5a5c69;">
                                                                <a class="btn btn-primary btn-lg mb-3"
                                                                    style="font-size:80%;" href="<?php echo $refund_akomodasi?>">Pengisian Refund</a>
                                                                </td>
                                                        <?php } ?>
                                                    <?php } ?>
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