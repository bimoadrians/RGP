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
                                <h1>Edit Data hotel</h1>
                                <div style="font-size: 24px">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <?php foreach ($hotel as $h => $ho) {
                                            $id_hotel = $ho['id_hotel'];
                                            $hotel = site_url("hotel");
                                        ?>
                                            <div class="mb-1">
                                                Hotel
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="nama_hotel" id="hotel" value="<?php echo(isset($hotel1)) ? $hotel1 : $ho['nama_hotel'];?>">
                                            </div>

                                            <div class="mb-1">
                                                Kota
                                            </div>
                                            <select class="select_nama_kota" name="nama_kota" style="width: 100%;">
                                                <option><?php echo (isset($kota1)) ? $kota1 : $ho['nama_kota'] ?></option>
                                            </select>
                                            <script>
                                            $(document).ready(function() {
                                                var nama_kota = [
                                                        <?php foreach ($kota as $kot) : ?> "<?php echo $kot['nama_kota']?>",
                                                        <?php endforeach ?>
                                                ]

                                                $(".select_nama_kota").select2({
                                                    data: nama_kota,
                                                    // tags: true,
                                                    // tokenSeparators: [',', ' '],
                                                });

                                                $('select:not(.normal)').each(function() {
                                                    $(this).select2({
                                                        dropdownParent: $(this)
                                                            .parent()
                                                    });
                                                });
                                            });
                                            </script>

                                            <div class="mb-1 mt-3">
                                                Alamat<a style="color: #e74a3b">*</a>
                                            </div>
                                            <div class="mb-1">
                                                <textarea class="form-control" name="alamat_hotel" rows="3" placeholder=""><?php echo (isset($hotel2)) ? $hotel2 : $ho['alamat_hotel'] ?></textarea>
                                            </div>

                                            <div class="mb-1 mt-3">
                                                Telp
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="telp_hotel" id="telp_hotel" value="<?php echo(isset($hotel3)) ? $hotel3 : $ho['telp_hotel'];?>">
                                            </div>

                                            <div class="mb-1 mt-3">
                                                Email<a style="color: #e74a3b">*</a>
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="email_hotel" id="email_hotel" value="<?php echo(isset($hotel4)) ? $hotel4 : $ho['email_hotel'];?>">
                                            </div>

                                            <div class="mb-1">
                                                Bintang
                                            </div>
                                            <select class="select_bintang_hotel" name="bintang_hotel" style="width: 100%;">
                                                <option><?php echo (isset($hotel5)) ? $hotel5 : $ho['bintang_hotel'] ?></option>
                                            </select>
                                            <script>
                                            $(document).ready(function() {
                                                var bintang_hotel = [
                                                    //     <php foreach ($bag as $bag) : ?> "<php echo $bag['strorgnm']?>",
                                                    //     <php endforeach ?>
                                                    '5', '4', '3', '2', '1'
                                                ]

                                                $(".select_bintang_hotel").select2({
                                                    data: bintang_hotel,
                                                    // tags: true,
                                                    // tokenSeparators: [',', ' '],
                                                });

                                                $('select:not(.normal)').each(function() {
                                                    $(this).select2({
                                                        dropdownParent: $(this)
                                                            .parent()
                                                    });
                                                });
                                            });
                                            </script>
                                            <script>
                                                $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                    if (event.which == '13') {
                                                        event.preventDefault();
                                                    }
                                                });
                                            </script>

                                            <div class="col-xl-12 col-lg-12 mt-3">
                                                <a style="color: #e74a3b">*Silahkan beri tanda "-" jika tidak ada informasi</a>
                                            </div>

                                            <a class="btn btn-secondary mt-3" type="button" href="<?php echo $hotel?>" style="font-size:100%">Batalkan</a>
                                            <button class="btn btn-success mt-3" type="submit" name="save" style="font-size:100%">Submit</button>
                                        <?php } ?>
                                    </form>
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