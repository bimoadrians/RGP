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
            <div class="col-lg-12 mb-4">
                <?php $mess = site_url("mess_admin");?>
                <a class="btn btn-secondary mb-3" href="<?php echo $mess?>" style="font-size:120%;">Kembali</a>
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-12">
                            <div class="card-body text-center">
                                <h1>Mess Kx Jakarta</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Order Statistics -->
            <div class="col-lg-12 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-3">
                            <h1>Set Kamar Mess</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="table-responsive text-nowrap">
                                <table id="mess_pending" class="display nowrap cell-border" style="width=100%;">
                                    <thead>
                                        <tr class="align-text-center">
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                No</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Personil</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1;?>
                                        <?php foreach ($tangg as $lit => $lim) {
                                            $id_personil_mess = $lim['id_personil_mess'];
                                            $id_trans = $lim['id_trans'];
                                            $id_akomodasi = $lim['id_akomodasi'];
                                            $atas_nama = $lim['atas_nama'];
                                            $batal_lim = site_url("set_mess_jkt/$id_trans/$id_akomodasi/?aksi=batal_mp&id_trans=$id_trans&id_akomodasi=$id_akomodasi&id_personil_mess=$id_personil_mess");
                                        ?>
                                            <tr>
                                                <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                <?php if ($lim['status'] == 0) { ?>
                                                    <td class="text-left" style="color: #e74a3b; font-size:24px;">
                                                        Nama: <?php echo $lim['atas_nama'] ?><br>
                                                        Tanggal Mess: <?php echo tanggal_indo($lim['tanggal_mess']) ?><br>
                                                        <!-- Tanggal Masuk Mess: <?php echo tanggal_jam_transaksi_kendaraan($lim['tanggal_jam_masuk']) ?><br>
                                                        Tanggal Keluar Mess: <?php echo tanggal_jam_transaksi_kendaraan($lim['tanggal_jam_keluar']) ?><br> -->
                                                        <?php if ($lim['jenis_kelamin'] == null) { ?>

                                                        <?php } else { ?>
                                                            <?php
                                                                if($lim['jenis_kelamin'] == 'l') {
                                                                    $jenis_kelamin = 'Laki-laki';
                                                                } else if($lim['jenis_kelamin'] == 'p') {
                                                                    $jenis_kelamin = 'Perempuan';
                                                                }
                                                            ?>
                                                            Jenis Kelamin: <?php echo $jenis_kelamin ?><br>
                                                        <?php } ?>
                                                        Kamar Mess: Belum di set<br>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <?php if ($lim['jenis_kelamin'] == null) { ?>
                                                            <a class="btn btn-info btn-lg mb-3"
                                                                style="font-size:80%; color:white;" data-bs-toggle="modal" data-bs-target="#jenis_kelamin<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>">Jenis Kelamin</a>
                                                        <?php } else { ?>
                                                            
                                                        <?php } ?>
                                                        
                                                        <a class="btn btn-success btn-lg mb-3"
                                                            style="font-size:80%; color:white;" data-bs-toggle="modal" data-bs-target="#set_mess<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>">Set Kamar Mess</a>
                                                        <!-- <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal_lim?>"
                                                            style="font-size:80%;">Batal</a> -->
                                                    </td>
                                                <?php } else if ($lim['status'] == 1) { ?>
                                                    <td class="text-left" style="color: #5a5c69; font-size:24px;">
                                                        Nama: <?php echo $lim['atas_nama'] ?><br>
                                                        Tanggal Mess: <?php echo $lim['tanggal_mess'] ?><br>
                                                        <?php 
                                                            if($lim['jenis_kelamin'] == 'l'){
                                                                $jenis_kelamin = 'Laki-laki';
                                                            } else {
                                                                $jenis_kelamin = 'Perempuan';
                                                            }
                                                        ?>
                                                        <?php if ($lim['jenis_kelamin'] == null) { ?>

                                                        <?php } else { ?>
                                                            <?php
                                                                if($lim['jenis_kelamin'] == 'l') {
                                                                    $jenis_kelamin = 'Laki-laki';
                                                                } else if($lim['jenis_kelamin'] == 'p') {
                                                                    $jenis_kelamin = 'Perempuan';
                                                                }
                                                            ?>
                                                            Jenis Kelamin: <?php echo $jenis_kelamin ?><br>
                                                        <?php } ?>
                                                        <?php 
                                                            if($lim['kamar_mess'] == 1) {
                                                                $kamar_mess = 'Kamar 1';
                                                            } else if($lim['kamar_mess'] == 2) {
                                                                $kamar_mess = 'Kamar 2';
                                                            } else if($lim['kamar_mess'] == 3) {
                                                                $kamar_mess = 'Kamar 3';
                                                            } else if($lim['kamar_mess'] == 4) {
                                                                $kamar_mess = 'Kamar 4';
                                                            } else if($lim['kamar_mess'] == 5) {
                                                                $kamar_mess = 'Kamar 5';
                                                            } else if($lim['kamar_mess'] == 6) {
                                                                $kamar_mess = 'Kamar 6';
                                                            } else if($lim['kamar_mess'] == 7) {
                                                                $kamar_mess = 'Kamar 7';
                                                            }
                                                        ?>
                                                        Kamar Mess: <?php echo $kamar_mess ?><br>
                                                    </td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <?php if ($lim['jenis_kelamin'] == null) { ?>
                                                            <a class="btn btn-primary btn-lg mb-3"
                                                                style="font-size:80%; color:white;" data-bs-toggle="modal" data-bs-target="#jenis_kelamin<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>">Jenis Kelamin</a>
                                                        <?php } else { ?>
                                                            
                                                        <?php } ?>
                                                        
                                                        <a class="btn btn-primary btn-lg mb-3"
                                                            style="font-size:80%; color:white;" data-bs-toggle="modal" data-bs-target="#ubah_mess<?php echo $id_akomodasi; echo $id_trans;; echo $id_personil_mess; ?>">Ubah Kamar Mess</a>
                                                        <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal_lim?>"
                                                            style="font-size:80%;">Cancel Kamar Mess</a>
                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/ Order Statistics -->

            <!-- Order Statistics -->
            <!-- <div class="col-md-6 col-lg-12 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-3">
                            <h1>List Confirm</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="table-responsive text-nowrap">
                                <table id="mess_confirm" class="display nowrap cell-border" style="width=100%">
                                    <thead>
                                        <tr class="align-text-center">
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                No</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                List Confirm</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <php $i=1;?>
                                        <php foreach ($trans as $t => $tra) {
                                            $id_trans = $tra['id_trans'];
                                        ?>
                                            <php foreach ($mess_confirm as $mc => $m_conf) { 
                                                $id_akomodasi = $m_conf['id_akomodasi'];
                                                $id_mess = $m_conf['id_mess'];
                                                $batal_mc = site_url("set_mess_jkt/?aksi=batal_mc&id_trans=$id_trans&id_akomodasi=$id_akomodasi&id_mess=$id_mess");
                                            ?>
                                                <php if ($m_conf['id_trans'] == $tra['id_trans']) { ?>
                                                    <tr>
                                                        <td class="text-center" style="color: #5a5c69;"><php echo $i++ ?></td>
                                                        <td class="text-left" style="color: #5a5c69;">
                                                            Nama: <php echo $m_conf['atas_nama'] ?><br>
                                                            Jabatan : <php echo $m_conf['jabatan'] ?><br>
                                                            Jenis Kelamin: <php echo $m_conf['jumlah_kamar'] ?><br>
                                                            Tanggal Jam Masuk: <php echo $m_conf['tanggal_jam_masuk'] ?><br>
                                                            Tanggal Jam keluar: <php echo $m_conf['tanggal_jam_keluar'] ?><br>
                                                        </td>
                                                        <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-danger btn-lg mb-3" href="<php echo $batal_mc?>" onclick="return confirm('Yakin akan membatalkan transaksi ini?')"
                                                            style="font-size:80%;">Batal</a>
                                                        </td>
                                                    </tr>
                                                <php } ?>
                                            <php } ?>
                                        <php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div> -->
            <!--/ Order Statistics -->
        </div>
        
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row text-center mb-3">
                <h3>Pilih Tanggal</h3>
                <div class="col-lg-11">
                    <?php if (empty($session->getFlashdata('tanggal'))) { ?>
                        <input required autocomplete="off" id='filter_k1' name="filter_k1" class="form-control mb-3" style="text-align: center; height:80%;" placeholder="YYYY-MM-DD" value="<?php echo tanggal_indo($date); ?>">
                    <?php } else { ?>
                        <input required autocomplete="off" id='filter_k1' name="filter_k1" class="form-control mb-3" style="text-align: center; height:80%;" placeholder="YYYY-MM-DD" value="<?php echo tanggal_indo($session->getFlashdata('tanggal')); ?>">
                    <?php } ?>

                    <script>
                        $(function() {
                            $.datetimepicker.setLocale('id');
                            $('#filter_k1').datetimepicker({
                                format: 'Y-m-d',
                                formatDate: 'Y-m-d',
                                step: 1,
                                timepicker : false,
                                closeOnDateSelect : true,
                                scrollMonth : false,
                                scrollInput : false,
                            });
                        });
                    </script>
                </div>
                <div class="col-lg-1">
                    <button class="btn btn-primary btn-lg mb-3" type="submit" name="save" style="font-size:120%">Cari</button>
                </div>
            </div>
        </form>

        <div class="row">
            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-6 col-xl-6 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-3">
                            <h1>Kamar 1</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="table-responsive text-nowrap">
                                <table id="kamar1" class="display nowrap cell-border" style="width=100%">
                                    <thead>
                                        <tr class="align-text-center">
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                No</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Personil</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1;?>
                                        <?php foreach ($list_kamar as $li => $lis) {
                                            $id_personil_mess = $lis['id_personil_mess'];
                                            $id_trans = $lis['id_trans'];
                                            $id_akomodasi = $lis['id_akomodasi'];
                                            $batal_mp = site_url("set_mess_jkt/$id_trans/$id_akomodasi/?aksi=batal_mp&id_trans=$id_trans&id_akomodasi=$id_akomodasi&id_personil_mess=$id_personil_mess");
                                            $tanggal = $session->getFlashdata('tanggal');
                                            if (empty($tanggal)) {
                                                $tanggal = $date;
                                            }
                                        ?>
                                            <?php if ($tanggal == $lis['tanggal_mess']) { ?>
                                                <?php if ($lis['kamar_mess'] == 1) { ?>
                                                    <tr>
                                                        <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                        <td class="text-left" style="color: #5a5c69; font-size:24px;">
                                                            Nama: <?php echo $lis['atas_nama'] ?><br>
                                                            Tanggal Masuk: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_masuk']) ?><br>
                                                            Jam Masuk: <?php echo waktu($lis['tanggal_jam_masuk']) ?><br>
                                                            Tanggal keluar: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_keluar']) ?><br>
                                                            Jam keluar: <?php echo waktu($lis['tanggal_jam_keluar']) ?><br>
                                                            <?php
                                                                if($lis['jenis_kelamin'] == 'l'){
                                                                    $jenis_kelamin = 'Laki-laki';
                                                                } else {
                                                                    $jenis_kelamin = 'Perempuan';
                                                                }
                                                            ?>
                                                            Jenis Kelamin : <?php echo $jenis_kelamin ?><br>
                                                            <!-- Tanggal Jam Masuk: <?php echo $lis['tanggal_mess'] ?><br>
                                                            Tanggal Jam keluar: <?php echo $lis['tanggal_mess'] ?><br> -->
                                                        </td>
                                                        <td class="text-center" style="color: #5a5c69;">
                                                            <a class="btn btn-success btn-lg mb-3"
                                                                style="font-size:80%; color:white;" data-bs-toggle="modal" data-bs-target="#edit_mess<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>">Pindah Kamar</a><br>
                                                            <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal_mp?>"
                                                                style="font-size:80%;">Batal</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/ Order Statistics -->

            <!-- Expense Overview -->
            <div class="col-md-6 col-lg-6 order-1 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-3">
                            <h1>Kamar 2</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="table-responsive text-nowrap">
                                <table id="kamar2" class="display nowrap cell-border" style="width=100%">
                                    <thead>
                                        <tr class="align-text-center">
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                No</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Personil</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1;?>
                                        <?php foreach ($list_kamar as $li => $lis) {
                                            $id_personil_mess = $lis['id_personil_mess'];
                                            $id_trans = $lis['id_trans'];
                                            $id_akomodasi = $lis['id_akomodasi'];
                                            $batal_mp = site_url("set_mess_jkt/$id_trans/$id_akomodasi/?aksi=batal_mp&id_trans=$id_trans&id_akomodasi=$id_akomodasi&id_personil_mess=$id_personil_mess");
                                            $tanggal = $session->getFlashdata('tanggal');
                                            if (empty($tanggal)) {
                                                $tanggal = $date;
                                            }
                                        ?>
                                            <?php if ($tanggal == $lis['tanggal_mess']) { ?>
                                                <?php if ($lis['kamar_mess'] == 2) { ?>
                                                    <tr>
                                                        <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                        <td class="text-left" style="color: #5a5c69; font-size:24px;">
                                                            Nama: <?php echo $lis['atas_nama'] ?><br>
                                                            Tanggal Masuk: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_masuk']) ?><br>
                                                            Jam Masuk: <?php echo waktu($lis['tanggal_jam_masuk']) ?><br>
                                                            Tanggal keluar: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_keluar']) ?><br>
                                                            Jam keluar: <?php echo waktu($lis['tanggal_jam_keluar']) ?><br>
                                                            <?php 
                                                                if($lis['jenis_kelamin'] == 'l'){
                                                                    $jenis_kelamin = 'Laki-laki';
                                                                } else {
                                                                    $jenis_kelamin = 'Perempuan';
                                                                }
                                                            ?>
                                                            Jenis Kelamin : <?php echo $jenis_kelamin ?><br>
                                                            <!-- Tanggal Jam Masuk: <?php echo $lis['tanggal_mess'] ?><br>
                                                            Tanggal Jam keluar: <?php echo $lis['tanggal_mess'] ?><br> -->
                                                        </td>
                                                        <td class="text-center" style="color: #5a5c69;">
                                                            <a class="btn btn-success btn-lg mb-3"
                                                                style="font-size:80%; color:white;" data-bs-toggle="modal" data-bs-target="#edit_mess<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>">Pindah Kamar</a><br>
                                                            <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal_mp?>"
                                                                style="font-size:80%;">Batal</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/ Expense Overview -->
        </div>

        <div class="row">
            <!-- Transactions -->
            <div class="col-md-6 col-lg-6 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-3">
                            <h1>Kamar 3</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="table-responsive text-nowrap">
                                <table id="kamar3" class="display nowrap cell-border" style="width=100%">
                                    <thead>
                                        <tr class="align-text-center">
                                        <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                No</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Personil</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1;?>
                                        <?php foreach ($list_kamar as $li => $lis) {
                                            $id_personil_mess = $lis['id_personil_mess'];
                                            $id_trans = $lis['id_trans'];
                                            $id_akomodasi = $lis['id_akomodasi'];
                                            $batal_mp = site_url("set_mess_jkt/$id_trans/$id_akomodasi/?aksi=batal_mp&id_trans=$id_trans&id_akomodasi=$id_akomodasi&id_personil_mess=$id_personil_mess");
                                            $tanggal = $session->getFlashdata('tanggal');
                                            if (empty($tanggal)) {
                                                $tanggal = $date;
                                            }
                                        ?>
                                            <?php if ($tanggal == $lis['tanggal_mess']) { ?>
                                                <?php if ($lis['kamar_mess'] == 3) { ?>
                                                    <tr>
                                                        <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                        <td class="text-left" style="color: #5a5c69; font-size:24px;">
                                                            Nama: <?php echo $lis['atas_nama'] ?><br>
                                                            Tanggal Masuk: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_masuk']) ?><br>
                                                            Jam Masuk: <?php echo waktu($lis['tanggal_jam_masuk']) ?><br>
                                                            Tanggal keluar: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_keluar']) ?><br>
                                                            Jam keluar: <?php echo waktu($lis['tanggal_jam_keluar']) ?><br>
                                                            <?php 
                                                                if($lis['jenis_kelamin'] == 'l'){
                                                                    $jenis_kelamin = 'Laki-laki';
                                                                } else {
                                                                    $jenis_kelamin = 'Perempuan';
                                                                }
                                                            ?>
                                                            Jenis Kelamin : <?php echo $jenis_kelamin ?><br>
                                                            <!-- Tanggal Jam Masuk: <?php echo $lis['tanggal_mess'] ?><br>
                                                            Tanggal Jam keluar: <?php echo $lis['tanggal_mess'] ?><br> -->
                                                        </td>
                                                        <td class="text-center" style="color: #5a5c69;">
                                                            <a class="btn btn-success btn-lg mb-3"
                                                                style="font-size:80%; color:white;" data-bs-toggle="modal" data-bs-target="#edit_mess<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>">Pindah Kamar</a><br>
                                                            <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal_mp?>"
                                                                style="font-size:80%;">Batal</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/ Transactions -->
        
            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-6 col-xl-6 order-1 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-3">
                            <h1>Kamar 4</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="table-responsive text-nowrap">
                                <table id="kamar4" class="display nowrap cell-border" style="width=100%">
                                    <thead>
                                        <tr class="align-text-center">
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                No</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Personil</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1;?>
                                        <?php foreach ($list_kamar as $li => $lis) {
                                            $id_personil_mess = $lis['id_personil_mess'];
                                            $id_trans = $lis['id_trans'];
                                            $id_akomodasi = $lis['id_akomodasi'];
                                            $batal_mp = site_url("set_mess_jkt/$id_trans/$id_akomodasi/?aksi=batal_mp&id_trans=$id_trans&id_akomodasi=$id_akomodasi&id_personil_mess=$id_personil_mess");
                                            $tanggal = $session->getFlashdata('tanggal');
                                            if (empty($tanggal)) {
                                                $tanggal = $date;
                                            }
                                        ?>
                                            <?php if ($tanggal == $lis['tanggal_mess']) { ?>
                                                <?php if ($lis['kamar_mess'] == 4) { ?>
                                                    <tr>
                                                        <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                        <td class="text-left" style="color: #5a5c69; font-size:24px;">
                                                            Nama: <?php echo $lis['atas_nama'] ?><br>
                                                            Tanggal Masuk: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_masuk']) ?><br>
                                                            Jam Masuk: <?php echo waktu($lis['tanggal_jam_masuk']) ?><br>
                                                            Tanggal keluar: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_keluar']) ?><br>
                                                            Jam keluar: <?php echo waktu($lis['tanggal_jam_keluar']) ?><br>
                                                            <?php 
                                                                if($lis['jenis_kelamin'] == 'l'){
                                                                    $jenis_kelamin = 'Laki-laki';
                                                                } else {
                                                                    $jenis_kelamin = 'Perempuan';
                                                                }
                                                            ?>
                                                            Jenis Kelamin : <?php echo $jenis_kelamin ?><br>
                                                            <!-- Tanggal Jam Masuk: <?php echo $lis['tanggal_mess'] ?><br>
                                                            Tanggal Jam keluar: <?php echo $lis['tanggal_mess'] ?><br> -->
                                                        </td>
                                                        <td class="text-center" style="color: #5a5c69;">
                                                            <a class="btn btn-success btn-lg mb-3"
                                                                style="font-size:80%; color:white;" data-bs-toggle="modal" data-bs-target="#edit_mess<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>">Pindah Kamar</a><br>
                                                            <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal_mp?>"
                                                                style="font-size:80%;">Batal</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/ Order Statistics -->
        </div>

        <div class="row">
            <!-- Expense Overview -->
            <div class="col-md-6 col-lg-6 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-3">
                            <h1>Kamar 5</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="table-responsive text-nowrap">
                                <table id="kamar5" class="display nowrap cell-border" style="width=100%">
                                    <thead>
                                        <tr class="align-text-center">
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                No</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Personil</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1;?>
                                        <?php foreach ($list_kamar as $li => $lis) {
                                            $id_personil_mess = $lis['id_personil_mess'];
                                            $id_trans = $lis['id_trans'];
                                            $id_akomodasi = $lis['id_akomodasi'];
                                            $batal_mp = site_url("set_mess_jkt/$id_trans/$id_akomodasi/?aksi=batal_mp&id_trans=$id_trans&id_akomodasi=$id_akomodasi&id_personil_mess=$id_personil_mess");
                                            $tanggal = $session->getFlashdata('tanggal');
                                            if (empty($tanggal)) {
                                                $tanggal = $date;
                                            }
                                        ?>
                                            <?php if ($tanggal == $lis['tanggal_mess']) { ?>
                                                <?php if ($lis['kamar_mess'] == 5) { ?>
                                                    <tr>
                                                        <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                        <td class="text-left" style="color: #5a5c69; font-size:24px;">
                                                            Nama: <?php echo $lis['atas_nama'] ?><br>
                                                            Tanggal Masuk: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_masuk']) ?><br>
                                                            Jam Masuk: <?php echo waktu($lis['tanggal_jam_masuk']) ?><br>
                                                            Tanggal keluar: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_keluar']) ?><br>
                                                            Jam keluar: <?php echo waktu($lis['tanggal_jam_keluar']) ?><br>
                                                            <?php 
                                                                if($lis['jenis_kelamin'] == 'l'){
                                                                    $jenis_kelamin = 'Laki-laki';
                                                                } else {
                                                                    $jenis_kelamin = 'Perempuan';
                                                                }
                                                            ?>
                                                            Jenis Kelamin : <?php echo $jenis_kelamin ?><br>
                                                            <!-- Tanggal Jam Masuk: <?php echo $lis['tanggal_mess'] ?><br>
                                                            Tanggal Jam keluar: <?php echo $lis['tanggal_mess'] ?><br> -->
                                                        </td>
                                                        <td class="text-center" style="color: #5a5c69;">
                                                            <a class="btn btn-success btn-lg mb-3"
                                                                style="font-size:80%; color:white;" data-bs-toggle="modal" data-bs-target="#edit_mess<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>">Pindah Kamar</a><br>
                                                            <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal_mp?>"
                                                                style="font-size:80%;">Batal</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/ Expense Overview -->

            <!-- Transactions -->
            <div class="col-md-6 col-lg-6 order-1 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-3">
                            <h1>Kamar 6</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="table-responsive text-nowrap">
                                <table id="kamar6" class="display nowrap cell-border" style="width=100%">
                                    <thead>
                                        <tr class="align-text-center">
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                No</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Personil</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1;?>
                                        <?php foreach ($list_kamar as $li => $lis) {
                                            $id_personil_mess = $lis['id_personil_mess'];
                                            $id_trans = $lis['id_trans'];
                                            $id_akomodasi = $lis['id_akomodasi'];
                                            $batal_mp = site_url("set_mess_jkt/$id_trans/$id_akomodasi/?aksi=batal_mp&id_trans=$id_trans&id_akomodasi=$id_akomodasi&id_personil_mess=$id_personil_mess");
                                            $tanggal = $session->getFlashdata('tanggal');
                                            if (empty($tanggal)) {
                                                $tanggal = $date;
                                            }
                                        ?>
                                            <?php if ($tanggal == $lis['tanggal_mess']) { ?>
                                                <?php if ($lis['kamar_mess'] == 6) { ?>
                                                    <tr>
                                                        <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                        <td class="text-left" style="color: #5a5c69; font-size:24px;">
                                                            Nama: <?php echo $lis['atas_nama'] ?><br>
                                                            Tanggal Masuk: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_masuk']) ?><br>
                                                            Jam Masuk: <?php echo waktu($lis['tanggal_jam_masuk']) ?><br>
                                                            Tanggal keluar: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_keluar']) ?><br>
                                                            Jam keluar: <?php echo waktu($lis['tanggal_jam_keluar']) ?><br>
                                                            <?php 
                                                                if($lis['jenis_kelamin'] == 'l'){
                                                                    $jenis_kelamin = 'Laki-laki';
                                                                } else {
                                                                    $jenis_kelamin = 'Perempuan';
                                                                }
                                                            ?>
                                                            Jenis Kelamin : <?php echo $jenis_kelamin ?><br>
                                                            <!-- Tanggal Jam Masuk: <?php echo $lis['tanggal_mess'] ?><br>
                                                            Tanggal Jam keluar: <?php echo $lis['tanggal_mess'] ?><br> -->
                                                        </td>
                                                        <td class="text-center" style="color: #5a5c69;">
                                                            <a class="btn btn-success btn-lg mb-3"
                                                                style="font-size:80%; color:white;" data-bs-toggle="modal" data-bs-target="#edit_mess<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>">Pindah Kamar</a><br>
                                                            <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal_mp?>"
                                                                style="font-size:80%;">Batal</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/ Transactions -->
        </div>

        <div class="row">
            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-6 col-xl-6 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-3">
                            <h1>Kamar 7</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="table-responsive text-nowrap">
                                <table id="kamar7" class="display nowrap cell-border" style="width=100%">
                                    <thead>
                                        <tr class="align-text-center">
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                No</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Personil</th>
                                            <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=1;?>
                                        <?php foreach ($list_kamar as $li => $lis) {
                                            $id_personil_mess = $lis['id_personil_mess'];
                                            $id_trans = $lis['id_trans'];
                                            $id_akomodasi = $lis['id_akomodasi'];
                                            $batal_mp = site_url("set_mess_jkt/$id_trans/$id_akomodasi/?aksi=batal_mp&id_trans=$id_trans&id_akomodasi=$id_akomodasi&id_personil_mess=$id_personil_mess");
                                            $tanggal = $session->getFlashdata('tanggal');
                                            if (empty($tanggal)) {
                                                $tanggal = $date;
                                            }
                                        ?>
                                            <?php if ($tanggal == $lis['tanggal_mess']) { ?>
                                                <?php if ($lis['kamar_mess'] == 7) { ?>
                                                    <tr>
                                                        <td class="text-center" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                        <td class="text-left" style="color: #5a5c69; font-size:24px;">
                                                            Nama: <?php echo $lis['atas_nama'] ?><br>
                                                            Tanggal Masuk: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_masuk']) ?><br>
                                                            Jam Masuk: <?php echo waktu($lis['tanggal_jam_masuk']) ?><br>
                                                            Tanggal keluar: <?php echo tanggal_transaksi_kendaraan($lis['tanggal_jam_keluar']) ?><br>
                                                            Jam keluar: <?php echo waktu($lis['tanggal_jam_keluar']) ?><br>
                                                            <?php 
                                                                if($lis['jenis_kelamin'] == 'l'){
                                                                    $jenis_kelamin = 'Laki-laki';
                                                                } else {
                                                                    $jenis_kelamin = 'Perempuan';
                                                                }
                                                            ?>
                                                            Jenis Kelamin : <?php echo $jenis_kelamin ?><br>
                                                            <!-- Tanggal Jam Masuk: <?php echo $lis['tanggal_mess'] ?><br>
                                                            Tanggal Jam keluar: <?php echo $lis['tanggal_mess'] ?><br> -->
                                                        </td>
                                                        <td class="text-center" style="color: #5a5c69;">
                                                            <a class="btn btn-success btn-lg mb-3"
                                                                style="font-size:80%; color:white;" data-bs-toggle="modal" data-bs-target="#edit_mess<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>">Pindah Kamar</a><br>
                                                            <a class="btn btn-danger btn-lg mb-3" href="<?php echo $batal_mp?>"
                                                                style="font-size:80%;">Batal</a>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--/ Order Statistics -->
        </div>
    </div>

    <?php foreach ($list_mess as $lit => $lim) {
        $id_personil_mess = $lim['id_personil_mess'];
        $id_trans = $lim['id_trans'];
        $id_akomodasi = $lim['id_akomodasi'];
        $atas_nama = $lim['atas_nama'];
        $tanggal_mess = $lim['tanggal_mess'];
    ?>
        <div class="modal" id="jenis_kelamin<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalTopTitle">Tambah Data Jenis Kelamin</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data" onsubmit="return check(this);">
                        <div class="modal-body">
                            <div class="tab-content">
                                <div class="tab-pane active show" style="font-size:120%;">
                                    <div class="mb-1" style="font-size:24px;">
                                        Jenis Kelamin
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <select class="select_jenis_kelamin" id="jenis_kelamin_pilih" name="jenis_kelamin" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                <option value="pilih">-- pilih salah satu --</option>
                                                <option value="l">Laki-laki</option>
                                                <option value="p">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- <script>
                                        function check(form){
                                            if(document.getElementById("jenis_kelamin_pilih").value.trim() == ""){
                                                alert("Silahkan pilih salah satu jenis kelamin terlebih dahulu");
                                                return false;
                                            } else {
                                                return true;
                                            }
                                        }
                                    </script> -->

                                    <input hidden type="text" class="form-control" name="id_ako" id="id_ako" value="<?php echo(isset($id_akomodasi1)) ? $id_akomodasi1 : $id_akomodasi;?>">

                                    <input hidden type="text" class="form-control" name="id_tra" id="id_tra" value="<?php echo(isset($id_trans1)) ? $id_trans1 : $id_trans;?>">

                                    <input hidden type="text" class="form-control" name="id_personil_mess" id="id_personil_mess" value="<?php echo(isset($id_personil_mess1)) ? $id_personil_mess1 : $id_personil_mess;?>">

                                    <input hidden type="text" class="form-control" name="atas_nama" id="atas_nama" value="<?php echo(isset($atas_nama1)) ? $atas_nama1 : $atas_nama;?>">

                                    <input hidden type="text" class="form-control" name="tanggal_mess" id="tanggal_mess" value="<?php echo(isset($tanggal_mess1)) ? $tanggal_mess1 : $tanggal_mess;?>">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success btn-lg" type="submit" name="save" style="font-size:120%">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php foreach ($list_mess as $lit => $lim) {
        $id_personil_mess = $lim['id_personil_mess'];
        $id_trans = $lim['id_trans'];
        $id_akomodasi = $lim['id_akomodasi'];
        $atas_nama = $lim['atas_nama'];
        $tanggal_mess = $lim['tanggal_mess'];
        $gender = $lim['jenis_kelamin'];
    ?>
        <div class="modal" id="set_mess<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalTopTitle">Set Mess</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="tab-content">
                                <div class="tab-pane active show" style="font-size:120%;">
                                    <div class="mb-1" style="font-size:24px;">
                                        Kamar Mess
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <select class="select_kamar" id="kamar_mess" name="kamar_mess" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                <option value="pilih">-- pilih salah satu --</option>
                                                <option value="1">Kamar 1</option>
                                                <option value="2">Kamar 2</option>
                                                <option value="3">Kamar 3</option>
                                                <option value="4">Kamar 4</option>
                                                <option value="5">Kamar 5</option>
                                                <option value="6">Kamar 6</option>
                                                <option value="7">Kamar 7</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-1 mt-3" style="font-size:24px;">
                                        Tanggal
                                    </div>

                                    <table class="text-center" style="width: 100%; font-size:100%;">
                                        <tbody>
                                            <th style="border: 1px solid #5a5c69; text-align: center; padding: 8px; font-size: 24px;">
                                                <?php foreach ($list_mess as $lm => $lme) { ?>
                                                    <?php if ($lme['atas_nama'] == $lim['atas_nama']) { ?>
                                                        <?php if ($lme['status'] == 0) { ?>
                                                            <input class="form-check-input" type="checkbox" value="<?php echo $lme['tanggal_mess']?>" name="1_nilai[1][]" style="border-style: solid; border-color: black;" checked> <?php echo $lme['tanggal_mess']?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            </th>
                                        </tbody>
                                    </table>

                                    <input hidden type="text" class="form-control" name="id_ako" id="id_ako" value="<?php echo(isset($id_akomodasi1)) ? $id_akomodasi1 : $id_akomodasi;?>">

                                    <input hidden type="text" class="form-control" name="id_tra" id="id_tra" value="<?php echo(isset($id_trans1)) ? $id_trans1 : $id_trans;?>">

                                    <input hidden type="text" class="form-control" name="id_personil_mess" id="id_personil_mess" value="<?php echo(isset($id_personil_mess1)) ? $id_personil_mess1 : $id_personil_mess;?>">

                                    <input hidden type="text" class="form-control" name="atas_nama" id="atas_nama" value="<?php echo(isset($atas_nama1)) ? $atas_nama1 : $atas_nama;?>">

                                    <input hidden type="text" class="form-control" name="tanggal_mess" id="tanggal_mess" value="<?php echo(isset($tanggal_mess1)) ? $tanggal_mess1 : $tanggal_mess;?>">

                                    <input hidden type="text" class="form-control" name="gender" id="gender" value="<?php echo(isset($gender1)) ? $gender1 : $gender;?>">

                                    <!-- <div class="mb-1 mt-3" style="font-size:24px;">
                                        Tersedia untuk:
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <select class="select_sisa_kamar" id="sisa_kamar" name="sisa_kamar" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                <option value="pilih">-- pilih kamar terlebih dahulu --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <script>
                                        $(".select_kamar").change(function() {
                                            var val = $(this).val();

                                            <php foreach ($kamar_mess as $km => $kamess) {?>
                                                if (val == "<php echo $kamess['nama_kamar'] ?>") {
                                                    $(".select_sisa_kamar").html(
                                                        "<option><php echo $kamess['kapasitas_kamar'] - $kamess['terpakai']?><php echo " orang" ?></option>"
                                                    );
                                                } else if (val == "pilih") {
                                                    $(".select_sisa_kamar").html(
                                                        "<option value='pilih'>-- pilih kamar terlebih dahulu --</option>"
                                                    );
                                                }
                                            <php } ?>
                                        });
                                    </script> -->
                                    
                                    <!-- <script>
                                        $(document).keypress(function(event){
                                            if (event.which == '13') {
                                            event.preventDefault();
                                            }
                                        });
                                    </script> -->
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success btn-lg" type="submit" name="save" style="font-size:120%">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php foreach ($list_mess as $lit => $lim) {
        $id_personil_mess = $lim['id_personil_mess'];
        $id_trans = $lim['id_trans'];
        $id_akomodasi = $lim['id_akomodasi'];
        $atas_nama = $lim['atas_nama'];
        $tanggal_mess = $lim['tanggal_mess'];
    ?>
        <div class="modal" id="ubah_mess<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalTopTitle">Ubah Mess</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="tab-content">
                                <div class="tab-pane active show" style="font-size:120%;">
                                    <div class="mb-1" style="font-size:24px;">
                                        Kamar Mess
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <select class="select_kamar" id="kamar_mess" name="kamar_mess" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                <option value="pilih">-- pilih salah satu --</option>
                                                <option value="1">Kamar 1</option>
                                                <option value="2">Kamar 2</option>
                                                <option value="3">Kamar 3</option>
                                                <option value="4">Kamar 4</option>
                                                <option value="5">Kamar 5</option>
                                                <option value="6">Kamar 6</option>
                                                <option value="7">Kamar 7</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-1 mt-3" style="font-size:24px;">
                                        Tanggal
                                    </div>

                                    <table class="text-center" style="width: 100%; font-size:100%;">
                                        <tbody>
                                            <th style="border: 1px solid #5a5c69; text-align: center; padding: 8px; font-size: 24px;">
                                                <?php foreach ($list_mess as $lm => $lme) { ?>
                                                    <?php if ($lme['id_personil_mess'] == $lim['id_personil_mess']) { ?>
                                                        <?php if ($lme['status'] == 1) { ?>
                                                            <input class="form-check-input" type="checkbox" value="<?php echo $lme['tanggal_mess']?>" name="1_nilai[1][]" style="border-style: solid; border-color: black;" checked> <?php echo $lme['tanggal_mess']?>
                                                        <?php } ?>
                                                    <?php } ?>
                                                <?php } ?>
                                            </th>
                                        </tbody>
                                    </table>

                                    <input hidden type="text" class="form-control" name="id_ako" id="id_ako" value="<?php echo(isset($id_akomodasi1)) ? $id_akomodasi1 : $id_akomodasi;?>">

                                    <input hidden type="text" class="form-control" name="id_tra" id="id_tra" value="<?php echo(isset($id_trans1)) ? $id_trans1 : $id_trans;?>">

                                    <input hidden type="text" class="form-control" name="id_personil_mess" id="id_personil_mess" value="<?php echo(isset($id_personil_mess1)) ? $id_personil_mess1 : $id_personil_mess;?>">

                                    <input hidden type="text" class="form-control" name="atas_nama" id="atas_nama" value="<?php echo(isset($atas_nama1)) ? $atas_nama1 : $atas_nama;?>">

                                    <input hidden type="text" class="form-control" name="tanggal_mess" id="tanggal_mess" value="<?php echo(isset($tanggal_mess1)) ? $tanggal_mess1 : $tanggal_mess;?>">

                                    <!-- <div class="mb-1 mt-3" style="font-size:24px;">
                                        Tersedia untuk:
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <select class="select_sisa_kamar" id="sisa_kamar" name="sisa_kamar" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                <option value="pilih">-- pilih kamar terlebih dahulu --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <script>
                                        $(".select_kamar").change(function() {
                                            var val = $(this).val();

                                            <php foreach ($kamar_mess as $km => $kamess) {?>
                                                if (val == "<php echo $kamess['nama_kamar'] ?>") {
                                                    $(".select_sisa_kamar").html(
                                                        "<option><php echo $kamess['kapasitas_kamar'] - $kamess['terpakai']?><php echo " orang" ?></option>"
                                                    );
                                                } else if (val == "pilih") {
                                                    $(".select_sisa_kamar").html(
                                                        "<option value='pilih'>-- pilih kamar terlebih dahulu --</option>"
                                                    );
                                                }
                                            <php } ?>
                                        });
                                    </script> -->
                                    
                                    <!-- <script>
                                        $(document).keypress(function(event){
                                            if (event.which == '13') {
                                            event.preventDefault();
                                            }
                                        });
                                    </script> -->
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success btn-lg" type="submit" name="save" style="font-size:120%">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php foreach ($list_kamar as $li => $lis) {
        $id_personil_mess = $lis['id_personil_mess']; 
        $id_trans = $lis['id_trans'];
        $id_akomodasi = $lis['id_akomodasi'];
        $atas_nama = $lis['atas_nama'];
        $tanggal_mess = $lis['tanggal_mess'];
    ?>
        <div class="modal" id="edit_mess<?php echo $id_akomodasi; echo $id_trans; echo $id_personil_mess; ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="modalTopTitle">Pindah Kamar Mess</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="tab-content">
                                <div class="tab-pane active show" style="font-size:120%;">
                                    <div class="mb-1" style="font-size:24px;">
                                        Kamar Mess
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <select class="select_kamar" id="pindah_mess" name="pindah_mess" style="width: 100%; padding-left: 3.5px; font-size: 25px;">
                                                <option value="pilih">-- pilih salah satu --</option>
                                                <option value="1">Kamar 1</option>
                                                <option value="2">Kamar 2</option>
                                                <option value="3">Kamar 3</option>
                                                <option value="4">Kamar 4</option>
                                                <option value="5">Kamar 5</option>
                                                <option value="6">Kamar 6</option>
                                                <option value="7">Kamar 7</option>
                                            </select>
                                        </div>
                                    </div>

                                    <input hidden type="text" class="form-control" name="id_ako" id="id_ako" value="<?php echo(isset($id_akomodasi1)) ? $id_akomodasi1 : $id_akomodasi;?>">

                                    <input hidden type="text" class="form-control" name="id_tra" id="id_tra" value="<?php echo(isset($id_trans1)) ? $id_trans1 : $id_trans;?>">

                                    <input hidden type="text" class="form-control" name="id_personil_mess" id="id_personil_mess" value="<?php echo(isset($id_personil_mess1)) ? $id_personil_mess1 : $id_personil_mess;?>">

                                    <input hidden type="text" class="form-control" name="atas_nama" id="atas_nama" value="<?php echo(isset($atas_nama1)) ? $atas_nama1 : $atas_nama;?>">

                                    <input hidden type="text" class="form-control" name="tanggal_mess" id="tanggal_mess" value="<?php echo(isset($tanggal_mess1)) ? $tanggal_mess1 : $tanggal_mess;?>">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success btn-lg" type="submit" name="save" style="font-size:120%">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>

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