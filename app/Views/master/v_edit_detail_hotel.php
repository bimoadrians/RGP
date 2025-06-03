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
                                <h1>Edit Data Detail hotel</h1>
                                <div style="font-size: 24px">
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <?php foreach ($detail_hotel as $h => $ho) {
                                            $id_detail_hotel = $ho['id_detail_hotel'];
                                            $detail_hotel = site_url("detail_hotel/$id_hotel");
                                        ?>
                                            <div class="mb-1">
                                                Type
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" type="text" class="form-control" name="jenis_kamar" id="jenis_kamar" value="<?php echo(isset($detail_hotel1)) ? $detail_hotel1 : $ho['jenis_kamar'];?>">
                                            </div>

                                            <div class="mb-1">
                                                Tanggal Valid
                                            </div>
                                            <div class="mb-3">
                                                <input required autocomplete="off" id='tgl_valid' class="form-control" name="tgl_valid" value="<?php echo(isset($detail_hotel3)) ? $detail_hotel3 : tanggal_kendaraan($ho['tgl_valid']);?>">

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

                                            <div class="mb-1">
                                                Harga
                                            </div>

                                            <input required type="text" autocomplete="off" class="form-control mb-3" name="price_kamar" id="currency-field" value="<?php echo "Rp"; echo(isset($detail_hotel2)) ? $detail_hotel2 : number_format($ho['price_kamar'], 2, ',', '.'); ?>" data-type="currency">

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

                                            <a class="btn btn-secondary mt-3" type="button" href="<?php echo $detail_hotel?>" style="font-size:100%">Batalkan</a>
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