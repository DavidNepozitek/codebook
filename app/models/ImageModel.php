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

    public function deleteImage()
    {
        
    }

    //TODO: delete unused images on logout
}