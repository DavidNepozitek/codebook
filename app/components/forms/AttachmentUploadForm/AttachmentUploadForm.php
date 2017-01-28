<?php

namespace App\Components;

use App\Model\Entities\Attachment;
use App\Model\Entities\Tutorial;
use App\Model\AttachmentModel;
use App\Model\RedirectHelper;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tracy\Debugger;

class AttachmentUploadForm extends Control{

    /** @var attachmentModel */
    private $attachmentModel;

    /** @var  RedirectHelper */
    private $redirectHelper;

    private $attachments;

    /** @persistent */
    public $id;

    public function __construct(AttachmentModel $attachmentModel, RedirectHelper $redirectHelper)
    {
        parent::__construct();

        $this->attachmentModel = $attachmentModel;
        $this->redirectHelper = $redirectHelper;
    }

    public function render($id = NULL)
    {
        $this->id = $id;

        $this->updateTemplateAttachments(FALSE);

        $template = $this->template;
        $template->setFile(__DIR__ . "/AttachmentUploadForm.latte");
        $template->render();
        
    }

    protected function createComponentForm()
    {
        $form = new Form();

        $form->addMultiUpload('attachments', 'Přílohy')
            ->setRequired()
            ->addRule(Form::MAX_FILE_SIZE, 'Maximální velikost souboru je 2 MB.', 2 * 1024 * 1024);
        $form->addInteger("id", "id");

        if(isset($this->id)) {
            $form->setDefaults(array("id" => $this->id));
        }

        $form->addSubmit("submit", "Nahrát obrázky");

        $form->onSuccess[] = array($this, "processForm");

        return $form;
    }

    public function processForm(Form $form)
    {
        $values = $form->getValues();

        if(isset($values["id"])) {
            $tutorial = $this->attachmentModel->getOne(Tutorial::class, array("id" => $values["id"]));
        }

        foreach ($values["attachments"] as $attachmentData) {
            $attachment = $this->attachmentModel->createAttachment($attachmentData);
            $this->presenter->attachments[$attachment->getId()] = $attachment->getId();

            if(isset($tutorial)) {
                $attachment->setTutorial($tutorial);
            }
        }

        $this->attachmentModel->flush();
        
        $this->updateTemplateAttachments();

        $this->redirectHelper->setRedirect(NULL, FALSE);
    }
    
    public function handleRemove($id)
    {
        unset($this->presenter->attachments[$id]);

        $this->attachmentModel->deleteAttachment($id);

        foreach ($this->presenter->attachments as $id) {
            $attachments[] = $this->attachmentModel->getOne(Attachment::class, array("id" => $id));
        }

        $this->updateTemplateAttachments();
    }

    public function updateTemplateAttachments($redraw = TRUE)
    {
        $attachments = Array();
        
        foreach ($this->presenter->attachments as $id) {
            $attachment = $this->attachmentModel->getOne(Attachment::class, array("id" => $id));
            $attachments[] = $attachment;
        }

        if(!empty($attachments)) {
            $this->template->attachments = $attachments;
        }

        if ($redraw) {
            $this->presenter->redrawControl("attachmentUploadForm");
        }
    }
}

interface IAttachmentUploadFormFactory
{
    /**
     * @return AttachmentUploadForm
     */
    function create();
}