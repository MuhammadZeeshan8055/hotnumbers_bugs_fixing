

<?php  echo view("includes/header")?>

<style>
    .details p {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 340px;
    }
</style>

<!-- toggle blogs articles -->
<script>
function showArtical(){
  var elms = document.getElementsByClassName("loadsblog");
  Array.from(elms).forEach((x) => {
    x.style.display = "block";
  })
}
</script>


    <!--main body ---> 
    
    <div class="underbanner" style="background: url('<?php echo base_url('assets/images'); ?>/banner.jpg');"></div>

    <div class="wrapper">
    	<h1 class="pagetitle">Blog</h1>
		<section id="blog_section" class="blog_section">
            <div class="container">
            <div class="clear"></div>

                <div class="blog_articles flexbox flex-wrap">
                    <?php
                    foreach($blog_posts as $blog_post){
                        $image = $media->get_media_src($blog_post->img,'medium');
                        $description = strip_tags($blog_post->content);
                        $description = substr($description,0,200);
                        $description .= strlen($blog_post->content) > 200 ? '...':'';
                        ?>
                        <article class="loadsblog">
                            <a href="<?php echo base_url('blog/'.$blog_post->slug) ?>" title="<?php echo $blog_post->title; ?>">
                                <div class="featured">
                                    <img src="<?php echo $image ?>">

                                    <div class="layer"></div>
                                </div>
                            </a>
                            <div class="details">
                                <h3>
                                    <a href="<?php echo base_url('blog/'.$blog_post->slug) ?>" title="<?php echo $blog_post->title; ?>"><?php echo $blog_post->title; ?></a>
                                </h3>
                                <p><?php echo $description; ?></p>
                                <a class="button" href="<?php echo base_url('blog/'.$blog_post->slug) ?>" title="<?php echo $blog_post->title; ?>">Read More</a>
                                <br>
                            </div>
                        </article>
                    <?php } ?>
                    
                </div>
                <div class="clear"></div>
			    <button type="button" class="button" id="showMoreArtical" onclick="showMoreArtical()" title="Load More">LOAD MORE</button>
            </div>
        </section>
    </div>



        <script>
            // vanilla JS
            showMoreArtical = function() {

                const curr_count = document.querySelector('.blog_articles').childElementCount;
                const limit = curr_count;
                const curr_scroll = $(window).scrollTop();

                $.post('<?php echo site_url() ?>blog/loadmore',{start:curr_count, limit: limit},(data)=>{
                    data = JSON.parse(data);
                    if(typeof data == "object") {
                        const total_posts = data.total;
                        $('.blog_articles').find('.new_articles').removeClass('new_articles');
                        let new_html = '';
                        data.posts.forEach((item)=>{
                            const html = `<article class="loadsblog new_articles" style="display: none;">
                            <a href="<?php echo base_url('blog/details') ?>/${item.slug}" title="${item.title}">
                                <div class="featured">
                                    <img src="${item.img_path}">
                                    <div class="layer"></div>
                                </div>
                            </a>
                            <div class="details">
                                <h3>
                                    <a href="<?php echo base_url('blog/details') ?>/${item.slug}" title="${item.title}">${item.title}</a>
                                </h3>
                                <p><?php echo base_url('blog/details') ?>/${item.content}</p>
                                <a class="button" href="<?php echo base_url('blog/details') ?>/${item.slug}" title="${item.title}">Read More</a>
                            </div>
                        </article>`;
                            document.querySelector('.blog_articles').innerHTML += html;
                            new_html += html;
                        });

                        $('.blog_articles').find('.new_articles').show();

                        $(document).scrollTop(curr_scroll);
                        setTimeout(()=>{
                            if(document.querySelector('.blog_articles').childElementCount >= total_posts) {
                                $('#showMoreArtical');
                            }
                        },50);
                    }

                });
            }
        </script>

        
<!------------footer ---------------------------------------->
<?php  echo view("includes/footer")?>
<!--------------- footer end -------------------------------->


