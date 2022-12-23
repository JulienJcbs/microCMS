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
    $db->insert('element', ['name' => $name, 'idArticle' => $idArticle, 'text' => $_POST['text'], 'idParent' => $idParent, 'type' => $type]);
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

        $attributs = $db->select('attributs', [], ['idElement' => $element['id']], []);
        while ($attribut = $attributs->fetch()) {
            echo '<script>setClass(selectElementById("id_' . $attribut['idElement'] . '"), "' . $attribut['value'] . '")</script>';
        }

        $enfants = $db->select('element', [], ['idParent' => $element['id']], []);
        while ($enfant = $enfants->fetch()) {
            echo (displayAll($db, $element['id'], $idArticle));
        }
    }
}

function addClassName($db, $classMore, $idElement)
{
    $attribut = $db->select('attributs', ['id', 'value'], ['idElement' => $idElement, 'name' => 'class'], ['AND'])->fetch();
    if ($attribut == false) {
        insertAttribute($db, 'class', $classMore, $idElement);
    } else {
        $valeur = $attribut['value'];
        $valeur .= ' ' . $classMore . ' ';
        $db->update('attributs', ['value' => $valeur], ['id' => $attribut['id']], []);
    }
}

function insertAttribute($db, $name, $value, $idElement)
{
    $db->insert('attributs', ['name' => $name, 'value' => $value, 'idElement' => $idElement]);
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

if (isset($_POST['centrer'])) {
    addClassName($db, 'text-center me-auto', $_POST['idElem']);
    header('location: index.php?article=' . $_GET['article']);
}

if (isset($_POST['suprElement'])) {
    $db->delete('element', ['id' => $_POST['idElem']], []);
    $db->delete('attributs', ['idElement' => $_POST['idElem']], []);
    header('location: index.php?article=' . $_GET['article']);
}

if (isset($_POST['addBorder'])) {
    addClassName($db, 'border border-2', $_POST['idElem']);
}

if (isset($_POST['diviser'])) {
    addClassName($db, 'row', $_POST['idElem']);
}

if (isset($_POST['modifText'])) {
    $db->update('element', ['text' => $_POST['textElement']], ['id' => $_POST['idElem']], []);
}
