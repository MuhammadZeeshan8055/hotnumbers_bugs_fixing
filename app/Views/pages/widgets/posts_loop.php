<?php
$style = '';
if(!empty($section_bg_color)) {
    $style .= 'background-color:'.$section_bg_color;
}

$rand = rand();
?>
<section class="widget-posts txt-block no_border_bottom <?php echo $classes ?>" <?php echo !empty($style) ? 'style="'.$style.'"' : ''; ?>>
    <div class="<?php echo !empty($container_wrap) && $container_wrap == 'yes' ? 'container':'' ?>">
        <div class="inner">
            <div  <?php echo !empty($padding) ? ' style="padding:'.$padding.'"':'' ?>>
                <?php if(!empty($title)) { ?>
                    <h2 class="column_title"><?php echo $title ?></h2>
                <?php } if(!empty($subtitle)) { ?>
                <h3 class="column_subtitle"><?php echo $subtitle ?></h3>
                <?php } ?>
            </div>

            <section id="articles-<?php echo $rand ?>" class="txt-block no_border_bottom margins_inherit gigs_articles trio-post">
                <?php
                /*foreach($gigs_posts as $gigs_post) { ?>
                    <div class="container padding_inherit">
                        <div class="text full align_left">
                            <h2 class="column_title"><?php echo $gigs_post->title; ?> </h2>
                            <h3 class="column_subtitle"><?php echo $gigs_post->date; ?></h3>
                            <div class="text-block single_col align_left">
                                <p><?php echo $gigs_post->description; ?> </p>
                                <p><a href="<?php echo $gigs_post->url; ?>"><?php echo $gigs_post->url; ?></a></p>
                                <p>Location: <?php echo $gigs_post->location; ?><br />Time: <?php echo $gigs_post->time; ?><br />Price: <?php echo $gigs_post->price; ?></p>
                                <p>Hope to see you there!</p>
                                <img aria-describedby="caption-attachment-34084" loading="lazy" src="<?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?>" alt="" width="226" height="300" class="size-medium wp-image-34084" srcset="<?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 226w, <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 771w,  <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 768w, <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 1156w, <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 1542w, <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 300w, <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 600w, <?php echo base_url('assets/images/site-images/gigs/'.$gigs_post->img) ?> 1882w" sizes="(max-width: 226px) 100vw, 226px" />
                            </div>
                        </div>
                    </div>
                <?php }*/ ?></section>

            <section style="padding: 4em 3em">
                <div class="text-center container">
                    <a class="button" href="#" onclick="loadPagination();return false;" id="loadMoreBlog" title="Load More">LOAD MORE</a>
                </div>
            </section>


            <!--section 4 end --->

            <script>
                let perPage = 6;

                const initPosts = (start=0)=> {
                    let url = '<?php echo site_url() ?>blog/loadmore';
                    let form = new FormData();
                    form.set('start',start);
                    form.set('limit',6);
                    form.set('post_type','<?php echo !empty($post_type) ? $post_type : 'post' ?>');
                    <?php
                    $post_type_ = $post_type === 'gig_event' ? 'event':'post';
                    ?>
                    fetch(url, {
                        method: "POST",
                        body: form
                    }).then(res=>res.json()).then((result)=>{
                        if(result.posts) {
                            result.posts.forEach((post)=>{
                                const postUrl = '<?php echo site_url() ?>blog/'+post.slug;
                                let content = post.content;
                                const htmlContent = `<article class="loadsblog animated fadeInDown" style="display: block">
                        <a href="${postUrl}" title="${post.title}">
                        <div class="featured">
                             <img width="300" height="300" src="${post.img_path}" class="post-image" alt="${post.title}" loading="lazy">
                        <div class="layer"></div>
                        </div>
                    </a>

                    <div class="details">
                        <h3><a href="${postUrl}" title="${post.title}">${post.title}</a></h3>
                        <p>${content}</p>
                        <a class="button" href="${postUrl}" title="${post.title}">Read More</a>
                    </div>
                    </article>`;
                                document.querySelector('#articles-<?php echo $rand ?>').innerHTML += htmlContent;
                            });
                        }else {
                            document.querySelector('#loadMoreBlog').style.display = 'none';
                        }

                        let totalShown = document.querySelector('#articles-<?php echo $rand ?>').childElementCount;
                        const totalResults = parseInt(result.total);
                        if(totalShown >= totalResults) {
                            document.querySelector('#loadMoreBlog').style.display = 'none';
                        }
                    });
                }

                initPosts();

                loadPagination = ()=> {
                    let totalShown = document.querySelector('#articles-<?php echo $rand ?>').childElementCount;
                    let start = totalShown;

                    initPosts(start);
                }
            </script>
        </div>
    </div>
</section>