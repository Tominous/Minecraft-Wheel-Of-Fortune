<div class="container">
<div class="row">
    <div class="col">
        <h1>Add items to win</h1>
    </div>
</div>
<div class="row">
    <form id="form-items" action="" method="post">
        <div id="form-base-fields" class="form-row">
            <?php 
                if(count($items['item_name']) == 0):
            ?>
                    <div class="base-fields form-row">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Item name" name="item_name[]" />
                            <small class="form-text text-muted">Item name visible on wheel of fortune</small>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Size of item on wheel" name="item_size[]" />
                            <small class="form-text text-muted">Size of area which item covers, leave <b>empty</b> if you want to have auto calculated size</small>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Item code" name="item_code[]" />
                            <small class="form-text text-muted">/give JhonDoe <b>diamondblock</b> - item code</small>
                        </div>
                        <div class="col">
                            <input type="number" class="form-control" placeholder="Item count" name="item_count[]" />
                            <small class="form-text text-muted">Count of items user will get</small>
                        </div>
                        <div class="col" style="text-align: right;">
                            <a class="btn btn-danger" style="color:#FFF">X</a>
                        </div>
                    </div>
            <?php
                else:
            ?>
                <?php
                    for($i = 0; $i < count($items['item_name']); $i++):
                ?>
                    <div class="base-fields form-row">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Item name" name="item_name[]" value="<?php echo $items['item_name'][$i] ?>" />
                            <small class="form-text text-muted">Item name visible on wheel of fortune</small>
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Size of item on wheel" name="item_size[]" value="<?php echo $items['item_size'][$i] ?>" />
                             <small class="form-text text-muted">Size of area which item covers, leave <b>empty</b> if you want to have auto calculated size</small>

                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Item code" name="item_code[]" value="<?php echo $items['item_code'][$i] ?>" />
                            <small class="form-text text-muted">/give JhonDoe <b>diamondblock</b> - item code</small>
                        </div>
                        <div class="col">
                            <input type="number" class="form-control" placeholder="Item count" name="item_count[]" value="<?php echo $items['item_count'][$i] ?>" />
                            <small class="form-text text-muted">Count of items user will get</small>
                        </div>
                        <div class="col" style="text-align: right;">
                            <a class="btn btn-danger" style="color:#FFF">X</a>
                        </div>
                    </div>
            <?php
                    endfor;  
                endif;    
            ?>
        </div>
        <input type="submit" id="wof-items-submit" type="submit" class="btn btn-primary" value="Save">
        <a id="wof-item-add" class="btn btn-success" style="color:#FFF">Add</a>
    </form>
</div>
</div>

<script>
jQuery('#form-items').on('click', 'a.btn.btn-danger' ,function(event)
{
    var owner = jQuery(this).parent().parent();

    jQuery(owner).remove(); 
});
    
jQuery('#wof-item-add').click(function()
{
    var $group = jQuery('#form-base-fields').clone();
    
    jQuery("#form-base-fields").append('<div class="base-fields form-row"><div class="col"><input type="text" class="form-control" placeholder="Item name" name="item_name[]" /><small class="form-text text-muted">Item name visible on wheel of fortune</small></div><div class="col"><input type="text" class="form-control" placeholder="Size of item on wheel" name="item_size[]" /> <small class="form-text text-muted">Size of area which item covers, leave <b>empty</b> if you want to have auto calculated size</small></div><div class="col"><input type="text" class="form-control" placeholder="Item code" name="item_code[]" /><small class="form-text text-muted">/give JhonDoe <b>diamondblock</b> - item code</small></div><div class="col"><input type="number" class="form-control" placeholder="Item count" name="item_count[]" /><small class="form-text text-muted">Count of items user will get</small></div><div class="col" style="text-align: right;"><a class="btn btn-danger" style="color:#FFF">X</a></div></div>');
});
</script>

<style>
#form-base-fields {
    display: block;
    width: 100% !important;
}
    
#form-items {
    padding: 1rem;
    margin-bottom: 1rem;
    border: 1px grey solid;
}

#form-items .form-row .base-fields {
    padding-bottom: 1rem;
    margin-bottom: 1rem;
    border-bottom: 1px grey solid;
}

input[type=number] {
    height: auto !important;
    line-height: unset !important;
}
</style>