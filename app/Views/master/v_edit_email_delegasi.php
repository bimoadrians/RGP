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
                                <h1>Edit Data Email Delegasi</h1>
                                <div style="font-size: 24px">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <?php foreach ($email_delegasi as $ed => $ema) {
                                            $id_email_delegasi = $ema['id_email_delegasi'];
                                            $pengguna = site_url("pengguna");
                                        ?>
                                            <div class="mb-1">
                                                Username
                                            </div>
                                            <select class="select_username" name="username" style="width: 100%;">
                                                <option>
                                                    <?php echo(isset($username)) ? $username : $ema['username'];?>
                                                </option>
                                            </select>
                                            <script>
                                            $(document).ready(function() {
                                                var data_select = [
                                                    <?php foreach ($add_delegasi as $addel) : ?> "<?php echo $addel['username']?>",
                                                    <?php endforeach ?>
                                                ]

                                                $(".select_username").select2({
                                                    data: data_select,
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
                                                Personil Delegasi
                                            </div>
                                            <select class="select_personil_delegasi" name="personil_delegasi" style="width: 100%;">
                                                <option>
                                                    <?php echo(isset($personil_delegasi1)) ? $personil_delegasi1 : $ema['nama_pengguna']?><?php echo " - " ?><?php echo $ema['nik_pengguna'] ?>
                                                </option>
                                            </select>
                                            <script>
                                            $(document).ready(function() {
                                                var data_select = [
                                                    <?php foreach ($nik_nama as $nina) : ?> "<?php echo $nina['nama_pengguna']?><?php echo " - " ?><?php echo $nina['nik_pengguna'] ?>",
                                                    <?php endforeach ?>
                                                ]

                                                $(".select_personil_delegasi").select2({
                                                    data: data_select,
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
                                                Tanggal dan Jam Mulai
                                            </div>

                                            <input autocomplete="off" id='tanggal_jam_mulai' class="form-control" name="tanggal_jam_mulai" value="<?php echo(isset($tanggal_jam_mulai1)) ? $tanggal_jam_mulai1 : date("Y-m-d H:i:s", substr($ema['tanggal_jam_mulai'], 0, 10));?>">
                                            
                                            <script>
                                                $(function() {
                                                    $.datetimepicker.setLocale('id');
                                                    $('#tanggal_jam_mulai').datetimepicker({
                                                        format: 'Y-m-d H:i',
                                                        formatDate: 'Y-m-d',
                                                        formatTime: 'H:i',
                                                        minDate:'0',
                                                        step: 1,
                                                        closeOnTimeSelect : true,
                                                        scrollMonth : false,
                                                        scrollInput : false,
                                                    });
                                                });
                                            </script>
                                            <div class="mb-1 mt-3">
                                                Tanggal dan Jam Akhir
                                            </div>

                                            <input autocomplete="off" id='tanggal_jam_akhir' class="form-control" name="tanggal_jam_akhir" value="<?php echo(isset($tanggal_jam_akhir1)) ? $tanggal_jam_akhir1 : date("Y-m-d H:i:s", substr($ema['tanggal_jam_akhir'], 0, 10));?>">
                                            
                                            <script>
                                                $(function() {
                                                    $.datetimepicker.setLocale('id');
                                                    $('#tanggal_jam_akhir').datetimepicker({
                                                        format: 'Y-m-d H:i',
                                                        formatDate: 'Y-m-d',
                                                        formatTime: 'H:i',
                                                        minDate:'0',
                                                        step: 1,
                                                        closeOnTimeSelect : true,
                                                        scrollMonth : false,
                                                        scrollInput : false,
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

                                            <button class="btn btn-success mt-3" type="submit" name="save" style="font-size:100%">Submit</button>
                                            <a class="btn btn-secondary mt-3" type="button" href="<?php echo $pengguna?>" style="font-size:100%">Batalkan</a>
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