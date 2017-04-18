(function() {

    var webinar_id = 0;
    var trackingBaseURL = base_url + "index.php?option=com_advisorlead&task=analytics.api_call&request=tracking";
    var metas = document.getElementsByTagName("meta");
    for (var i = 0; i < metas.length; i++) {
        var element = metas[i];
        if (element.name === 'itps-meta-id')
            trackingId = element.content;
        if (element.name === 'gotowebinar-id')
            webinar_id = element.content;
    }

    var load = function(ps, cb) {

        var finalURL = trackingBaseURL;
        var image = document.createElement("img");
        var i = 0;
        for (key in ps) {
            if (ps[key] !== undefined && ps[key] !== '') {
                finalURL += "&" + encodeURIComponent(key) + "=" + encodeURIComponent(ps[key]);
            }
            i++;
        }
        image.src = finalURL;
        if (cb)
            image.onload = cb;
    };

    load({
        id: trackingId,
        type: "view",
        tracking_type: tracking_type
    });


    var forms = document.getElementsByTagName("form");
    for (var i = 0; i < forms.length; i++) {
        var form = forms[i];
        form.oldOnSubmit = form.onsubmit;
        form.onsubmit = function() {

            var self = this;
            var inputs = self.elements;
            var email_input = '', name_input = '', phone_input = '';
            for (var i = 0; i < inputs.length; i++) {
                var element = inputs[i];
                var name = element.getAttribute("name");
                if (!name)
                    continue;
                if (name.toLowerCase().indexOf("email") != -1 || name.toLowerCase().indexOf("mail") != -1) {
                    email_input = element.value;
                }
                if (name.toLowerCase().indexOf("name") != -1) {
                    name_input = element.value;
                }
                if (name.toLowerCase().indexOf("phone") != -1) {
                    phone_input = element.value;
                }
            }

            load({
                id: trackingId,
                type: "optin",
                webinar_id: webinar_id,
                name: name_input,
                email: email_input,
                phone: phone_input,
                tracking_type: tracking_type
            }, function() {
                self.onsubmit = function() {
                    return true;
                };
                self.submit();
            });

            return false;
        };
    }
})();