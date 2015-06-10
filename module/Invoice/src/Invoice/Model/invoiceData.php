<?php
namespace Invoice\Model;

class InvoiceData extends InvoiceRow
{
    public $id,$order_id,$invoice_number,$invoice_date,$invoice_status,$invoice_comment;
}