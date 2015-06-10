<?php
namespace Order\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\RowGateWayFeature;
use Zend\Db\Sql\Select, Zend\Db\Sql\Where;


class ShippingTable extends TableGateway
{
    protected $table = 'shipping';
    public function __construct(Adapter $adapter)
    {
        $this->adapter=$adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new \Order\Model\ShippingRow($adapter));
        $this->initialize();
    }
    
    public function getShippingById($id)
    {
        $where=new Where();
        $where->equalTo('id',$id);
        $rowset=$this->select($where);
        $row=$rowset->current();
        return $row;
    }
    
    public function getShippingsByOrderId($id)
    {
        $where=new where();
        $where->equalTo('order_id',$id);
        $rowset=$this->select($where);
        return $rowset;
    }
    
    
}