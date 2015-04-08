<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a
                href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box" data-token="<?php echo $token; ?>">
        <div class="heading">
            <h1><img src="view/image/module.png" alt=""/> <?php echo $heading_title; ?></h1>
        </div>
        <div class="content" id="content-list">
            <div class="wait"></div>
            <div class="select-category-box">
                <form class="catalog-form" enctype="multipart/form-data" method="get">
                    <div class="select-container">
                        <select class="category-select">
                            <option value="">Seleziona la categoria padre</option>
                            <?php foreach( $array_categories as $category ){
                            if( $category['level'] == 1 ){
                        ?>
                            <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                            <?php }/*fine if*/
                         } ?>
                        </select>
                    </div>
                    <div class="select-container">
                        <select class="level-category">
                            <option value="">Seleziona il livello di profondità</option>
                            <?php for($i=1; $i<=10; $i++){ ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="catalog-button">Crea</button>
                    <button href="#" class="generate-catalog">Genera Catalogo</button>
                    <div class="clear"></div>
                </form>
            </div>
            <div class="list-container"></div>
            <div class="archive-container">
                <div class="archive-label"><span>Archivio</span></div>
                <ul class="archive-box">
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                    <li><a href="#">Primo file</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>

<?php echo $footer; ?>