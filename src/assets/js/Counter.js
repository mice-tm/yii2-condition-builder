'use strict';

class Counter {

    constructor(){
        this.list = new ListBuilder();
    };
    init() {
        $('.counter').each(function (index, value) {
            const parentFieldSet = $(value).parents('.fieldset:first');
            $('span', value).html(
                this.list.pointer(
                  parentFieldSet.attr('data-level'),
                  parentFieldSet.attr('data-position')
                ) + '.'
            );
        }.bind(this));
    }
    setPositions(element){
        $(element).find('.fieldset:first').parent().children('.fieldset').each(function(index, value) {
            $(value).attr('data-position', index);
            this.setPositions(value);
        }.bind(this))
    }
}
