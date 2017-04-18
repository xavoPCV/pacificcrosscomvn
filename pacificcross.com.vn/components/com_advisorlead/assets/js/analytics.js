var pageAnalytics = new function() {
    this.picker = function(id, fn) {
        $("#" + id).daterangepicker({
            ranges: {
                "Today": ["today", "tomorrow"],
                "Yesterday": ["yesterday", "today"],
                "Last 3 Days": [Date.today().add({days: -3}), "today"],
                "Last 7 Days": [Date.today().add({days: -7}), "today"],
                "Last 30 Days": [Date.today().add({days: -30}), "today"],
                "This Month": [Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth()],
                "Last Month": [Date.today().moveToFirstDayOfMonth().add({months: -1}), Date.today().moveToFirstDayOfMonth().add({days: -1})]
            }
        }, fn);
    };
    this.chart = function(id, options) {
        var defaultOptions = {
            credits: {
                enabled: false
            },
            chart: {
                type: "column",
                renderTo: id,
//                plotBackgroundImage: MEDIA_URL + "img/chart-background.png"
            },
            title: {
                text: ""
            },
            xAxis: {
                type: "datetime"
            },
            series: [
                {name: "Views", id: "Views"},
                {name: "Optins", id: "Optins"},
                {name: "Uniques", id: "Uniques"},
                {
                    name: "Conversion Rate",
                    id: "Conversion Rate",
                    yAxis: 1,
                    tooltip: {
                        valueDecimals: 2,
                        valueSuffix: "%"
                    }
                }
            ],
            loading: {
                hideDuration: 800,
                showDuration: 800
            }
        };
        if (typeof options !== "undefined") {
            for (var key in options) {
                defaultOptions[key] = options[key];
            }
        }
        return new Highcharts.Chart(defaultOptions);
    };
    this.timeParameters = function(from, until) {
        from = from.getTime() / 1000;
        until = until.getTime() / 1000;
        var results = {
            from: from,
            until: until,
            timezoneOffset: -((new Date).getTimezoneOffset() * 60),
            increment: (until - from < 86400) ? "hour" : "day"
        };
        return results;
    };
};