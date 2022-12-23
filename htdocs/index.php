<?php
include 'lib/php/microLibCMS.php';
if (isset($_GET['article'])) {
    $idArticle = $db->select('article', ['id'], ['name' => $_GET['article']], [])->fetch()['id'];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>micro CMS</title>
    <link rel="stylesheet" href="lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="lib/css/microLibCSS.css">
</head>

<body>
    <main>
        <div class="row">
            <div class="col col-2 text-center menuLeft">
                <a href="index.php">
                    <h1 class="mt-3 h1">MicroCMS</h1>
                </a>
                <hr>
                <div class="mt-4">
                    <form action="index.php" method="post">
                        <label for="nameArticle">
                            Ajouter un article
                        </label>
                        <div class="row m-2">
                            <input class="col col-12 mt-3" required minlength="3" type="text" name="nameArticle" placeholder="Nom de l'article">
                            <input class="col col-12 mt-3 addBtn button" type="submit" value="Ajouter">
                        </div>
                    </form>
                    <hr>
                </div>
                <div class="mt-3">
                    <label>
                        Vos articles
                    </label>
                    <div class="m-2 text-center">
                        <?php
                        $articles = $db->select('article', [], [], []);
                        while ($article = $articles->fetch()) {
                            $name = $article['name'];
                            if (isset($_GET['article']) && $idArticle == $article["id"]) {
                                echo '<a class="articleSelected linkArticle" href="index.php?article=' . $name . '">' . $name . '</a><br/>';
                            } else {
                                echo '<a class="linkArticle" href="index.php?article=' . $name . '">' . $name . '</a><br/>';
                            }
                        }
                        ?>
                    </div>
                    <div id="fileAction" class="mt-1">
                        <form action="#" method="post">
                            <div class="row m-2">
                                <button type="submit" name="remove" class="removeBtn button">Supprimer</button>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
                <div id="addElement" class="m-2">
                    <form action="#" method="post">
                        <label for="newElement">
                            Ajouter un élément
                        </label>
                        <input class="mt-3 w-100" placeholder="nom de l'élément" type="text" name="newElement" id="name" required minlength="3">
                        <input type="text" name="text" class="mt-1 w-100">
                        <select name="elementType" id="typeElement" required class="w-100 mt-1 selectCMS">
                            <option value="" default>Type d'élément</option>
                        </select>
                        <select name="parent" class="w-100 mt-1 selectCMS" id="parent" required>
                            <option value="" default>Element parent</option>
                            <option value="<?php
                                            echo 'main_' . $idArticle; ?>">Corp de page</option>
                            <?php
                            $elements = $db->select('element', [], ['idArticle' => $idArticle], []);
                            while ($element = $elements->fetch()) {
                                echo '<option value="' . $element['id'] . '">' . $element['name'] . '</option>';
                            }
                            ?>
                        </select>
                        <button type="submit" class="mt-1 w-100 addBtn button" id="addElementBtn" name="addElementBtn">Ajouter</button>
                    </form>
                </div>
            </div>
            <div class="col col-8" id="view">
                <?php
                if (isset($_GET['article'])) {
                    include 'articles/' . $_GET['article'] . '.php';
                }
                ?>
            </div>
            <div class="col col-2 text-center menuRight">
                <h3 class="mt-4">Outil de modification</h3>
                <hr>
                <div class="mt-3 m-2">
                    <form action="#" method="POST">
                        <label for="elementModif">Modifier</label>
                        <select name="elementModif" class="w-100 mt-1 selectCMS">
                            <option value="<?php echo 'main_' . $idArticle; ?>">Corp de page</option>
                            <?php
                            $elements = $db->select('element', [], ['idArticle' => $idArticle], []);
                            while ($element = $elements->fetch()) {
                                echo '<option value="' . $element['id'] . '">' . $element['name'] . '</option>';
                            }
                            ?>
                        </select>
                        <button type="submit" name="modifySelect" class="w-100 btnSelect mt-1 button">Selectionner</button>
                    </form>
                    <hr>
                    <?php
                    if (isset($_POST['elementModif'])) {
                        $nameElem = $db->select('element', ['name'], ['id' => $_POST['elementModif']], [])->fetch();
                        if ($nameElem != false) {
                            echo '<label>Element ' . $nameElem['name'] . '</label>';
                        } else {
                            echo '<label>Corp de page</label>';
                        }
                    }
                    ?>
                    <div class="mt-3">
                        <?php
                        if (isset($_POST['modifySelect'])) {
                        ?>
                            <div class="row">
                                <form action="#" method="POST">
                                    <input type="hidden" name="idElem" value="<?php echo $_POST['elementModif']; ?>">
                                    <button class="col col-5 m-1 button" type="submit" name="centrer">Centrer</button>
                                    <button class="col col-5 m-1 button" type="submit" name="addBorder">Bordures</button>
                                    <div class="col col-12">
                                        <div class="row m-2">
                                            <input type="text" name="textElement" placeholder="Contenu Text">
                                            <button class="button col col-12 mt-1" type="submit" name="modifText">Changer le text</button>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="col col-12">
                                        <div class="row mt-3 m-2">
                                            <button type="submit" name="diviser" class="button col col-12">Diviser</button>
                                            <input type="number" name="tailleDivision" min="1" max="12" class="col col-6 input mt-1">
                                            <button type="submit" name="tailleOk" class="col col-6 mt-1 button">Ok</button>
                                            <select name="whereMove" class="input selectCMS col col-12 mt-1">
                                                <?php
                                                echo '<option value="main_' . $idArticle . '">Corp de page</option>';
                                                $elements = $db->select('element', [], ['idArticle' => $idArticle], []);
                                                while ($element = $elements->fetch()) {
                                                    echo '<option value="' . $element['id'] . '">' . $element['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <button type="submit" name="moveTo" class="button col col-12 mt-1 mb-1">Déplacer</button>
                                        </div>
                                        <hr>
                                        <button class="col col-12 button removeBtn" type="submit" name="suprElement">Supprimer</button>
                                    </div>
                            </div>
                            </form>
                    </div>
                <?php
                        }
                ?>
                </div>
            </div>
        </div>
        </div>
    </main>
    <script src="lib/js/microLibCMS.js"></script>
    <?php displayAll($db, 'main_' . $idArticle, $idArticle);
    if (isset($_POST['elementModif'])) {
        echo '<script>addAttribute(selectElementById("id_' . $_POST['elementModif'] . '"), {"style": "border: solid 4px green;"})</script>';
    }
    ?>
</body>

</html>