<?php
function add_category($name)
{
    $category = CategoryQuery::create()->findOneByName($name);
    if (empty($category)) {
        $category = new Category();
        $category->setName($name);
        $affected_rows = $category->save();

        if ($affected_rows == 1)
            return true;
    }

    return false;
}

function update_category_by_id($id, $newName)
{
    $category = CategoryQuery::create()->findPk($id);

    if ($category !== null) {
        $category->setName($newName);
        $affected_rows = $category->save();
        if ($affected_rows > 0)
            return true;
    }

    return false;
}

function update_category_by_name($oldName, $newName) {
    $category = CategoryQuery::create()->findOneByName($oldName);

    if ($category !== null) {
        $category->setName($newName);
        $affected_rows = $category->save();
        if ($affected_rows > 0)
            return true;
    }

    return false;
}

function get_category_by_name($name)
{
    return CategoryQuery::create()->findOneByName($name);
}

function get_category_by_id($id)
{
    return CategoryQuery::create()->findPk($id);
}