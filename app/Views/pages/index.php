<?php
/*
  * Default Page Template
  * Author: Mr. Bilal
 */
$session = session();
?>
<?php
if(!empty($page)) {
    echo view('/includes/header.php', [
        'meta_title' => $page['meta_title'],
        'meta_description' => $page['meta_description'],
        'meta_keywords' => $page['meta_keywords'],
        'meta_image' => $page['meta_image']
    ]);
    if(!empty($page['content'])){
        $contents = json_decode($page['content'],true);

        ?>
        <div class="page-content pages">
                <?php foreach($contents as $data) {
                    $widget = key($data);
                    $content = $data[$widget];

                   // pr($content,false);

                    echo view('pages/widgets/'.$widget,$content);
                }?>
        </div>

        <br>
        <br>

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css" integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ==" crossorigin=""/>

        <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>

        <script src="<?php echo site_url() ?>assets/javascript/map-innit.js"></script>


        <?php
    }
    echo view( '/includes/footer.php');
}?>

