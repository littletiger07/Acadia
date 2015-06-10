<?php
namespace Customer\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\RowGateWayFeature;
use Zend\Db\Sql\Select, Zend\Db\Sql\Where;


class CustomerTable extends TableGateway
{
    protected $table = 'customer';
    public function __construct(Adapter $adapter)
    {
        $this->adapter=$adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new \Customer\Model\CustomerRow($adapter));
        $this->initialize();
    }
    
    public function getCustomerById($id)
    {
        $where=new Where();
        $where->equalTo('id',$id);
        $rowset=$this->select($where);
        $row=$rowset->current();
        return $row;
    }
    
    public function getCustomerOptions()
    {
        $option= array();
        $rowset=$this->select();
        foreach($rowset as $row)
        {
            $option[$row->id] = $row->customer_name;
        }
        return $option;
    }
    public function getAllCustomer()
    {
        $select=new \Zend\Db\Sql\Select('customer');
        $select->order('customer_name');
        return $this->selectWith($select);
    }
}