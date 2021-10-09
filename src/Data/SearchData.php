<?php

namespace App\Data;

use App\Entity\Genre;

class SearchData
{
    /**
     * @var int
     */
    public $page = 1;

    /**
     * @var string|null
     */
    public $q ='';

    /**
     * @var Genre[]
     */
    public $genres = [];

    /**
     * @var boolean
     */
    public $status = false;
}