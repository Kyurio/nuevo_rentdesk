<div class="row" style="margin-top:0px;">
     <div class="col-md-3">
         <form action="../../../includes/select-sucursal-procesa.php" method="POST">
             <label for="sucursal"></label>
             <?php

                $url_web = $config->url_web;

                // Check if sucursales data is available in session
                if (isset($_SESSION['sesion_rd_sucursales'])) {
                    $sucursales = unserialize($_SESSION["sesion_rd_sucursales"]);
                    $currentSucursal = unserialize($_SESSION['rd_current_sucursal']);
                    // var_dump("currentSucursal desde vista: ", $currentSucursal);
                    // Generate the HTML for the select dropdown
                    echo "<select class='form-select' name='sucursal' id='sucursal' onchange='actualizaSucursal(\"" . $url_web . "\",this.value)' style='min-width:150px; font-size:12px;width: max-content;'>";
                    foreach ($sucursales as $sucursal) {
                        // Add the 'selected' attribute to the first option
                        $selected = $currentSucursal->sucursalToken == $sucursal->sucursalToken ? 'selected' : '';

                        $sucursalJSON = json_encode($sucursal);
                        echo "<option value='$sucursalJSON' $selected >" . ($sucursal->sucursalCasaMatriz ? $sucursal->sucursalNombre . " - Casa Matriz" : $sucursal->sucursalNombre) . "</option>";
                        $first = false;
                    }
                    echo '</select>';
                } else {
                    echo 'No sucursales found in session storage.';
                }
                ?>
             <!-- <button type="submit">Submit</button> -->
         </form>
     </div>
 </div>



 <script>
    // Function to sort the options in the select element and retain the selected option
    function sortSelect(selector) {
        let select = document.getElementById(selector);
        let selectedValue = select.value; // Save the currently selected value

        let options = Array.from(select.options);

        // Sort options alphabetically based on the visible text
        options.sort(function (a, b) {
            return a.text.localeCompare(b.text, 'es', { sensitivity: 'base' });
        });

        // Clear the current options in the select
        select.innerHTML = '';

        // Append the sorted options back into the select
        options.forEach(option => select.appendChild(option));

        // Restore the previously selected option
        select.value = selectedValue;
    }

    // Call the sort function after the page has loaded
    document.addEventListener('DOMContentLoaded', function () {
        sortSelect('sucursal');
    });
</script>