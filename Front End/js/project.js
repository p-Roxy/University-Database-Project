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
