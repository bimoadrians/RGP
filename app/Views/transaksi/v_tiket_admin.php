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
                                <h1>Tiket</h1>
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
                                                    Tiket</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Harga Tiket</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jumlah Tiket</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Keberangkatan</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Pemberhentian</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Dari/Tujuan</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Nama</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jabatan</th>
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
                                            <?php $i=1;?>
                                            <?php foreach ($trans as $t => $tra) {
                                                $id_trans = $tra['id_trans'];
                                            ?>
                                                <tr>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $tra['id_trans'] ?></td>
                                                    <?php foreach ($tiket as $ti => $tik) {?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $tik['peminta'] ?>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo $tik['peminta'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket as $ti => $tik) {?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $tik['nama_vendor'] ?>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo $tik['nama_vendor'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket as $ti => $tik) {?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo "Rp"; echo number_format($tik['harga_tiket'], 2, ',', '.'); ?>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo "Rp"; echo number_format($tik['harga_tiket'], 2, ',', '.'); ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket as $ti => $tik) {?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $tik['jumlah_tiket'] ?>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo $tik['jumlah_tiket'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket as $ti => $tik) {?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0) { ?>
                                                                <?php if (empty($tik['id_keberangkatan'])) { ?>
                                                                    <td class="text-center" style="color: #e74a3b;">
                                                                        <?php echo $tik['dari_tiket'] ?>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <?php foreach ($berangkat_tiket as $be => $berang) {?>
                                                                        <?php if ($tik['id_keberangkatan'] == $berang['id_pemberhentian']) { ?>
                                                                            <td class="text-center" style="color: #e74a3b;">
                                                                                <?php echo $berang['nama_pemberhentian'] ?>
                                                                            </td>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                                <?php if (empty($tik['id_keberangkatan'])) { ?>
                                                                    <td class="text-center" style="color: #1cc88a;">
                                                                        <?php echo $tik['dari_tiket'] ?>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <?php foreach ($berangkat_tiket as $be => $berang) {?>
                                                                        <?php if ($tik['id_keberangkatan'] == $berang['id_pemberhentian']) { ?>
                                                                            <td class="text-center" style="color: #1cc88a;">
                                                                                <?php echo $berang['nama_pemberhentian'] ?>
                                                                            </td>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket as $ti => $tik) {?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0) { ?>
                                                                <?php if (empty($tik['id_keberangkatan'])) { ?>
                                                                    <td class="text-center" style="color: #e74a3b;">
                                                                        <?php echo $tik['tujuan_tiket'] ?>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <td class="text-center" style="color: #e74a3b;">
                                                                        <?php echo $tik['nama_pemberhentian'] ?>
                                                                    </td>
                                                                <?php } ?>
                                                            <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                                <?php if (empty($tik['id_keberangkatan'])) { ?>
                                                                    <td class="text-center" style="color: #1cc88a;">
                                                                        <?php echo $tik['tujuan_tiket'] ?>
                                                                    </td>
                                                                <?php } else { ?>
                                                                    <td class="text-center" style="color: #1cc88a;">
                                                                        <?php echo $tik['nama_pemberhentian'] ?>
                                                                    </td>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket as $ti => $tik) {?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $tik['dari_tiket'] ?><?php echo "/"?><?php echo $tik['tujuan_tiket'] ?>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo $tik['dari_tiket'] ?><?php echo "/"?><?php echo $tik['tujuan_tiket'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket as $ti => $tik) {?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $tik['atas_nama'] ?>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo $tik['atas_nama'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket as $ti => $tik) {?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $tik['jabatan'] ?>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo $tik['jabatan'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket as $ti => $tik) {?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $tik['pic'] ?>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo $tik['pic'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket as $ti => $tik) {?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo tanggal_jam_transaksi_kendaraan($tik['tanggal_jam_tiket']) ?>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo tanggal_jam_transaksi_kendaraan($tik['tanggal_jam_tiket']) ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket as $ti => $tik) {?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0 && $tik['batal_tiket'] == '0') { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php 
                                                                        $status_tiket = 'Belum Disetujui GS';
                                                                    ?>
                                                                    <?php echo $status_tiket ?>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1 && $tik['batal_tiket'] == '0') { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php 
                                                                        $status_tiket = 'Disetujui GS';
                                                                    ?>
                                                                    <?php echo $status_tiket ?>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 0 && $tik['batal_tiket'] == '1') { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php 
                                                                        $status_tiket = 'User mengajukan permintaan batal tiket';
                                                                    ?>
                                                                    <?php echo $status_tiket ?>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1 && $tik['batal_tiket'] == '1') { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php 
                                                                        $status_tiket = 'User mengajukan permintaan batal tiket';
                                                                    ?>
                                                                    <?php echo $status_tiket ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket as $ti => $tik) { ?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo tanggal_jam_transaksi_kendaraan($tik['created_at']) ?>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1) { ?>
                                                                <td class="text-center" style="color: #1cc88a;">
                                                                    <?php echo tanggal_jam_transaksi_kendaraan($tik['created_at']) ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket as $ti => $tik) {
                                                        $id_tiket = $tik['id_tiket'];
                                                        $edit_tiket = site_url("edit_tiket/$id_trans/$id_tiket");
                                                        $confirm_tiket = site_url("tiket_admin/?aksi=confirm&id_trans=$id_trans&id_tiket=$id_tiket");
                                                        $batal = site_url("tiket_admin/?aksi=batal&id_trans=$id_trans&id_tiket=$id_tiket");
                                                        $tolak_batal = site_url("tiket_admin/?aksi=tolak_permintaan_batal&id_trans=$id_trans&id_tiket=$id_tiket");
                                                        $batal_confirm = site_url("tiket_admin/?aksi=batal_confirm&id_trans=$id_trans&id_tiket=$id_tiket");
                                                    ?>
                                                        <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($tik['status_tiket'] == 0 && $tik['batal_tiket'] == '0') { ?>
                                                                <td class="text-center" style="color: #5a5c69;">
                                                                <a class="btn btn-info btn-lg mb-3"
                                                                    style="font-size:80%;" href="<?php echo $edit_tiket?>">Edit</a>
                                                                <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                    style="font-size:80%;">Batal</a>
                                                                <a class="btn btn-success btn-lg mb-3"
                                                                    style="font-size:80%;" href="<?php echo $confirm_tiket?>" onclick="return confirm('Yakin akan melakukan konfirmasi untuk transaksi ini?')">Confirm</a>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1 && $tik['batal_tiket'] == '0') { ?>
                                                                <td class="text-center" style="color: #5a5c69;">
                                                                <a class="btn btn-info btn-lg mb-3"
                                                                    style="font-size:80%;" href="<?php echo $edit_tiket?>">Detail</a>
                                                                <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal_confirm?>" onclick="return confirm('Yakin akan membatalkan transaksi yang telah disetujui ini?')"
                                                                    style="font-size:80%;">Batal</a>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 0 && $tik['batal_tiket'] == '1') { ?>
                                                                <td class="text-center" style="color: #5a5c69;">
                                                                <a class="btn btn-danger btn-lg mb-3" href="<?php echo $tolak_batal?>" onclick="return confirm('Yakin akan menolak pembatalan transaksi ini?')"
                                                                    style="font-size:80%;">Tolak Pembatalan Tiket</a>
                                                                <a class="btn btn-success btn-lg mb-3" href="<?php echo $batal?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                    style="font-size:80%;">Confirm Pembatalan Tiket</a>
                                                                </td>
                                                            <?php } else if ($tik['status_tiket'] == 1 && $tik['batal_tiket'] == '1') { ?>
                                                                <td class="text-center" style="color: #5a5c69;">
                                                                <a class="btn btn-danger btn-lg mb-3" href="<?php echo $tolak_batal?>" onclick="return confirm('Yakin akan menolak pembatalan transaksi ini?')"
                                                                    style="font-size:80%;">Tolak Pembatalan Tiket</a>
                                                                <a class="btn btn-success btn-lg mb-3" href="<?php echo $batal_confirm?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                    style="font-size:80%;">Confirm Pembatalan Tiket</a>
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
                                <h1>Tiket Batal</h1>
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
                                                    Tiket</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jumlah Tiket</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Harga Tiket</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Nilai Refund Tiket</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Keberangkatan</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Pemberhentian</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Dari/Tujuan</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Nama</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jabatan</th>
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
                                            <?php $i=1;?>
                                            <?php foreach ($trans_batal as $tb => $traba) {
                                                $id_trans = $traba['id_trans'];
                                            ?>
                                                <tr>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $traba['id_trans'] ?></td>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo $tikba['peminta'] ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo $tikba['nama_vendor'] ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo $tikba['jumlah_tiket'] ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo "Rp"; echo number_format($tikba['harga_tiket'], 2, ',', '.'); ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo "Rp"; echo number_format($tikba['refund_tiket'], 2, ',', '.'); ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <?php if (empty($tikba['id_keberangkatan'])) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $tikba['dari_tiket'] ?>
                                                                </td>
                                                            <?php } else { ?>
                                                                <?php foreach ($berangkat_tiket as $be => $berang) {?>
                                                                    <?php if ($tikba['id_keberangkatan'] == $berang['id_pemberhentian']) { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php echo $berang['nama_pemberhentian'] ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <?php if (empty($tikba['id_keberangkatan'])) { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $tikba['tujuan_tiket'] ?>
                                                                </td>
                                                            <?php } else { ?>
                                                                <td class="text-center" style="color: #e74a3b;">
                                                                    <?php echo $tikba['nama_pemberhentian'] ?>
                                                                </td>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo $tikba['dari_tiket'] ?><?php echo "/"?><?php echo $tikba['tujuan_tiket'] ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo $tikba['atas_nama'] ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo $tikba['jabatan'] ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo $tikba['pic'] ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo tanggal_jam_transaksi_kendaraan($tikba['tanggal_jam_tiket']) ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">Transaksi telah dibatalkan</td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) { ?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #e74a3b;">
                                                                <?php echo tanggal_jam_transaksi_kendaraan($tikba['created_at']) ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    <?php foreach ($tiket_batal as $tib => $tikba) {
                                                        $id_tiket = $tikba['id_tiket'];
                                                        $refund_tiket = site_url("tiket_admin/?aksi=refund&id_trans=$id_trans&id_tiket=$id_tiket");
                                                    ?>
                                                        <?php if ($tikba['id_trans'] == $traba['id_trans']) { ?>
                                                            <td class="text-center" style="color: #5a5c69;">
                                                                <a class="btn btn-primary btn-lg mb-3"
                                                                    style="font-size:80%;" href="<?php echo $refund_tiket?>">Pengisian Refund</a>
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