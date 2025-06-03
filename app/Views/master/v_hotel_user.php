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
                                <h1>Daftar Hotel</h1>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    No</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Hotel</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Kota</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Alamat</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Telp</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Email</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Bintang</th>
                                                <th class="text-center" style="color: #5a5c69; vertical-align: middle;">
                                                    Master Kamar Hotel</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($hotel as $h => $ho) {
                                                $id_hotel = $ho['id_hotel'];
                                                $detail_hotel = site_url("detail_hotel_user/$id_hotel");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++ ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ho['nama_hotel'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ho['nama_kota'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ho['alamat_hotel'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ho['telp_hotel'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ho['email_hotel'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $ho['bintang_hotel'] ?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><a
                                                            class="btn btn-success btn-lg mb-3" style="font-size:80%;"
                                                            href="<?php echo $detail_hotel ?>">Lihat master kamar hotel</a></td>
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