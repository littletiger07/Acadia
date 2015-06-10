<?php
namespace Order\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\RowGateWayFeature;
use Zend\Db\Sql\Select, Zend\Db\Sql\Where;


class ItemTable extends TableGateway
{
    protected $table = 'po_item';
    public function __construct(Adapter $adapter)
    {
        $this->adapter=$adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new \Order\Model\ItemRow($adapter));
        $this->initialize();
    }
    
    public function getItemById($id)
    {
        $where=new Where();
        $where->equalTo('id',$id);
        $rowset=$this->select($where);
        $row=$rowset->current();
        return $row;
    }
    
    public function getItemsByOrderId($id)
    {
        $where = new Where();
        $where->equalTo('order_id',$id);
        $rowset=$this->select($where);
        return $rowset;
    }
    public function getItemByOrderIdAndCatalogNo($order_id, $catalogNo)
    {
        $where = new Where();
        $where->equalTo('order_id', $order_id);
        $where->equalTo('catalogNO', $catalogNo);
        $rowset=$this->select($where);
        return $rowset->current();
    }

}