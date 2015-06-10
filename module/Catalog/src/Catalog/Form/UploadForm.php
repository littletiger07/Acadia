<?php 
namespace Catalog\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class UploadForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('csv_file');
        $file->setLabel('CSV File Upload')
             ->setAttribute('id', 'csv_file');
        $this->add($file);
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        // File Input
        $fileInput = new InputFilter\FileInput('csv_file');
        $fileInput->setRequired(true);
        $fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    =>CATALOG_PATH.'\tempUploads\catalog.csv',
                'randomize' => true,
            )
        );
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
}
