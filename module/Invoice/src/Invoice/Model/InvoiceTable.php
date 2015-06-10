<?php
namespace Invoice\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\Reflection as ReflectionHydrator;


class InvoiceTable extends TableGateway
{
    protected $table = 'invoice';
    public function __construct(Adapter $adapter)
    {
        $this->adapter=$adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new \Invoice\Model\InvoiceRow($adapter));
        $this->initialize();
    }
    
    public function getInvoiceById($id)
    {
        $where=new \Zend\Db\Sql\Where();
        $where->equalTo('id',$id);
        $rowset=$this->select($where);
        $row=$rowset->current();

        return $row;
    }
    
    public function getOverdue()
    {
       

        $query='select * from invoice join po on invoice.order_id=po.id join customer on po.customer_id=customer.id where ADDDATE(invoice.invoice_date,
            INTERVAL customer.term DAY) < CURDATE() and invoice_status <> "paid"';
        $rowset=$this->adapter->query($query)->execute();
        if ($rowset instanceof ResultInterface && $rowset->isQueryResult())
        {
            $resultset= new HydratingResultSet(new ReflectionHydrator(), new \Invoice\Model\InvoiceData($this->adapter));
            $resultset->initialize($rowset);
        }

        return $resultset;
       
    }
    
    public function getOutstanding()
    {
        $sql=new \Zend\Db\Sql\Select('invoice');
        $sql->where->notEqualTo('invoice_status','paid');
        return $this->selectWith($sql);
        
    }

}