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
                                <h1>Edit Data Negara</h1>
                                <div style="font-size: 24px">
                                    <div class="mb-1">
                                        Negara
                                    </div>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <?php foreach ($negara as $n => $neg) {
                                                $id_negara = $neg['id_negara'];
                                                $negara = site_url("negara");
                                            ?>
                                                <input required autocomplete="off" style="font-size:100%" type="text" class="form-control" name="negara" id="negara" value="<?php echo(isset($negara1)) ? $negara1 : $neg['nama_negara'];?>">
                                            <?php } ?>
                                        </div>
                                        <script>
                                            $(document).keypress(function(event){
                                                if (event.which == '13') {
                                                event.preventDefault();
                                                }
                                            });
                                        </script>
                                        <a class="btn btn-secondary" type="button" href="<?php echo $negara?>" style="font-size:100%">Batalkan</a>
                                        <button class="btn btn-success" type="submit" name="save" style="font-size:100%">Submit</button>
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