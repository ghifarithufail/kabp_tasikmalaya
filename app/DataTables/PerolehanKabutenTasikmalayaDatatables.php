<?php

namespace App\DataTables;

use App\Models\PerolehanKabutenTasikmalayaDatatable;
use App\Models\Tps;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PerolehanKabutenTasikmalayaDatatables extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('action', 'perolehankotatasikmalayadatatables.action')
            ->setRowId('id');

    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Tps $model): QueryBuilder
    {
        return $model->newQuery()
            ->select('tps.id AS tps_id', 'tps.tps', 'kelurahans.nama_kelurahan', 'kecamatans.nama AS nama_kecamatan', 'kelurahans.dapil AS dapil')
            ->leftJoin('kelurahans', 'tps.kelurahan_id', '=', 'kelurahans.id')
            ->leftJoin('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
            ->leftJoin('kabkotas', 'kelurahans.kabkota', '=', 'kabkotas.id')
            ->leftJoin('perolehan_suaras', 'perolehan_suaras.tps_id', '=', 'tps.id')
            ->leftJoin('calons', 'perolehan_suaras.caleg_id', '=', 'calons.id')
            ->where('kabkotas.id', '=', 498)
            ->groupBy('tps.id', 'tps.tps', 'kelurahans.nama_kelurahan')
            ->selectRaw('
            MAX(CASE WHEN calons.name = "Iwan Saputra - Dede Muksit Aly" THEN perolehan_suaras.total_suara ELSE 0 END) AS `Iwan Saputra - Dede Muksit Aly`,
            MAX(CASE WHEN calons.name = "Cecep Nurul Yakin - Asep Sopari Alayubi" THEN perolehan_suaras.total_suara ELSE 0 END) AS `Cecep Nurul Yakin - Asep Sopari Alayubi`,
            MAX(CASE WHEN calons.name = "Hj Ai - Iip Miftahul Faoz" THEN perolehan_suaras.total_suara ELSE 0 END) AS `Hj Ai - Iip Miftahul Faoz`
        ');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('perolehankotatasikmalayadatatables-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            // ->dom('Bfrtip')
            ->orderBy([0, 1,2,3,], 'asc' )
            ->selectStyleSingle()
            ->parameters([
                'searching' => false, // Nonaktifkan search box
                'orderMulti' => true, // Aktifkan multiple sorting
            ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            // Column::make('tps_id')->title('ID TPS')->searchable(false),
            Column::make('dapil')->title('Dapil')->searchable(false),
            Column::make('nama_kecamatan')->title('Kecamatan')->searchable(false),
            Column::make('nama_kelurahan')->title('Kelurahan')->searchable(false),
            Column::make('tps')->title('TPS')->searchable(false),
            Column::make("Iwan Saputra - Dede Muksit Aly")->title('Iwan Saputra & Dede Muksit Aly')->searchable(false),
            Column::make("Cecep Nurul Yakin - Asep Sopari Alayubi")->title('Cecep Nurul Yakin & Asep Sopari Alayubi')->searchable(false),
            Column::make("Hj Ai - Iip Miftahul Faoz")->title('Hj Ai & Iip Miftahul Faoz')->searchable(false),
            // Column::computed('action')
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'PerolehanKotaTasikmalayaDatatables_' . date('YmdHis');
    }
}
