<?php
namespace App\Models;

class Book extends \Core\Database\Elegant
{
    protected $fields = [
        "title",
        "author",
        "isbn"
    ];
}