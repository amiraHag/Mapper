counter = 1;

function get_remove_button(counter_arg) {
    let remove_button_code = '<input type="button" value="Remove Button" id="removeButton" onclick="remove_external_link('
        + counter_arg + ');">';
    return remove_button_code;
}

function get_external_link_content(counter_arg) {

    let label_external_link = '<label>Product #' + counter_arg + ' : </label>';
    let input_url_external_link = '<input type="text" name="externallinkurl' + counter_arg +
        '" id="name' + counter_arg + '" value="" placeholder="External Link URL" required>';
    let input_text_external_link = '<input type="text" placeholder="External Link Desc" name="externallinkdesc' + counter_arg +
        '" id="activityname' + counter_arg + '" value="" required>'

    let external_link_row = label_external_link + input_url_external_link + input_text_external_link + get_remove_button(counter_arg);

    return external_link_row;
}

function get_external_link_form(userid_arg, counter_arg) {
    let input_user_id_external_form = '<input type="hidden" name="userid" id="userid" value="' + userid_arg + '" >';

    let external_link_div_group = '<div id ="TextBoxesGroup" >'
        + input_user_id_external_form
        + '<div id ="TextBoxDiv1" >'
        + get_external_link_content(counter_arg)
        + '</div></div>'

    return external_link_div_group;
}

function remove_external_link(id) {

    $("#TextBoxDiv" + id).remove();
}


function add_external_link() {
    counter++;
    let newTextBoxDiv = $(document.createElement('div'))
        .attr("id", 'TextBoxDiv' + counter);

    newTextBoxDiv.after().html(get_external_link_content(counter));

    newTextBoxDiv.appendTo("#TextBoxesGroup");


}
function back_products_external() {
    counter = 1;
    document.getElementById('updateProductsForm').style.display = "none";
}

function update_products_external(id) {
    counter = 1;
    document.getElementById('updateProductsForm').style.display = "block";
    let addButton = '<input class="form-button" type="button" value="Add Button" id="addButton" onclick="add_external_link();">';
    let submitButton = '<input class="update form-button" type="submit" name="submit" value="Update"> <input class="back form-button" type="button" name="submit-back" value="Back" onclick="back_products_external()">';
    let newTextBoxDiv = $(document.createElement('div'));

    newTextBoxDiv.after().html(get_external_link_form(id, counter)
        + addButton + submitButton);

    let mydiv = document.getElementById("formInsertExternalLink");
    mydiv.innerHTML = newTextBoxDiv.html();
}