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
                                <h1>Transaksi</h1>
                                <a class="btn btn-primary mb-3" href="trans_add" style="font-size:120%;"><i class="fa-solid fa-plus"></i> Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69;">No</th>
                                                <th class="text-center" style="color: #5a5c69;">Id</th>
                                                <th class="text-center" style="color: #5a5c69;">Tanggal Transaksi
                                                </th>
                                                <th class="text-center" style="color: #5a5c69;">Tiket
                                                </th>
                                                <th class="text-center" style="color: #5a5c69;">Hotel
                                                </th>
                                                <th class="text-center" style="color: #5a5c69;">Transport
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($trans as $t => $tra) {
                                                $id_trans = $tra['id_trans'];
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $tra['id_trans'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo tanggal_transaksi_kendaraan($tra['created_at']) ?></td>
                                                    <td class="text-left" style="color: #5a5c69;">
                                                            <?php foreach ($tiket as $ti => $tik) {
                                                                $id_tiket = $tik['id_tiket'];
                                                                $batal_tiket = site_url("trans/?aksi=permintaan_batal_tiket&id_trans=$id_trans&id_tiket=$id_tiket");
                                                            ?>
                                                                <?php if ($tik['id_trans'] == $tra['id_trans']) { ?>
<abbr title="PIC : <?php echo $tik['pic'] ?>
                                                                        
Pool : <?php echo $tik['nama_pool'] ?>

Tiket : <?php echo $tik['nama_vendor'] ?>
<?php foreach ($pemberhentian as $pem => $ber) { ?>
<?php if ($tik['id_keberangkatan'] == $ber['id_pemberhentian']) { ?>

Keberangkatan : <?php echo $ber['nama_pemberhentian'] ?>

Pemberhentian : <?php echo $tik['nama_pemberhentian'] ?>
<?php } ?>
<?php } ?>

Dari/Tujuan : <?php echo $tik['dari_tiket'] ?><?php echo "/" ?><?php echo $tik['tujuan_tiket'] ?>

Jumlah Tiket : <?php echo $tik['jumlah_tiket'] ?>

Harga Tiket : <?php echo "Rp"; echo number_format($tik['harga_tiket'], 2, ',', '.'); ?>

Atas Nama : <?php echo $tik['atas_nama'] ?>

Jabatan : <?php echo $tik['jabatan'] ?>

Tanggal Tiket : <?php echo tanggal_jam_transaksi_kendaraan($tik['tanggal_jam_tiket']) ?>
<?php 
    if($tik['pembayaran'] == 'k'){
        $pembayaran = 'Kantor';
    } else if($tik['pembayaran'] == 'p'){
        $pembayaran = 'Personal';
    }
?>

Pembayaran : <?php echo $pembayaran ?>
<?php 
    if($tik['keterangan_tiket'] == null){
        $keterangan_tiket = '-';
    } else if($tik['keterangan_tiket'] != null){
        $keterangan_tiket = $tik['keterangan_tiket'];
    }
?>

Keterangan : <?php echo $keterangan_tiket ?>
<?php 
    if($tik['status_tiket'] == '0' && $tik['batal_tiket'] == '0'){
        $status_tiket = 'Belum Disetujui GS';
    } else if($tik['status_tiket'] == '0' && $tik['batal_tiket'] == '1'){
        $status_tiket = 'Belum Disetujui GS';
    } else if($tik['status_tiket'] == '0' && $tik['batal_tiket'] == '2'){
        $status_tiket = 'Transaksi dibatalkan';
    } else if($tik['status_tiket'] == '0' && $tik['batal_tiket'] == '3'){
        $status_tiket = 'Transaksi dibatalkan';
    } else if($tik['status_tiket'] == '0' && $tik['batal_tiket'] == '4'){
        $status_tiket = 'Transaksi dibatalkan';
    } else if($tik['status_tiket'] == '1' && $tik['batal_tiket'] == '0'){
        $status_tiket = 'Disetujui GS';
    } else if($tik['status_tiket'] == '1' && $tik['batal_tiket'] == '1'){
        $status_tiket = 'Disetujui GS';
    } else if($tik['status_tiket'] == '1' && $tik['batal_tiket'] == '2'){
        $status_tiket = 'Transaksi dibatalkan';
    } else if($tik['status_tiket'] == '1' && $tik['batal_tiket'] == '3'){
        $status_tiket = 'Transaksi dibatalkan';
    } else if($tik['status_tiket'] == '1' && $tik['batal_tiket'] == '4'){
        $status_tiket = 'Transaksi dibatalkan';
    }
?>

Status Tiket : <?php echo $status_tiket ?>
<?php if($tik['batal_tiket'] == '1'){ ?>

Permintaan Batal Tiket : Belum Disetujui GS
<?php } else if($tik['batal_tiket'] > '1'){ ?>

<?php } else { ?>

<?php } ?>" style="text-decoration:none;-webkit-text-decoration:none;text-decoration:none;cursor:pointer;border-bottom:0;-webkit-text-decoration-skip-ink:none;text-decoration-skip-ink:none">
                                                                            PIC : <?php echo $tik['pic'] ?><br>
                                                                            Pool : <?php echo $tik['nama_pool'] ?><br>
                                                                            Tiket : <?php echo $tik['nama_vendor'] ?><br>
                                                                            <?php foreach ($pemberhentian as $pem => $ber) { ?>
                                                                                <?php if ($tik['id_keberangkatan'] == $ber['id_pemberhentian']) { ?>
                                                                                    Keberangkatan : <?php echo $ber['nama_pemberhentian'] ?><br>

                                                                                    Pemberhentian : <?php echo $tik['nama_pemberhentian'] ?><br>
                                                                                <?php } ?>
                                                                            <?php } ?>
                                                                            Dari/Tujuan : <?php echo $tik['dari_tiket'] ?><?php echo "/" ?><?php echo $tik['tujuan_tiket'] ?><br>
                                                                            Jumlah Tiket : <?php echo $tik['jumlah_tiket'] ?><br>
                                                                            Atas Nama : <?php echo $tik['atas_nama'] ?><br>
                                                                            Jabatan : <?php echo $tik['jabatan'] ?><br>
                                                                            Tanggal Tiket : <?php echo tanggal_jam_transaksi_kendaraan($tik['tanggal_jam_tiket']) ?><br>
                                                                            Harga Tiket : <?php echo "Rp"; echo number_format($tik['harga_tiket'], 2, ',', '.'); ?><br>
                                                                            <?php 
                                                                                if($tik['pembayaran'] == 'k'){
                                                                                    $pembayaran = 'Kantor';
                                                                                } else if($tik['pembayaran'] == 'p'){
                                                                                    $pembayaran = 'Personal';
                                                                                }
                                                                            ?>
                                                                            Pembayaran : <?php echo $pembayaran ?><br>
                                                                            <?php 
                                                                                if($tik['keterangan_tiket'] == null){
                                                                                    $keterangan_tiket = '-';
                                                                                } else if($tik['keterangan_tiket'] != null){
                                                                                    $keterangan_tiket = $tik['keterangan_tiket'];
                                                                                }
                                                                            ?>
                                                                            Keterangan : <?php echo $keterangan_tiket ?><br>
                                                                            <?php 
                                                                                if($tik['status_tiket'] == '0' && $tik['batal_tiket'] == '0'){
                                                                                    $status_tiket = 'Belum Disetujui GS';
                                                                                } else if($tik['status_tiket'] == '0' && $tik['batal_tiket'] == '1'){
                                                                                    $status_tiket = 'Belum Disetujui GS';
                                                                                } else if($tik['status_tiket'] == '0' && $tik['batal_tiket'] == '2'){
                                                                                    $status_tiket = 'Transaksi dibatalkan';
                                                                                } else if($tik['status_tiket'] == '0' && $tik['batal_tiket'] == '3'){
                                                                                    $status_tiket = 'Transaksi dibatalkan';
                                                                                } else if($tik['status_tiket'] == '0' && $tik['batal_tiket'] == '4'){
                                                                                    $status_tiket = 'Transaksi dibatalkan';
                                                                                } else if($tik['status_tiket'] == '1' && $tik['batal_tiket'] == '0'){
                                                                                    $status_tiket = 'Disetujui GS';
                                                                                } else if($tik['status_tiket'] == '1' && $tik['batal_tiket'] == '1'){
                                                                                    $status_tiket = 'Disetujui GS';
                                                                                } else if($tik['status_tiket'] == '1' && $tik['batal_tiket'] == '2'){
                                                                                    $status_tiket = 'Transaksi dibatalkan';
                                                                                } else if($tik['status_tiket'] == '1' && $tik['batal_tiket'] == '3'){
                                                                                    $status_tiket = 'Transaksi dibatalkan';
                                                                                } else if($tik['status_tiket'] == '1' && $tik['batal_tiket'] == '4'){
                                                                                    $status_tiket = 'Transaksi dibatalkan';
                                                                                }
                                                                            ?>
                                                                            Status Tiket : <?php echo $status_tiket ?><br>
                                                                            <?php if($tik['batal_tiket'] == '1'){ ?>
                                                                            Permintaan Batal Tiket : Belum Disetujui GS<br>
                                                                            <?php } else if($tik['batal_tiket'] > '1'){ ?>
                                                                                
                                                                            <?php } else { ?>

                                                                            <?php } ?>
                                                                        </abbr>
                                                                        <?php if($tik['batal_tiket'] == '0'){ ?>
                                                                            <a class="btn btn-danger btn-lg mt-3 mb-3" href="<?php echo $batal_tiket?>" onclick="return confirm('Yakin akan mengajukan permintaan batal untuk transaksi ini?')" style="font-size:80%;">Ajukan Permintaan Batal</a>
                                                                        <?php } else { ?>
                                                                    
                                                                        <?php } ?>
                                                                        
                                                                    <?php } else { ?>
                                                                        
                                                                    <?php } ?>
                                                                <?php } ?>
                                                        </td>
                                                        <td class="text-left" style="color: #5a5c69;">
                                                            <?php foreach ($akomodasi as $ak => $ako) {
                                                                $id_akomodasi = $ako['id_akomodasi'];
                                                                $batal_akomodasi = site_url("trans/?aksi=permintaan_batal_akomodasi&id_trans=$id_trans&id_akomodasi=$id_akomodasi");
                                                            ?>
                                                                <?php if ($ako['id_trans'] == $tra['id_trans']) { ?>
<abbr title="PIC : <?php echo $ako['pic'] ?>

Pool : <?php echo $ako['nama_pool'] ?>

Hotel : <?php echo $ako['nama_hotel'] ?>

Kamar : <?php echo $ako['jenis_kamar'] ?>

Kota : <?php echo $ako['nama_kota'] ?>

Periode : <?php echo tanggal_jam_transaksi_kendaraan($ako['tanggal_jam_masuk']) ?><?php echo " - " ?><?php echo tanggal_jam_transaksi_kendaraan($ako['tanggal_jam_keluar']) ?>

Jumlah kamar : <?php echo $ako['jumlah_kamar'] ?>

Harga Akomodasi : <?php echo "Rp"; echo number_format($ako['harga_akomodasi'], 2, ',', '.'); ?>

Atas Nama : <?php echo $ako['atas_nama'] ?>

Jabatan : <?php echo $ako['jabatan'] ?>

<?php 
    if($ako['pembayaran'] == 'k'){
        $pembayaran = 'Kantor';
    } else if($ako['pembayaran'] == 'p'){
        $pembayaran = 'Personal';
    }
?>
Pembayaran : <?php echo $pembayaran ?>

<?php 
    if($ako['keterangan_akomodasi'] == null){
        $keterangan_akomodasi = '-';
    } else if($ako['keterangan_akomodasi'] != null){
        $keterangan_akomodasi = $ako['keterangan_akomodasi'];
    }
?>
Keterangan : <?php echo $keterangan_akomodasi ?>

<?php if ($ako['nama_hotel'] == 'Mess Kx Jkt') { ?>
<?php
    if($ako['status_mess'] == '0' && $ako['batal_akomodasi'] == '0'){
        $status_mess = 'Sudah Checkout';
    } else if($ako['status_mess'] == '0' && $ako['batal_akomodasi'] == '1'){
        $status_mess = 'Sudah Checkout';
    } else if($ako['status_mess'] == '0' && $ako['batal_akomodasi'] == '2'){
        $status_mess = 'Transaksi dibatalkan';
    } else if($ako['status_mess'] == '0' && $ako['batal_akomodasi'] == '3'){
        $status_mess = 'Transaksi dibatalkan';
    } else if($ako['status_mess'] == '0' && $ako['batal_akomodasi'] == '4'){
        $status_mess = 'Transaksi dibatalkan';
    } else if($ako['status_mess'] == '1' && $ako['batal_akomodasi'] == '0'){
        $status_mess = 'Sudah Booking';
    } else if($ako['status_mess'] == '1' && $ako['batal_akomodasi'] == '1'){
        $status_mess = 'Sudah Booking';
    } else if($ako['status_mess'] == '1' && $ako['batal_akomodasi'] == '2'){
        $status_mess = 'Transaksi dibatalkan';
    } else if($ako['status_mess'] == '1' && $ako['batal_akomodasi'] == '3'){
        $status_mess = 'Transaksi dibatalkan';
    } else if($ako['status_mess'] == '1' && $ako['batal_akomodasi'] == '4'){
        $status_mess = 'Transaksi dibatalkan';
    }

    if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '0'){
        $status_akomodasi = 'Belum Disetujui GS';
    } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '1'){
        $status_akomodasi = 'Belum Disetujui GS';
    } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '2'){
        $status_akomodasi = 'Transaksi dibatalkan';
    } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '3'){
        $status_akomodasi = 'Transaksi dibatalkan';
    } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '4'){
        $status_akomodasi = 'Transaksi dibatalkan';
    } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '0'){
        $status_akomodasi = 'Disetujui GS';
    } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '1'){
        $status_akomodasi = 'Disetujui GS';
    } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '2'){
        $status_akomodasi = 'Transaksi dibatalkan';
    } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '3'){
        $status_akomodasi = 'Transaksi dibatalkan';
    } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '4'){
        $status_akomodasi = 'Transaksi dibatalkan';
    }
?>
<?php if ($status_mess == 'Transaksi dibatalkan') { ?>
Status Mess : <?php echo $status_mess ?>
<?php } else { ?>
Status Mess : <?php echo $status_mess ?>, <?php echo $status_akomodasi ?>
<?php } ?>
<?php } else { ?>
<?php
    if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '0'){
        $status_akomodasi = 'Belum Disetujui GS';
    } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '1'){
        $status_akomodasi = 'Belum Disetujui GS';
    } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '2'){
        $status_akomodasi = 'Transaksi dibatalkan';
    } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '3'){
        $status_akomodasi = 'Transaksi dibatalkan';
    } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '4'){
        $status_akomodasi = 'Transaksi dibatalkan';
    } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '0'){
        $status_akomodasi = 'Disetujui GS';
    } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '1'){
        $status_akomodasi = 'Disetujui GS';
    } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '2'){
        $status_akomodasi = 'Transaksi dibatalkan';
    } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '3'){
        $status_akomodasi = 'Transaksi dibatalkan';
    } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '4'){
        $status_akomodasi = 'Transaksi dibatalkan';
    }
?>
Status Hotel : <?php echo $status_akomodasi ?>
<?php } ?>
<?php if($ako['batal_akomodasi'] == '1'){ ?>

Permintaan Batal Akomodasi : Belum Disetujui GS
<?php } else if($ako['batal_akomodasi'] > '1'){ ?>

<?php } else { ?>

<?php } ?>" style="text-decoration:none;-webkit-text-decoration:none;text-decoration:none;cursor:pointer;border-bottom:0;-webkit-text-decoration-skip-ink:none;text-decoration-skip-ink:none">
                                                                    PIC : <?php echo $ako['pic'] ?><br>
                                                                    Pool : <?php echo $ako['nama_pool'] ?><br>
                                                                    Hotel : <?php echo $ako['nama_hotel'] ?><br>
                                                                    Kamar : <?php echo $ako['jenis_kamar'] ?><br>
                                                                    Kota : <?php echo $ako['nama_kota'] ?><br>
                                                                    Periode : <?php echo tanggal_jam_transaksi_kendaraan($ako['tanggal_jam_masuk']) ?><?php echo " - " ?><?php echo tanggal_jam_transaksi_kendaraan($ako['tanggal_jam_keluar']) ?><br>
                                                                    Jumlah kamar : <?php echo $ako['jumlah_kamar'] ?><br>
                                                                    Harga Akomodasi : <?php echo "Rp"; echo number_format($ako['harga_akomodasi'], 2, ',', '.'); ?><br>
                                                                    Atas Nama : <?php echo $ako['atas_nama'] ?><br>
                                                                    Jabatan : <?php echo $ako['jabatan'] ?><br>
                                                                    <?php 
                                                                        if($ako['pembayaran'] == 'k'){
                                                                            $pembayaran = 'Kantor';
                                                                        } else if($ako['pembayaran'] == 'p'){
                                                                            $pembayaran = 'Personal';
                                                                        }
                                                                    ?>
                                                                    Pembayaran : <?php echo $pembayaran ?><br>
                                                                    <?php 
                                                                        if($ako['keterangan_akomodasi'] == null){
                                                                            $keterangan_akomodasi = '-';
                                                                        } else if($ako['keterangan_akomodasi'] != null){
                                                                            $keterangan_akomodasi = $ako['keterangan_akomodasi'];
                                                                        }
                                                                    ?>
                                                                    Keterangan : <?php echo $keterangan_akomodasi ?><br>
                                                                    <?php if ($ako['nama_hotel'] == 'Mess Kx Jkt') { ?>
                                                                        <?php
                                                                            if($ako['status_mess'] == '0' && $ako['batal_akomodasi'] == '0'){
                                                                                $status_mess = 'Sudah Checkout';
                                                                            } else if($ako['status_mess'] == '0' && $ako['batal_akomodasi'] == '1'){
                                                                                $status_mess = 'Sudah Checkout';
                                                                            } else if($ako['status_mess'] == '0' && $ako['batal_akomodasi'] == '2'){
                                                                                $status_mess = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_mess'] == '0' && $ako['batal_akomodasi'] == '3'){
                                                                                $status_mess = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_mess'] == '0' && $ako['batal_akomodasi'] == '4'){
                                                                                $status_mess = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_mess'] == '1' && $ako['batal_akomodasi'] == '0'){
                                                                                $status_mess = 'Sudah Booking';
                                                                            } else if($ako['status_mess'] == '1' && $ako['batal_akomodasi'] == '1'){
                                                                                $status_mess = 'Sudah Booking';
                                                                            } else if($ako['status_mess'] == '1' && $ako['batal_akomodasi'] == '2'){
                                                                                $status_mess = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_mess'] == '1' && $ako['batal_akomodasi'] == '3'){
                                                                                $status_mess = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_mess'] == '1' && $ako['batal_akomodasi'] == '4'){
                                                                                $status_mess = 'Transaksi dibatalkan';
                                                                            }

                                                                            if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '0'){
                                                                                $status_akomodasi = 'Belum Disetujui GS';
                                                                            } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '1'){
                                                                                $status_akomodasi = 'Belum Disetujui GS';
                                                                            } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '2'){
                                                                                $status_akomodasi = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '3'){
                                                                                $status_akomodasi = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '4'){
                                                                                $status_akomodasi = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '0'){
                                                                                $status_akomodasi = 'Disetujui GS';
                                                                            } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '1'){
                                                                                $status_akomodasi = 'Disetujui GS';
                                                                            } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '2'){
                                                                                $status_akomodasi = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '3'){
                                                                                $status_akomodasi = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '4'){
                                                                                $status_akomodasi = 'Transaksi dibatalkan';
                                                                            }
                                                                        ?>
                                                                        <?php if ($status_mess == 'Transaksi dibatalkan') { ?>
                                                                        Status Mess : <?php echo $status_mess ?><br>
                                                                        <?php } else { ?>
                                                                        Status Mess : <?php echo $status_mess ?>, <?php echo $status_akomodasi ?><br>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <?php
                                                                            if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '0'){
                                                                                $status_akomodasi = 'Belum Disetujui GS';
                                                                            } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '1'){
                                                                                $status_akomodasi = 'Belum Disetujui GS';
                                                                            } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '2'){
                                                                                $status_akomodasi = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '3'){
                                                                                $status_akomodasi = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_akomodasi'] == '0' && $ako['batal_akomodasi'] == '4'){
                                                                                $status_akomodasi = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '0'){
                                                                                $status_akomodasi = 'Disetujui GS';
                                                                            } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '1'){
                                                                                $status_akomodasi = 'Disetujui GS';
                                                                            } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '2'){
                                                                                $status_akomodasi = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '3'){
                                                                                $status_akomodasi = 'Transaksi dibatalkan';
                                                                            } else if($ako['status_akomodasi'] == '1' && $ako['batal_akomodasi'] == '4'){
                                                                                $status_akomodasi = 'Transaksi dibatalkan';
                                                                            }
                                                                        ?>
                                                                        Status Hotel : <?php echo $status_akomodasi ?><br>
                                                                    <?php } ?>
                                                                    <?php if($ako['batal_akomodasi'] == '1'){ ?>
                                                                        Permintaan Batal Akomodasi : Belum Disetujui GS <br>
                                                                    <?php } else if($ako['batal_akomodasi'] > '1'){ ?>
                                                                        
                                                                    <?php } else { ?>

                                                                    <?php } ?>
                                                                    </abbr>
                                                                    <?php if($ako['batal_akomodasi'] == '0'){ ?>
                                                                        <a class="btn btn-danger btn-lg mt-3 mb-3" href="<?php echo $batal_akomodasi?>" onclick="return confirm('Yakin akan mengajukan permintaan batal untuk transaksi ini?')" style="font-size:80%;">Ajukan Permintaan Batal</a>
                                                                    <?php } else { ?>
                                                                
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-left" style="color: #5a5c69;">
                                                        <?php foreach ($transportasi as $tr => $transpo) {
                                                            $id_transportasi = $transpo['id_transportasi'];
                                                            $batal_transportasi_antar = site_url("trans/?aksi=permintaan_batal_transportasi_antar&id_trans=$id_trans&id_transportasi=$id_transportasi");
                                                        ?>
                                                                <?php if ($transpo['id_trans'] == $tra['id_trans']) { ?>
<abbr title="PIC : <?php echo $transpo['pic'] ?>

Pool : <?php echo $transpo['nama_pool'] ?>

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
Jenis Mobil : <?php echo $jenis_kendaraan ?>

<?php 
    if($transpo['dalkot_lukot'] == 'd'){
        $dalkot_lukot = 'Dalam Kota';
    } else if($transpo['dalkot_lukot'] == 'l'){
        $dalkot_lukot = 'Luar Kota';
    }
?>
Dalam/Luar Kota : <?php echo $dalkot_lukot ?>

<?php 
    if($transpo['menginap'] == '0'){
        $menginap = 'Tidak';
    } else if($transpo['menginap'] == '1'){
        $menginap = 'Iya';
    }
?>
Menginap : <?php echo $menginap ?>

Kapasitas : <?php echo $transpo['kapasitas'] ?>

Jumlah : <?php echo $transpo['jumlah_mobil'] ?><?php echo ' buah' ?>

Tanggal : <?php echo date_transaksi_kendaraan($transpo['tanggal_mobil']) ?>

Tujuan : <?php echo $transpo['tujuan_mobil'] ?>

Siap di : <?php echo $transpo['siap_di'] ?>

Jam : <?php echo $transpo['jam_siap'] ?>

Untuk : <?php echo $transpo['atas_nama'] ?>

Jabatan : <?php echo $transpo['jabatan'] ?>

<?php 
    if($transpo['keterangan_mobil'] == null){
        $keterangan_mobil = '-';
    } else if($transpo['keterangan_mobil'] != null){
        $keterangan_mobil = $transpo['keterangan_mobil'];
    }
?>
Keterangan : <?php echo $keterangan_mobil ?>

<?php 
    if($transpo['status_mobil'] == '0' && $transpo['batal_transportasi'] == '0'){
        $status_mobil = 'Belum Disetujui GS';
    } else if($transpo['status_mobil'] == '0' && $transpo['batal_transportasi'] == '1'){
        $status_mobil = 'Belum Disetujui GS';
    } else if($transpo['status_mobil'] == '0' && $transpo['batal_transportasi'] == '2'){
        $status_mobil = 'Transaksi dibatalkan';
    } else if($transpo['status_mobil'] == '1' && $transpo['batal_transportasi'] == '0'){
        $status_mobil = 'Disetujui GS';
    } else if($transpo['status_mobil'] == '1' && $transpo['batal_transportasi'] == '1'){
        $status_mobil = 'Disetujui GS';
    } else if($transpo['status_mobil'] == '1' && $transpo['batal_transportasi'] == '3'){
        $status_mobil = 'Transaksi dibatalkan';
    }
?>
Status : <?php echo $status_mobil ?>
<?php if($transpo['batal_transportasi'] == '1'){ ?>

Permintaan Batal Transport : Belum Disetujui GS
<?php } else if($transpo['batal_transportasi'] > '1'){ ?>

<?php } else { ?>

<?php } ?>" style="text-decoration:none;-webkit-text-decoration:none;text-decoration:none;cursor:pointer;border-bottom:0;-webkit-text-decoration-skip-ink:none;text-decoration-skip-ink:none">
                                                                
                                                                PIC : <?php echo $transpo['pic'] ?><br>
                                                                Pool : <?php echo $transpo['nama_pool'] ?><br>
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
                                                                Jenis Mobil : <?php echo $jenis_kendaraan ?><br>
                                                                <?php 
                                                                    if($transpo['tenaga_angkut'] == '1'){
                                                                        $tenaga_angkut = 'Iya';
                                                                    } else {
                                                                        $tenaga_angkut = 'Tidak';
                                                                    }
                                                                ?>
                                                                Dalam/Luar Kota : <?php echo $dalkot_lukot ?><br>
                                                                <?php 
                                                                    if($transpo['menginap'] == '0'){
                                                                        $menginap = 'Tidak';
                                                                    } else if($transpo['menginap'] == '1'){
                                                                        $menginap = 'Iya';
                                                                    }
                                                                ?>
                                                                Menginap : <?php echo $menginap ?><br>
                                                                Kapasitas : <?php echo $transpo['kapasitas'] ?><br>
                                                                Jumlah : <?php echo $transpo['jumlah_mobil'] ?><?php echo ' buah' ?><br>
                                                                Tanggal : <?php echo date_transaksi_kendaraan($transpo['tanggal_mobil']) ?><br>
                                                                Tujuan : <?php echo $transpo['tujuan_mobil'] ?><br>
                                                                Siap di : <?php echo $transpo['siap_di'] ?><br>
                                                                Jam : <?php echo $transpo['jam_siap'] ?><br>
                                                                Untuk : <?php echo $transpo['atas_nama'] ?><br>
                                                                Jabatan : <?php echo $transpo['jabatan'] ?><br>
                                                                <?php 
                                                                    if($transpo['keterangan_mobil'] == null){
                                                                        $keterangan_mobil = '-';
                                                                    } else if($transpo['keterangan_mobil'] != null){
                                                                        $keterangan_mobil = $transpo['keterangan_mobil'];
                                                                    }
                                                                ?>
                                                                Keterangan : <?php echo $keterangan_mobil ?><br>
                                                                <?php 
                                                                    if($transpo['status_mobil'] == '0' && $transpo['batal_transportasi'] == '0'){
                                                                        $status_mobil = 'Belum Disetujui GS';
                                                                    } else if($transpo['status_mobil'] == '0' && $transpo['batal_transportasi'] == '1'){
                                                                        $status_mobil = 'Belum Disetujui GS';
                                                                    } else if($transpo['status_mobil'] == '0' && $transpo['batal_transportasi'] == '2'){
                                                                        $status_mobil = 'Transaksi dibatalkan';
                                                                    } else if($transpo['status_mobil'] == '1' && $transpo['batal_transportasi'] == '0'){
                                                                        $status_mobil = 'Disetujui GS';
                                                                    } else if($transpo['status_mobil'] == '1' && $transpo['batal_transportasi'] == '1'){
                                                                        $status_mobil = 'Disetujui GS';
                                                                    } else if($transpo['status_mobil'] == '1' && $transpo['batal_transportasi'] == '3'){
                                                                        $status_mobil = 'Transaksi dibatalkan';
                                                                    }
                                                                ?>
                                                                Status : <?php echo $status_mobil ?><br>
                                                                <?php if($transpo['batal_transportasi'] == '1'){ ?>
                                                                    Permintaan Batal Transport : Belum Disetujui GS<br>
                                                                <?php } else if($transpo['batal_transportasi'] > '1'){ ?>

                                                                <?php } else { ?>

                                                                <?php } ?>
                                                                </abbr>
                                                                <?php if($transpo['batal_transportasi'] == '0'){ ?>
                                                                    <a class="btn btn-danger btn-lg mt-3 mb-3" href="<?php echo $batal_transportasi_antar?>" onclick="return confirm('Yakin akan mengajukan permintaan batal untuk transaksi ini?')" style="font-size:80%;">Ajukan Permintaan Batal</a>
                                                                <?php } else { ?>
                                                            
                                                                <?php } ?>
                                                            
                                                            <?php } else { ?>
                                                                
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
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