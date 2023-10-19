<?php

declare(strict_types=1);

namespace App\Service;


class Table
{
    public $table = '';
    public $columnOrder = [];
    public $columnSearch = [];
    public $order = [];
    public $post = [];

    private $onTable;

    public $return;
    public $totalRow;
    public $totalFilter;

    public function __construct($table, $columnOrder = [], $columnSearch = [], $defaultOrder = ['id' => 'desc'])
    {
        $this->onTable = $table;
        $this->columnOrder = array_values($columnOrder);
        $this->columnSearch = array_values($columnSearch);
        $this->order = $defaultOrder;
    }

    private function _get_datatables_query()
    {
        if (isset($this->post['search']['value'])) {
            $this->onTable = $this->onTable->where(function ($q) {
                $i = 0;
                foreach ($this->post['search']['column'] as $c => $item) {
                    if (in_array($item, $this->columnSearch)) {
                        if ($i === 0) {
                            $q->where($item, 'like', "%{$this->post['search']['value']}%");
                        } else {
                            $q->orWhere($item, 'like', "%{$this->post['search']['value']}%");
                        }
                    }
                    $i++;
                }
            });
        }


        if (isset($this->post['order'])) {
            $temp = [];
            foreach ($this->post['order'] as $item) {
                if (in_array($item['column'], $this->columnOrder)) {
                    $temp[$item['column']] = $item['dir'];
                }
            }
            $this->onTable = $this->onTable->orderBy($temp);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->onTable = $this->onTable->orderBy(key($order), $order[key($order)]);
        }
    }

    function getDatatables()
    {
        $this->_get_datatables_query();
        if (isset($this->post['length'])) {
            if ($this->post['length'] > 0) {
                $this->onTable = $this->onTable->page($this->post['page'], $this->post['length']);
            }
        }
        $this->return = $this->onTable->get();
        return $this->return;
    }

    function countFiltered()
    {
        $this->totalFilter = count($this->return);
        return $this->totalFilter;
    }

    public function countAll()
    {
        $this->onTable = $this->onTable->addFieldCount($this->columnSearch[0], 'total');
        $this->_get_datatables_query();
        $return = $this->onTable->one();
        $this->totalRow = $return['total'];
        return $this->totalRow;
    }
}
