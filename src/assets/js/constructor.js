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
            .prop("selected", true)
            .trigger('change')
            .parent('.condition-attribute')
            .prop("disabled", true)
        ;

        $(".comparison-comparison", parentFieldset)
            .val('')
            .trigger('change');
        $(".comparison-value", parentFieldset).val('')
            .trigger('change');
        if ($(parentFieldset).attr('data-level') < (maxLevel-1) ) {
            $(".add-condition", parentFieldset).show();
        }
    } else {
        $(".add-condition", parentFieldset).hide();
        $(".condition-attribute", parentFieldset)
            .prop("disabled", false)
        ;
    }
})

$(document).ready(function(){
    const counter = new Counter();
    counter.setPositions($('fieldset'));
    counter.init();
});