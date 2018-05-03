var newConditionEndpoint = window.newConditionEndpoint || '';
var maxLevel = window.maxLevel || 1;
var maxPosition = window.maxPosition || 5;

function removeCondition(fieldSet) {
    const parentFieldSet = $(fieldSet).parents('.fieldset:first');
    fieldSet.remove();
    // if (parentFieldSet.length > 0 && 0 === $('.fieldset', parentFieldSet).length) {
    //     removeCondition(parentFieldSet);
    // }
}

$(".conditionality").on("click", ".delete-condition", function(){
    removeCondition($(this).parents('.fieldset:first'));
    const counter = new Counter();
    counter.setPositions($('fieldset'));
    counter.init();
});

$(".conditionality").on("click", ".add-condition", function(){
    let parentFieldset = $(this).parents('.fieldset:first');
    if (!$(parentFieldset).length) {
        parentFieldset = $('.conditionality');
    }
    const children = $(parentFieldset).children(".row:last").children('.fieldset')
    if (children.length >= maxPosition) {
        return false;
    }
    $.ajax({
        "url": newConditionEndpoint,
        "method": "POST",
        "data": {
            "level": $(parentFieldset).attr('data-level'),
            "position": children.length,
            "path": $(parentFieldset).attr('data-path') + "[conditions]"
        }
    }).done(function (data) {
        $(parentFieldset).children(".row:last").append(data);
        $(parentFieldset).find(".depdrop-comparison").depdrop('initData');
        $(parentFieldset).find(".depdrop-comparison").depdrop('init');
        const counter = new Counter();
        counter.setPositions($('fieldset'));
        counter.init();
    }.bind(this));
});

$(".conditionality").on("change", ".condition-operator", function () {
    const parentFieldset = $(this).parents('.fieldset:first');
    if ($(this).val()) {

        $(".condition-attribute [value='']", parentFieldset)
            .first()
            .prop("selected", true)
            .trigger('change')
            .parent('.condition-attribute')
            .prop("disabled", true)
        ;
        $(".comparison-comparison", parentFieldset)
            .first()
            .val('')
            .trigger('change');
        $(".comparison-value.tt-input", parentFieldset)
            .first()
            .val('')
            .prop("disabled", true)
            .trigger('change');

        if ($(parentFieldset).attr('data-level') < (maxLevel-1) ) {
            $(".add-condition", parentFieldset).first().show();
        }
    } else {
        $(".add-condition", parentFieldset).first().hide();
        $(".condition-attribute", parentFieldset)
            .first()
            .prop("disabled", false)
        ;
    }
});


$(".conditionality").on("change", ".condition-attribute", function () {
    const parentFieldset = $(this).parents('.fieldset:first');
    if ($(this).val()) {
        $(".comparison-value", parentFieldset)
            .first()
            .val('')
            .prop("disabled", false)
            .trigger('change');


    } else {
        $(".field-condition-value", parentFieldset).first().find('input').each(function () {
            $(this).val('');
        })
        $(".comparison-value", parentFieldset)
            .first()
            .val('')
            .attr('value', '')
            .click();
    }
})


$(document).ready(function(){
    const counter = new Counter();
    counter.setPositions($('fieldset'));
    counter.init();
});