$(function () {

    if ($(".js-admin-content").data("ga") == "0") {
        return;
    }

    // == NOTE ==
// This code uses ES6 promises. If you want to use this code in a browser
// that doesn't supporting promises natively, you'll have to include a polyfill.

    gapi.analytics.ready(function() {
        
        /**
         * Authorize the user immediately if the user has already granted access.
         * If no access has been created, render an authorize button inside the
         * element with the ID "embed-api-auth-container".
         */
        gapi.analytics.auth.authorize({
            'serverAuth': {
                'access_token': gaAccessToken
            }
        });

        var defaultChartNow = {
            backgroundColor : 'rgba(229,69,55,0.5)',
            borderColor : 'rgba(229,69,55,0.9)',
            pointBackgroundColor : 'white',
            pointBorderColor : 'rgba(229,69,55,0.9)',
            pointBorderWidth : 3,
            lineTension: 0,
            pointRadius: 4
        };

        var defaultChartPast = {
            backgroundColor : 'rgba(220,220,220,0.5)',
            borderColor : 'rgba(220,220,220,1)',
            pointBackgroundColor : 'rgba(220,220,220,1)',
            pointBorderWidth : 3,
            lineTension: 0,
            pointBorderColor : '#fff',
            pointRadius: 4
        };


        /**
         * Draw the a chart.js line chart with data from the specified view that
         * overlays session data for the current week over session data for the
         * previous week.
         */
        function renderWeekOverWeekChart(ids) {

            // Adjust `now` to experiment with different days, for testing only...
            var now = moment(); // .subtract(3, 'day');

            var thisWeek = query({
                'ids': ids,
                'dimensions': 'ga:date,ga:nthDay',
                'metrics': 'ga:sessions',
                'start-date': moment(now).day(-6).format('YYYY-MM-DD'),
                'end-date': moment(now).format('YYYY-MM-DD')
            });

            var lastWeek = query({
                'ids': ids,
                'dimensions': 'ga:date,ga:nthDay',
                'metrics': 'ga:sessions',
                'start-date': moment(now).day(-13)
                    .format('YYYY-MM-DD'),
                'end-date': moment(now).day(-7)
                    .format('YYYY-MM-DD')
            });

            Promise.all([thisWeek, lastWeek]).then(function(results) {

                var data1 = results[0].rows.map(function(row) { return +row[2]; });
                var data2 = results[1].rows.map(function(row) { return +row[2]; });
                var labels = results[1].rows.map(function(row) { return +row[0]; });


                labels = labels.map(function(label) {
                    return moment(label, 'YYYYMMDD').format('ddd');
                });

                var data = {
                    labels : labels,
                    datasets : [
                        getChartSettings("now", {
                            label: 'Tento týden',
                            data : data1
                        }),
                        getChartSettings("past", {
                            label: 'Minulý týden',
                            data : data2
                        })
                    ]
                };

                new Chart(makeCanvas('chart-1-container'), {
                    type: 'line',
                    data: data,
                    options: {
                        lineTension: 0,
                        steppedLine: true
                    }
                });
            });
        }


        function getChartSettings(type, settings) {

            if (type == "now") {
                return $.extend({}, settings, defaultChartNow);
            } else {
                return $.extend({}, settings, defaultChartPast);
            }

        }

        /**
         * Extend the Embed APIs `gapi.analytics.report.Data` component to
         * return a promise the is fulfilled with the value returned by the API.
         * @param {Object} params The request parameters.
         * @return {Promise} A promise.
         */
        function query(params) {
            return new Promise(function(resolve, reject) {
                var data = new gapi.analytics.report.Data({query: params});
                data.once('success', function(response) { resolve(response); })
                    .once('error', function(response) { reject(response); })
                    .execute();
            });
        }


        /**
         * Create a new canvas inside the specified element. Set it to be the width
         * and height of its container.
         * @param {string} id The id attribute of the element to host the canvas.
         * @return {RenderingContext} The 2D canvas context.
         */
        function makeCanvas(id) {
            var container = document.getElementById(id);
            var canvas = document.createElement('canvas');
            var ctx = canvas.getContext('2d');

            container.innerHTML = '';
            canvas.width = container.offsetWidth;
            canvas.height = container.offsetHeight;
            container.appendChild(canvas);

            return ctx;
        }


        // Set some global Chart.js defaults.
        Chart.defaults.global.animationSteps = 60;
        Chart.defaults.global.animationEasing = 'easeInOutQuart';
        Chart.defaults.global.responsive = true;
        Chart.defaults.global.maintainAspectRatio = false;



        $.nette.ext({
            load: function () {
                if ($("#chart-1-container").length > 0) {
                    renderWeekOverWeekChart(gaView);

                    gapi.analytics.auth.authorize({
                        'serverAuth': {
                            'access_token': gaAccessToken
                        }
                    });
                }
            }
        });

        if ($("#chart-1-container").length > 0) {
            renderWeekOverWeekChart(gaView);
        }
    });
});