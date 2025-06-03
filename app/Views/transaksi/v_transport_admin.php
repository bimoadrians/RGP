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
                                <h1>Transport</h1>
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
                                                    Nama</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jabatan</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Tujuan</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jenis</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    PIC</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Tanggal</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jam</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Menginap</th>
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
                                                <?php foreach ($transportasi_antar as $tr => $transpo) {
                                                    $id_transportasi = $transpo['id_transportasi'];
                                                    $edit_transportasi = site_url("edit_transportasi_antar/$id_trans/$id_transportasi");
                                                    $confirm_transportasi = site_url("transport_admin/?aksi=confirm&id_trans=$id_trans&id_transportasi=$id_transportasi");
                                                    $batal = site_url("transport_admin/?aksi=batal&id_trans=$id_trans&&id_transportasi=$id_transportasi");
                                                    $tolak_batal = site_url("transport_admin/?aksi=tolak_permintaan_batal&id_trans=$id_trans&id_transportasi=$id_transportasi");
                                                    $batal_confirm = site_url("transport_admin/?aksi=batal_confirm&id_trans=$id_trans&id_transportasi=$id_transportasi");
                                                ?>
                                                    <?php if ($transpo['id_trans'] == $tra['id_trans']) { ?>
                                                            <?php if ($transpo['jemput'] == 0) { ?>
                                                                <tr>
                                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $tra['id_trans'] ?></td>
                                                                    <?php if ($transpo['status_mobil'] == 0) { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php echo $transpo['peminta'] ?>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1) { ?>
                                                                        <td class="text-center" style="color: #1cc88a;">
                                                                            <?php echo $transpo['peminta'] ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($transpo['status_mobil'] == 0) { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php echo $transpo['atas_nama'] ?>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1) { ?>
                                                                        <td class="text-center" style="color: #1cc88a;">
                                                                            <?php echo $transpo['atas_nama'] ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($transpo['status_mobil'] == 0) { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php echo $transpo['jabatan'] ?>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1) { ?>
                                                                        <td class="text-center" style="color: #1cc88a;">
                                                                            <?php echo $transpo['jabatan'] ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($transpo['status_mobil'] == 0) { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php echo $transpo['tujuan_mobil'] ?>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1) { ?>
                                                                        <td class="text-center" style="color: #1cc88a;">
                                                                            <?php echo $transpo['tujuan_mobil'] ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($transpo['status_mobil'] == 0) { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php 
                                                                                if($transpo['jenis_kendaraan'] == 's'){
                                                                                    $jenis_kendaraan = 'Sedan';
                                                                                } else if($transpo['jenis_kendaraan'] == 'a'){
                                                                                    $jenis_kendaraan = 'Station';
                                                                                } else if($transpo['jenis_kendaraan'] == 'p'){
                                                                                    $jenis_kendaraan = 'Pick Up';
                                                                                } else if($transpo['jenis_kendaraan'] == 'b'){
                                                                                    $jenis_kendaraan = 'Box';
                                                                                } else if($transpo['jenis_kendaraan'] == 't'){
                                                                                    $jenis_kendaraan = 'Truck';
                                                                                }
                                                                            ?>
                                                                            <?php echo $jenis_kendaraan ?>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1) { ?>
                                                                        <td class="text-center" style="color: #1cc88a;">
                                                                            <?php 
                                                                                if($transpo['jenis_kendaraan'] == 's'){
                                                                                    $jenis_kendaraan = 'Sedan';
                                                                                } else if($transpo['jenis_kendaraan'] == 'a'){
                                                                                    $jenis_kendaraan = 'Station';
                                                                                } else if($transpo['jenis_kendaraan'] == 'p'){
                                                                                    $jenis_kendaraan = 'Pick Up';
                                                                                } else if($transpo['jenis_kendaraan'] == 'b'){
                                                                                    $jenis_kendaraan = 'Box';
                                                                                } else if($transpo['jenis_kendaraan'] == 't'){
                                                                                    $jenis_kendaraan = 'Truck';
                                                                                }
                                                                            ?>
                                                                            <?php echo $jenis_kendaraan ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($transpo['status_mobil'] == 0) { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php echo $transpo['pic'] ?>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1) { ?>
                                                                        <td class="text-center" style="color: #1cc88a;">
                                                                            <?php echo $transpo['pic'] ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($transpo['status_mobil'] == 0) { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php echo date_transaksi_kendaraan($transpo['tanggal_mobil']) ?>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1) { ?>
                                                                        <td class="text-center" style="color: #1cc88a;">
                                                                            <?php echo date_transaksi_kendaraan($transpo['tanggal_mobil']) ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($transpo['status_mobil'] == 0) { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php echo $transpo['jam_siap'] ?>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1) { ?>
                                                                        <td class="text-center" style="color: #1cc88a;">
                                                                            <?php echo $transpo['jam_siap'] ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($transpo['status_mobil'] == 0) { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php 
                                                                                if($transpo['menginap'] == 0){
                                                                                    $menginap = 'Tidak';
                                                                                } else if($transpo['menginap'] == 1){
                                                                                    $menginap = 'Iya';
                                                                                }
                                                                            ?>
                                                                            <?php echo $menginap ?>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1) { ?>
                                                                        <td class="text-center" style="color: #1cc88a;">
                                                                        <?php 
                                                                                if($transpo['menginap'] == 0){
                                                                                    $menginap = 'Tidak';
                                                                                } else if($transpo['menginap'] == 1){
                                                                                    $menginap = 'Iya';
                                                                                }
                                                                            ?>
                                                                            <?php echo $menginap ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($transpo['status_mobil'] == 0 && $transpo['batal_transportasi'] == '0') { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php 
                                                                                $status_mobil = 'Belum Disetujui GS';
                                                                            ?>
                                                                            <?php echo $status_mobil ?>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 0 && $transpo['batal_transportasi'] == '1') { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php 
                                                                                $status_mobil = 'User mengajukan permintaan batal transport';
                                                                            ?>
                                                                            <?php echo $status_mobil ?>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1 && $transpo['batal_transportasi'] == '0') { ?>
                                                                        <td class="text-center" style="color: #1cc88a;">
                                                                            <?php 
                                                                                $status_mobil = 'Disetujui GS';
                                                                            ?>
                                                                            <?php echo $status_mobil ?>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1 && $transpo['batal_transportasi'] == '1') { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php 
                                                                                $status_mobil = 'User mengajukan permintaan batal transport';
                                                                            ?>
                                                                            <?php echo $status_mobil ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($transpo['status_mobil'] == 0) { ?>
                                                                        <td class="text-center" style="color: #e74a3b;">
                                                                            <?php echo tanggal_jam_transaksi_kendaraan($transpo['created_at']) ?>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1) { ?>
                                                                        <td class="text-center" style="color: #1cc88a;">
                                                                            <?php echo tanggal_jam_transaksi_kendaraan($transpo['created_at']) ?>
                                                                        </td>
                                                                    <?php } ?>
                                                                    <?php if ($transpo['status_mobil'] == 0 && $transpo['batal_transportasi'] == 0) { ?>
                                                                        <td class="text-center" style="color: #5a5c69;">
                                                                            <a class="btn btn-info btn-lg mb-3"
                                                                                style="font-size:80%;" href="<?php echo $edit_transportasi?>">Edit</a>
                                                                            <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                                style="font-size:80%;">Batal</a>
                                                                            <a class="btn btn-success btn-lg mb-3"
                                                                                style="font-size:80%;" href="<?php echo $confirm_transportasi?>">Set Driver</a>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1 && $transpo['batal_transportasi'] == 0) { ?>
                                                                        <td class="text-center" style="color: #5a5c69;">
                                                                            <a class="btn btn-info btn-lg mb-3"
                                                                                style="font-size:80%;" href="<?php echo $edit_transportasi?>">Detail</a>
                                                                            <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal_confirm?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                                style="font-size:80%;">Batal</a>
                                                                                <?php foreach ($set_driver as $sd => $set) { ?>
                                                                                    <?php if ($set['id_trans'] == $tra['id_trans']) { ?>
                                                                                        <?php if (empty($set)) { ?>
                                                                                            
                                                                                        <?php } else { ?>
                                                                                            <a class="btn btn-secondary btn-lg mb-3"
                                                                                            style="font-size:80%; color:white;" data-bs-toggle="modal" data-bs-target="#set_driver">Jam Selesai</a>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                            <a class="btn btn-primary btn-lg mb-3"
                                                                                style="font-size:80%;" href="<?php echo $confirm_transportasi?>">Edit Set Driver</a>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 0 && $transpo['batal_transportasi'] == 1) { ?>
                                                                        <td class="text-center" style="color: #5a5c69;">
                                                                            <a class="btn btn-danger btn-lg mb-3" href="<?php echo $tolak_batal?>" onclick="return confirm('Yakin akan menolak pembatalan transaksi ini?')"
                                                                            style="font-size:80%;">Tolak Pembatalan Transportasi</a>
                                                                            <a class="btn btn-success btn-lg mb-3" href="<?php echo $batal?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                                style="font-size:80%;">Confirm Pembatalan Transportasi</a>
                                                                        </td>
                                                                    <?php } else if ($transpo['status_mobil'] == 1 && $transpo['batal_transportasi'] == 1) { ?>
                                                                        <td class="text-center" style="color: #5a5c69;">
                                                                            <a class="btn btn-danger btn-lg mb-3" href="<?php echo $tolak_batal?>" onclick="return confirm('Yakin akan menolak pembatalan transaksi ini?')"
                                                                            style="font-size:80%;">Tolak Pembatalan Transportasi</a>
                                                                            <a class="btn btn-success btn-lg mb-3" href="<?php echo $batal_confirm?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                                style="font-size:80%;">Confirm Pembatalan Transportasi</a>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                            <?php } else if ($transpo['jemput'] == 1) { ?>
                                                                <?php foreach ($transportasi_jemput as $traj => $transpoaj) {
                                                                    $id_transportasi1 = $transpoaj['id_transportasi'];
                                                                    $id_transportasi_jemput1 = $transpoaj['id_transportasi_jemput'];
                                                                    $edit_transportasi1 = site_url("edit_transportasi_jemput/$id_trans/$id_transportasi1/$id_transportasi_jemput1");
                                                                    $confirm_transportasi1 = site_url("transport_admin/?aksi=confirm&id_trans=$id_trans&id_transportasi=$id_transportasi1&id_transportasi_jemput=$id_transportasi_jemput1");
                                                                    $batal1 = site_url("transportasi_admin/?aksi=batal&id_transportasi=$id_transportasi1");
                                                                ?>
                                                                    <?php if ($transpoaj['id_trans'] == $transpo['id_trans']) { ?>
                                                                        <tr>
                                                                            <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                                            <?php if ($transpoaj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoaj['peminta'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoaj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoaj['peminta'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoaj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoaj['atas_nama'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoaj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoaj['atas_nama'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoaj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoaj['jabatan'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoaj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoaj['jabatan'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoaj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php 
                                                                                        if($transpoaj['jenis_kendaraan'] == 's'){
                                                                                            $jenis_kendaraan = 'Sedan';
                                                                                        } else if($transpoaj['jenis_kendaraan'] == 'a'){
                                                                                            $jenis_kendaraan = 'Station';
                                                                                        } else if($transpoaj['jenis_kendaraan'] == 'p'){
                                                                                            $jenis_kendaraan = 'Pick Up';
                                                                                        } else if($transpoaj['jenis_kendaraan'] == 'b'){
                                                                                            $jenis_kendaraan = 'Box';
                                                                                        } else if($transpoaj['jenis_kendaraan'] == 't'){
                                                                                            $jenis_kendaraan = 'Truck';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $jenis_kendaraan ?>
                                                                                </td>
                                                                            <?php } else if ($transpoaj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php 
                                                                                        if($transpoaj['jenis_kendaraan'] == 's'){
                                                                                            $jenis_kendaraan = 'Sedan';
                                                                                        } else if($transpoaj['jenis_kendaraan'] == 'a'){
                                                                                            $jenis_kendaraan = 'Station';
                                                                                        } else if($transpoaj['jenis_kendaraan'] == 'p'){
                                                                                            $jenis_kendaraan = 'Pick Up';
                                                                                        } else if($transpoaj['jenis_kendaraan'] == 'b'){
                                                                                            $jenis_kendaraan = 'Box';
                                                                                        } else if($transpoaj['jenis_kendaraan'] == 't'){
                                                                                            $jenis_kendaraan = 'Truck';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $jenis_kendaraan ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoaj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoaj['pic'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoaj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoaj['pic'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoaj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo date_transaksi_kendaraan($transpoaj['tanggal_mobil']) ?>
                                                                                </td>
                                                                            <?php } else if ($transpoaj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo date_transaksi_kendaraan($transpoaj['tanggal_mobil']) ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoaj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoaj['jam_siap'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoaj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoaj['jam_siap'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoaj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php 
                                                                                        if($transpoaj['menginap'] == 0){
                                                                                            $menginap = 'Tidak';
                                                                                        } else if($transpoaj['menginap'] == 1){
                                                                                            $menginap = 'Iya';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $menginap ?>
                                                                                </td>
                                                                            <?php } else if ($transpoaj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                <?php 
                                                                                        if($transpoaj['menginap'] == 0){
                                                                                            $menginap = 'Tidak';
                                                                                        } else if($transpoaj['menginap'] == 1){
                                                                                            $menginap = 'Iya';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $menginap ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoaj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php 
                                                                                        if($transpoaj['status_mobil'] == '0'){
                                                                                            $status_mobil = 'Belum Disetujui GS';
                                                                                        } else if($$transpoaj['status_mobil'] == '1'){
                                                                                            $status_mobil = 'Disetujui GS';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $status_mobil ?>
                                                                                </td>
                                                                            <?php } else if ($transpoaj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php 
                                                                                        if($transpoaj['status_mobil'] == '0'){
                                                                                            $status_mobil = 'Belum Disetujui GS';
                                                                                        } else if($transpoaj['status_mobil'] == '1'){
                                                                                            $status_mobil = 'Disetujui GS';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $status_mobil ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoaj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo tanggal_jam_transaksi_kendaraan($transpoaj['created_at']) ?>
                                                                                </td>
                                                                            <?php } else if ($transpoaj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo tanggal_jam_transaksi_kendaraan($transpoaj['created_at']) ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoaj['status_mobil'] == 0 && $transpoaj['batal_transportasi'] == 0) { ?>
                                                                                <td class="text-center" style="color: #5a5c69;">
                                                                                    <a class="btn btn-info btn-lg mb-3"
                                                                                        style="font-size:80%;" href="<?php echo $edit_transportasi1?>">Edit</a>
                                                                                    <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal1?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                                        style="font-size:80%;">Batal</a>
                                                                                    <a class="btn btn-success btn-lg mb-3"
                                                                                        style="font-size:80%;" href="<?php echo $confirm_transportasi1?>">Set Driver</a>
                                                                                </td>
                                                                            <?php } else if ($transpoaj['status_mobil'] == 1 && $transpoaj['batal_transportasi'] == 0) { ?>
                                                                                <td class="text-center" style="color: #5a5c69;">
                                                                                    <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal_confirm?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                                        style="font-size:80%;">Batal</a>
                                                                                </td>
                                                                            <?php } else if ($transpoaj['status_mobil'] == 0 && $transpoaj['batal_transportasi'] == 1) { ?>
                                                                                <td class="text-center" style="color: #5a5c69;">
                                                                                    <a class="btn btn-danger btn-lg mb-3" href="<?php echo $tolak_batal1?>" onclick="return confirm('Yakin akan menolak pembatalan transaksi ini?')"
                                                                                    style="font-size:80%;">Tolak Pembatalan Transportasi</a>
                                                                                    <a class="btn btn-success btn-lg mb-3" href="<?php echo $batal1?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                                        style="font-size:80%;">Confirm Pembatalan Transportasi</a>
                                                                                </td>
                                                                            <?php } else if ($transpoaj['status_mobil'] == 1 && $transpoaj['batal_transportasi'] == 1) { ?>
                                                                                <td class="text-center" style="color: #5a5c69;">
                                                                                    <a class="btn btn-danger btn-lg mb-3" href="<?php echo $tolak_batal1?>" onclick="return confirm('Yakin akan menolak pembatalan transaksi ini?')"
                                                                                    style="font-size:80%;">Tolak Pembatalan Transportasi</a>
                                                                                    <a class="btn btn-success btn-lg mb-3" href="<?php echo $batal_confirm?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                                                        style="font-size:80%;">Confirm Pembatalan Transportasi</a>
                                                                                </td>
                                                                            <?php } ?>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php } else if ($transpo['jemput'] == 2) { ?>
                                                                <?php foreach ($transportasi_antar_jemput1 as $trj => $transpoj) {
                                                                    $id_transportasi2 = $transpoj['id_transportasi'];
                                                                    $edit_transportasi2 = site_url("edit_transportasi_antar/$id_trans/$id_transportasi2");
                                                                    $confirm_transportasi2 = site_url("confirm_transportasi/$id_transportasi2");
                                                                    $batal2 = site_url("transportasi_admin/?aksi=batal&id_transportasi=$id_transportasi2");
                                                                ?>
                                                                    <?php if ($transpoj['id_trans'] == $transpo['id_trans']) { ?>
                                                                        <tr>
                                                                            <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                                            <?php if ($transpoj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoj['peminta'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoj['peminta'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoj['atas_nama'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoj['atas_nama'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoj['jabatan'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoj['jabatan'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php 
                                                                                        if($transpoj['jenis_kendaraan'] == 's'){
                                                                                            $jenis_kendaraan = 'Sedan';
                                                                                        } else if($transpoj['jenis_kendaraan'] == 'a'){
                                                                                            $jenis_kendaraan = 'Station';
                                                                                        } else if($transpoj['jenis_kendaraan'] == 'p'){
                                                                                            $jenis_kendaraan = 'Pick Up';
                                                                                        } else if($transpoj['jenis_kendaraan'] == 'b'){
                                                                                            $jenis_kendaraan = 'Box';
                                                                                        } else if($transpoj['jenis_kendaraan'] == 't'){
                                                                                            $jenis_kendaraan = 'Truck';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $jenis_kendaraan ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php 
                                                                                        if($transpoj['jenis_kendaraan'] == 's'){
                                                                                            $jenis_kendaraan = 'Sedan';
                                                                                        } else if($transpoj['jenis_kendaraan'] == 'a'){
                                                                                            $jenis_kendaraan = 'Station';
                                                                                        } else if($transpoj['jenis_kendaraan'] == 'p'){
                                                                                            $jenis_kendaraan = 'Pick Up';
                                                                                        } else if($transpoj['jenis_kendaraan'] == 'b'){
                                                                                            $jenis_kendaraan = 'Box';
                                                                                        } else if($transpoj['jenis_kendaraan'] == 't'){
                                                                                            $jenis_kendaraan = 'Truck';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $jenis_kendaraan ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoj['pic'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoj['pic'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo date_transaksi_kendaraan($transpoj['tanggal_mobil']) ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo date_transaksi_kendaraan($transpoj['tanggal_mobil']) ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoj['jam_siap'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoj['jam_siap'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php 
                                                                                        if($transpoj['menginap'] == 0){
                                                                                            $menginap = 'Tidak';
                                                                                        } else if($transpoj['menginap'] == 1){
                                                                                            $menginap = 'Iya';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $menginap ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                <?php 
                                                                                        if($transpoj['menginap'] == 0){
                                                                                            $menginap = 'Tidak';
                                                                                        } else if($transpoj['menginap'] == 1){
                                                                                            $menginap = 'Iya';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $menginap ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php 
                                                                                        if($transpoj['status_mobil'] == '0'){
                                                                                            $status_mobil = 'Belum Disetujui GS';
                                                                                        } else if($$transpoj['status_mobil'] == '1'){
                                                                                            $status_mobil = 'Disetujui GS';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $status_mobil ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php 
                                                                                        if($transpoj['status_mobil'] == '0'){
                                                                                            $status_mobil = 'Belum Disetujui GS';
                                                                                        } else if($transpoj['status_mobil'] == '1'){
                                                                                            $status_mobil = 'Disetujui GS';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $status_mobil ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo tanggal_jam_transaksi_kendaraan($transpoj['created_at']) ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo tanggal_jam_transaksi_kendaraan($transpoj['created_at']) ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <td class="text-center" style="color: #5a5c69;">
                                                                                <a class="btn btn-info btn-lg mb-3" style="font-size:80%;" href="<?php echo $edit_transportasi2?>">Edit</a>
                                                                                <a class="btn btn-danger btn-lg mb-3" style="font-size:80%;" href="<?php echo $edit_transportasi2?>">Batal</a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                                <?php foreach ($transportasi_antar_jemput2 as $trj2 => $transpoj2) {
                                                                    $id_transportasi3 = $transpoj2['id_transportasi'];
                                                                    $id_transportasi_jemput3 = $transpoj2['id_transportasi_jemput'];
                                                                    $edit_transportasi3 = site_url("edit_transportasi_jemput/$id_trans/$id_transportasi3/$id_transportasi_jemput3");
                                                                    $confirm_transportasi3 = site_url("confirm_transportasi/$id_transportasi3");
                                                                    $batal3 = site_url("transportasi_admin/?aksi=batal&id_transportasi=$id_transportasi3");
                                                                ?>
                                                                    <?php if ($transpoj2['id_trans'] == $transpo['id_trans']) { ?>
                                                                        <tr>
                                                                            <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                                            <?php if ($transpoj2['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoj2['peminta'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj2['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoj2['peminta'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj2['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoj2['atas_nama'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj2['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoj2['atas_nama'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj2['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoj2['jabatan'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj2['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoj2['jabatan'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj2['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php 
                                                                                        if($transpoj2['jenis_kendaraan'] == 's'){
                                                                                            $jenis_kendaraan = 'Sedan';
                                                                                        } else if($transpoj2['jenis_kendaraan'] == 'a'){
                                                                                            $jenis_kendaraan = 'Station';
                                                                                        } else if($transpoj2['jenis_kendaraan'] == 'p'){
                                                                                            $jenis_kendaraan = 'Pick Up';
                                                                                        } else if($transpoj2['jenis_kendaraan'] == 'b'){
                                                                                            $jenis_kendaraan = 'Box';
                                                                                        } else if($transpoj2['jenis_kendaraan'] == 't'){
                                                                                            $jenis_kendaraan = 'Truck';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $jenis_kendaraan ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj2['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php 
                                                                                        if($transpoj2['jenis_kendaraan'] == 's'){
                                                                                            $jenis_kendaraan = 'Sedan';
                                                                                        } else if($transpoj2['jenis_kendaraan'] == 'a'){
                                                                                            $jenis_kendaraan = 'Station';
                                                                                        } else if($transpoj2['jenis_kendaraan'] == 'p'){
                                                                                            $jenis_kendaraan = 'Pick Up';
                                                                                        } else if($transpoj2['jenis_kendaraan'] == 'b'){
                                                                                            $jenis_kendaraan = 'Box';
                                                                                        } else if($transpoj2['jenis_kendaraan'] == 't'){
                                                                                            $jenis_kendaraan = 'Truck';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $jenis_kendaraan ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj2['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoj2['pic'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj2['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoj2['pic'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj2['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo date_transaksi_kendaraan($transpoj2['tanggal_mobil']) ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj2['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo date_transaksi_kendaraan($transpoj2['tanggal_mobil']) ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj2['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo $transpoj2['jam_siap'] ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj2['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo $transpoj2['jam_siap'] ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj2['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php 
                                                                                        if($transpoj2['menginap'] == 0){
                                                                                            $menginap = 'Tidak';
                                                                                        } else if($transpoj2['menginap'] == 1){
                                                                                            $menginap = 'Iya';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $menginap ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj2['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                <?php 
                                                                                        if($transpoj2['menginap'] == 0){
                                                                                            $menginap = 'Tidak';
                                                                                        } else if($transpoj2['menginap'] == 1){
                                                                                            $menginap = 'Iya';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $menginap ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj2['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php 
                                                                                        if($transpoj2['status_mobil'] == '0'){
                                                                                            $status_mobil = 'Belum Disetujui GS';
                                                                                        } else if($$transpoj2['status_mobil'] == '1'){
                                                                                            $status_mobil = 'Disetujui GS';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $status_mobil ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj2['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php 
                                                                                        if($transpoj2['status_mobil'] == '0'){
                                                                                            $status_mobil = 'Belum Disetujui GS';
                                                                                        } else if($transpoj2['status_mobil'] == '1'){
                                                                                            $status_mobil = 'Disetujui GS';
                                                                                        }
                                                                                    ?>
                                                                                    <?php echo $status_mobil ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <?php if ($transpoj2['status_mobil'] == 0) { ?>
                                                                                <td class="text-center" style="color: #e74a3b;">
                                                                                    <?php echo tanggal_jam_transaksi_kendaraan($transpoj2['created_at']) ?>
                                                                                </td>
                                                                            <?php } else if ($transpoj2['status_mobil'] == 1) { ?>
                                                                                <td class="text-center" style="color: #1cc88a;">
                                                                                    <?php echo tanggal_jam_transaksi_kendaraan($transpoj2['created_at']) ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <td class="text-center" style="color: #5a5c69;">
                                                                                <a class="btn btn-info btn-lg mb-3" style="font-size:80%;" href="<?php echo $edit_transportasi3?>">Edit</a>
                                                                                <a class="btn btn-danger btn-lg mb-3" style="font-size:80%;" href="<?php echo $edit_transportasi3?>">Batal</a>
                                                                            </td>
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

        <!-- <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <h1>Monitoring Driver</h1>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTables" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Nama Pengemudi</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Jenis Pengemudi</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Mobil</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No HP</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Email</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($status_pengemudi as $p => $penge) { ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $penge['nama_pengemudi'];?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $penge['jenis_sopir'];?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <?php foreach ($mobil_pengemudi as $mp => $mobil_penge) { ?>
                                                            <?php if ($mobil_penge['id_pengemudi'] == $penge['id_pengemudi']) { ?>
                                                                <?php echo(isset($pengemudi2)) ? $pengemudi2 : $mobil_penge['nama_mobil']; ?>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $penge['nomor_hp'];?>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $penge['email'];?></td>
                                                    <td class="text-center" style="color: #5a5c69;">Tersedia</td>
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
        </div> -->
    </div>

    <div class="modal" id="set_driver" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="modalTopTitle">Tambah Durasi</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" style="font-size:120%;">
                                <div class="mb-1">
                                    Durasi
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <input required type="number" min="0" class="form-control" name="durasi" id="durasi" style="height:95%;">
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="col-md-12">
                                            <select class="select_satuan_waktu" id="satuan_waktu" name="satuan_waktu" style="width: 100%;"></select>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    $(document).ready(function() {
                                        var data_select = ['Hari', 'Jam', 'Menit']

                                        $(".select_satuan_waktu").select2({
                                            data: data_select,
                                            // tags: true,
                                            // tokenSeparators: [',', ' '],
                                        });

                                        $('select:not(.normal)').each(function() {
                                            $(this).select2({
                                                // tags: true,
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
                        <button class="btn btn-success btn-lg" type="submit" name="save" style="font-size:120%">Set Driver</button>
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