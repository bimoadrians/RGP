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
                                <h1>Master Detail Hotel</h1>
                                <?php $hotel = site_url("hotel");?>
                                <a class="btn btn-secondary mb-3" href="<?php echo $hotel?>" style="font-size:120%;">Kembali</a>
                                <a class="btn btn-primary mb-3" href="javascript:void(0)" style="font-size:120%;"
                                    data-bs-toggle="modal" data-bs-target="#tambah">+ Tambah Data</a>
                                <div class="table-responsive text-nowrap">
                                    <table id="myTable" class="display nowrap cell-border" style="width=100%">
                                        <thead>
                                            <tr class="align-text-center">
                                                <th class="text-center" style="color: #5a5c69;">No</th>
                                                <th class="text-center" style="color: #5a5c69;">Type</th>
                                                <th class="text-center" style="color: #5a5c69;">Harga</th>
                                                <th class="text-center" style="color: #5a5c69;">Tanggal Valid</th>
                                                <th class="text-center" style="color: #5a5c69;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i=1;?>
                                            <?php foreach ($detail_hotel as $d => $det) {
                                                $id_detail_hotel = $det['id_detail_hotel'];
                                                $edit_detail_hotel = site_url("edit_detail_hotel/$id_hotel/$id_detail_hotel");
                                                $hapus = site_url("detail_hotel/$id_hotel/?aksi=hapus&id_detail_hotel=$id_detail_hotel");
                                            ?>
                                                <tr>
                                                    <td class="text-center col-1" style="color: #5a5c69;"><?php echo $i++;?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo $det['jenis_kamar']?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo "Rp"; echo number_format($det['price_kamar'], 2, ',', '.');?></td>
                                                    <td class="text-center" style="color: #5a5c69;"><?php echo tanggal_indo_kendaraan($det['tgl_valid'])?></td>
                                                    <td class="text-center" style="color: #5a5c69;">
                                                        <a class="btn btn-info btn-lg mb-3"
                                                            style="font-size:80%;" href="<?php echo $edit_detail_hotel?>">Edit</a>
                                                        <a class="btn btn-danger btn-lg mb-3" href="<?php echo $hapus?>" onclick="return confirm('Yakin akan menghapus data detail hotel?')"
                                                            style="font-size:80%;">Hapus</a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="tambah" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="font-size:120%;">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h2 class="modal-title" id="exampleModalLabel">Tambah Data Kamar Hotel</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button></button>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="tab-content">
                            <div class="tab-pane active show" style="font-size:120%">
                                <div class="mb-1">
                                    Type
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" type="text" class="form-control" name="jenis_kamar" id="jenis_kamar">
                                </div>

                                <div class="mb-1">
                                    Harga
                                </div>

                                <input required type="text" autocomplete="off" class="form-control mb-3" name="price_kamar" placeholder="Rp" id="currency-field" data-type="currency">
                                <script>
                                    $("input[data-type='currency']").on({
                                        keyup: function() {
                                        formatCurrency($(this));
                                        },
                                        blur: function() { 
                                        formatCurrency($(this), "blur");
                                        }
                                    });

                                    function formatNumber(n) {
                                    // format number 1000000 to 1,234,567
                                    return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                                    }

                                    function formatCurrency(input, blur) {
                                    // appends $ to value, validates decimal side
                                    // and puts cursor back in right position.
                                    
                                    // get input value
                                    var input_val = input.val();
                                    
                                    // don't validate empty input
                                    if (input_val === "") { return; }
                                    
                                    // original length
                                    var original_len = input_val.length;

                                    // initial caret position 
                                    var caret_pos = input.prop("selectionStart");
                                        
                                    // check for decimal
                                    if (input_val.indexOf(",") >= 0) {

                                        // get position of first decimal
                                        // this prevents multiple decimals from
                                        // being entered
                                        var decimal_pos = input_val.indexOf(",");

                                        // split number by decimal point
                                        var left_side = input_val.substring(0, decimal_pos);
                                        var right_side = input_val.substring(decimal_pos);

                                        // add commas to left side of number
                                        left_side = formatNumber(left_side);

                                        // validate right side
                                        right_side = formatNumber(right_side);
                                        
                                        // On blur make sure 2 numbers after decimal
                                        if (blur === "blur") {
                                        right_side += "00";
                                        }
                                        
                                        // Limit decimal to only 2 digits
                                        right_side = right_side.substring(0, 2);

                                        // join number by .
                                        input_val = "Rp" + left_side + "," + right_side;

                                    } 
                                    else {
                                        // no decimal entered
                                        // add commas to number
                                        // remove all non-digits
                                        input_val = formatNumber(input_val);
                                        input_val = "Rp" + input_val;
                                        
                                        // final formatting
                                        if (blur === "blur") {
                                        input_val += "";
                                        }
                                    }
                                    
                                    // send updated string to input
                                    input.val(input_val);

                                    // put caret back in the right position
                                    var updated_len = input_val.length;
                                    caret_pos = updated_len - original_len + caret_pos;
                                    input[0].setSelectionRange(caret_pos, caret_pos);
                                    }
                                </script>

                                <div class="mb-1">
                                    Tanggal Valid
                                </div>
                                <div class="mb-3">
                                    <input required autocomplete="off" id='tgl_valid' class="form-control" name="tgl_valid">

                                    <script>
                                        $(function() {
                                            $.datetimepicker.setLocale('id');
                                            $('#tgl_valid').datetimepicker({
                                                format: 'Y-m-d',
                                                formatDate: 'Y-m-d',
                                                minDate:'0',
                                                step: 1,
                                                timepicker : false,
                                                closeOnDateSelect : true,
                                                scrollMonth : false,
                                                scrollInput : false,
                                            });
                                        });
                                    </script>
                                </div>
                                <script>
                                    $(document).on("keypress", ":input:not(textarea)", function(event) {
                                        if (event.which == '13') {
                                            event.preventDefault();
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal"
                            style="font-size:120%">Batalkan</button>
                        <button class="btn btn-success" type="submit" name="save" style="font-size:120%">Submit</button>
                    </div>
                </form>
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