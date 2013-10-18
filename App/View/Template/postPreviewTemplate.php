<article class="news-article panel panel-primary">
    <header class="panel-heading">
        <h2>
            <?php echo $blog->getTitle(); ?>
        </h2>
        <time itemprop="datePublished" datetime="2009-10-09"><?php echo $blog->getdate(); ?></time>
        <span>
            Posted by <?php echo $blog->getUserName(); ?>
        </span>
    </header>
    <div class="panel-body">
    <p>
        <?php echo $blog->getContent(); ?>... <a href="index.php?location=viewBlog&blog=<?php echo $blog->getPostid(); ?>">View more</a>
    </p>
</div>
    <footer class="panel-footer">
        <span>Comments 5</span>
    </footer>
</article>