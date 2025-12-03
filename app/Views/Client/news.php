<div class="breadcrumb container">
    <a href="/">Trang chủ</a> <i class="fa-solid fa-angle-right"></i>
    <span>Tin tức & Kỹ thuật</span>
</div>

<section class="blog-page container section-padding">
    <div class="blog-layout">
        
        <div class="blog-main">
            
            <?php if (!empty($newsList)): ?>
                
                <?php 
                    $heroPost = $newsList[0]; 
                    $heroImg = !empty($heroPost->thumbnail) ? '/assets/uploads/news/'.$heroPost->thumbnail : 'https://placehold.co/800x450?text=No+Img';
                ?>
                <article class="blog-card hero-post">
                    <div class="img-box">
                        <span class="cat-tag">Nổi bật</span>
                        <a href="/news/detail/<?php echo $heroPost->id; ?>">
                            <img src="<?php echo $heroImg; ?>" alt="<?php echo $heroPost->title; ?>">
                        </a>
                    </div>
                    <div class="info">
                        <div class="meta">
                            <span><i class="fa-regular fa-calendar"></i> <?php echo date('d/m/Y', strtotime($heroPost->created_at)); ?></span>
                            <span><i class="fa-regular fa-user"></i> <?php echo $heroPost->author_name ?? 'Admin'; ?></span>
                        </div>
                        <h2 class="title">
                            <a href="/news/detail/<?php echo $heroPost->id; ?>"><?php echo $heroPost->title; ?></a>
                        </h2>
                        <p class="desc"><?php echo $heroPost->summary; ?></p>
                        <a href="/news/detail/<?php echo $heroPost->id; ?>" class="read-more">Xem chi tiết <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </article>

                <div class="blog-grid">
                    <?php 
                        // Lấy từ bài thứ 2 trở đi
                        $subList = array_slice($newsList, 1); 
                        foreach ($subList as $item):
                            $thumb = !empty($item->thumbnail) ? '/assets/uploads/news/'.$item->thumbnail : 'https://placehold.co/400x250?text=No+Img';
                    ?>
                        <article class="blog-card">
                            <div class="img-box">
                                <a href="/news/detail/<?php echo $item->id; ?>">
                                    <img src="<?php echo $thumb; ?>" alt="<?php echo $item->title; ?>">
                                </a>
                            </div>
                            <div class="info">
                                <div class="meta"><span><i class="fa-regular fa-calendar"></i> <?php echo date('d/m/Y', strtotime($item->created_at)); ?></span></div>
                                <h3 class="title">
                                    <a href="/news/detail/<?php echo $item->id; ?>"><?php echo $item->title; ?></a>
                                </h3>
                                <p class="desc">
                                    <?php echo (strlen($item->summary) > 100) ? substr($item->summary, 0, 100) . '...' : $item->summary; ?>
                                </p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="pagination">
                    <a href="#" class="page-link prev"><i class="fa-solid fa-angle-left"></i></a>
                    <a href="#" class="page-link active">1</a>
                    <a href="#" class="page-link next"><i class="fa-solid fa-angle-right"></i></a>
                </div>

            <?php else: ?>
                <p style="text-align:center; padding:30px; color:#999;">Chưa có bài viết nào.</p>
            <?php endif; ?>

        </div>

        <aside class="blog-sidebar">
            
            <form action="/news" method="GET" class="sidebar-widget search-widget">
                <input type="text" name="keyword" placeholder="Tìm kiếm bài viết..." value="<?php echo $_GET['keyword'] ?? ''; ?>">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            <div class="sidebar-widget">
                <h3 class="widget-title">Chuyên mục</h3>
                <ul class="cat-list">
                    <li><a href="/news">Tất cả bài viết</a></li>
                    
                    <?php if (!empty($news_menu)): foreach ($news_menu as $cat): ?>
                        <li>
                            <a href="/news?cat=<?php echo $cat->id; ?>">
                                <?php echo $cat->name; ?> 
                                </a>
                        </li>
                    <?php endforeach; endif; ?>
                </ul>
            </div>

            <div class="sidebar-widget">
                <h3 class="widget-title">Bài viết mới</h3>
                <div class="popular-posts">
                    <?php if(!empty($newsList)): 
                        // Lấy 3 bài mới nhất làm widget
                        $recentPosts = array_slice($newsList, 0, 3);
                        foreach($recentPosts as $post):
                            $pThumb = !empty($post->thumbnail) ? '/assets/uploads/news/'.$post->thumbnail : 'https://placehold.co/80x80';
                    ?>
                        <a href="/news/detail/<?php echo $post->id; ?>" class="post-item">
                            <div class="post-thumb"><img src="<?php echo $pThumb; ?>" alt="Img"></div>
                            <div class="post-info">
                                <h4><?php echo $post->title; ?></h4>
                                <span class="date"><?php echo date('d/m/Y', strtotime($post->created_at)); ?></span>
                            </div>
                        </a>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <div class="sidebar-widget ads-widget">
                <img src="https://placehold.co/300x400?text=Quang+Cao+Thuoc" alt="Ads">
            </div>

        </aside>

    </div>
</section>