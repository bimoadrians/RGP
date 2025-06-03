<?php
namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->group('/', function ($routes) {
    $routes->add('logout', 'Admin\Admin::logout');
    $routes->add('userguide', 'Admin\Admin::userguide');
    $routes->get('user', 'Admin\Admin::user');
    $routes->get('treasury', 'Admin\Admin::treasury');
    $routes->get('gs', 'Admin\Admin::gs');
});

// '/', ['filter'=> 'auth'], function ($routes)
$routes->group('/', ['filter'=> 'auth'], function ($routes) {
    $routes->add('superuser', 'Admin\Admin::superuser');
    $routes->add('sukses', 'Admin\Admin::sukses');

    //Master
    $routes->add('dept', 'Admin\Master::dept');
    $routes->add('edit_dept/(:num)', 'Admin\Master::edit_dept/$1');
    $routes->add('edit_persetujuan/(:num)', 'Admin\Master::edit_persetujuan/$1');
    $routes->add('jabt/(:num)', 'Admin\Master::jabt/$1');
    $routes->add('edit_jabt/(:any)', 'Admin\Master::edit_jabt/$1/$2');
    $routes->add('negara', 'Admin\Master::negara');
    $routes->add('edit_negara/(:num)', 'Admin\Master::edit_negara/$1');
    $routes->add('kota/(:num)', 'Admin\Master::kota/$1');
    $routes->add('edit_kota/(:any)', 'Admin\Master::edit_kota/$1/$2');
    $routes->add('vendo', 'Admin\Master::vendor');
    $routes->add('edit_vendor/(:num)', 'Admin\Master::edit_vendor/$1');
    $routes->add('edit_pemberhentian/(:num)', 'Admin\Master::edit_pemberhentian/$1');
    $routes->add('pasjalangs', 'Admin\Master::pasjalangs');
    $routes->add('hotel_user', 'Admin\Master::hotel_user');
    $routes->add('detail_hotel_user/(:num)', 'Admin\Master::detail_hotel_user/$1');
    $routes->add('hotel', 'Admin\Master::hotel');
    $routes->add('edit_hotel/(:num)', 'Admin\Master::edit_hotel/$1');
    $routes->add('detail_hotel/(:num)', 'Admin\Master::detail_hotel/$1');
    $routes->add('edit_detail_hotel/(:any)', 'Admin\Master::edit_detail_hotel/$1/$2');
    $routes->add('mobil', 'Admin\Master::mobil');
    $routes->add('edit_mobil/(:num)', 'Admin\Master::edit_mobil/$1');
    $routes->add('pengemudi', 'Admin\Master::pengemudi');
    $routes->add('edit_pengemudi/(:num)', 'Admin\Master::edit_pengemudi/$1');
    $routes->add('pengguna', 'Admin\Master::pengguna');
    $routes->add('edit_pengguna/(:num)', 'Admin\Master::edit_pengguna/$1');
    $routes->add('edit_email_delegasi/(:num)', 'Admin\Master::edit_email_delegasi/$1');
    $routes->add('detail_pengguna/(:num)', 'Admin\Master::detail_pengguna/$1');
    $routes->add('edit_detail_pengguna/(:any)', 'Admin\Master::edit_detail_pengguna/$1/$2');
    $routes->add('pool', 'Admin\Master::pool');
    $routes->add('edit_pool/(:num)', 'Admin\Master::edit_pool/$1');
    $routes->add('tujuan', 'Admin\Master::tujuan');
    $routes->add('edit_tujuan/(:num)', 'Admin\Master::edit_tujuan/$1');
    // $routes->add('jam_kend', 'Admin\Master::jam_kend');
    // $routes->add('jam_driv', 'Admin\Master::jam_driv');
    // $routes->add('warning', 'Admin\Master::warning');

    $routes->add('trans', 'Admin\Transaksi::trans');
    $routes->add('trans_add', 'Admin\Transaksi::trans_add');
    $routes->add('tiket_admin', 'Admin\Transaksi::tiket_admin');
    $routes->add('harga_tiket/(:any)', 'Admin\Transaksi::harga_tiket/$1/$2');
    $routes->add('permintaan_batal_tiket/(:any)', 'Admin\Transaksi::permintaan_batal_tiket/$1/$2');
    $routes->add('batal_tiket/(:any)', 'Admin\Transaksi::batal_tiket/$1/$2');
    $routes->add('batal_tiket_confirm/(:any)', 'Admin\Transaksi::batal_tiket_confirm/$1/$2');
    $routes->add('edit_tiket/(:any)', 'Admin\Transaksi::edit_tiket/$1/$2');
    $routes->add('akomodasi_admin', 'Admin\Transaksi::akomodasi_admin');
    $routes->add('mess_admin', 'Admin\Transaksi::mess_admin');
    $routes->add('harga_akomodasi/(:any)', 'Admin\Transaksi::harga_akomodasi/$1/$2');
    $routes->add('permintaan_batal_akomodasi/(:any)', 'Admin\Transaksi::permintaan_batal_akomodasi/$1/$2');
    $routes->add('batal_akomodasi/(:any)', 'Admin\Transaksi::batal_akomodasi/$1/$2');
    $routes->add('batal_kamar_mess/(:any)', 'Admin\Transaksi::batal_kamar_mess/$1/$2/$3');
    $routes->add('batal_akomodasi_confirm/(:any)', 'Admin\Transaksi::batal_akomodasi_confirm/$1/$2');
    $routes->add('edit_akomodasi/(:any)', 'Admin\Transaksi::edit_akomodasi/$1/$2');
    $routes->add('set_mess_jkt/(:any)', 'Admin\Transaksi::set_mess_jkt/$1/$2');
    $routes->add('transport_admin', 'Admin\Transaksi::transport_admin');
    $routes->add('batal_transport/(:any)', 'Admin\Transaksi::batal_transport/$1/$2');
    $routes->add('batal_transport_confirm/(:any)', 'Admin\Transaksi::batal_transport_confirm/$1/$2');
    $routes->add('permintaan_batal_transport_antar/(:any)', 'Admin\Transaksi::permintaan_batal_transport_antar/$1/$2');
    $routes->add('permintaan_batal_transport_jemput/(:any)', 'Admin\Transaksi::permintaan_batal_transport_jemput/$1/$2/$3');
    $routes->add('set_driver/(:any)', 'Admin\Transaksi::set_driver/$1/$2');
    $routes->add('event', 'Admin\Transaksi::loadData');
    $routes->add('eventAjax', 'Admin\Transaksi::ajax');
    $routes->add('edit_transportasi_antar/(:any)', 'Admin\Transaksi::edit_transportasi_antar/$1/$2');
    $routes->add('edit_transportasi_jemput/(:any)', 'Admin\Transaksi::edit_transportasi_jemput/$1/$2/$3');
    $routes->add('tele', 'Admin\Transaksi::tele');
    $routes->add('cetak_pas', 'Admin\Transaksi::cetak_pas');
    $routes->add('arsip_tiket', 'Admin\Transaksi::arsip_tiket');
    $routes->add('arsip_akomodasi', 'Admin\Transaksi::arsip_akomodasi');
    $routes->add('arsip_transport', 'Admin\Transaksi::arsip_transport');
    $routes->add('bbm', 'Admin\Transaksi::bbm');
    $routes->add('daftar_pakai_kend', 'Admin\Transaksi::daftar_pakai_kend');
    $routes->add('set_pas', 'Admin\Transaksi::set_pas');
    
    $routes->add('eval_tiket_user', 'Admin\Evaluasi::eval_tiket_user');
    $routes->add('detail_evaluasi_tiket/(:any)', 'Admin\Evaluasi::detail_evaluasi_tiket/$1/$2');
    $routes->add('eval_akomodasi_user', 'Admin\Evaluasi::eval_akomodasi_user');
    $routes->add('detail_evaluasi_akomodasi/(:any)', 'Admin\Evaluasi::detail_evaluasi_akomodasi/$1/$2');
    $routes->add('eval_transport_user', 'Admin\Evaluasi::eval_transport_user');
    $routes->add('detail_evaluasi_transport_antar/(:any)', 'Admin\Evaluasi::detail_evaluasi_transport_antar/$1/$2');
    $routes->add('detail_evaluasi_transport_jemput/(:any)', 'Admin\Evaluasi::detail_evaluasi_transport_jemput/$1/$2/$3');
    $routes->add('eval_jasa_tiket', 'Admin\Evaluasi::eval_jasa_tiket');
    $routes->add('eval_jasa_akomodasi', 'Admin\Evaluasi::eval_jasa_akomodasi');
    $routes->add('eval_jasa_transport', 'Admin\Evaluasi::eval_jasa_transport');
    $routes->add('eval_lain', 'Admin\Evaluasi::eval_lain');
});

// '/', ['filter'=> 'noauth'], function ($routes)
$routes->group('/', ['filter'=> 'noauth'], function ($routes) {
    $routes->add('', 'Admin\Admin::main_page');
    $routes->add('post_login', 'Admin\Admin::post_login');
    $routes->add('ci4', 'Home::index');
});

$routes->match(['get', 'post'], 'email', 'SendEmail::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
