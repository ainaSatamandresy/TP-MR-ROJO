<?php
/**
 * Pied de page du site
 */
?>
    </main>
    <?php if (empty($hide_global_footer)): ?>
        <footer class="front-footer">
            <div class="container">
                <p>&copy; 2026 <?php echo escapeHtml((string) SITE_NAME); ?> - Tous droits reserves</p>
                <p><a href="/">Accueil</a> | <a href="/actualites/">Actualites</a> | <a href="/contact/">Contact</a></p>
            </div>
        </footer>
    <?php endif; ?>
    <script src="/assets/js/script.js" defer></script>
</body>
</html>
