<?php include_once VIEW . "Client/base/header.php" ?>
<?php if(isset($message) && $message != "") : ?>
    <script>alert("<?= $message ?>")</script>
       <?php endif; ?>
<div class="main">
    <div class="baner">
        <img class="baner-img" src="Assets/images/baner/baner1.jpg" alt="">
    </div>
    <div class="about-texts">
        <div class="texts">
            <div class="about-text-1">MODA FUKADA</div>
            <div class="about-text-2">là một người mẫu nổi bật trong lĩnh vực thời trang Nhật Bản, nổi bật với phong cách thanh lịch và sự sáng tạo trong các bộ sưu tập</div>
        </div>
    </div>
    <div class="container">
        <div class="products">
            <?php foreach($products as $values):?>
                <?php if(strtoupper($values['type']) == "ÁO"): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?= $values['image']   ?>" alt="">
                </div>
                <a class="detail-btn" href="index.php?act=Detail&id=<?= $values['id'] ?>"><button class="detail-product-btn">XEM NGAY</button></a>
                <div class="product-name-price">
                    <h3><?= $values['name'] ?></h3>
                    <span><?= number_format($values['price'], 0, ',', '.') ?> vnđ</span>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="short-baner">
            <img src="images/short-baner/short-baner.png" alt="">
        </div>
        <div class="title-card"><span>BEST SELLER</span></div>
        <div class="products">
        <?php foreach($topseller as $values): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?= $values['image'] ?>" alt="">
                </div>
                <a class="detail-btn" href="index.php?act=Detail&id=<?= $values['id'] ?>"><button class="detail-product-btn">XEM NGAY</button></a>
                <div class="product-name-price">
                    <h3><?= $values['name'] ?></h3>
                    <span><?= number_format($values['price'], 0, ',', '.') ?> vnđ</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="show-more"><a href="index.php?act=Products"><button class="show-more-btn">< SEE MORE></button></a></div>
    </div>
</div>
<?php include_once VIEW . "Client/base/foot.php" ?>