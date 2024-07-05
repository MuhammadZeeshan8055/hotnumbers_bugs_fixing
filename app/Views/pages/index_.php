<?php $session = session(); ?>
<!--header-------------->
<?php echo  view( '/includes/header.php'); ?>

<style>
    #page_bilder{
        margin-top: 50px;
    }
    <?php  $style = str_replace("}.","} #page_bilder .",$single_page->css);
     echo $style;
    ?>
</style>
<div id="page_bilder">
    <?php echo $single_page->html;?>
</div>


<?php echo  view( '/includes/footer.php'); ?>

