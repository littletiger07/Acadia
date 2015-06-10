<?php
namespace Quote\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\TableGateway\Feature\RowGateWayFeature;
use Zend\Db\Sql\Select, Zend\Db\Sql\Where;


class QuoteTable extends TableGateway
{
    protected $table = 'quote';
    public function __construct(Adapter $adapter)
    {
        $this->adapter=$adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new QuoteRow($adapter));
        $this->initialize();
    }
    
    public function getQuotesByCas($cas)
    {
       	$where=new Where();
		$where->like('quote_cas','%'.$cas.'%');
		$rowset=$this->select($where);
        return $rowset;
    }

}