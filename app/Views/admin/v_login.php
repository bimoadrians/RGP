<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login TABS</title>
    <link rel="shortcut icon" type="image/png" href="<?php echo base_url()?>/konimex.png">

    <link href="<?php echo base_url('login')?>/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url('login')?>/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="<?php echo base_url('login')?>/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="<?php echo base_url('login')?>/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="<?php echo base_url('login')?>/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="<?php echo base_url('login')?>/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="<?php echo base_url('login')?>/vendor/simple-datatables/style.css" rel="stylesheet">

    <link href="<?php echo base_url('sneat')?>/assets/css/select2.min.css" rel="stylesheet" />
    <link href="<?php echo base_url('login')?>/css/style.css" rel="stylesheet">
    <script src="<?php echo base_url('admin')?>/js/jquery-3.7.0.js"></script>
</head>

<body style="background-image: url(sneat/img/header.jpg); background-repeat: no-repeat; background-attachment: fixed; background-size: 100% 100%;">
    <main>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-12 col-md-9">
                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center mb-4">                                    
                                        <img class="img-fluid logo-dark mb-4" src="<?php echo base_url()?>/konimex.png" alt="Logo" width="30%">
                                        <h1 class="card-title text-center pb-0 fs-1">TABS</h1>
                                        <P class="text-center medium">PT KONIMEX</P>
                                    </div>
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
                                        <?php if (!session()->get('login_email')) { ?>
                                            <form method="POST" action="<?php echo site_url('')?>">
                                                <div class="form-group mb-3">
                                                    <input required class="form-control" autocomplete="on" id="email" type="email" style="font-size: 20px;" placeholder="Email Konimex" name="email" value="<?php if($session->getFlashdata('email')) {
                                                        echo $session->getFlashdata('email');
                                                    }?>" />
                                                </div>
                                                <div class="d-grid">
                                                    <button class="btn btn-lg btn-block btn-primary" type="submit" name="submit">Login</button>
                                                </div>
                                            </form>
                                        <?php } else { ?>
                                            <form method="POST" action="<?php echo site_url('post_login')?>">
                                                <div class="form-group mb-3">
                                                    <div class="mb-1" style="font-size: 20px;">
                                                        Email
                                                    </div>
                                                    <input disabled class="form-control" autocomplete="off" id="email_hidden" name="email_hidden" type="email" style="font-size: 20px;" placeholder="Email Konimex" value="<?php echo session()->get('login_email')?>" />
                                                    <input hidden class="form-control" autocomplete="off" id="email" type="email" placeholder="Email Konimex" name="email" value="<?php echo session()->get('login_email')?>" />
                                                </div>

                                                <?php if ($cek_email == 'superuser') { ?>
                                                    <div class="mb-1" style="font-size: 20px;">
                                                        Code
                                                    </div>
                                                    <input required class="form-control mb-3" autocomplete="off" id="username" type="text" name="username" style="width: 100%; font-size: 20px;">
                                                <?php } else { ?>
                                                    <div class="form-group mb-3">
                                                        <!-- <div class="mb-1" style="font-size: 20px;">
                                                            Username
                                                        </div>
                                                        <select class="form-control" autocomplete="off" id="username" name="username" style="width: 100%; font-size: 20px;">
                                                            <php if (empty($cek_email)) { ?>
                                                                        
                                                            <php } else { ?>
                                                                <php foreach ($cek_email as $cek) { ?>
                                                                    <option>
                                                                        <php echo $cek['username']?>
                                                                    </option>
                                                                <php } ?>
                                                                <php foreach ($cek_email1 as $cek1) { ?>
                                                                    <option>
                                                                        <php echo $cek1['username']?>
                                                                    </option>
                                                                <php } ?>
                                                            <php } ?>
                                                        </select> -->

                                                        <div class="mb-1" style="font-size: 20px;">
                                                            Username
                                                        </div>
                                                        <select class="select_pass" autocomplete="off" id="username" name="username" style="width: 100%; font-size: 20px;"></select>

                                                        <script>
                                                            $(document).ready(function() {
                                                                var data_select = [
                                                                    <?php foreach ($cek_email as $cek) : ?> "<?php echo $cek['username']?>",
                                                                    <?php endforeach ?>,<?php foreach ($cek_email1 as $cek1) : ?> "<?php echo $cek1['username']?>",
                                                                    <?php endforeach ?>
                                                                ]

                                                                $(".select_pass").select2({
                                                                    data: data_select,
                                                                    // tags: true,
                                                                    // tags:["Semua"],
                                                                    // tokenSeparators: [',', ' '],
                                                                });
                                                            });
                                                        </script>
                                                    </div>
                                                <?php } ?>

                                                <div class="pass-group mb-3">
                                                    <div class="mb-1" style="font-size: 20px;">
                                                        Password Webmail
                                                    </div>
                                                    <input required class="form-control" autocomplete="off"  id="password" style="font-size: 20px;" type="password" placeholder="Password Webmail" name="password" />
                                                </div>

                                                <div class="d-grid">
                                                    <button class="btn btn-lg btn-block btn-primary" type="submit" name="submit">Login</button>
                                                </div>
                                                    
                                                <!-- <input required class="form-control" autocomplete="off" id="username" type="text" placeholder="Username" name="username" value="<php if($session->getFlashdata('username')) {
                                                    echo $session->getFlashdata('username');
                                                }?>" /> -->
                                            </form>
                                        <?php } ?>
                                    <div class="text-center">
                                        <p></p>
                                        <a class="btn btn-success mt-3 mb-3" href="<?php echo site_url("userguide")?>">USER GUIDE</a>
                                    </div>

                                    <div class="text-center">
                                        <p></p>
                                        <a>Copyright &copy; <strong><span>MIS 2024</span></strong>.</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap core JavaScript-->
    <script
        src="<?php echo base_url('admin')?>/vendor/jquery/jquery.min.js">
    </script>
    <!-- Core plugin JavaScript-->
    <script
        src="<?php echo base_url('admin')?>/vendor/jquery-easing/jquery.easing.min.js">
    </script>

    <script
        src="<?php echo base_url('login')?>/vendor/apexcharts/apexcharts.min.js">
    </script>

    <script
        src="<?php echo base_url('login')?>/vendor/bootstrap/js/bootstrap.bundle.min.js">
    </script>

    <script
        src="<?php echo base_url('login')?>/vendor/chart.js/chart.umd.js">
    </script>

    <script
        src="<?php echo base_url('login')?>/vendor/echarts/echarts.min.js">
    </script>

    <script
        src="<?php echo base_url('login')?>/vendor/quill/quill.min.js">
    </script>

    <script
        src="<?php echo base_url('login')?>/vendor/simple-datatables/simple-datatables.js">
    </script>

    <script
        src="<?php echo base_url('login')?>/vendor/tinymce/tinymce.min.js">
    </script>

    <script
        src="<?php echo base_url('login')?>/vendor/php-email-form/validate.js">
    </script>

    <!-- Custom scripts for all pages-->
    <script
        src="<?php echo base_url('login')?>/js/main.js">
    </script>

    <script 
        src="<?php echo base_url('sneat')?>/js/select2.min.js">
    </script>

</body>

</html>