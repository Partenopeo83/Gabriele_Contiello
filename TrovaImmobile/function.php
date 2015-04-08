<?php
function category_list_trovaimmobile($category_check, $exclude)
{ ?>
    <div class="categories-box">
        <h4>Categorie</h4>

        <div class="categories-list">
            <?php
            $categories = get_terms('category', 'orderby=name&exclude=1,' . $exclude . '&hide_empty=0');
            if ($categories) {
                foreach ($categories as $category) { ?>
                    <input type="radio" class='category_choise' name="category_choise"
                           value='<?php echo $category->term_id; ?>' <?php if ($category->term_id == $category_check) {
                        echo "checked='checked'";
                    } ?> /> <?php echo $category->name ?><br/>
                <?php }/*end foreach*/
            }/*end if*/ ?>
        </div>
        <!---fine categories-list-->
    </div><!----fine categories-box---->
<?php } ?>