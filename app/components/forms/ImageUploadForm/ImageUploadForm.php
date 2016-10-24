<?php

namespace App\Components;

use App\Model\Entities\Image;
use App\Model\Entities\Tutorial;
use App\Model\ImageModel;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tracy\Debugger;

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
            $image = $this->imageModel->createImage($imageData, $this->presenter->getUser()->getId());
            $this->presenter->images[$image->getId()] = $image->getId();
        }

        foreach ($this->presenter->images as $id) {
            $this->images[] = $this->imageModel->getOne(Image::class, array("id" => $id));
        }

        Debugger::barDump($this->presenter->images);

        $this->template->images = $this->images;
        
        $this->redrawControl("images");
    }
    
    public function handleRemove($id)
    {
        unset($this->presenter->images[$id]);
        $this->imageModel->deleteImage($id);

        $this->images = null;

        foreach ($this->presenter->images as $id) {
            $this->images[] = $this->imageModel->getOne(Image::class, array("id" => $id));
        }

        $this->template->images = $this->images;

        Debugger::barDump($this->template->images);


        $this->redrawControl("images");
        Debugger::barDump($this->presenter->images);
    }

}

interface IImageUploadFormFactory
{
    /**
     * @param $id
     * @return ImageUploadForm
     */
    function create($id);
}