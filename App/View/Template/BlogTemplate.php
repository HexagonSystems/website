<article class="post">
    <header>
        <?php echo $blog->getTitle(); ?>
    </header>
    <ul>
        <li>Posted by <?php echo $blog->getUserName();?></li>
        <li><?php echo $blog->getdate(); ?></li>
    </ul>
    <article>
        <?php echo $blog->getContent(); ?>
    </article>
</article>
