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
                                <?php foreach ($akomodasi as $ak => $ako) { ?>
                                    <?php if ($ako['batal_akomodasi'] == 3) { ?>
                                        <h1>Nilai Refund</h1>
                                        <div style="font-size: 24px">
                                            <form action="" method="post" enctype="multipart/form-data">
                                                <input required type="text" autocomplete="off" class="form-control mb-3" name="nilai_refund" id="currency-field" value="<?php echo "Rp"; ?>" data-type="currency">

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

                                                <script>
                                                    $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                        if (event.which == '13') {
                                                            event.preventDefault();
                                                        }
                                                    });
                                                </script>

                                                <a class="btn btn-secondary" type="button" href="<?php echo session()->get('url_kend')?>" style="font-size:100%">Batalkan</a>
                                                <button class="btn btn-success" type="submit" name="save" style="font-size:100%">Submit</button>
                                            </form>
                                        </div>
                                    <?php } else { ?>
                                        <h1>Harga Akomodasi</h1>
                                        <div style="font-size: 24px">
                                            <form action="" method="post" enctype="multipart/form-data">
                                                <input required type="text" autocomplete="off" class="form-control mb-3" name="harga_akomodasi" id="currency-field" value="<?php echo "Rp"; ?>" data-type="currency">

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

                                                <script>
                                                    $(document).on("keypress", ":input:not(textarea)", function(event) {
                                                        if (event.which == '13') {
                                                            event.preventDefault();
                                                        }
                                                    });
                                                </script>

                                                <a class="btn btn-secondary" type="button" href="<?php echo session()->get('url_kend')?>" style="font-size:100%">Batalkan</a>
                                                <button class="btn btn-success" type="submit" name="save" style="font-size:100%">Submit</button>
                                            </form>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
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