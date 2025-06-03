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
                                <h1>Edit Data Pemberhentian</h1>
                                <div style="font-size: 24px">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <?php foreach ($pemberhentian as $p => $pem) {
                                            $id_pemberhentian = $pem['id_pemberhentian'];
                                            $pemberhentian = site_url("vendo");
                                        ?>
                                            <div class="mb-1">
                                                Jenis
                                            </div>
                                            <select class="select_jenis_pemberhentian" name="jenis_pemberhentian" style="width: 100%;">
                                                <option>
                                                    <?php
                                                        if($pem['jenis_pemberhentian'] == 'B'){
                                                            $jenis_pemberhentian = 'Bandara';
                                                        } else if($pem['jenis_pemberhentian'] == 'P'){
                                                            $jenis_pemberhentian = 'Pelabuhan';
                                                        } else if($pem['jenis_pemberhentian'] == 'S'){
                                                            $jenis_pemberhentian = 'Stasiun';
                                                        } else if($pem['jenis_pemberhentian'] == 'T'){
                                                            $jenis_pemberhentian = 'Terminal';
                                                        }
                                                        echo(isset($vendor2)) ? $vendor2 : $jenis_pemberhentian
                                                    ?>
                                                </option>
                                            </select>
                                            <script>
                                                $(document).ready(function() {
                                                    var jenis = [
                                                        //     <php foreach ($bag as $bag) : ?> "<php echo $bag['strorgnm']?>",
                                                        //     <php endforeach ?>
                                                        'Bandara', 'Pelabuhan', 'Stasiun', 'Terminal'
                                                    ]

                                                    $(".select_jenis_pemberhentian").select2({
                                                        data: jenis,
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

                                            <div class="mt-3 mb-1">
                                                Pemberhentian
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" style="font-size:100%" type="text" class="form-control" name="nama_pemberhentian" id="nama_pemberhentian" value="<?php echo(isset($vendor1)) ? $vendor1 : $pem['nama_pemberhentian'];?>">
                                            </div>

                                            <div class="mb-1">
                                                Kota
                                            </div>
                                            <select class="select_nama_kota" name="nama_kota" style="width: 100%;">
                                                <option>
                                                    <?php echo(isset($vendor3)) ? $vendor3 : $pem['nama_kota']; ?>
                                                </option>
                                            </select>
                                            <script>
                                                $(document).ready(function() {
                                                    var jenis = [
                                                            <?php foreach ($kota as $kot) : ?> "<?php echo $kot['nama_kota']?>",
                                                            <?php endforeach ?>
                                                    ]

                                                    $(".select_nama_kota").select2({
                                                        data: jenis,
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
                                            <a class="btn btn-secondary mt-3" type="button" href="<?php echo $pemberhentian?>" style="font-size:100%">Batalkan</a>
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