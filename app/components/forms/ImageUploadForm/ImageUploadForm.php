<?php

namespace App\Components;

use App\Model\Entities\Image;
use App\Model\Entities\Tutorial;
use App\Model\ImageModel;
use App\Model\RedirectHelper;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tracy\Debugger;

class ImageUploadForm extends Control{

    /** @var ImageModel */
    private $imageModel;

    /** @var  RedirectHelper */
    private $redirectHelper;

    private $images;

    private $id;

    public function __construct(ImageModel $imageModel, RedirectHelper $redirectHelper)
    {
        parent::__construct();

        $this->imageModel = $imageModel;
        $this->redirectHelper = $redirectHelper;
    }

    public function render($id = NULL)
    {
        $this->id = $id;

        if ($this->id) {
            $tutorial = $this->imageModel->getOne(Tutorial::class, array("id" => $this->id));

            foreach ($tutorial->getImages()->toArray() as $image) {
                $this->presenter->images[$image->getId()] = $image->getId();
            }

            $this->template->images = $tutorial->getImages()->toArray();
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
            $image = $this->imageModel->createImage($imageData);
            $this->presenter->images[$image->getId()] = $image->getId();
        }

        Debugger::barDump($this->presenter->images);
        $this->updateTemplateImages();

        $this->redirectHelper->setRedirect(NULL, FALSE);
    }
    
    public function handleRemove($id)
    {
        unset($this->presenter->images[$id]);

        $this->imageModel->deleteImage($id);

        foreach ($this->presenter->images as $id) {
            $images[] = $this->imageModel->getOne(Image::class, array("id" => $id));
        }

        $this->updateTemplateImages();
    }

    public function updateTemplateImages()
    {
        $images = Array();
        
        foreach ($this->presenter->images as $id) {
            $image = $this->imageModel->getOne(Image::class, array("id" => $id));
            $images[] = $image;
        }

        $this->template->images = $images;
        $this->presenter->redrawControl("imageUploadForm");
    }

}

interface IImageUploadFormFactory
{
    /**
     * @return ImageUploadForm
     */
    function create();
}