<?php

namespace App\Components;

use App\Model\Entities\Image;
use App\Model\Entities\Tutorial;
use App\Model\ImageModel;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class ImageUploadForm extends Control{

    /** @var  ImageModel @inject */
    public $imageModel;

    private $images;

    private $id;

    public function __construct($id, ImageModel $imageModel)
    {
        parent::__construct();

        $this->imageModel = $imageModel;
        $this->id = $id;

        if ($this->id) {
            $tutorial = $this->imageModel->getOne(Tutorial::class, array("id" => $id));
            $this->images = $tutorial->getImages()->toArray();
        }
    }

    public function render()
    {
        if ($this->id) {
            $tutorial = $this->imageModel->getOne(Tutorial::class, array("id" => $this->id));
            $this->images = $tutorial->getImages()->toArray();
            $this->template->images = $this->images;
        }
        
        $template = $this->template;
        $template->setFile(__DIR__ . "/ImageUploadForm.latte");
        $template->render();

        
    }

    protected function createComponentForm()
    {
        $form = new Form();

        $form->addMultiUpload('images', 'Obrázky')
            ->setRequired()
            ->addRule(Form::IMAGE, 'Obrázek musí být JPEG, PNG nebo GIF.')
            ->addRule(Form::MAX_FILE_SIZE, 'Maximální velikost souboru je 2,5 MB.', 2.5 * 1024 * 1024);;

        $form->addSubmit("submit", "Nahrát obrázky");

        $form->onSuccess[] = array($this, "processForm");

        return $form;
    }

    public function processForm(Form $form)
    {
        $values = $form->getValues();

        foreach ($values["images"] as $imageData) {
            $this->images[] = $this->imageModel->createImage($imageData, $this->presenter->getUser()->getId());
        }

        $this->template->images = $this->images;
        $this->redrawControl();
    }
    
    public function handleRemove($id)
    {
        $image = $this->imageModel->getOne(Image::class, array("id" => $id));
        $target = "assets/images/uploads/" . $image->getName() . "." . $image->getExtension();

        if (!file_exists($target)) {
            $this->imageModel->remove($image);
        }
        
    }
    
//TODO: Delete image
}

interface IImageUploadFormFactory
{
    /** @return ImageUploadForm */
    function create($id);
}