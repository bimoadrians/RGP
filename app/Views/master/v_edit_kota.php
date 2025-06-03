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
                                <h1>Edit Data Kota</h1>
                                <div style="font-size: 24px">
                                    <?php foreach ($kota as $k => $kot) {
                                        $id_kota = $kot['id_kota'];
                                        $kota = site_url("kota/$id_negara");
                                    ?>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="mb-1">
                                                Kota
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" style="font-size:100%" type="text" class="form-control" name="nama_kota" id="nama_kota" value="<?php echo(isset($kota1)) ? $kota1 : $kot['nama_kota'];?>">
                                            </div>

                                            <div class="mb-1">
                                                Pool
                                            </div>
                                            <div class="mb-3">
                                                <select class="select_pool" name="nama_pool" style="width: 100%;">
                                                    <?php foreach ($pool_kota as $p => $po) { ?>
                                                        <?php if ($po['id_kota'] == $id_kota) { ?>
                                                            <option>
                                                                <?php echo(isset($kota0)) ? $kota0 : $po['nama_pool']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>
                                                <script>
                                                    $(document).ready(function() {
                                                        var pool = [
                                                            <?php foreach ($pool as $p) : ?>"<?php echo $p['nama_pool']?>",<?php endforeach ?>
                                                        ]

                                                        $(".select_pool").select2({
                                                            data: pool,
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
                                                    $(document).keypress(function(event){
                                                        if (event.which == '13') {
                                                        event.preventDefault();
                                                        }
                                                    });
                                                </script>
                                            </div>

                                            <a class="btn btn-secondary" type="button" href="<?php echo $kota?>" style="font-size:100%">Batalkan</a>
                                            <button class="btn btn-success" type="submit" name="save" style="font-size:100%">Submit</button>
                                        </form>
                                    <?php } ?>
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