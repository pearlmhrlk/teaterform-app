<?php

use App\Controllers\Admin;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

//Routes Controller Mitra Teater
$routes->get('/', 'Home::index');

$routes->match(['GET', 'POST'], 'MitraTeater/registration', 'MitraTeater::register');

$routes->post('MitraTeater/cekStatus', 'MitraTeater::cekStatus');
$routes->get('MitraTeater/cekStatus', 'MitraTeater::cekStatus');
$routes->get('MitraTeater/cekStatusView', 'MitraTeater::cekStatusView');

$routes->get('Mitra/homepage', 'MitraTeater::homepageAfterLogin');
$routes->get('MitraTeater/listPenampilan', 'MitraTeater::penampilanAfterLogin');
$routes->get('MitraTeater/listAudisi', 'MitraTeater::audisiAfterLogin');

$routes->get('MitraTeater/searchPenampilan', 'MitraTeater::searchPenampilan');
$routes->get('MitraTeater/searchAudisi', 'MitraTeater::searchAudisi');

$routes->get('MitraTeater/crudPenampilan', 'MitraTeater::penampilan');
$routes->post('MitraTeater/saveShow', 'MitraTeater::saveShow');
$routes->post('MitraTeater/uploadDenahSeat', 'MitraTeater::uploadDenahSeatAsync');

$routes->delete('MitraTeater/deleteDenah', 'MitraTeater::deleteDenah');

$routes->get('MitraTeater/editShow/(:num)', 'MitraTeater::editPertunjukan/$1');

$routes->post('MitraTeater/deleteShowByTeater', 'MitraTeater::deleteShowByTeater');

$routes->get('MitraTeater/crudAudisi', 'MitraTeater::audisi');
$routes->post('MitraTeater/saveAuditionAktor', 'MitraTeater::saveAuditionAktor');
$routes->post('MitraTeater/saveAuditionStaff', 'MitraTeater::saveAuditionStaff');

$routes->get('MitraTeater/editAudisiAktor/(:num)', 'MitraTeater::editAudisiAktor/$1');
$routes->get('MitraTeater/editAudisiStaff/(:num)', 'MitraTeater::editAudisiStaff/$1');

$routes->post('MitraTeater/deleteAudisiByTeater', 'MitraTeater::deleteAudisiByTeater');

$routes->get('MitraTeater/getTeaterData', 'MitraTeater::getTeaterData');

$routes->delete('MitraTeater/deleteSchedule', 'MitraTeater::deleteSchedule');

$routes->get('MitraTeater/get-booking/(:segment)/(:num)', 'MitraTeater::getBookingBySchedule/$1/$2');
$routes->post('update-booking-status/(:num)/(:alpha)', 'MitraTeater::updateBookingStatus/$1/$2');
$routes->post('MitraTeater/validasi-bukti', 'MitraTeater::validasiBukti');
$routes->get('bukti/(:any)', 'MitraTeater::showBuktiBayar/$1');

$routes->get('teater/getMitraSosmed/(:num)', 'MitraTeater::getMitraSosmed/$1');
$routes->post('teater-sosmed/add', 'MitraTeater::addSosmed');

$routes->delete('MitraTeater/deleteWeb', 'MitraTeater::deleteWeb');

$routes->get('MitraTeater/listMitraTeater', 'MitraTeater::listMitraTeater');
$routes->get('MitraTeater/detailMitraTeater/(:num)', 'MitraTeater::detailMitra/$1');
$routes->get('MitraTeater/aboutUs', 'MitraTeater::aboutUs');
$routes->get('MitraTeater/profile', 'MitraTeater::profile');


//Routes Controller Audiens
$routes->get('Audiens/homepage', 'Audiens::homepage');
$routes->get('Audiens/listPenampilan', 'Audiens::listPenampilan');
$routes->get('Audiens/listAudisi', 'Audiens::ListAudisi');

$routes->match(['GET', 'POST'], 'Audiens/registration', 'Audiens::register'); // untuk method register yang menangani form
$routes->get('Audiens/confirmation', 'Audiens::confirmation');

$routes->get('Audiens/homepageAudiens', 'Audiens::homepageAfterLogin');

$routes->get('user/searchPenampilan', 'Audiens::searchPenampilan');
$routes->get('Audiens/searchPenampilan', 'Audiens::searchPenampilanAfterLogin');

$routes->get('Audiens/penampilanAudiens', 'Audiens::penampilanAfterLogin');
$routes->get('Audiens/detailPenampilan/(:num)', 'Audiens::DetailPenampilan/$1');

$routes->get('User/searchAudisi', 'Audiens::searchAudisi');
$routes->get('Audiens/searchAudisi', 'Audiens::searchAudisiAfterLogin');

$routes->get('Audiens/audisiAudiens', 'Audiens::audisiAfterLogin');
$routes->get('Audiens/detailAudisiAktor/(:num)', 'Audiens::DetailAudisiAktor/$1');
$routes->get('Audiens/detailAudisiStaff/(:num)', 'Audiens::DetailAudisiStaff/$1');

$routes->get('Audiens/booking-popup/(:segment)/(:num)', 'Audiens::showBookingPopup/$1/$2');
$routes->post('Booking/simpanBooking', 'Audiens::simpanBooking');
$routes->post('Booking/konfirmasiUploadBukti/(:num)', 'Audiens::konfirmasiUploadBukti/$1');
$routes->delete('Booking/hapusBookingPending/(:num)', 'Audiens::hapusBookingPending/$1');
$routes->post('Booking/ubahStatusSuccess/(:num)', 'Audiens::ubahStatusSuccess/$1');

$routes->post('Audiens/uploadBuktiPembayaran', 'Audiens::uploadBuktiPembayaran');

$routes->get('Audiens/mitraTeater', 'Audiens::listMitraTeater');
$routes->get('Audiens/detailMitraTeater/(:num)', 'Audiens::detailMitra/$1');
$routes->get('Audiens/aboutUs', 'Audiens::aboutUs');
$routes->get('Audiens/profile', 'Audiens::profile');

$routes->post('Admin/approveMitra/(:num)', 'Admin::approveMitra/$1');
$routes->post('Admin/rejectMitra', 'Admin::rejectMitra');


//routes User sebelum login
$routes->get('User/mitraTeater', 'Audiens::mitraTeater');
$routes->get('User/detailMitraTeater/(:num)', 'Audiens::detail/$1');
$routes->get('User/tentangKami', 'Audiens::tentangKami');

$routes->match(['GET', 'POST'], 'User/login', 'Login::login');
$routes->get('User/logout', 'Login::logout');





$routes->get('Admin/homepage', 'Admin::homepageAfterLogin');
$routes->get('Admin/listPenampilan', 'Admin::penampilan');
$routes->post('Admin/saveShow', 'Admin::saveShow');
$routes->delete('Admin/deleteDenah', 'Admin::deleteDenah');

$routes->get('teater/getApprovedMitra', 'MitraTeater::getApprovedMitra');

$routes->get('Admin/listAudisi', 'Admin::audisi');
$routes->post('Admin/saveAuditionAktor', 'Admin::saveAuditionAktor');
$routes->post('Admin/saveAuditionStaff', 'Admin::saveAuditionStaff');
$routes->get('Admin/approveMitra', 'Admin::approveMitraList');

$routes->get('Admin/mitraTeater', 'Admin::mitraTeater');
$routes->get('Admin/detailMitraTeater/(:num)', 'Admin::detail/$1');

$routes->get('Admin/searchPenampilan', 'Admin::searchPenampilan');
$routes->get('Admin/searchAudisi', 'Admin::searchAudisi');

$routes->post('Admin/saveAuditionAdmin', 'Admin::saveAuditionAdmin');
$routes->post('Admin/updateAuditionAdmin/(:num)', 'Admin::updateAuditionAdmin/$1/');
$routes->get('Admin/getTeaterData', 'Admin::getTeaterData');
$routes->delete('Admin/deleteSchedule', 'Admin::deleteSchedule');
$routes->delete('Admin/deleteWeb', 'Admin::deleteWeb');

$routes->get('Admin/profile', 'Admin::profile');
$routes->get('Admin/aboutUs', 'Admin::aboutUs');
