<?php


namespace App\Services;


/**
 * Class UnlinkFile
 * @package App\Services
 */
class UnlinkFile
{
    /**
     * @var
     */
    protected $entity;

    /**
     * UnlinkFile constructor.
     * @param $entity
     */
    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     *
     */
    public function delFile()
    {
        $fileName = $this->entity;
        if ($fileName !== "figure_default.jpeg") {
            unlink("uploads/figures/" . $fileName);
        }
    }
}