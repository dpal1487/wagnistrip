let prices = [];


(function($) {
    $(document).ready(function() {
        $('.input-range').each(function() {
            var value = $(this).attr('data-slider-value');
            var separator = value.indexOf(',');
            if (separator !== -1) {
                value = value.split(',');
                value.forEach(function(item, i, arr) {
                    arr[i] = parseFloat(item);
                });
            } else {
                value = parseFloat(value);
            }
            $(this).slider({
                formatter: function(value) {



                    //  code by uddeshya just one line to call my function


                    updatebyprice(value)

                     // console.log(value);

                      return 'Rs. ' + value;
                },
                min: parseFloat($(this).attr('data-slider-min')),
                max: parseFloat($(this).attr('data-slider-max')),
                range: $(this).attr('data-slider-range'),
                value: value,
                tooltip_split: $(this).attr('data-slider-tooltip_split'),
                tooltip: $(this).attr('data-slider-tooltip')
            });
        });

        $('.input-range2').each(function() {
            var value = $(this).attr('data-slider-value');
            var separator = value.indexOf(',');
            if (separator !== -1) {
                value = value.split(',');
                value.forEach(function(item, i, arr) {
                    arr[i] = parseFloat(item);
                });


                } else {
                value = parseFloat(value);
            }
            $(this).slider({
                formatter: function(value) {
                    // console.log(value);
                    updatebyprice(value)
                    return value + ' Hr';
                },
                min: parseFloat($(this).attr('data-slider-min')),
                max: parseFloat($(this).attr('data-slider-max')),
                range: $(this).attr('data-slider-range'),
                value: value,
                tooltip_split: $(this).attr('data-slider-tooltip_split'),
                tooltip: $(this).attr('data-slider-tooltip')
            });
        });
        //  code by uddeshya 

         function updatebyprice(price) {

            // console.log(price)

            for (let j = 0; j < prices.length; j++) {
                if (prices[j] >= price[1]) {
                    document.getElementById(idlist[j]).classList.add("hide");
                }
                if (prices[j] <= price[1]) {
                    document.getElementById(idlist[j]).classList.remove("hide");

                     }

            }
            for (let j = 0; j < prices.length; j++) {
                if (prices[j] <= price[0]) {
                    document.getElementById(idlist[j]).classList.add("hide");
                }
                if (prices[j] >= price[0]) {
                    document.getElementById(idlist[j]).classList.remove("hide");

                    }

            }
        }

         //  code end by uddeshya 

         });
})(jQuery);


//  code by uddeshya

let idlist = [];
let citytags = document.getElementsByClassName('City');
setTimeout(() => {
    for (var i = 0; i < citytags.length; i++) {
        let city = citytags[i].innerHTML.replace(/\s+/g, ' ');
        idlist[i] = city + i
    }

    // console.table(idlist)


   }, 20)


let pricetags = document.getElementsByClassName('bacePrice');setTimeout(() => {
var arrayindex = 0; for (var i = 0; i < pricetags.length; i++) { let price = parseInt(pricetags[i].innerHTML.replace(/\s+/g, ''));


if (prices.includes(price)) {


     // console.log('Okk')


     } else { prices[arrayindex] = price; arrayindex++;
     } 
    
}
     // prices = prices.sort(function(a, b) { return a - b });
    // console.table(prices);

    }, 100);
    setTimeout(() => { 
        let MSH = document.getElementsByClassName('max-slider-handle');

        if(MSH.length > 0){
            console.log("see this " , MSH.length)

            // MSH?.style.display = 'none';
        }
    }, 1000);

    // code end by uddeshya

    