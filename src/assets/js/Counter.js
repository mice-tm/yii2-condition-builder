'use strict';

class Counter {

    constructor(){
        this.lists = [
            [1,2,3,4,5],
            ["a","b","c","d","e"],
            ["i","ii","iii","iv","v"],
            [1,2,3,4,5],
            ["a","b","c","d","e"]
        ];
    };
    init() {
        $('.counter').each(function (index, value) {
            const parentFieldSet = $(value).parents('.fieldset:first');
            $('span', value).html(
                this.lists[parentFieldSet.attr('data-level')][parentFieldSet.attr('data-position')] + '.'
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
