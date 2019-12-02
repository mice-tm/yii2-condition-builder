'use strict';

class ListBuilder {

    constructor(){
        this.latinAlphabet = [
          "a","b","c","d","e","f","g","h","i","j","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"
        ];
    };

    pointer(level, position) {
        switch (level%5) {
            case 0:
                return this.decimalNumerals(position);
            case 1:
                return this.latinSymbols(position);
            case 2:
                return this.romanNumerals(position);
            case 3:
                return this.decimalNumerals(position);
            case 4:
                return this.latinSymbols(position);
        }
    };

    decimalNumerals(number) {
        return parseInt(number) + 1;
    }

    latinSymbols(number) {
        const fraction = number % 5;
        return this.latinAlphabet[fraction];
    }

    romanNumerals(number) {
        number++;
        let roman = "";
        const romanNumList = {m:1000,cm:900, d:500,cd:400, c:100, xc:90,l:50, xv: 40, x:10, ix:9, v:5, iv:4, i:1};
        let a;
        if(number < 1 || number > 3999)
            return 0;
        else{
            for(let key in romanNumList){
                a = Math.floor(number / romanNumList[key]);
                if(a >= 0){
                    for(let i = 0; i < a; i++){
                        roman += key;
                    }
                }
                number = number % romanNumList[key];
            }
        }
        return roman;
    }
}
