function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
function KeycheckOnlyPhonenumber(e) {
    var KeyID = getKeyID(e);
    return (!((KeyID >= 65 && KeyID <= 90) || (KeyID >= 97 && KeyID <= 122) || (KeyID >= 33 && KeyID <= 39) || (KeyID >= 42 && KeyID <= 42) || (KeyID == 44) || (KeyID >= 46 && KeyID <= 47) || (KeyID >= 58 && KeyID <= 64) || (KeyID >= 91 && KeyID <= 96) || (KeyID >= 123 && KeyID <= 126)));
}

function KeycheckOnlyNumeric(e) {
    var KeyID = getKeyID(e);
    return (!((KeyID >= 65 && KeyID <= 90) || (KeyID >= 97 && KeyID <= 122) || (KeyID >= 33 && KeyID <= 39) || (KeyID >= 42 && KeyID <= 42) || (KeyID == 44) || (KeyID == 43) || (KeyID == 45) || (KeyID >= 46 && KeyID <= 47) || (KeyID >= 58 && KeyID <= 64) || (KeyID >= 91 && KeyID <= 96) || (KeyID >= 123 && KeyID <= 126)));
}

function KeycheckOnlyPrice(e) {
    var KeyID = getKeyID(e);
    return (!((KeyID >= 65 && KeyID <= 90) || (KeyID >= 97 && KeyID <= 122) || (KeyID >= 33 && KeyID <= 39) || (KeyID >= 42 && KeyID <= 42) || (KeyID == 44) || (KeyID == 43) || (KeyID == 45) || (KeyID > 46 && KeyID <= 47) || (KeyID >= 58 && KeyID <= 64) || (KeyID >= 91 && KeyID <= 96) || (KeyID >= 123 && KeyID <= 126)));
}

function KeycheckDontAllowSpecialChar(e) {
    var KeyID = getKeyID(e);
    return ((KeyID >= 97 && KeyID <= 122) || (KeyID >= 65 && KeyID <= 90) || KeyID == 45 || KeyID == 95 || KeyID == 0 || KeyID == 32 || (KeyID >= 48 && KeyID <= 57));
}

function getKeyID(e) {
    var _dom = 0;
    _dom = document.all ? 3 : (document.getElementById ? 1 : (document.layers ? 2 : 0));
    if (document.all)
        e = window.event; // for IE
    var ch = '';
    var KeyID = '';
    if (_dom == 2) { // for NN4
        if (e.which > 0)
            ch = '(' + String.fromCharCode(e.which) + ')';
        KeyID = e.which;
    } else {
        if (_dom == 3) { // for IE
            KeyID = (window.event) ? event.keyCode : e.which;
        } else { // for Mozilla
            if (e.charCode > 0)
                ch = '(' + String.fromCharCode(e.charCode) + ')';
            KeyID = e.charCode;
        }
    }
    console.log(KeyID);
    return KeyID;
}

function roundPrice(num, dec) {
    var d = Math.pow(10, dec);
    return (Math.round(num * d) / d).toFixed(dec);
}

function number_format(number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function stripslashes(str) {
    return (str + '').replace(/\\(.?)/g, function (s, n1) {
        switch (n1) {
            case '\\':
                return '\\';
            case '0':
                return '\u0000';
            case '':
                return '';
            default:
                return n1;
        }
    });
}

function popover_data_activation($element, $trigger, $event) {
    $($element).unbind("popover");
    var $popover = $($element).popover({
        animation: true,
        content: function () {
            return $(this).attr('data-contant');
        },
        html: true,
        trigger: (($trigger !== undefined) ? $trigger : 'manual')
    });
    if ($event == undefined || $event == 'click') {
        $popover.on((($event !== undefined) ? $event : 'click'), function (e) {
            $($element).not(this).popover('hide');
            if ($(this).data('bs.popover').tip().hasClass('in')) {
                $(this).popover('hide');
            } else {
                $(this).popover('show');
            }
            e.stopPropagation();
        });
    }
    if($trigger == undefined || $trigger == 'click'){
        $(document).on((($trigger !== undefined) ? $trigger : 'click'), function (e) {
            var isPopover = $(e.target).is('[data-toggle=popover]');
            var inPopover = ($(e.target).closest('.popover').length > 0);
            if (!isPopover && !inPopover)
                $popover.popover('hide');
        });
    }
}
 
$(document).ready(function() {
    var filePath = (location.pathname).split('/'); 
    EditableTable.init('',filePath[filePath.length-1],filePath[filePath.length-1]);        
    
});