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
                                <h1>Edit Data Pool</h1>
                                <div style="font-size: 24px">
                                    <?php foreach ($pool as $p => $poo) {
                                        $id_pool = $poo['id_pool'];
                                        $pool = site_url("pool");
                                    ?>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="mb-1">
                                                Pool
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="nama_pool" id="nama_pool" value="<?php echo(isset($pool1)) ? $pool1 : $poo['nama_pool'];?>">
                                            </div>

                                            <div class="mb-1">
                                                No HP
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" min="0" type="number" class="form-control" name="no_hp_pool" id="no_hp_pool" value="<?php echo(isset($pool2)) ? $pool2 : $poo['no_hp_pool'];?>">
                                            </div>

                                            <div class="mb-1">
                                                Email
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="email_pool" id="email_pool" value="<?php echo(isset($pool3)) ? $pool3 : $poo['email_pool'];?>">
                                            </div>

                                            <script>
                                                $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                    if (event.which == '13') {
                                                        event.preventDefault();
                                                    }
                                                });
                                            </script>

                                            <a class="btn btn-secondary" type="button" href="<?php echo $pool?>" style="font-size:100%">Batalkan</a>
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