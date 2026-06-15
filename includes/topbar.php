<?php
$currentParams = $_GET;
?>




<div class="top-nav">

    <div class="top-nav-search">

        <form method="get" action="members.php">

            <input
                type="text"
                name="search"
                placeholder="<?php echo t('search_placeholder'); ?>">

            <button type="submit">
                <?php echo t('search'); ?>
            </button>

        </form>

    </div>

    <div class="top-nav-actions">

<div class="language-switcher">
<a href="?<?php echo http_build_query(array_merge($currentParams, ['lang' => 'el'])); ?>">
    <span class="flag-badge">GR</span> Ελληνικά
</a>

<a href="?<?php echo http_build_query(array_merge($currentParams, ['lang' => 'en'])); ?>">
    <span class="flag-badge">EN</span> English
</a>

<a href="?<?php echo http_build_query(array_merge($currentParams, ['lang' => 'ru'])); ?>">
    <span class="flag-badge">RU</span> Русский
</a>
</div>

        <a href="members.php?filter=today">
            📅 <?php echo t('today'); ?>
        </a>

        <a href="members.php?filter=overdue">
            🔴 <?php echo t('overdue'); ?>
        </a>

        <span class="top-user">
            👤 Χρήστος
        </span>

    </div>

</div>