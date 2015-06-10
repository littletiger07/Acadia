<?php
namespace Catalog\Model;
use Zend\Db\RowGateway\RowGateway;
class Catalog
{
    public $id;
    public $catalogNo;
    public $name;
    public $cas;
    public $purity;
    public $package_size1;
    public $price1;
    public $package_size2;
    public $price2;
    public $formula;
    public $mw;
    public $smile;
    public $comment;
    public $appearance;
    public $color;
    
    public function exchangeArray($data)
    {
        $this->id = (isset($data['id']))? $data['id']:null;
        $this->catalogNo = (isset($data['catalogNo']))? $data['catalogNo']:null;
        $this->name = (isset($data['name']))? $data['name']:null;
        $this->cas = (isset($data['cas']))? $data['cas']:null;
        $this->purity = (isset($data['purity']))? $data['purity']:null;
        $this->package_size1 = (isset($data['package_size1']))? $data['package_size1']:null;
        $this->price1 = (isset($data['price1']))? $data['price1']:null;
        $this->packagesize2 = (isset($data['packagesize2']))? $data['packagesize2']:null;
        $this->price2 = (isset($data['price2']))? $data['price2']:null;
        $this->formula = (isset($data['formula']))? $data['formula']:null;
        $this->mw = (isset($data['mw']))? $data['mw']:null;
        $this->smile = (isset($data['smile']))? $data['smile']:null;
        $this->comment = (isset($data['comment']))? $data['comment']:null;
        $this->appearance = (isset($data['appearance']))? $data['appearance']:null;
        $this->color = (isset($data['color']))? $data['color']:null;
    }
}

