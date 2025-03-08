<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use App\Traits\HasMenuPermission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    use HasMenuPermission;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cache::forget('menus');
        /** 
         * @var Menu $mm
         */

        //  KONFIGURASI
        $mm = Menu::firstOrCreate(['url' => 'konfigurasi'],['name' => 'Konfigurasi', 'category' => 'KONFIGURASI','icon' => 'bx-cog']);
        $this->attachMenuPermission($mm, ['read '], ['admin']);
        
        $sm = $mm->subMenus()->create(['name' => 'Menu', 'url' => $mm->url . '/menu', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, ['create ', 'read ', 'update ', 'delete ', 'sort '], ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Role', 'url' => $mm->url . '/roles', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Permission', 'url' => $mm->url . '/permissions', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Hak Akses', 'url' => $mm->url . '/hak-akses', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        // END KONFIGURASI

        // KOORDINATOR
        $mm = Menu::firstOrCreate(['url' => 'koordinator'], ['name' => 'Koordinator', 'category' => 'KOORDINATOR', 'icon' => 'bxs-user-account']);
        $this->attachMenuPermission($mm, ['read '], ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Korcam', 'url' => $mm->url . '/korcam', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Koordinator Tps', 'url' => $mm->url . '/korlur', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Agent-Tps', 'url' => $mm->url . '/agent-tps', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Anggota', 'url' => $mm->url . '/anggota', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);
        // END KOORDINATOR

        // INPUT SUARA
        $mm = Menu::firstOrCreate(['url' => 'input-suara'], ['name' => 'Input Suara', 'category' => 'INPUT SUARA', 'icon' => 'bx-box']);
        $this->attachMenuPermission($mm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Wali Kota Tasikmalaya', 'url' => $mm->url . '/walkot-tasikmalaya', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Bupati Tasikmalaya', 'url' => $mm->url . '/bupati-tasikmalaya', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Bupati Garut', 'url' => $mm->url . '/bupati-garut', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Gubernur Jawa Barat', 'url' => $mm->url . '/gubernur-jawa-barat', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);
        // END INPUT SUARA

        // DATA
        $mm = Menu::firstOrCreate(['url' => 'data'], ['name' => 'Data', 'category' => 'DATA', 'icon' => 'bx-data']);
        $this->attachMenuPermission($mm, ['read '], ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Tim', 'url' => $mm->url . '/partai', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Calon', 'url' => $mm->url . '/calon', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);
        // END DATA

        // USERS
        $mm = Menu::firstOrCreate(['url' => 'users'], ['name' => 'Users', 'category' => 'USERS', 'icon' => 'bx-user']);
        $this->attachMenuPermission($mm, null, ['admin']);
        // END USERS

        // REPORT
        $mm = Menu::firstOrCreate(['url' => 'report'], ['name' => 'Report', 'category' => 'REPORT', 'icon' => 'bxs-report']);
        $this->attachMenuPermission($mm, null, ['admin']);
        
        $sm = $mm->subMenus()->create(['name' => 'Report Korcam', 'url' => $mm->url . '/korcam', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Report Koordinator Agent', 'url' => $mm->url . '/korlur', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Report Agent', 'url' => $mm->url . '/agent', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Report Anggota', 'url' => $mm->url . '/anggota', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Report Kecamatan', 'url' => $mm->url . '/kecamatan', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, ['create ', 'read ', 'update ', 'delete ', 'kota-tasikmalaya ', 'kabupaten-tasikmalaya ', 'kabupaten-garut '], ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Report Kelurahan', 'url' => $mm->url . '/kelurahan', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, ['create ', 'read ', 'update ', 'delete ', 'kota-tasikmalaya ', 'kabupaten-tasikmalaya ', 'kabupaten-garut '], ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Report Tps', 'url' => $mm->url . '/tps', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, ['create ', 'read ', 'update ', 'delete ', 'kota-tasikmalaya ', 'kabupaten-tasikmalaya ', 'kabupaten-garut '], ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Report Wali Kota Tasik', 'url' => $mm->url . '/walkot-tasik', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Report Bupati Tasik', 'url' => $mm->url . '/bupati-tasik', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Report Bupati Garut', 'url' => $mm->url . '/bupati-garut', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Report Gubernur Jawa Barat', 'url' => $mm->url . '/gubernur-jawabarat', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);
        // END REPORT

        // CEK DATA
        $mm = Menu::firstOrCreate(['url' => 'cek_data'], ['name' => 'Cek Data', 'category' => 'CEK DATA', 'icon' => 'bx-file-find']);
        $this->attachMenuPermission($mm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Cek Data Korcam', 'url' => $mm->url . '/korcam', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Cek Data Koordinator Agent', 'url' => $mm->url . '/korlur', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Cek Data agent', 'url' => $mm->url . '/agent', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);
        // END CEK DATA

        // LOG
        $mm = Menu::firstOrCreate(['url' => 'log'], ['name' => 'Log', 'category' => 'LOG', 'icon' => 'bx-file-find']);
        $this->attachMenuPermission($mm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Log Korcam', 'url' => $mm->url . '/korcam', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Log Koordinator Agent', 'url' => $mm->url . '/korlur', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Log Agent', 'url' => $mm->url . '/agent', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Log Anggota', 'url' => $mm->url . '/anggota', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);

        $sm = $mm->subMenus()->create(['name' => 'Log Input Suara', 'url' => $mm->url . '/input_suara', 'category' => $mm->category]);
        $this->attachMenuPermission($sm, null, ['admin']);
        // END LOG
    }
}
