<?php


namespace App\Services;


class PaginationParam
{

    private $startPageNumber;
    private $pageItemLimit;
    private $numberOfItemPerPage;


    public function __construct($start, $limit, $itemPerPage)
    {
        $this->startPageNumber = $start;
        $this->pageItemLimit = $limit;
        $this->numberOfItemPerPage = $itemPerPage;
    }

    public function paginationOffset()
    {
        $paginationOffset = $this->getStartPageNumber() * $this->getPageItemLimit() - $this->getPageItemLimit();

        return $paginationOffset;
    }

    /**
     * @return mixed
     */
    public function getStartPageNumber()
    {
        return $this->startPageNumber;
    }

    /**
     * @param mixed $startPageNumber
     */
    public function setStartPageNumber($startPageNumber): void
    {
        $this->startPageNumber = $startPageNumber;
    }

    /**
     * @return mixed
     */
    public function getPageItemLimit()
    {
        return $this->pageItemLimit;
    }

    /**
     * @param mixed $pageItemLimit
     */
    public function setPageItemLimit($pageItemLimit): void
    {
        $this->pageItemLimit = $pageItemLimit;
    }

    /**
     * @return mixed
     */
    public function getNumberOfItemPerPage()
    {
        return $this->numberOfItemPerPage;
    }

    /**
     * @param mixed $numberOfItemPerPage
     */
    public function setNumberOfItemPerPage($numberOfItemPerPage): void
    {
        $this->numberOfItemPerPage = $numberOfItemPerPage;
    }


}