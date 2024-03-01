<?php


function table_header(array $headers){
?>
    <thead>
        <tr>
            <?php foreach ($headers as $head): ?>
                <th>
                    <?= $head ?> 
                </th>

            <?php endforeach ?> 
        </tr>
    </thead>

<?php } ?>