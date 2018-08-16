'use strict';

class Counter {

    constructor(){
        this.lists = [
            [1,2,3,4,5,6,7,8,9,10],
            ["a","b","c","d","e","f","g","h","i","j"],
            ["i","ii","iii","iv","v", "vi", "vii", "viii", "ix", "x"],
            [1,2,3,4,5,6,7,8,9,10],
            ["a","b","c","d","e","f","g","h","i","j"],
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
