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
            <div class="col-lg-12 mb-4">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-12">
                            <div class="card-body">
                                <h1>Set Driver</h1>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="table-responsive text-nowrap">
                                        <div id='calendar' class="text-center" style="width:100%; font-size:20px;"></div>
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                var calendarEl = document.getElementById('calendar');
                                                var modal_transport = document.getElementById('data_transport');

                                                var calendar = new FullCalendar.Calendar(calendarEl, {
                                                    customButtons: {
                                                        kembali: {
                                                            text: 'Kembali',
                                                            click: function() {
                                                                window.location = "<?php echo site_url("transport_admin"); ?>";
                                                            }
                                                        },
                                                        data_transport: {
                                                            text: 'Data Transport',
                                                            click: function() {
                                                                $('#data_transport').modal('toggle');
                                                            }
                                                        }
                                                    },
                                                    headerToolbar: {
                                                    left: 'kembali prev,next today',
                                                    center: 'title',
                                                    right: 'data_transport dayGridMonth'
                                                    // right: 'data_transport dayGridMonth,timeGridWeek,timeGridDay'
                                                    },
                                                    eventTimeFormat: { // like '14:30:00'
                                                        hour: '2-digit',
                                                        minute: '2-digit',
                                                        hour12: false,
                                                        meridiem: false
                                                    },
                                                    events: "<?php echo site_url("event"); ?>",
                                                    eventColor: "blue",
                                                    displayEventEnd: true,
                                                    initialDate: '<?php echo $date?>',
                                                    navLinks: false, // can click day/week names to navigate views
                                                    selectable: true,
                                                    selectMirror: false,
                                                    select: function(arg, start, end, id) {
                                                        $('#set_id').val(arg.id);
                                                        $('#start_time').val(moment(arg.start).format('YYYY-MM-DD'));
                                                        $('#end_time').val(moment(arg.end).format('YYYY-MM-DD'));
                                                        $('#set_driver').modal('toggle');
                                                    },
                                                    eventClick: function(arg, start, end, id) {
                                                        $('#hapus_id').val(arg.event.id);
                                                        $('#hapus_driver').modal('toggle');
                                                    },
                                                    editable: false,
                                                    dayMaxEvents: false, // allow "more" link when too many events
                                                    eventDidMount: function(arg) {
                                                        $(arg.el).tooltip({ 
                                                            title: arg.event.extendedProps.description,
                                                            placement: "top",
                                                            trigger: "hover",
                                                            container: "body"
                                                        });
                                                    },
                                                });
                                                calendar.render();
                                            });
                                        </script>

                                        <div class="modal" id="data_transport" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title" id="modalTopTitle">Data Transport</h2>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="tab-content">
                                                            <div class="tab-pane active show" style="font-size:120%; text-align: center;">
                                                                <form action="" method="post" enctype="multipart/form-data">
                                                                    <div class="table-responsive text-nowrap">
                                                                        <table class="text-center" style="width: 100%;">
                                                                            <tbody>
                                                                                <?php foreach ($trans as $t => $tra) {?>
                                                                                    <?php foreach ($transportasi_antar as $tr => $transpo) { ?>
                                                                                        <?php if ($transpo['id_trans'] == $tra['id_trans']) { ?>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Jenis Kendaraan:</th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;"><?php 
                                                                                                    if($transpo['jenis_kendaraan'] == 's'){
                                                                                                        $jenis_kendaraan = 'Sedan';
                                                                                                    } else if($transpo['jenis_kendaraan'] == 'a'){
                                                                                                        $jenis_kendaraan = 'Station';
                                                                                                    } else if($transpo['jenis_kendaraan'] == 'p'){
                                                                                                        $jenis_kendaraan = 'Pick Up';
                                                                                                    } else if($transpo['jenis_kendaraan'] == 'b'){
                                                                                                        $jenis_kendaraan = 'Box';
                                                                                                    } else if($transpo['jenis_kendaraan'] == 't'){
                                                                                                        $jenis_kendaraan = 'Truck';
                                                                                                    }
                                                                                                    ?>
                                                                                                    <?php echo $jenis_kendaraan ?>
                                                                                                </th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Dalam/Luar Kota:</th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">
                                                                                                    <?php
                                                                                                        if($transpo['dalkot_lukot'] == 'd'){
                                                                                                            $dalkot_lukot = 'Dalam Kota';
                                                                                                        } else if($transpo['dalkot_lukot'] == 'l'){
                                                                                                            $dalkot_lukot = 'Luar Kota';
                                                                                                        }
                                                                                                    ?>
                                                                                                    <?php echo $dalkot_lukot ?>
                                                                                                </th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Menginap?:</th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">
                                                                                                    <?php 
                                                                                                        if($transpo['menginap'] == '0'){
                                                                                                            $menginap = 'Tidak';
                                                                                                        } else if($transpo['menginap'] == '1'){
                                                                                                            $menginap = 'Iya';
                                                                                                        }
                                                                                                    ?>
                                                                                                    <?php echo $menginap ?>
                                                                                                </th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Jumlah:</th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;"><?php echo $transpo['jumlah_mobil']; ?></th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Kapasitas:</th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;"><?php echo $transpo['kapasitas']; ?></th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Nama/Jabatan:</th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;"><?php echo $transpo['atas_nama']; ?> <?php echo "(" ?><?php echo $transpo['jabatan']; ?><?php echo ")" ?></th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Tanggal:</th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;"><?php echo date_transaksi_kendaraan($transpo['tanggal_mobil']); ?></th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Jam:</th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;"><?php echo $transpo['jam_siap']; ?></th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px; background-color: #D6EEEE;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;">Tujuan ke:</th>
                                                                                            </tr>
                                                                                            <tr style="border: 3px solid #03c3ec; text-align: center; padding: 8px;">
                                                                                                <th colspan ="2" style="border: 3px solid #03c3ec; text-align: left; padding: 8px;"><?php echo $transpo['tujuan_mobil']; ?></th>
                                                                                            </tr>
                                                                                        <?php } ?>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal" id="set_driver" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title" id="modalTopTitle">Set Driver</h2>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="" method="post" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <div class="tab-content">
                                                                <div class="tab-pane active show" style="font-size:120%;">
                                                                    <?php foreach ($trans as $t => $tra) {?>
                                                                        <?php foreach ($transportasi_antar as $tr => $transpo) { ?>
                                                                            <?php if ($transpo['id_trans'] == $tra['id_trans']) { ?>
                                                                                <div class="mb-1">
                                                                                    User
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <input disabled type="text" class="form-control" name="atas_nama" id="atas_nama" value="<?php echo(isset($user1)) ? $user1 : $transpo['atas_nama'];?>">
                                                                                </div>
                                                                                <div class="mb-1">
                                                                                    Siap Di
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <input disabled type="text" class="form-control" name="siap_di1" id="siap_di1" value="<?php echo(isset($siap_di1)) ? $siap_di1 : $transpo['siap_di'];?>">
                                                                                </div>
                                                                                <div class="mb-1">
                                                                                    Jam Siap
                                                                                </div>
                                                                                <div class="mb-3">
                                                                                    <?php
                                                                                        if($transpo['jenis_kendaraan'] == 's'){
                                                                                            $jenis_kendaraan2 = 'Sedan';
                                                                                        } else if($transpo['jenis_kendaraan'] == 'a'){
                                                                                            $jenis_kendaraan2 = 'Station';
                                                                                        } else if($transpo['jenis_kendaraan'] == 'p'){
                                                                                            $jenis_kendaraan2 = 'Pick Up';
                                                                                        } else if($transpo['jenis_kendaraan'] == 'b'){
                                                                                            $jenis_kendaraan2 = 'Box';
                                                                                        } else if($transpo['jenis_kendaraan'] == 't'){
                                                                                            $jenis_kendaraan2 = 'Truck';
                                                                                        }

                                                                                        if(empty($transpo['keterangan_mobil'])){
                                                                                            $keterangan_mobil2 = null;
                                                                                        } else {
                                                                                            $keterangan_mobil2 = $transpo['keterangan_mobil'];
                                                                                        }
                                                                                    ?>
                                                                                    <textarea hidden rows="3" class="form-control" name="description" id="description"><?php
                                                                                            echo "Siap Di : "; echo(isset($siap_di2)) ? $siap_di2 : $transpo['siap_di']; echo "\n";
                                                                                            echo "Tujuan : "; echo(isset($tujuan_mobil2)) ? $tujuan_mobil2 : $transpo['tujuan_mobil']; echo "\n";
                                                                                            echo "Tamu : "; echo(isset($atas_nama1)) ? $atas_nama1 : $transpo['atas_nama']; echo " ("; echo(isset($jabatan)) ? $jabatan : $transpo['jabatan']; echo ")"; echo "\n";
                                                                                            echo "Jenis Kendaraan : "; echo(isset($jenis_kendaraan1)) ? $jenis_kendaraan1 : $jenis_kendaraan2; echo "\n";
                                                                                            echo "Jumlah : "; echo(isset($jumlah_mobil1)) ? $jumlah_mobil1 : $transpo['jumlah_mobil']; echo "\n";
                                                                                            echo "Kapasitas : "; echo(isset($kapasitas1)) ? $kapasitas1 : $transpo['kapasitas']; echo "\n";
                                                                                            echo "Keterangan : "; echo(isset($keterangan_mobil1)) ? $keterangan_mobil1 : $keterangan_mobil2; echo "\n";
                                                                                        ?>
                                                                                    </textarea>

                                                                                    <input hidden type="text" class="form-control" name="tanggal_mobil" id="tanggal_mobil" value="<?php echo(isset($tanggal_mobil1)) ? $tanggal_mobil1 : $transpo['tanggal_mobil'];?>">
                                                                                    <input hidden type="text" class="form-control" name="tujuan_mobil" id="tujuan_mobil" value="<?php echo(isset($tujuan_mobil1)) ? $tujuan_mobil1 : $transpo['tujuan_mobil'];?>">
                                                                                    <input hidden type="text" class="form-control" name="id_pool" id="id_pool" value="<?php echo(isset($id_pool1)) ? $id_pool1 : $transpo['id_pool'];?>">
                                                                                    <input hidden type="text" class="form-control" name="menginap" id="menginap" value="<?php echo(isset($menginap1)) ? $menginap1 : $transpo['menginap'];?>">
                                                                                    <input hidden type="text" class="form-control" name="set_id" id="set_id">
                                                                                    <input hidden type="text" class="form-control" name="start_time" id="start_time">
                                                                                    <input hidden type="text" class="form-control" name="end_time" id="end_time">
                                                                                    <input autocomplete="off" id='jam_siap' class="form-control" name="jam_siap" value="<?php echo(isset($jam_siap1)) ? $jam_siap1 : $transpo['jam_siap'];?>">

                                                                                    <script>
                                                                                        $(function() {
                                                                                            $.datetimepicker.setLocale('id');
                                                                                            $('#jam_siap').datetimepicker({
                                                                                                format: 'H:i',
                                                                                                formatTime: 'H:i',
                                                                                                step: 1,
                                                                                                datepicker : false,
                                                                                            });
                                                                                        });
                                                                                    </script>
                                                                                </div>
                                                                                <!-- <div class="mb-1">
                                                                                    Durasi
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="col-lg-6">
                                                                                        <input required type="number" min="0" class="form-control" name="durasi" id="durasi" style="height:95%;">
                                                                                    </div>
                                                                                    <div class="col-lg-6">
                                                                                        <div class="col-md-12">
                                                                                            <select class="select_satuan_waktu" id="satuan_waktu" name="satuan_waktu" style="width: 100%;"></select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <script>
                                                                                    $(document).ready(function() {
                                                                                        var data_select = ['Hari', 'Jam', 'Menit']

                                                                                        $(".select_satuan_waktu").select2({
                                                                                            data: data_select,
                                                                                            // tags: true,
                                                                                            // tokenSeparators: [',', ' '],
                                                                                        });

                                                                                        $('select:not(.normal)').each(function() {
                                                                                            $(this).select2({
                                                                                                // tags: true,
                                                                                                dropdownParent: $(this)
                                                                                                    .parent()
                                                                                            });
                                                                                        });
                                                                                    });
                                                                                </script> -->

                                                                                <div class="mb-1 mt-3">
                                                                                    Driver
                                                                                </div>
                                                                                <select class="select_pengemudi" name="nama_pengemudi" style="width: 100%;"></select>
                                                                                <script>
                                                                                    $(document).ready(function() {
                                                                                        var pengemudi = [
                                                                                            <?php foreach ($pengemudi as $penge) : ?>"<?php echo $penge['nama_pengemudi']?>",<?php endforeach ?>
                                                                                        ]

                                                                                        $(".select_pengemudi").select2({
                                                                                            data: pengemudi,
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
                                                                                <select class="select_mobil" name="nama_mobil" style="width: 100%;"></select>
                                                                                <script>
                                                                                    $(document).ready(function() {
                                                                                        var mobil = [
                                                                                            <?php foreach ($mobil_pengemudi as $mob_penge) : ?>"<?php echo $mob_penge['nama_mobil']?>",<?php endforeach ?>
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
                                                                                    $(document).keypress(function(event){
                                                                                        if (event.which == '13') {
                                                                                        event.preventDefault();
                                                                                        }
                                                                                    });
                                                                                </script>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-success btn-lg" type="submit" name="save" style="font-size:120%">Set Driver</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal" id="hapus_driver" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title" id="modalTopTitle">Hapus Set Driver</h2>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="" method="post" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <div class="tab-content">
                                                                <div class="tab-pane active show" style="font-size:120%; text-align: center;">
                                                                    <h3 class="mt-4 mb-1">
                                                                        Yakin akan hapus data?
                                                                    </h3>
                                                                    <input hidden type="text" class="form-control" name="hapus_id" id="hapus_id">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-success btn-lg" type="submit" name="save" style="font-size:120%">Hapus Set Driver</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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