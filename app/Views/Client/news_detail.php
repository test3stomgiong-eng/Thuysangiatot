<div class="breadcrumb container">
    <a href="/">Trang chủ</a> <i class="fa-solid fa-angle-right"></i>
    <a href="/news">Tin tức & Kỹ thuật</a> <i class="fa-solid fa-angle-right"></i>
    <span><?php echo $news->title; ?></span>
</div>

<section class="blog-page container section-padding">
    <div class="blog-layout">

        <div class="blog-main">

            <article class="article-container">

                <div class="article-header">
                    <span class="cat-label"><?php echo $news->category_name ?? 'Tin tức'; ?></span>
                    <h1 class="main-title"><?php echo $news->title; ?></h1>
                    <div class="meta-info">
                        <span><i class="fa-regular fa-calendar"></i> <?php echo date('d/m/Y', strtotime($news->created_at)); ?></span>
                        <span><i class="fa-regular fa-user"></i> Bởi: <?php echo $news->author_name ?? 'Admin'; ?></span>
                        <span>
                            <i class="fa-regular fa-eye"></i>
                            <?php echo number_format($news->views); ?> lượt xem
                        </span>
                    </div>
                </div>

                <div class="article-content">

                    <p class="lead-text" style="font-weight:bold; font-size:16px; color:#555; margin-bottom:20px;">
                        <?php echo $news->summary; ?>
                    </p>

                    <div class="content-body-text">
                        <?php echo $news->content; ?>
                    </div>

                </div>

                <div class="share-post">
                    <span>Chia sẻ bài viết:</span>
                    <a href="#" class="fb"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="zalo"><i class="fa-solid fa-z"></i></a>
                </div>

                <?php if (!empty($related_news)): ?>
                    <div class="related-posts-section">
                        <h3 class="sec-title">Bài viết liên quan</h3>
                        <div class="related-grid">
                            <?php foreach ($related_news as $item): ?>
                                <?php
                                $thumb = !empty($item->thumbnail) ? '/assets/uploads/news/' . $item->thumbnail : 'https://placehold.co/300x200';
                                ?>
                                <a href="/news/detail/<?php echo $item->id; ?>" class="related-item">
                                    <img src="<?php echo $thumb; ?>" alt="<?php echo $item->title; ?>">
                                    <h4><?php echo $item->title; ?></h4>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </article>
        </div>

        <aside class="blog-sidebar">

            <form action="/news" method="GET" class="sidebar-widget search-widget">
                <input type="text" name="keyword" placeholder="Tìm kiếm bài viết...">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>

            <div class="sidebar-widget">
                <h3 class="widget-title">Chuyên mục</h3>
                <ul class="cat-list">
                    <li><a href="/news">Tất cả bài viết</a></li>
                    <?php if (!empty($news_menu)): foreach ($news_menu as $cat): ?>
                            <li><a href="/news?cat=<?php echo $cat->id; ?>"><?php echo $cat->name; ?></a></li>
                    <?php endforeach;
                    endif; ?>
                </ul>
            </div>

            <?php if (!empty($suggest_products)): ?>
                <div class="sidebar-widget">
                    <h3 class="widget-title">Sản phẩm gợi ý</h3>
                    <div class="popular-posts">
                        <?php foreach ($suggest_products as $prod): ?>
                            <?php
                            $pImg = !empty($prod->main_image) ? '/assets/uploads/products/' . $prod->main_image : 'https://placehold.co/80x80';
                            ?>
                            <a href="/product/detail/<?php echo $prod->id; ?>" class="post-item">
                                <div class="post-thumb"><img src="<?php echo $pImg; ?>" alt="SP"></div>
                                <div class="post-info">
                                    <h4><?php echo $prod->name; ?></h4>
                                    <span class="date" style="color: #d0021b; font-weight: bold;">
                                        <?php echo number_format($prod->price); ?>đ
                                    </span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </aside>

    </div>
</section>