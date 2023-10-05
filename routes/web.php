<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backend as Backend;
use App\Http\Controllers\Api as Api;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('backend.login');
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout');
Route::prefix('backend')->name('backend.')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm']);
    Route::post('/', [LoginController::class, 'login'])->name('login');
});

Route::prefix('api')->name('api.')->group(function(){
    Route::resource('api_users', Api\Api_UsersController::class);
    Route::get('api_login/login', [Api\Api_LoginController::class, 'login'])->name('api_login.login');
    Route::resource('api_login', Api\Api_LoginController::class);
});

Route::middleware('auth:henmus')->group(function(){
    Route::prefix('backend')->name('backend.')->group(function(){
      Route::post('resetpassword', [Backend\UserController::class, 'resetpassword'])->name('users.resetpassword');
      Route::post('changepassword', [Backend\UserController::class, 'changepassword'])->name('users.changepassword');
      Route::post('users/offline', [Backend\UserController::class, 'offline'])->name('users.offline');
      Route::get('users/select2', [Backend\UserController::class, 'select2'])->name('users.select2');
      Route::post('users/import', [Backend\UserController::class, 'import'])->name('users.import');
      Route::resource('users', Backend\UserController::class);
      Route::get('roles/select2', [Backend\RoleController::class, 'select2'])->name('roles.select2');
      Route::resource('roles', Backend\RoleController::class);
      Route::resource('permissions', Backend\PermissionController::class);
      Route::get('menupermissions/select2', [Backend\MenuPermissionController::class, 'select2'])->name('menupermissions.select2');
      Route::resource('menupermissions', Backend\MenuPermissionController::class)->except('create', 'edit', 'show');
      Route::resource('menu', Backend\MenuManagerController::class)->except('create', 'show');
      Route::post('menu/changeHierarchy', [Backend\MenuManagerController::class, 'changeHierarchy'])->name('menu.changeHierarchy');
      Route::resource('settings', Backend\SettingsController::class);

            Route::get('dashboard/dtjo', [Backend\DashboardController::class, 'dtjo'])->name('dashboard.dtjo');
            Route::get('dashboard/dtinvoice', [Backend\DashboardController::class, 'dtinvoice'])->name('dashboard.dtinvoice');
            Route::get('dashboard/dtmobil', [Backend\DashboardController::class, 'dtmobil'])->name('dashboard.dtmobil');
            Route::get('dashboard/dtdriver', [Backend\DashboardController::class, 'dtdriver'])->name('dashboard.dtdriver');
            Route::resource('dashboard', Backend\DashboardController::class);
            //tipemobil
            Route::get('tipemobil/select2', [Backend\TipemobilController::class, 'select2'])->name('tipemobil.select2');
            Route::resource('tipemobil', Backend\TipemobilController::class);

            //merkmobil
            Route::get('merkmobil/select2', [Backend\MerkmobilController::class, 'select2'])->name('merkmobil.select2');
            Route::resource('merkmobil', Backend\MerkmobilController::class);

            //jenismobil
            Route::get('jenismobil/select2', [Backend\JenismobilController::class, 'select2'])->name('jenismobil.select2');
            Route::resource('jenismobil', Backend\JenismobilController::class);

            //mobil
            Route::put('mobil/validasi', [Backend\MobilController::class, 'validasi'])->name('mobil.validasi');
            Route::get('mobil/select2', [Backend\MobilController::class, 'select2'])->name('mobil.select2');
            Route::resource('mobil', Backend\MobilController::class);

       //mobilrincian
            Route::put('mobilrincian/validasi', [Backend\MobilRincianController::class, 'validasi'])->name('mobilrincian.validasi');
            Route::get('mobilrincian/findmobilrincian', [Backend\MobilRincianController::class, 'findmobilrincian'])->name('mobilrincian.findmobilrincian');
            Route::get('mobilrincian/datatablecekmobilrincian', [Backend\MobilRincianController::class, 'datatablecekmobilrincian'])->name('mobilrincian.datatablecekmobilrincian');
            Route::get('mobilrincian/select2', [Backend\MobilRincianController::class, 'select2'])->name('mobilrincian.select2');
            Route::resource('mobilrincian', Backend\MobilRincianController::class);

            //driver
            Route::put('driver/aktivasi', [Backend\DriverController::class, 'aktivasi'])->name('driver.aktivasi');
            Route::put('driver/validasi', [Backend\DriverController::class, 'validasi'])->name('driver.validasi');
            Route::get('driver/select2', [Backend\DriverController::class, 'select2'])->name('driver.select2');
            Route::resource('driver', Backend\DriverController::class);

            //Customer
            Route::put('customer/validasi', [Backend\CustomerController::class, 'validasi'])->name('customer.validasi');
            Route::get('customer/select2', [Backend\CustomerController::class, 'select2'])->name('customer.select2');
            Route::resource('customer', Backend\CustomerController::class);

            //Alamatrute
            Route::get('alamatrute/select2', [Backend\AlamatruteController::class, 'select2'])->name('alamatrute.select2');
            Route::get('alamatrute/select2first', [Backend\AlamatruteController::class, 'select2first'])->name('alamatrute.select2first');
            Route::get('alamatrute/select2last', [Backend\AlamatruteController::class, 'select2last'])->name('alamatrute.select2last');
            Route::resource('alamatrute', Backend\AlamatruteController::class);

            //Muatan
            Route::get('muatan/select2', [Backend\MuatanController::class, 'select2'])->name('muatan.select2');
            Route::resource('muatan', Backend\MuatanController::class);


            //Rute
            Route::get('rute/select2', [Backend\RuteController::class, 'select2'])->name('rute.select2');
            Route::put('rute/validasidelete', [Backend\RuteController::class, 'validasidelete'])->name('rute.validasidelete');
            Route::put('rute/validasi', [Backend\RuteController::class, 'validasi'])->name('rute.validasi');

            Route::resource('rute', Backend\RuteController::class);

            //Joborder
            Route::get('joborder/pdf', [Backend\JoborderController::class, 'pdf'])->name('joborder.pdf');
            Route::get('joborder/excel', [Backend\JoborderController::class, 'excel'])->name('joborder.excel');
            Route::get('joborder/findkonfirmasijoborder', [Backend\JoborderController::class, 'findkonfirmasijoborder'])->name('joborder.findkonfirmasijoborder');
            Route::get('joborder/findjoborder', [Backend\JoborderController::class, 'findjoborder'])->name('joborder.findjoborder');
            Route::get('Joborder/datatablecekjoborder', [Backend\JoborderController::class, 'datatablecekjoborder'])->name('joborder.datatablecekjoborder');
            Route::get('joborder/select2', [Backend\JoborderController::class, 'select2'])->name('joborder.select2');
            Route::put('joborder/validasi', [Backend\JoborderController::class, 'validasi'])->name('joborder.validasi');
            Route::resource('joborder', Backend\JoborderController::class);

            //PaymentJo
            Route::put('paymentjo/updatesingle', [Backend\PaymentJoController::class, 'updatesingle'])->name('paymentjo.updatesingle');
            Route::get('paymentjo/{id}/pdf', [Backend\PaymentJoController::class, 'pdf'])->name('paymentjo.pdf');
            Route::resource('paymentjo', Backend\PaymentJoController::class);

            //KonfirmasiJo
            Route::post('konfirmasijo/findkonfirmasijo', [Backend\KonfirmasiJoController::class, 'findkonfirmasijo'])->name('konfirmasijo.findkonfirmasijo');
            Route::get('konfirmasijo/datatablecekjo', [Backend\KonfirmasiJoController::class, 'datatablecekjo'])->name('konfirmasijo.datatablecekjo');
            Route::resource('konfirmasijo', Backend\KonfirmasiJoController::class);

            //Kasbon
            Route::get('kasbon/pdf', [Backend\KasbonController::class, 'pdf'])->name('kasbon.pdf');
            Route::get('kasbon/excel', [Backend\KasbonController::class, 'excel'])->name('kasbon.excel');
            Route::put('kasbon/validasi', [Backend\KasbonController::class, 'validasi'])->name('kasbon.validasi');
            Route::get('kasbon/select2', [Backend\KasbonController::class, 'select2'])->name('kasbon.select2');

            Route::resource('kasbon', Backend\KasbonController::class);


            //Invoice
            Route::get('invoice/sisapayment', [Backend\InvoiceController::class, 'sisapayment'])->name('invoice.sisapayment');
            Route::get('invoice/excel', [Backend\InvoiceController::class, 'excel'])->name('invoice.excel');
            Route::get('invoice/pdf', [Backend\InvoiceController::class, 'pdf'])->name('invoice.pdf');
            Route::get('invoice/findinvoice', [Backend\InvoiceController::class, 'findinvoice'])->name('invoice.findinvoice');
            Route::get('invoice/select2', [Backend\InvoiceController::class, 'select2'])->name('invoice.select2');
            Route::resource('invoice', Backend\InvoiceController::class);


            //PaymentInvoice
            Route::put('paymentinvoice/updatesingle', [Backend\PaymentInvoiceController::class, 'updatesingle'])->name('paymentinvoice.updatesingle');
            Route::resource('paymentinvoice', Backend\PaymentInvoiceController::class);


            //Penggajian
            Route::get('penggajian/sisapayment', [Backend\PenggajianController::class, 'sisapayment'])->name('penggajian.sisapayment');
            Route::get('penggajian/pdf', [Backend\PenggajianController::class, 'pdf'])->name('penggajian.pdf');
            Route::get('penggajian/excel', [Backend\PenggajianController::class, 'excel'])->name('penggajian.excel');
            Route::get('penggajian/findpenggajian', [Backend\PenggajianController::class, 'findpenggajian'])->name('penggajian.findpenggajian');
            Route::get('penggajian/select2', [Backend\PenggajianController::class, 'select2'])->name('penggajian.select2');
            Route::resource('penggajian', Backend\PenggajianController::class);

            //PaymentGaji
            Route::put('paymentgaji/updatesingle', [Backend\PaymentGajiController::class, 'updatesingle'])->name('paymentgaji.updatesingle');
            Route::resource('paymentgaji', Backend\PaymentGajiController::class);


             //Mutasi Kasbon
             Route::get('mutasikasbon/pdf', [Backend\MutasiKasbonController::class, 'pdf'])->name('mutasikasbon.pdf');
            Route::get('mutasikasbon/excel', [Backend\MutasiKasbonController::class, 'excel'])->name('mutasikasbon.excel');
            Route::get('mutasikasbon/ceksaldo', [Backend\MutasiKasbonController::class, 'ceksaldo'])->name('mutasikasbon.ceksaldo');
            Route::get('mutasikasbon/datatablecekdriver', [Backend\MutasiKasbonController::class, 'datatablecekdriver'])->name('mutasikasbon.datatablecekdriver');
            Route::resource('mutasikasbon', Backend\MutasiKasbonController::class);

                   //Mutasi Kasbon all
                   Route::get('mutasikasbonall/pdf', [Backend\MutasiKasbonAllController::class, 'pdf'])->name('mutasikasbonall.pdf');
                   Route::get('mutasikasbonall/excel', [Backend\MutasiKasbonAllController::class, 'excel'])->name('mutasikasbonall.excel');
                   Route::get('mutasikasbonall/ceksaldo', [Backend\MutasiKasbonAllController::class, 'ceksaldo'])->name('mutasikasbonall.ceksaldo');
                   Route::get('mutasikasbonall/datatablecekdriver', [Backend\MutasiKasbonAllController::class, 'datatablecekdriver'])->name('mutasikasbonall.datatablecekdriver');
                   Route::resource('mutasikasbonall', Backend\MutasiKasbonAllController::class);

            //Laporan PaymentJo
            Route::post('rptjo/getreport', [Backend\RptJoController::class, 'getreport'])->name('rptjo.getreport');
            Route::get('rptjo/pdf', [Backend\RptJoController::class, 'pdf'])->name('rptjo.pdf');
            Route::get('rptjo/excel', [Backend\RptJoController::class, 'excel'])->name('rptjo.excel');
            Route::resource('rptjo', Backend\RptJoController::class);

             //Laporan PaymentGaji
             Route::post('rptgaji/getreport', [Backend\RptGajiController::class, 'getreport'])->name('rptgaji.getreport');
             Route::get('rptgaji/pdf', [Backend\RptGajiController::class, 'pdf'])->name('rptgaji.pdf');
             Route::get('rptgaji/excel', [Backend\RptGajiController::class, 'excel'])->name('rptgaji.excel');
             Route::resource('rptgaji', Backend\RptGajiController::class);
    });
  });
