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
                                <h1>Edit Data Persetujuan</h1>
                                <div style="font-size: 24px">
                                    <?php foreach ($persetujuan as $p => $perse) {
                                        $id_persetujuan = $perse['id_persetujuan'];
                                        $dept = site_url("dept");
                                    ?>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="mb-1">
                                                Jabatan Atasan
                                            </div>
                                            <select class="select_persetujuan" name="jabatan_atasan" style="width: 100%;">
                                                <?php foreach ($jabatan_atasan as $at => $atasan) { ?>
                                                    <?php if ($atasan['id_persetujuan'] == $id_persetujuan) { ?>
                                                        <option>
                                                            <?php echo(isset($persetujuan2)) ? $persetujuan2 : $atasan['jabatan_atasan']?> 
                                                            <!-- <php echo "-" ?> <php echo $atasan['jabatan_atasan'];?> -->
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                $(document).ready(function() {
                                                    var pool = [
                                                        <?php foreach ($jabatan as $p) : ?>"<?php echo $p['nama_jabatan']?>",<?php endforeach ?>
                                                    ]

                                                    $(".select_persetujuan").select2({
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

                                            <div class="mb-1 mt-3">
                                                Jabatan Bawahan
                                            </div>
                                            <select class="select_perse" name="jabatan_bawahan" style="width: 100%;">
                                                <?php foreach ($jabatan_bawahan as $at => $bawahan) { ?>
                                                    <?php if ($bawahan['id_persetujuan'] == $id_persetujuan) { ?>
                                                        <option>
                                                            <?php echo(isset($persetujuan3)) ? $persetujuan3 : $bawahan['jabatan_bawahan']?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                $(document).ready(function() {
                                                    var pool = [
                                                        <?php foreach ($jabatan as $p) : ?>"<?php echo $p['nama_jabatan']?>",<?php endforeach ?>
                                                    ]

                                                    $(".select_perse").select2({
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

                                            <a class="btn btn-secondary mt-3" type="button" href="<?php echo $dept?>" style="font-size:100%">Batalkan</a>
                                            <button class="btn btn-success mt-3" type="submit" name="save" style="font-size:100%">Submit</button>
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