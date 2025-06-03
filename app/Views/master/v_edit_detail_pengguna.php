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
                                <h1>Edit Data Pengguna</h1>
                                <div style="font-size: 24px">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <?php foreach ($detail_pengguna as $p => $peng) {
                                            $id_detail_pengguna = $peng['id_detail_pengguna'];
                                            $detail_pengguna = site_url("detail_pengguna/$id_pengguna");
                                        ?>
                                            <div class="mb-1">
                                                Username
                                            </div>
                                            <div class="mb-3">
                                                <input autocomplete="off" type="text" class="form-control" name="username"
                                                    id="username" value="<?php echo(isset($detail_pengguna0)) ? $detail_pengguna0 : $peng['username'];?>">
                                            </div>

                                            <div class="mb-1">
                                                Bagian
                                            </div>
                                            <select class="select_bagian" name="nama_bagian" style="width: 100%;">
                                                <?php foreach ($bagian_detail_pengguna as $p => $ba) { ?>
                                                    <?php if ($ba['id_detail_pengguna'] == $id_detail_pengguna) { ?>
                                                        <option>
                                                            <?php echo(isset($detail_pengguna1)) ? $detail_pengguna1 : $ba['nama_bagian']; ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            $(document).ready(function() {
                                                var bagian = [
                                                    <?php foreach ($bagian as $b) : ?>"<?php echo $b['nama_bagian']?>",<?php endforeach ?>
                                                ]

                                                $(".select_bagian").select2({
                                                    data: bagian,
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
                                                Jabatan
                                            </div>
                                            <select class="select_jabatan" name="nama_jabatan" style="width: 100%;">
                                                <?php foreach ($jabatan_detail_pengguna as $j => $ja) { ?>
                                                    <?php if ($ja['id_detail_pengguna'] == $id_detail_pengguna) { ?>
                                                        <option>
                                                            <?php echo(isset($detail_pengguna2)) ? $detail_pengguna2 : $ja['nama_jabatan']; ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <script>
                                            $(document).ready(function() {
                                                var jabatan = [
                                                    <?php foreach ($jabatan as $j) : ?>"<?php echo $j['nama_jabatan']?>",<?php endforeach ?>
                                                ]

                                                $(".select_jabatan").select2({
                                                    data: jabatan,
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
                                                Level User
                                            </div>
                                            <select class="select_level" name="admin_gs" style="width: 100%;">
                                                <?php
                                                    if($peng['admin_gs'] == '0'){
                                                        $admin_gs = 'User';
                                                    } else if($peng['admin_gs'] == '1'){
                                                        $admin_gs = 'Admin GS';
                                                    } else if($peng['admin_gs'] == '2'){
                                                        $admin_gs = 'Petugas Pool';
                                                    }
                                                    echo(isset($detail_pengguna3)) ? $detail_pengguna3 : $admin_gs;
                                                ?>
                                            </select>
                                            <script>
                                            $(document).ready(function() {
                                                var level = [
                                                    'User', 'Admin GS', 'Petugas Pool'
                                                ]

                                                $(".select_level").select2({
                                                    data: level,
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
                                                Pool
                                            </div>
                                            <select class="select_pool" name="nama_pool" style="width: 100%;">
                                                <?php foreach ($pool_detail_pengguna as $p => $po) { ?>
                                                    <?php if ($po['id_detail_pengguna'] == $id_detail_pengguna) { ?>
                                                        <option>
                                                            <?php echo(isset($detail_pengguna4)) ? $detail_pengguna4 : $po['nama_pool']; ?>
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
                                                $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                    if (event.which == '13') {
                                                        event.preventDefault();
                                                    }
                                                });
                                            </script>

                                            <a class="btn btn-secondary mt-3" type="button" href="<?php echo $detail_pengguna?>" style="font-size:100%">Batalkan</a>
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