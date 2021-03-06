<?php


namespace App\Services;


class ImageUploadHelper
{
    /**
     * @param $imageFile
     * @param $entity
     * @param $urlImageField
     * @return string
     */
    public function imageUploadTest($imageFile, $entity, $urlImageField)
    {

        //  if ($imageFile) {
        $imageFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = $imageFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();


        // updates the 'picture' field property to store the jpeg file name
        // instead of its contents
        $entity->$urlImageField($newFilename);

        //  }
        return $newFilename;
    }
}