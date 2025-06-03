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
                                <h1>Detail Hotel</h1>
                                <?php $hotel = site_url("hotel_user");?>
                                <a class="btn btn-secondary mb-3" href="<?php echo $hotel?>" style="font-size:120%;">Kembali</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69;">No</th>
                                                <th class="text-center" style="color: #5a5c69;">Type</th>
                                                <th class="text-center" style="color: #5a5c69;">Harga</th>
                                                <th class="text-center" style="color: #5a5c69;">Tanggal Valid</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($detail_hotel as $d => $det) {
                                                $id_detail_hotel = $det['id_detail_hotel'];
                                                $edit_detail_hotel = site_url("edit_detail_hotel/$id_hotel/$id_detail_hotel");
                                                $hapus = site_url("detail_hotel/$id_hotel/?aksi=hapus&id_detail_hotel=$id_detail_hotel");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++;?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $det['jenis_kamar']?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo "Rp"; echo number_format($det['price_kamar'], 2, ',', '.');?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo tanggal_indo_kendaraan($det['tgl_valid'])?></td>
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