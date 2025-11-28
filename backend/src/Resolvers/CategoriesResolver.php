<?php

namespace App\Resolvers;

use App\Database\Connection;
use App\Model\Category;

class CategoriesResolver
{
    public function categories(): array
    {
        $db = Connection::get();
        $categories = Category::getAll($db);
        return $categories;
    }
}
