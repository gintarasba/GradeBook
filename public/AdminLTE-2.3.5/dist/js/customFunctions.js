function showMessageBox(parentDiv, errorsList, errorType, speed) {
    var defaultSpeed = 1000;
    if(typeof speed !== 'undefined') {
        defaultSpeed = speed;
    }
    var errorsHtml = '';
    for(var key in errorsList) {
        var error = errorsList[key];

        errorsHtml += error+"<br/>";
    }

    var element = $('<div class="alert alert-'+errorType+' newAllert">'+errorsHtml+'</div>');
    $(parentDiv).prepend(element);
    element.delay( 1000 ).fadeOut( "300", function() {
        element.remove();
    });
}

function destroyOldTooltips(elementsIds) {
    for(var key in elementsIds) {
        var element = elementsIds[key];
        $(element).tooltip("destroy").removeClass('inputError');
    }
}

function showInputTooltips(errorsList) {
    for(var key in errorsList) {
        var error = errorsList[key];
        showInputError('#' + key, error[0]);
    }

}

function showInputError(divId, errorMessage) {
    console.log(divId, errorMessage);
    var element = $(divId);
    element.tooltip("destroy") // Destroy any pre-existing tooltip so we can repopulate with new tooltip content
              .data("title", errorMessage)
              .addClass("inputError")
              .tooltip({trigger: 'manual'})
              .tooltip('show');
}

function clearInputsValues(inputsList) {
    for(var key in inputsList) {
        var input = inputsList[key];
        element = $(input);
        element.val('');
    }
}
