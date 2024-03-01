<?php 
// this component is appended to the end of interactive tables to hanlde editing and deleting respective data of that table
function editDelete($edit=true, $delete=true, $hidden=false){

?>
<td class='icon-cell' 
<?php if ($hidden): ?>
    style="visibility:hidden" 
<?php endif; ?>
>

    <?php if($edit): ?> <i class='fa-regular fa-pen-to-square fa-sm edit' ></i> <?php endif ?>

    <?php if($delete): ?> <i class='fa-solid fa-trash-can fa-sm delete'></i> <?php endif ?>

</td>

<?php } ?>