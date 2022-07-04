<?php

require_once __DIR__ . '../../../../default-handlers.php';
?>


<footer id="colophon" class="site-footer">
    <nav class="footer-links">
        <ul>
            <li>
                <a href="contact">contact</a>
            </li>
            <li>
                <a href="faq">faq</a>
            </li>
            <li>
                <a href="https://github.com/websealevel/archives-de-bureaulogie">code source</a>
            </li>
            <li>
                <a href="https://github.com/websealevel/archives-de-bureaulogie/issues">signaler un problème</a>
            </li>
            <li>
                <a href="nous-soutenir">nous soutenir</a>
            </li>
            <li>
                <a href="https://twitter.com/archivesdb_fr">twitter</a>
            </li>
        </ul>
    </nav>
</footer>

<div id="date">
    <?php echo date('Y'); ?>
</div>

</div> <!-- #content !-->
</div> <!-- #page !-->

<?php enqueue_js_scripts($js_scripts); ?>

</body>

</html>

<?php
