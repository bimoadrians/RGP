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
            <div class="col-lg-5 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <h1>Laporan Evaluasi Tiket</h1>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div style="font-size:150%">
                                        <div class="mb-1">
                                            Pool
                                        </div>
                                        <select class="select_pool" name="pool" style="width: 100%;"></select>
                                        <script>
                                        $(document).ready(function() {
                                            var pool = [
                                                //     <php foreach ($bag as $bag) : ?> "<php echo $bag['strorgnm']?>",
                                                //     <php endforeach ?>
                                                'Pool Solo', 'Pool Jakarta', 'Pool MNJ Pulo Gadung'
                                            ]

                                            $(".select_pool").select2({
                                                data: pool,
                                                // tags: true,
                                                // tokenSeparators: [',', ' '],
                                            });
                                        });
                                        </script>

                                        <div class="mb-1 mt-3">
                                            Tanggal Awal
                                        </div>
                                        <div class="mb-3">
                                            <input autocomplete="off" type="date" class="form-control" name="tanggal_awal"
                                                id="tanggal_awal" style="width: 100%;">
                                        </div>

                                        <div class="mb-1">
                                            Tanggal Akhir
                                        </div>
                                        <div class="mb-3">
                                            <input autocomplete="off" type="date" class="form-control" name="tanggal_akhir"
                                                id="tanggal_akhir" style="width: 100%;">
                                        </div>

                                        <button class="btn btn-success" type="submit" name="save"
                                            style="font-size:120%">Submit</button>
                                    </div>
                                </form>
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