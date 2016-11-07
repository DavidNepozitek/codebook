<?php

namespace App\Model;


use App\Model\Entities\Image;
use Nette\Http\FileUpload;
use Tracy\Debugger;

class ImageModel extends BaseModel
{

    public function createImage(FileUpload $imageData)
    {

        $name = uniqid("img_");

        switch ($imageData->getContentType()) {
            case "image/jpeg":
                $extension = "jpeg";
                break;
            case "image/png":
                $extension = "png";
                break;
            case "image/gif":
                $extension = "gif";
                break;
        }
        

        $target = "assets/images/uploads/" . $name . "." . $extension;

        $image = new Image();
        $image->setName($name);
        $image->setExtension($extension);

        move_uploaded_file($imageData->getTemporaryFile(), $target);

        if(file_exists($target)) {
            $this->persist($image);
            $this->flush();
        }

        return $image;
    }

    public function deleteImage($id)
    {

        if (is_array($id)) {
            foreach ($id as $imageId) {
                $image = $this->getOne(Image::class, array("id" => $imageId));

                if ($image) {
                    $target = "assets/images/uploads/" . $image->getName() . "." . $image->getExtension();

                    if(file_exists($target)){
                        unlink($target);
                    }

                    if (!file_exists($target)) {
                        $this->remove($image);
                    }
                }
            }
        } else {
            $image = $this->getOne(Image::class, array("id" => $id));

            if ($image) {
                $target = "assets/images/uploads/" . $image->getName() . "." . $image->getExtension();

                if(file_exists($target)){
                    unlink($target);
                }

                if (!file_exists($target)) {
                    $this->remove($image);
                }
            }
        }

        $this->flush();
    }

    public function purgeImages()
    {
        $imageIds = array();
        $time = new \DateTime();
        $time->modify("-1 day");

        $query = $this->getEm()->createQuery('
            SELECT i
            FROM App\Model\Entities\Image i
            WHERE i.tutorial is NULL
            AND i.upDate < :time'
        )->setParameter('time', $time);
        $images = $query->getResult();

        foreach ($images as $image) {
            $imageIds[] = $image->getId();
        }

        $this->deleteImage($imageIds);
    }
    
}