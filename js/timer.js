/**
 *
 * Countdown timer for the listing view pages
 * @param end
 * @param elements
 * @param callback
 */


var countdown = function(end, elements, callback) {
    var _second = 1000,
        _minute = _second * 60,
        _hour = _minute * 60,
        _day = _hour * 24,

        end = new Date(end),
        timer,


        calculate = function() {

            var now = new Date(),
                remaining = end.getTime() - now.getTime(),
                data;

            if(remaining <= 0 ) {
                clearInterval(timer);

                if(typeof callback == 'function'){
                    callback();
                }
                //Clear the output
                document.getElementById("days").textContent="";
                document.getElementById("hours").textContent="";
                document.getElementById("minutes").textContent="";
                document.getElementById("seconds").textContent="";
                document.getElementById("timeleft").textContent="This listing has ended";
            } else {
                if(!timer) {
                    timer = setInterval(calculate, _second);
                }

                data = {
                    "days": Math.floor(remaining / _day),
                    "hours": Math.floor((remaining % _day) / _hour),
                    "minutes": Math.floor((remaining % _hour) / _minute),
                    "seconds": Math.floor((remaining % _minute) / _second)
                }

                //Change the output based on the data objects properties
                if(data['days'] < 1) {
                    document.getElementById('days').innerHTML = "";
                } else {
                    data['days'] = ('00' + data['days']).slice(-2) + "d ";
                    document.getElementById('days').innerHTML = data['days'];
                }
                if(data['hours'] < 1) {
                    document.getElementById('hours').innerHTML = "";
                } else {
                    data['hours'] = ('00' + data['hours']).slice(-2) + "h ";
                    document.getElementById('hours').innerHTML = data['hours'];
                }
                data['minutes'] = ('00' + data['minutes']).slice(-2) + "m ";
                document.getElementById('minutes').innerHTML = data['minutes'];
                data['seconds'] = ('00' + data['seconds']).slice(-2) + "s ";
                document.getElementById('seconds').innerHTML = data['seconds'];

            }


    };
    calculate();
}