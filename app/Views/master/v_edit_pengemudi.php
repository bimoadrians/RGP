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
                                <h1>Edit Data Pengemudi</h1>
                                <div style="font-size: 24px">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <?php foreach ($pengemudi as $p => $penge) {
                                            $id_pengemudi = $penge['id_pengemudi'];
                                            $pengemudi = site_url("pengemudi");
                                        ?>
                                            <div class="mb-1">
                                                Pool
                                            </div>
                                            <select class="select_pool" name="nama_pool" style="width: 100%;">
                                                <?php foreach ($pool_pengemudi as $p => $po) { ?>
                                                    <?php if ($po['id_pengemudi'] == $id_pengemudi) { ?>
                                                        <option>
                                                            <?php echo(isset($pengemudi0)) ? $pengemudi0 : $po['nama_pool']; ?>
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

                                            <div class="mb-1 mt-3">
                                                Nama
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="nama_pengemudi" id="nama_pengemudi" value="<?php echo(isset($pengemudi1)) ? $pengemudi1 : $penge['nama_pengemudi'];?>">
                                            </div>

                                            <div class="mb-1 mt-3">
                                                Email<a style="color: #e74a3b">*</a>
                                            </div>
                                            <div class="mb-3">
                                                <input autocomplete="off" type="text" class="form-control" name="email" id="email" value="<?php echo(isset($pengemudi2)) ? $pengemudi2 : $penge['email'];?>">
                                            </div>

                                            <div class="mb-1 mt-3">
                                                No HP
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="nomor_hp" id="nomor_hp" value="<?php echo(isset($pengemudi3)) ? $pengemudi3 : $penge['nomor_hp'];?>">
                                            </div>

                                            <div class="mb-1">
                                                Jenis Driver
                                            </div>
                                            <select class="select_jenis_sopir" name="jenis_sopir" style="width: 100%;">
                                                <?php foreach ($jenis_sopir_pengemudi as $jsp => $sop_penge) { ?>
                                                    <?php if ($sop_penge['id_pengemudi'] == $id_pengemudi) { ?>
                                                        <option>
                                                            <?php echo(isset($pengemudi4)) ? $pengemudi4 : $sop_penge['jenis_sopir']; ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                $(document).ready(function() {
                                                    var jenis_sopir = [
                                                        <?php foreach ($jenis_sopir as $js) : ?>"<?php echo $js['jenis_sopir']?>",<?php endforeach ?>
                                                    ]

                                                    $(".select_jenis_sopir").select2({
                                                        data: jenis_sopir,
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
                                                Mobil
                                            </div>
                                            <select class="select_mobil" name="nama_mobil" style="width: 100%;">
                                                <?php foreach ($mobil_pengemudi as $mp => $mob_penge) { ?>
                                                    <?php if ($mob_penge['id_pengemudi'] == $id_pengemudi) { ?>
                                                        <option>
                                                            <?php echo(isset($pengemudi5)) ? $pengemudi5 : $mob_penge['nama_mobil']; ?>
                                                        </option>
                                                    <?php } ?>
                                                <?php } ?>
                                            </select>
                                            <script>
                                                $(document).ready(function() {
                                                    var mobil = [
                                                        <?php foreach ($mobil as $mb) : ?>"<?php echo $mb['nama_mobil']?>",<?php endforeach ?>
                                                    ]

                                                    $(".select_mobil").select2({
                                                        data: mobil,
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
                                            <div class="col-xl-12 col-lg-12 mt-3">
                                                <a style="color: #e74a3b">&nbsp;*Silahkan beri tanda "-" jika tidak ada informasi</a>
                                            </div>
                                            <a class="btn btn-secondary mt-3" type="button" href="<?php echo $pengemudi?>" style="font-size:100%">Batalkan</a>
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