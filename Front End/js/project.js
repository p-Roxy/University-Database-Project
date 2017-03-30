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

function  generateTable(data) {
    var tbl_body = document.createElement("tbody");
    var odd_even = false;
    console.log("DATA",data);
    $.each(data,function () {
        var tbl_row = tbl_body.insertRow();
        tbl_row.className = odd_even ? "odd" : "even";
        $.each(this, function (k, v) {
            var cell = tbl_row.insertCell();
            cell.appendChild(document.createTextNode(v.toString()));
        })
        odd_even = !odd_even;
    })
    document.getElementById("tblResults").appendChild(tbl_body);
}

/** Deals with the forms on the login page
 *  sends form input value to server to validate
 *  @param ()
 *  @return {*}
 */
$(document).ready(function () {
    $("#codeform").submit(function (e) {
        var text = $('input#code').val();
        $.ajax({
            url: window.puppetURL + "/token/validate/" + text,
            success: function (result) {
                // content back, was admin code and this is the new code
                if (result !== '') {
                    sessionStorage.setItem("token", text);
                    window.location.href = 'codegen.html';
                }
                // valid - empty result, but not a HTTP error
                else if (result !== text) {
                    sessionStorage.setItem("token", text);
                    window.location.href = 'selection.html';
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