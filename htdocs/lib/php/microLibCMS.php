<?php
include 'microLibSQL.php';

/**
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */

function createArticle($name, $id)
{
    $file = fopen($name, 'w');
    $element =  '<div id="id_main_' . $id . '"></div>';
    fwrite($file, $element);
}

function addArticleDB($db, $nameArticle)
{
    $db->insert('article', ['name' => $nameArticle]);
    return $db->select('article', ['id'], ['name' => $_POST['nameArticle']], [])->fetch()['id'];
}

function addElement($db, $name, $idParent, $type)
{
    $idArticle = $db->select('article', ['id'], ['name' => $_GET['article']], [])->fetch()['id'];
    $db->insert('element', ['name' => $name, 'idArticle' => $idArticle, 'text' => $name, 'idParent' => $idParent, 'type' => $type]);
}

function addAttrsElement($db, $idElement, $attrName, $attrValue)
{
    $db->insert('attribut', ['idElement' => $idElement, 'name' => $attrName, 'value' => $attrValue]);
}

function displayAll($db, $parent, $idArticle)
{
    echo $parent;
    echo $idArticle;
    $elements = $db->select('element', [], ['idParent' => $parent, 'idArticle' => $idArticle], ['AND']);
    while ($element = $elements->fetch()) {
        echo $element['type'] . '","' . $element['text'] . '",' . $element['id'] . ',' . $element['idParent'];
        echo '<script>displayElement("' . $element['type'] . '","' . $element['text'] . '",' . $element['id'] . ',"' . $element['idParent'] . '")</script>';
        $enfants = $db->select('element', [], ['idParent' => $element['id']], []);
        while ($enfant = $enfants->fetch()) {
            echo (displayAll($db, $element['id'], $idArticle));
        }
    }
}

/**
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */

if (isset($_POST['nameArticle'])) {
    $idArticle = addArticleDB($db, $_POST['nameArticle']);
    createArticle('articles/' . $_POST['nameArticle'] . '.php', $idArticle);
    header('location: index.php');
}

if (isset($_POST['remove'])) {
    $idArticle = $db->select('article', ['id'], ['name' => $_GET['article']], [])->fetch()['id'];
    $db->delete('article', ['name' => $_GET['article']], []);
    unlink('articles/' . $_GET['article'] . '.php');
    $db->delete('element', ['idArticle' => $idArticle], []);
    header('location: index.php');
}

if (isset($_POST['addElementBtn'])) {
    addElement($db, $_POST['newElement'], $_POST['parent'], $_POST['elementType']);
    header('location: index.php?article=' . $_GET['article']);
}
