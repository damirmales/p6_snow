<?php


namespace App\Services;


class UnlinkFile
{
    protected $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    public function delFile()
    {
        $fileName = $this->entity;
        if ($fileName !== "figure_default.jpeg") {
            unlink("uploads/figures/" . $fileName);
        }
    }
}