<?php

namespace App\Model;


use App\Model\Entities\Image;
use App\Model\Entities\User;
use Nette\Http\FileUpload;

class ImageModel extends BaseModel
{

    public function createImage(FileUpload $imageData, $userId)
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

        $user = $this->getOne(User::class, array("id" => $userId));

        $target = "assets/images/uploads/" . $name . "." . $extension;

        $image = new Image();
        $image->setUser($user);
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
        $image = $this->getOne(Image::class, array("id" => $id));
        $target = "assets/images/uploads/" . $image->getName() . "." . $image->getExtension();

        unlink($target);

        if (!file_exists($target)) {
            $this->remove($image);
            $this->flush();
        }
    }
    
}