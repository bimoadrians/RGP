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
                                        <?php foreach ($pengguna as $p => $peng) {
                                            $id_pengguna = $peng['id_pengguna'];
                                            $pengguna = site_url("pengguna");
                                        ?>
                                            <div class="mb-1">
                                                NIK
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="nik_pengguna" id="nik_pengguna" value="<?php echo(isset($pengguna1)) ? $pengguna1 : $peng['nik_pengguna'];?>">
                                            </div>

                                            <div class="mb-1">
                                                Nama
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="nama_pengguna" id="nama_pengguna" value="<?php echo(isset($pengguna2)) ? $pengguna2 : $peng['nama_pengguna'];?>">
                                            </div>

                                            <div class="mb-1">
                                                Jenis Kelamin
                                            </div>
                                            <select class="select_kelamin" name="jenis_kelamin" style="width: 100%;">
                                                <option>
                                                    <?php
                                                        if($peng['jenis_kelamin'] == 'l'){
                                                            $jenis_kelamin = 'Laki-laki';
                                                        } else if($peng['jenis_kelamin'] == 'p'){
                                                            $jenis_kelamin = 'Perempuan';
                                                        }
                                                        echo(isset($pengguna3)) ? $pengguna3 : $jenis_kelamin;
                                                    ?>
                                                </option>
                                            </select>
                                            <script>
                                            $(document).ready(function() {
                                                var kelamin = [
                                                    'Laki-laki', 'Perempuan'
                                                ]

                                                $(".select_kelamin").select2({
                                                    data: kelamin,
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
                                                No HP<a style="color: #e74a3b">*</a>
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="no_hp_pengguna" id="no_hp_pengguna" value="<?php echo(isset($pengguna4)) ? $pengguna4 : $peng['no_hp_pengguna'];?>">
                                            </div>

                                            <div class="mb-1">
                                                Email
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="email" class="form-control" name="email_pengguna" id="email_pengguna" value="<?php echo(isset($pengguna5)) ? $pengguna5 : $peng['email_pengguna'];?>">
                                            </div>

                                            <div class="mb-1 mt-3">
                                                Alamat Rumah<a style="color: #e74a3b">*</a>
                                            </div>
                                            <div class="mb-1">
                                                <textarea class="form-control" name="alamat_rumah" rows="3" placeholder=""><?php echo(isset($pengguna6)) ? $pengguna6 : $peng['alamat_rumah']; ?></textarea>
                                            </div>
                                            <script>
                                                $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                    if (event.which == '13') {
                                                        event.preventDefault();
                                                    }
                                                });
                                            </script>

                                            <div class="col-xl-12 col-lg-12 mt-3">
                                                <a style="color: #e74a3b">&nbsp;*Silahkan beri tanda "-" jika tidak ada informasi</a>
                                            </div>
                                            <a class="btn btn-secondary mt-3" type="button" href="<?php echo $pengguna?>" style="font-size:100%">Batalkan</a>
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