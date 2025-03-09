<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TpsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CalonController;
use App\Http\Controllers\KorcamController;
use App\Http\Controllers\KorlurController;
use App\Http\Controllers\PartaiController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AgentTpsController;
use App\Http\Controllers\HakAksesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PerolehanSuaraController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/generate-geojson', [DashboardController::class, 'generateGeoJson'])->middleware('auth')->name('fetch-geojeson');
Route::get('/generate-geojson-kabupaten-tasik', [DashboardController::class, 'generateGeoJsonKabupatenTasik'])->middleware('auth')->name('fetch-geojson-kabupaten-tasik');
Route::get('/genererate-geojson-kabupaten-garut', [DashboardController::class, 'generateGeoJsonKabupatenGarut'])->middleware('auth')->name('fetch-geojson-kabupaten-garut');
Route::get('/test', [DashboardController::class, 'testExport'])->name('testExport')->middleware('auth');

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login/store', [AuthController::class, 'store'])->name('store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::group(['middleware' => ['auth']], function () {
    Route::post('/getKecamatan', [DataController::class, 'getKecamatans'])->name('getKecamatan');
    Route::post('/getKabkotas', [DataController::class, 'getKabkotas'])->name('getKabkotas');
    Route::post('/getKabkotasById/{id}', [DataController::class, 'getKabkotaById'])->name('getKabkotaById');
    Route::post('/getKelurahans', [DataController::class, 'getKelurahans'])->name('getKelurahans');
    Route::get('/getAllKelurahans', [DataController::class, 'getAllKelurahans'])->name('getAllKelurahans');
    Route::post('/getKorcams', [DataController::class, 'getKorcams'])->name('getKorcams');
    Route::post('/getKorlurs', [DataController::class, 'getKorlurs'])->name('getKorlurs');
    Route::post('/getTps', [DataController::class, 'getTps'])->name('getTps');
    Route::post('/getTpsById/{id}', [DataController::class, 'getTpsById'])->name('getTpsById');
    Route::post('/getTpsSatuan', [DataController::class, 'getTpsSatuan'])->name('getTpsSatuan');
    Route::post('/getWalkot', [DataController::class, 'getWalkot'])->name('getWalkot');
    Route::post('/getAgentTps', [DataController::class, 'getAgentTps'])->name('getAgentTps');
    Route::post('/getSuaraCalon', [DataController::class, 'getSuaraCalon'])->name('getSuaraCalon');
    Route::get('/getDpt', [DataController::class, 'getDpt'])->name('getDpt');
    Route::get('/list_suara_calon', [PerolehanSuaraController::class, 'list_suara_calon'])->name('list_suara_calon');



    Route::group(['prefix' => 'koordinator', 'as' => 'koordinator/'], function () {
        Route::prefix('korlur')->group(function () {
            Route::get('/', [KorlurController::class, 'index'])->name('korlur');
            Route::get('/create', [KorlurController::class, 'create'])->name('korlur/create');
            Route::post('/store', [KorlurController::class, 'store'])->name('korlur/store');
            Route::get('/edit/{id}', [KorlurController::class, 'edit'])->name('korlur/edit');
            Route::post('/update/{id}', [KorlurController::class, 'update'])->name('korlur/update');
            Route::get('/destroy/{id}', [KorlurController::class, 'destroy'])->name('korlur/destroy');
        });

        Route::prefix('korcam')->group(function () {
            Route::get('/', [KorcamController::class, 'index'])->name('korcam');
            Route::get('/create', [KorcamController::class, 'create'])->name('korcam/create');
            Route::post('/store', [KorcamController::class, 'store'])->name('korcam/store');
            Route::get('/{id}', [KorcamController::class, 'edit'])->name('korcam/edit');
            Route::post('/update/{id}', [KorcamController::class, 'update'])->name('korcam/update');
            Route::get('/destroy/{id}', [KorcamController::class, 'destroy'])->name('korcam/destroy');
        });

        Route::prefix('agent-tps')->group(function () {
            Route::get('/', [AgentTpsController::class, 'index'])->name('agent');
            Route::get('/create', [AgentTpsController::class, 'create'])->name('agent/create');
            Route::post('/store', [AgentTpsController::class, 'store'])->name('agent/store');
            Route::get('/edit/{id}', [AgentTpsController::class, 'edit'])->name('agent/edit');
            Route::post('/update/{id}', [AgentTpsController::class, 'update'])->name('agent/update');
            Route::get('/destroy/{id}', [AgentTpsController::class, 'destroy'])->name('agent/destroy');
            Route::get('/add_koordinator/{id}', [AgentTpsController::class, 'add_koordinator'])->name('agent/add_koordinator');
        });
        
        Route::prefix('anggota')->group(function () {
            Route::get('/', [AnggotaController::class, 'index'])->name('anggota');
            Route::get('/create', [AnggotaController::class, 'create'])->name('anggota/create');
            Route::post('/store', [AnggotaController::class, 'store'])->name('anggota/store');
            Route::get('/edit/{id}', [AnggotaController::class, 'edit'])->name('anggota/edit');
            Route::post('/update/{id}', [AnggotaController::class, 'update'])->name('anggota/update');
            Route::get('/edit/report/{id}', [AnggotaController::class, 'edit_report'])->name('anggota/report/edit');
            Route::post('/update/report/{id}', [AnggotaController::class, 'report_update'])->name('anggota/report/update');
            Route::get('/destroy/{id}', [AnggotaController::class, 'destroy'])->name('anggota/destroy');
        });
    });
    Route::group(['prefix' => 'report', 'as' => 'report/'], function () {
        //KORCAM REPORT
        Route::get('/korcam', [KorcamController::class, 'report'])->name('korcam');
        Route::get('/korcam/{id}', [KorcamController::class, 'report_detail'])->name('korcam/detail');
        Route::get('/korcam/pdf/{id}', [KorcamController::class, 'pdf'])->name('korcam/pdf');
        Route::get('/korcam/excel/{id}', [KorcamController::class, 'excel'])->name('korcam/excel');
        Route::get('/koordinator/general_excel', [KorcamController::class, 'generelExcel'])->name('korcam/general_excel');

        //KORLUR REPORT
        Route::get('/korlur', [KorlurController::class, 'report'])->name('korlur');
        Route::get('/korlur/{id}', [KorlurController::class, 'report_detail'])->name('korlur/detail');
        Route::get('/korlur/pdf/{id}', [KorlurController::class, 'pdf'])->name('korlur/pdf');
        Route::get('/korlur/excel/{id}', [KorlurController::class, 'excel'])->name('korlur/excel');
        Route::get('/kor-agent/genereal_excel', [KorlurController::class, 'general_excel'])->name('korlur/general/excel');

        //AGENT REPORT
        Route::get('/agent', [AgentTpsController::class, 'report'])->name('agent');
        Route::get('/agent/export', [AgentTpsController::class, 'exportTandaTerima'])->name('agent/export-agent');
        Route::get('/agent/{id}/{tps}', [AgentTpsController::class, 'report_detail'])->name('agent/detail');
        Route::get('/agent/pdf/download/{id}/{tps}', [AgentTpsController::class, 'pdf'])->name('agent/pdf/download');
        Route::get('/agent/excel/{id}/{tps}', [AgentTpsController::class, 'excel'])->name('agent/excel/download');
        Route::get('/agent/general_excel', [AgentTpsController::class, 'generalExcel'])->name('agent/general/excel');

         //Anggota
         Route::get('/anggota', [AnggotaController::class, 'report'])->name('anggota');
         Route::get('/anggota/lolos/{id}', [AnggotaController::class, 'verif_lolos'])->name('anggota/verif_lolos');
         Route::get('/anggota/gagal/{id}', [AnggotaController::class, 'verif_gagal'])->name('anggota/verif_gagal');
         Route::get('/anggota/excel', [AnggotaController::class, 'excel'])->name('anggota/excel');


        //KECAMATAN REPORT
        Route::get('/kecamatan', [KecamatanController::class, 'report'])->name('kecamatan');
        Route::get('/data_kecamatan', [KecamatanController::class, 'data_report_kecamatan'])->name('data_report_kecamatan');
        Route::get('/kecamatan/{id}', [KecamatanController::class, 'detailReport'])->name('kecamatan/detail');
        Route::get('/kecamatan/download/export', [KecamatanController::class, 'excel_report'])->name('kecamatan/excel/donwload');

        
        //KELURAHAN REPORT
        Route::get('/kelurahan', [KelurahanController::class, 'report'])->name('kelurahan');
        Route::get('/kelurahan/{id}', [KelurahanController::class, 'reportDetail'])->name('kelurahan/detail');
        Route::get('/kelurahan/download/export', [KelurahanController::class, 'excel_report'])->name('kelurahan/excel/donwload');


        //TPS REPORT
        Route::get('/tps', [TpsController::class, 'report'])->name('tps');
        Route::get('/tps/{id}', [TpsController::class, 'reportDetail'])->name('tps/detail');
        Route::get('/data/export', [TpsController::class, 'excel_report'])->name('tps/export');

        // WALI KOTA TASIK REPORT
        Route::get('/walkot-tasik', [PerolehanSuaraController::class, 'reportWalkotTasik'])->name('reportWalkotTasik');

        // BUPATI TASIK REPORT 
        Route::get('/bupati-tasik', [PerolehanSuaraController::class, 'reportBupatiTasik'])->name('reportBupatiTasik');

        // BUPATI GARUT REPORT
        Route::get('/bupati-garut', [PerolehanSuaraController::class, 'reportBupatiGarut'])->name('reportBupatiGarut');

        // GUBERNUR JAWA BARAT REPORT
        Route::get('/gubernur-jawabarat', [PerolehanSuaraController::class, 'reportGubernurJawabarat'])->name('reportGubernurJawabarat');
    });

    Route::group(['prefix' => 'cek_data', 'as' => 'cek_data/'], function () {
        Route::get('/korcam', [KorcamController::class, 'cek_data'])->name('korcam');
        Route::get('/korlur', [KorlurController::class, 'cek_data'])->name('korlur');
        Route::get('/agent', [AgentTpsController::class, 'cek_data'])->name('agent');

    });

    Route::group(['prefix' => 'log', 'as' => 'log/'], function () {
        Route::get('/korcam', [KorcamController::class, 'log'])->name('log/korcam');
        Route::get('/korlur', [KorlurController::class, 'log'])->name('log/korlur');
        Route::get('/agent', [AgentTpsController::class, 'log'])->name('log/agent');
        Route::get('/anggota', [AnggotaController::class, 'log'])->name('log/anggota');
        Route::get('/input_suara', [PerolehanSuaraController::class, 'log'])->name('log/input_suara');
    });


    Route::group(['prefix' => 'data', 'as' => 'data/'], function () {
        Route::prefix('partai')->group(function () {
            Route::get('/', [PartaiController::class, 'index'])->name('partai');
            Route::get('/create', [PartaiController::class, 'create'])->name('partai/create');
            Route::post('/store', [PartaiController::class, 'store'])->name('partai/store');
            Route::get('/edit/{id}', [PartaiController::class, 'edit'])->name('partai/edit');
            Route::post('/update/{id}', [PartaiController::class, 'update'])->name('partai/update');
            Route::get('/destroy/{id}', [PartaiController::class, 'destroy'])->name('partai/destroy');
        });

        Route::prefix('calon')->group(function () {
            Route::get('/', [CalonController::class, 'index'])->name('calon');
            Route::get('/create', [CalonController::class, 'create'])->name('calon/create');
            Route::post('/store', [CalonController::class, 'store'])->name('calon/store');
            Route::get('/edit/{id}', [CalonController::class, 'edit'])->name('calon/edit');
            Route::post('/update/{id}', [CalonController::class, 'update'])->name('calon/update');
            Route::post('/destroy/{id}', [CalonController::class, 'destroy'])->name('calon/destroy');
        });
    });
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user');
        Route::get('/create', [UserController::class, 'create'])->name('user/create');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('user/edit');
        Route::post('/store', [UserController::class, 'store'])->name('user/store');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('user/update');
        Route::post('/destroy/{id}', [UserController::class, 'destroy'])->name('user/destroy');
    });

    Route::group(['prefix' => 'konfigurasi', 'as' => 'konfigurasi/'], function () {

        Route::prefix('menu')->group(function(){
            Route::get('/', [MenuController::class, 'index'])->name('menu');
            Route::get('/create', [MenuController::class, 'create'])->name('menu/create');
            Route::get('/{menu}/edit', [MenuController::class, 'edit'])->name('menu/edit');
            Route::post('/update{id}', [MenuController::class, 'update'])->name('menu/update');
            Route::post('/store', [MenuController::class, 'store'])->name('menu/store');
            Route::put('/sorting', [MenuController::class, 'sort'])->name('menu/sort');
            Route::post('/{id}/destroy', [MenuController::class, 'destroy'])->name('menu/destroy');

        });

        Route::prefix('roles')->group(function(){
            Route::get('/', [RoleController::class, 'index'])->name('roles');
            Route::get('/create', [RoleController::class, 'create'])->name('roles/create');
            Route::post('/store', [RoleController::class, 'store'])->name('roles/store');
            Route::get('/edit/{id}', [RoleController::class, 'edit'])->name('roles/edit');
            Route::post('/update/{id}', [RoleController::class, 'update'])->name('roles/update');
            Route::delete('/destroy/{id}', [RoleController::class, 'destroy'])->name('roles/destroy');
        });

        Route::prefix('permissions')->group(function(){
            Route::get('/', [PermissionController::class, 'index'])->name('permissions');
            Route::get('/create', [PermissionController::class, 'create'])->name('permissions/create');
            Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('permissions/edit');
            Route::post('/store', [PermissionController::class, 'store'])->name('permissions/store');
            Route::post('/update/{id}', [PermissionController::class, 'update'])->name('permissions/update');
            Route::delete('/destroy/{id}', [PermissionController::class, 'destroy'])->name('permissions/destroy');
        });

        Route::prefix('hak-akses')->group(function(){
            Route::get('/', [HakAksesController::class, 'index'])->name('hak-akses');
            Route::get('/edit/hak-akses-role/{id}', [HakAksesController::class, 'editAksesRole'])->name('hak-akses/role/edit');
            Route::post('/update/role/{id}', [HakAksesController::class, 'updateAksesRole'])->name('hak-akses/role/update');
            Route::get('/edit/hak-akses-user/{id}', [HakAksesController::class, 'editAksesUser'])->name('hak-akses/user/edit');
            Route::post('/update/user/{id}', [HakAksesController::class, 'updateAksesUser'])->name('hak-akses/user/update');
        });
    });

    Route::group(['prefix' => 'input-suara', 'as' => 'input-suara/'], function(){

        Route::prefix('walkot-tasikmalaya')->group(function(){
            Route::get('/', [PerolehanSuaraController::class, 'index'])->name('input-suara');
            Route::post('/store', [PerolehanSuaraController::class, 'store'])->name('walkot/store');
        });

        Route::prefix('bupati-tasikmalaya')->group(function(){
            Route::get('/', [PerolehanSuaraController::class, 'indexBupatiTasikmalaya'])->name('input-suara-bupati-tasikmalaya');
            Route::post('/store', [PerolehanSuaraController::class, 'storeBupatiTasikmalaya'])->name('bupati-tasikmalaya/store');
        });

        Route::prefix('bupati-garut')->group(function(){
            Route::get('/', [PerolehanSuaraController::class, 'indexBupatiGarut'])->name('input-suara-bupati-garut');
            Route::post('/store', [PerolehanSuaraController::class, 'storeBupatiGarut'])->name('bupati-garut/store');
        });

        Route::prefix('gubernur-jawa-barat')->group(function(){
            Route::get('/', [PerolehanSuaraController::class, 'indexGubernurJawaBarat'])->name('input-suara-gubernur-jawa-barat');
            Route::post('/store', [PerolehanSuaraController::class, 'storeGubernurJawaBarat'])->name('gubernur-jawa-barat/store');
        });

        
    });

});
