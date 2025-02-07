<div class="row g-3">
    <div class="row">
        <div class="col-md-3">
            <form action="../models/select-subsidiaria.php" method="POST">
                <label for="subsidiaria"></label>
                <?php
         
                $url_web = $config->url_web;
                
                // Check if subsidiarias data is available in session
                if (isset($_SESSION['sesion_rd_subsidiarias'])) {
                    $subsidiarias = unserialize($_SESSION["sesion_rd_subsidiarias"]);
                    $currentSubsidiaria = unserialize($_SESSION['rd_current_subsidiaria']);

                    // Generate the HTML for the select dropdown
                    echo "<select class='form-select' name='subsidiaria' id='subsidiaria' onchange='actualizaSubsidiaria(\"".$url_web."\",this.value)'>";
                    foreach ($subsidiarias as $subsidiaria) {
                        // Add the 'selected' attribute to the first option
                        $selected = $currentSubsidiaria->token == $subsidiaria->token ? 'selected' : '';

                        $subsidiariaJSON = json_encode($subsidiaria);

                        echo "<option value='$subsidiariaJSON' $selected >$subsidiaria->nombre</option>";
                        $first = false;
                    }
                    echo '</select>';
                } else {
                    echo 'No subsidiarias found in session storage.';
                }
                ?>
                <!-- <button type="submit">Submit</button> -->
            </form>
        </div>
    </div>