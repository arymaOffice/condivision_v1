<?php require_once('./code/gallery.php');?>


<!-- PORTFOLIO -->
    <article id="portfolio" class="isg-panel">
        <div class="isg-panel-inner" data-bordercolor="<?php echo $color2; ?>">
            <!-- CLOSE ICON -->
            <div class="isg-panel-close">
                <span class="fa fa-times-circle-o"></span>
            </div>
            <!-- ROTATED TEXT -->
            <div class="isg-panel-rotated-text">
                <span class="isg-panel-rotated-text-inner"><?php echo $mod_2_subtitle ?></span>
            </div>
            <!-- UP ICON -->
            <div class="isg-panel-up">
                <span class="fa fa-arrow-circle-o-up"></span>
            </div>
            <!-- PANEL CONTENT -->
            <h2><?php echo $mod_2_title ?></h2>
            <h4><?php echo $mod_2_subtitle ?></h4>
            <?php echo $mod_2_content ?>
            <div class="isg-filter-menu-icon"><i class="fa fa-bars"></i> Filtra </div>
            <ul id="isg-portfolio-filter" class="isg-portfolio-filter">
                <li data-filter="all" class="isg-filter-active">Tutte</li>
                <?php echo $gallery; ?>

            </ul>
            <!-- PORTFOLIO ITEMS -->
            <div id="isg-portfolio-container" class="isg-portfolio-container">
               <?php echo $galleryIMG; ?>
            </div>
        </div>
    </article>
    <script>
        <?php echo $gallerySCRIPT ?>
    </script>
