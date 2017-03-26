/** Function to deal with login area
 *  Accepts a button ID and determines whether to hide and show an area
 * @param bID
 * @returns {*}
 */
function buttonHelper(bID) {
    var button = getParentID(bID);
    var group;
    if (bID.id === "bgroup") {
        group = document.getElementById("group");
    } else if (bID.id === "badmin") {
       group = document.getElementById("admin");
    } else {
        group = document.getElementById("buttons");
    }
    button.style.display = 'none';
    group.style.display = 'block';
}

/** Gets id of parent div
 * @param obj
 * @returns {*}
 */

function getParentID(obj) {
    while (!obj.id || obj.tagName != "DIV") {
        obj = obj.parentNode;
    }
    return obj;
}

/** Gets code from server and returns the value of the button
 * @param
 * @returns {*}
 */
function getCode() {
    $.ajax({
        url: "http://puppet/token/generate",
        success: function (result) {
            $("#codearea").html(result);
        },
        error: function () {
            console.log("nope");
        }
    })
}

/** Deals with the forms on the login page
 *  sends form input value to server to validate
 *  @param ()
 *  @return {*}
 */
$(document).ready(function () {
    $("#adminlogin").submit(function (e) {
        console.log(e);
        var text = $('input#admin').val();
        $.ajax({
            type: "POST",
            url: "http://puppet/token/validate/" + text,
            success: function (result) {
                if (result === "true") {
                    window.location.href = selection.html;
                    sessionStorage.setItem("token", text);
                } else {
                    $("#error").html("Invalid Admin");
                }
            },
            error: function () {
                $("#error").html("Invalid Admin");
            }
        });
        e.preventDefault();
    });
    $("#codeform").submit(function (e) {
        console.log(e);
        var text = $('input#code').val();
        $.ajax({
            type: "POST",
            url: "http://puppet/token/validate/" + text,
            success: function (result) {
                if (result === "false") {
                    window.location.href = selection.html;
                    sessionStorage.setItem("token", text);
                } else {
                    $("#codeerror").html("Invalid Code");
                }
            },
            error: function () {
                $("#codeerror").html("Invalid Code");
            }
        });
        e.preventDefault();
    });
});
