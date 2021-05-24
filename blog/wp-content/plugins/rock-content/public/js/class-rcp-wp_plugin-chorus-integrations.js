(function() {
    "use strict";

    function sendData(url, data) {
        var contentType = "application/x-www-form-urlencoded; charset=utf-8";

        if (!trySendBeacon(url)) {
            trySendXHR(url);
        }

        function trySendBeacon(url) {
            if (!("sendBeacon" in navigator)) {
                return false;
            }

            try {
                var blob = new Blob([data], { type: contentType });
                return navigator.sendBeacon(url, blob);
            } catch (e) {
                // Chrome can throw an exception if it doesn’t like the sendBeacon
                // content type: http://crbug.com/490015
                // This violates the spec as sendBeacon() should only return false
                // if it fails to send data. Whatever, Chrome.
                return false;
            }
        }

        function trySendXHR(url) {
            var request = new XMLHttpRequest();
            request.open("POST", url, true);
            request.setRequestHeader("Content-type", contentType);
            request.onload = function() {
                typeof callback === "function" &&
                    callback(request.responseText);
            };
            request.onerror = function() {
                console.error(request);
            };
            request.send(data);
        }
    }

    function getFormGroup(formAction) {
        var group = "others";

        // Guessing a WP comment form
        if (formAction.indexOf("/wp-comments-post.php") !== -1) {
            group = "commenters";
        }

        // Guessing a Contact Form 7 form
        if (formAction.indexOf("wpcf7") !== -1) {
            group = "subscribers";
        }

        return group;
    }

    function getEmail(form) {
        var email = null;

        for (var i = 0; i < form.elements.length; i++) {
            var el = form.elements[i];
            var elValue = el.value.trim();

            if (el.nodeName !== "INPUT") {
                continue;
            }

            if (
                elValue.indexOf("@") !== -1 &&
                elValue.indexOf(".") !== -1 &&
                elValue.indexOf(" ") === -1
            ) {
                email = elValue;
            }
        }

        return email;
    }

    function trackFormSubmit(form) {
        var formAction = form.getAttribute("action") || "";
        var email = getEmail(form);

        if (!email) {
            console.debug(
                "Couldn’t get an email from form with action:",
                formAction,
            );
            return;
        }

        var groupStr = "data[group]=" + getFormGroup(formAction);
        var emailStr = "data[email]=" + encodeURIComponent(email);

        sendData("/rcp-add-contact", groupStr + "&" + emailStr);
    }

    function handleFormSubmit(event) {
        var target = event.target;

        if (target.tagName === "FORM") {
            trackFormSubmit(target);
        }
    }

    document.addEventListener("submit", handleFormSubmit, true);
})();
