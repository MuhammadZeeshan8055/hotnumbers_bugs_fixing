<?php
if(!empty($page)) {
    $meta_data = !empty($page->meta_data) ? json_decode($page->meta_data,true) : ['description'=>'','keywords'=>''];
echo view("includes/header",[
    'page'=>[
        'meta_title'=>$page->title,
        'meta_description'=>$meta_data['description'],
        'meta_keywords'=>$meta_data['keywords']
    ]
]) ?>

<?php
if(!empty($page->content)) {
    echo base64_decode($page->content);
}
?>

<!------------footer ---------------------------------------->
<?php echo view("includes/footer");

} ?>
<!--------------- footer end -------------------------------->


