const typeElement = ['header', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'hr', 'ul', 'li', 'a', 'br', 'span', 'img', 'audio', 'video', 'iframe', 'button', 'form', 'input', 'label', 'option', 'select', 'textarea', 'p'];

function createElement(type) {
    return document.createElement(type);
}

function selectElementById(id) {
    return document.getElementById(id);
}

function addAttribute(element, attrs) {
    for (var nameAttr in attrs) {
        element.setAttribute(nameAttr, attrs[nameAttr]);
    }
}

function innerText(element, text) {
    element.innerText = text;
}

function setClass(element, classHtml) {
    addAttribute(element, { 'class': classHtml });
}

function addElementByIdParent(idParent, element) {
    if (selectElementById(element.id) == null) {
        selectElementById(idParent).appendChild(element);
    } else if (selectElementById(idParent) == null) {
        console.log('encore un probleme ' + idParent);
    }
}

function displaySelectType() {
    var select = selectElementById('typeElement');
    for (var type of typeElement) {
        option = createElement('option');
        addAttribute(option, { 'value': type });
        innerText(option, type);
        select.appendChild(option);
    }
}

function displayElement(type, text, id, idParent) {
    var element = createElement(type);
    if (text.length > 0) {
        innerText(element, text);
    }
    addAttribute(element, { 'id': 'id_' + id });
    addElementByIdParent('id_' + idParent, element);
    console.log('element : ' + idParent);
}

function addElement() {
    parent = selectElementById('parent').value;
    type = selectElementById('typeElement').value;
    nom = selectElementById('name').value;
    /**
     * 
     */
    var element = createElement(type);
    innerText(element, nom);
    addElementByIdParent(parent, element);

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

//selectElementById('addElementBtn').addEventListener('click', function () { addElement() });

displaySelectType();