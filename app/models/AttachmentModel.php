<?php

namespace App\Model;


use App\Model\Entities\Attachment;
use Nette\Http\FileUpload;
use Nette\Neon\Exception;
use Tracy\Debugger;

class AttachmentModel extends BaseModel
{

    const INVALID_FORMAT = 0;

    const UPLOAD_PATH = "assets/attachments/";


    /**
     * Creates an attachment entity, assigns a name to the attachment and uploads it
     *
     * @param FileUpload $fileData
     * @return Attachment
     */
    public function createAttachment(FileUpload $fileData)
    {

        $name = uniqid("att_");

        switch ($fileData->getContentType()) {
        case "image/jpeg":
            $extension = "jpeg";
            break;
        case "image/png":
            $extension = "png";
            break;
        case "image/gif":
            $extension = "gif";
            break;
        case "application/pdf":
            $extension = "pdf";
            break;
        default:
            throw new \Exception(
                "Nahraný soubor musí být obrázek nebo PDF", 
                $this::INVALID_FORMAT
            );
        }
        

        $target = "assets/attachments/" . $name . "." . $extension;

        $attachment = new Attachment();
        $attachment->setName($name);
        $attachment->setExtension($extension);

        move_uploaded_file($fileData->getTemporaryFile(), $target);

        if (file_exists($target)) {
            $this->persist($attachment);
            $this->flush();
        }

        return $attachment;
    }

    /**
     * Deletes an attachment with given ID 
     * OR deletes all attachments from the array of IDs as a parameter
     * 
     * @param $id
     */
    public function deleteAttachment($id)
    {

        if (is_array($id)) {
            foreach ($id as $attachmentId) {
                $attachment = $this->getOne(
                    Attachment::class, 
                    array("id" => $attachmentId)
                );

                if ($attachment) {
                    $target = $this::UPLOAD_PATH . 
                        $attachment->getName() . "." . $attachment->getExtension();

                    if (file_exists($target)) {
                        unlink($target);
                    }

                    if (!file_exists($target)) {
                        $this->remove($attachment);
                    }
                }
            }
        } else {
            $attachment = $this->getOne(Attachment::class, array("id" => $id));

            if ($attachment) {
                $target = $this::UPLOAD_PATH . 
                    $attachment->getName() . "." . $attachment->getExtension();

                if (file_exists($target)) {
                    unlink($target);
                }

                if (!file_exists($target)) {
                    $this->remove($attachment);
                }
            }
        }

        $this->flush();
    }

    /**
     * Deletes all attachments, which aren't assigned to a tutorial
     * and are at least 1 day old
     */
    public function purgeAttachments()
    {
        $attachmentIds = array();
        $time = new \DateTime();
        $time->modify("-1 day");

        $query = $this->getEm()->createQuery(
            'SELECT i
            FROM App\Model\Entities\Attachment i
            WHERE i.tutorial is NULL
            AND i.upDate < :time
            ')
            ->setParameter('time', $time);
        $attachments = $query->getResult();

        foreach ($attachments as $attachment) {
            $attachmentIds[] = $attachment->getId();
        }

        $this->deleteAttachment($attachmentIds);
    }
    
}