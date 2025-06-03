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
                                <h1>Edit Data Vendor</h1>
                                <div style="font-size: 24px">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <?php foreach ($vendor as $v => $ven) {
                                            $id_vendor = $ven['id_vendor'];
                                            $vendor = site_url("vendo");
                                        ?>
                                            <div class="mb-1">
                                                Jenis
                                            </div>
                                            <select class="select_jenis_vendor" name="jenis_vendor" style="width: 100%;">
                                                <option>
                                                    <?php
                                                        if($ven['jenis_vendor'] == 'B'){
                                                            $jenis_vendor = 'Bus';
                                                        } else if($ven['jenis_vendor'] == 'K'){
                                                            $jenis_vendor = 'Kereta Api';
                                                        } else if($ven['jenis_vendor'] == 'P'){
                                                            $jenis_vendor = 'Pesawat';
                                                        } else if($ven['jenis_vendor'] == 'T'){
                                                            $jenis_vendor = 'Travel';
                                                        } else if($ven['jenis_vendor'] == 'Ka'){
                                                            $jenis_vendor = 'Kapal Laut';
                                                        }
                                                        echo(isset($vendor2)) ? $vendor2 : $jenis_vendor
                                                    ?>
                                                </option>
                                            </select>
                                            <script>
                                            $(document).ready(function() {
                                                var jenis = [
                                                    //     <php foreach ($bag as $bag) : ?> "<php echo $bag['strorgnm']?>",
                                                    //     <php endforeach ?>
                                                    'Pesawat', 'Kereta Api', 'Bus', 'Travel', 'Kapal Laut'
                                                ]

                                                $(".select_jenis_vendor").select2({
                                                    data: jenis,
                                                    // tags: true,
                                                    // tokenSeparators: [',', ' '],
                                                });
                                            });
                                            </script>

                                            <div class="mt-3 mb-1">
                                                Vendor
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="nama_vendor" id="nama_vendor" value="<?php echo(isset($vendor1)) ? $vendor1 : $ven['nama_vendor'];?>">
                                            </div>
                                            <script>
                                                $(document).keypress(function(event){
                                                    if (event.which == '13') {
                                                    event.preventDefault();
                                                    }
                                                });
                                            </script>
                                            <a class="btn btn-secondary" type="button" href="<?php echo $vendor?>" style="font-size:100%">Batalkan</a>
                                            <button class="btn btn-success" type="submit" name="save" style="font-size:100%">Submit</button>
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