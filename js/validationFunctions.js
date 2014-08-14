function hasPresence(value) {
    return value.trim().length != 0;
}

function hasMaxLength(value, max) {
    return value.trim().length <= max;
}

function hasMinLength(value, min) {
    return value.trim().length >= min;
}

function isNumberBetween(value, min, max) {
    var intValue = parseInt(value.trim());
    if($.isNumeric(value)) {
        return intValue >= min && intValue <= max;
    }
    return false;
}