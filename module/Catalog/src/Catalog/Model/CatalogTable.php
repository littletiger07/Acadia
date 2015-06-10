<?php
namespace Catalog\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\RowGateWayFeature;
use Zend\Db\Sql\Select, Zend\Db\Sql\Where;


class CatalogTable extends TableGateway
{
    protected $table = 'catalog';
    public function __construct(Adapter $adapter)
    {
        $this->adapter=$adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new CatalogRow($adapter));
        $this->initialize();
    }
    
    public function getItemByCatalogNo($catalogNo)
    {
        $where=new Where();
        $where->equalTo('catalogNo',$catalogNo);
        $rowset=$this->select($where);
        $row=$rowset->current();
        return $row;
    }

}