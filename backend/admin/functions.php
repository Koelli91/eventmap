<?php
function add_category($name) {
    $count = CategoryQuery::create()
        ->filterByName($name)
        ->count();
    if ($count > 0) {
        return false;
    } else {
        $category = new Category();
        $category->setName($name);
        $affected_rows = $category->save();

        if ($affected_rows == 1)
            return true;
        else
            return false;
    }
}