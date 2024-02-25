<?php $this->extends('layouts/products'); ?>
<h1>产品</h1>
<p>
    这是<?php print $product; ?>的产品页面。
    <?php print $this->escape($scary); ?>
</p>