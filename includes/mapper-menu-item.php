<?php

/**
 * Add menu items to the dashboard.
 */
add_action('admin_menu', 'mapper_plugin_setup_menu');
function mapper_plugin_setup_menu()
{
    add_menu_page('Mapper', 'Mapper', 'manage_options', 'mapper', 'mapper_main_page');
    add_submenu_page('mapper', 'Add User Activity', 'Add User Activity', 'manage_options', 'add_mapper_user_activity', 'add_mapper_user_activity_page');
    add_submenu_page('mapper', 'Add Product', 'Add Product', 'manage_options', 'add_mapper_product', 'add_mapper_product_page');
}


/**
 * Main Page.
 */
function mapper_main_page()
{
    // WP Globals
    global $table_prefix, $wpdb;

// Define Table Names
    $usersTable = $table_prefix . 'mapper_users';
    $productsTable = $table_prefix . 'mapper_products';
    $userProductTable = $table_prefix . 'mapper_user_product';
    $externalLinksTable = $table_prefix . 'mapper_external_links';




    echo "<div class='mapper-title'><img class='img-rounded mapper-icon' src='../wp-content/plugins/mapper/assets/media/Logo.png'><h1>Welcome to Mapper</h1></div><div class='container'>";


// Display All users Activities
    echo "<table class='table-bordered table-user-activities'>
    <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Activity</th>
    <th>Time</th>
    <th>Products</th>
    <th>Edit</th>
    </tr>";

    $result = $wpdb->get_results("SELECT * FROM $usersTable");
    foreach ($result as $print) {
        echo '<tr>';
        echo '<td>' . $print->user_id . '</td>';
        echo '<td>' . $print->user_name . '</td>';
        echo '<td>' . $print->activity . '</td>';
        echo '<td>' . $print->time . '</td>';
        echo '<td><ul>';
         $productResult = $wpdb->get_results("SELECT * FROM $productsTable INNER JOIN $userProductTable ON $productsTable.product_id=$userProductTable.product_id WHERE  $userProductTable.user_id = $print->user_id");
        foreach ($productResult as $productprint) {
        
        echo '<li class="single-user-product">' . $productprint->product_name . '</li>';
        }
        
        echo' </ul></td><td><input type="button" value="Update Products" id="updateProductsExternal" onclick="update_products_external(' . $print->user_id . ');"> </td>';
        echo '</tr>';

    }


    echo "</table>";



// Define Update form for the user products
    echo "<form method='post' id='updateProductsForm'>
        <div class='row products-form-section'><div class='col-sm-2 section-title'>Products</div><div class='col-sm-10'><select data-placeholder='Start type product name...' multiple class='chosen-select' name='userProducts[]'>
            <option value=''></option>";

    $result = $wpdb->get_results("SELECT * FROM $productsTable");
    foreach ($result as $print) {
        echo '<option value="' . $print->product_id . '">' . $print->product_name . '</option>';

    }
    echo "</select></div></div>
     <div class='row products-form-section'><div class='col-sm-2 section-title'>External Links</div><div class='col-sm-10'> <div id='formInsertExternalLink'>
      </div></div></div>
    </form>";
    echo ' <script>$(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        })</script>';
        
        
        
        if (isset($_POST['submit'])) {
        $externalLinkUrl = "";
        $externalLinkDesc = "";
        $productsIdSelected=[];
        $userIdSelected = -1;

        foreach ($_POST as $key => $value) {

            if ($counter == 0) {
                if($_POST['userProducts'] ){
                    foreach ($_POST['userProducts'] as $selectedOption) {
                        array_push($productsIdSelected,$selectedOption);
                    }
                }else{
                    $counter +=1;
                }
            }

            if ($counter == 1) {
                $userIdSelected = $value ;
                foreach ($productsIdSelected as $productIdSelected) {
                    $data = array( 'product_id' => $productIdSelected, 'user_id' => $userIdSelected);
                    $wpdb->insert( $userProductTable, $data );
                }

            } else if ($counter % 2 == 0) {
                $externalLinkUrl = $value;
            } else if ($counter % 2 == 1) {
                $externalLinkDesc = $value;
                $data = array('external_url' => $externalLinkUrl, 'external_desc' => $externalLinkDesc , 'user_id' => $userIdSelected);
                $wpdb->insert( $externalLinksTable, $data );
            }
            $counter += 1;


        }

    }
    echo '</div>';
}

/**
 * Add User Activity Page.
 */
function add_mapper_user_activity_page()
{
    echo "<h2>Add User Activity</h2>";
    
    echo "<form class='insert-form' method='post'>
             <label >User Name</label>
             <input type='text' name='user_mapper_name' required>
             <label >User Activity</label>
	         <textarea name='user_mapper_activity' required> </textarea>
             <input type='submit' name='submit-insert-user' value='Add User'>
         </form>";
         
     if (isset($_POST['submit-insert-user'])) {
        insert_mapper_user();
    }
}

/**
 * Add Product Page.
 */
function add_mapper_product_page()
{
    echo "<h2> Add Product </h2>";
    
    echo "<form class='insert-form' method='post'>
             <label >Product Name</label>
	         <input name='product_mapper_name' required>
	         <label >Product Desc</label>
             <input type='text' name='product_mapper_desc' >
             <input type='submit' name='submit-insert-product' value='Add Product'>
         </form>";

      if (isset($_POST['submit-insert-product'])) {
        insert_mapper_product();
    }
}



/**
 * Update user in db table.
 */
function update_mapper_user($userid_arg)
{
    $data = [];
    $where = array('user_id' => $userid_arg);
    $wpdb->update($usersTable, $data, $where);
}


/**
 * Delete user in db table.
 */
function delete_mapper_user($userid_arg)
{
    $data = [];
    $where = array('user_id' => $userid_arg);
    $wpdb->delete($usersTable, $where);
}


/**
 * Insert user in db table.
 */
function insert_mapper_user()
{
    // WP Globals
    global $table_prefix, $wpdb;

    // Define Table Names
    $usersTable = $table_prefix . 'mapper_users';
    
    $data = array('time' => current_time('mysql'), 'user_name' => $_POST["user_mapper_name"], 'activity' => $_POST["user_mapper_activity"]);
    $wpdb->insert($usersTable, $data);
}


/**
 * Insert Product in db table.
 */
function insert_mapper_product()
{
    // WP Globals
    global $table_prefix, $wpdb;

    // Define Table Names
    $productsTable = $table_prefix . 'mapper_products';

    $data = array('product_name' => $_POST["product_mapper_name"], 'product_desc' => $_POST["product_mapper_desc"]);
    $wpdb->insert($productsTable, $data);
}
