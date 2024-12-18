<?php

namespace Webkul\RMA\DataGrids\Admin;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class RmaStatusDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('rma_status')
            ->addSelect(
                'id',
                'title',
                'color',
                'status',
            );

        $this->addFilter('id', 'rma_status.id');
        
        return $queryBuilder;
    }

    /**
     * Prepare columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('rma::app.admin.sales.rma.reasons.index.datagrid.id'),
            'type'       => 'integer',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('rma::app.admin.sales.rma.reasons.index.datagrid.reason'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'color',
            'label'      => trans('admin::app.catalog.attributes.create.color'),
            'type'       => 'dropdown',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
            'options'    => [
                'type' => 'searchable',
        
                'params' => [
                    'repository' => \Webkul\RMA\Repositories\RMAStatusRepository::class,
                    'column'     => [
                        'label' => 'color',
                        'value' => 'color',
                    ],
                ],
            ],
            'closure'    => function ($row) {
                if ($row->color) {
                    return '<p class="label-active" style="background: ' . $row->color . ';">' . $row->color . '</p>';
                }                
            },
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('rma::app.admin.sales.rma.reasons.index.datagrid.status'),
            'type'       => 'dropdown',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => true,
            'options'    => [
                'type' => 'basic',
        
                'params' => [
                    'options' => [
                        [
                            'label' => trans('rma::app.admin.sales.rma.reasons.index.datagrid.enabled'),
                            'value' => '1',
                        ], [
                            'label' => trans('rma::app.admin.sales.rma.reasons.index.datagrid.disabled'),
                            'value' => '0',
                        ],
                    ],
                ],
            ],
            'closure'    => function ($row) {
                if ($row->status) {
                    return '<p class="label-active">'.trans('rma::app.admin.sales.rma.reasons.index.datagrid.enabled').'</p>';
                }

                return '<p class="label-canceled">'.trans('rma::app.admin.sales.rma.reasons.index.datagrid.disabled').'</p>';
            },
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('rma.reason.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('rma::app.shop.customer-rma-index.edit'),
                'type'   => 'Edit',
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.sales.rma.rma-status.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('rma.reason.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('rma::app.shop.customer-rma-index.delete'),
                'type'   => 'Delete',
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.sales.rma.rma-status.delete', $row->id);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        $this->addMassAction([
            'title'   => trans('rma::app.shop.customer-rma-index.update'),
            'method'  => 'POST',
            'url'     => route('admin.sales.rma.rma-status.mass-update'),
            'options' => [
                [
                    'label' => trans('rma::app.admin.sales.rma.reasons.index.datagrid.enabled'),
                    'value' => 1,
                ], [
                    'label' => trans('rma::app.admin.sales.rma.reasons.index.datagrid.disabled'),
                    'value' => 0,
                ],
            ],
        ]);

        $this->addMassAction([
            'title'  => trans('rma::app.shop.customer-rma-index.delete'),
            'method' => 'POST',
            'url'    => route('admin.sales.rma.rma-status.mass-delete'),
        ]);
    }
}