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
                                <h1>Alasan Pembatalan Tiket</h1>
                                <div style="font-size: 24px">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <textarea class="form-control" name="alasan_batal" rows="3" placeholder=""></textarea>

                                        <?php if (session()->get('admin_gs') == 0) { ?>
                                            <a class="btn btn-secondary mt-3" type="button" href="<?php echo site_url("trans"); ?>" style="font-size:100%">Batalkan</a>
                                        <?php } else if (session()->get('admin_gs') == 1) { ?>
                                            <a class="btn btn-secondary mt-3" type="button" href="<?php echo site_url("tiket_admin"); ?>" style="font-size:100%">Batalkan</a>
                                        <?php } ?>
                                        
                                        <button class="btn btn-success mt-3" type="submit" name="save" style="font-size:100%">Submit</button>
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