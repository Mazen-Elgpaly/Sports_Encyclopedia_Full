<!-- FAQ -->
 
<?php
$extraCss = ['faq.css']; 
?>
<?php ob_start(); ?>
<div class="content-wrapper" style="max-width:960px;margin:2rem auto;padding:0 2rem;">
    <h1 class="faq-title">FAQ</h1>
    <div class="search-box-container">
        <div class="search-box">
            <span class="search-icon"><i class="bi bi-search"></i></span>
            <input class="search-input" id="faqSearch" type="text" placeholder="Search questions...">
        </div>
    </div>
    <div class="faq-container" id="faqList">
        <?php foreach ($faqs as $i => $item): ?>
        <div class="faq-item" data-q="<?= htmlspecialchars(strtolower($item['q'])) ?>">
            <details>
                <summary class="faq-summary">
                    <span class="faq-question"><?= htmlspecialchars($item['q']) ?></span>
                    <span class="material-symbols-outlined" style="font-family:'Material Symbols Outlined';font-size:1.3rem;color:#9aa3a6;">expand_more</span>
                </summary>
                <p class="faq-answer"><?= htmlspecialchars($item['a']) ?></p>
            </details>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="footer" style="margin-top:3rem;text-align:center;color:#9aa3a6;">
        <p>Still have questions? <a href="<?= BASE_URL ?>/contact" style="color:#0da6f2;">Contact us</a></p>
    </div>
</div>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/faq.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
<script>
document.getElementById('faqSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase().trim();
    document.querySelectorAll('#faqList .faq-item').forEach(item => {
        item.style.display = (!q || item.dataset.q.includes(q)) ? '' : 'none';
    });
});
</script>
<?php $faqContent = ob_get_clean(); echo $faqContent; ?>
