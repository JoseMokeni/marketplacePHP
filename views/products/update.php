<body>
<?php
include_once __DIR__ . '/../partials/_navbar.php';
?>

<!--    tailwind css update product banner-->

<!--todo:finish update form-->
<div class="bannerAddProd w-full mt-8">
    <h1 class="text-4xl text-center font-bold">Update Product</h1>
</div>

<!--    addProductForm-->
<form method="POST" class="w-[85%] lg:w-1/2 mx-auto mt-8" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">
    <div class="mb-4">
        <label for="name" class="sr-only">Name</label>
        <input type="text" name="name" id="name" placeholder="Product name" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="<?=$product['name'] ?>" required>
    </div>
    <div class="mb-4">
        <label for="description" class="sr-only">Description</label>
        <textarea name="description" id="description" cols="30" rows="4" placeholder="Description" class="bg-gray-100 border-2 w-full p-4 rounded-lg" required><?=$product['description'] ?></textarea>
    </div>
    <div class="mb-4">
        <label for="price" class="sr-only">Price</label>
        <input type="number" name="price" id="price" placeholder="Price" class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="<?=$product['price'] ?>" required>
    </div>
    <div class="mb-4">
        <label for="image" class="sr-only">Image</label>
        <input type="file" name="image" id="image" placeholder="Image" class="bg-gray-100 border-2 w-full p-4 rounded-lg">
    </div>
    <div class="mb-4">
        <label for="category" class="sr-only">Category</label>
        <select name="category_id" id="category" class="bg-gray-100 border-2 w-full p-4 rounded-lg">
            <option value="">Select a category</option>
            <?php foreach ($categories as $category): ?>
                <?php if ($category['id'] == $product['category_id']): ?>
                    <option value="<?= $category['id'] ?>" selected><?= $category['name'] ?></option>
                <?php else: ?>
                    <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-4">
        <label for="status" class="sr-only">Category</label>
        <select name="status" id="status" class="bg-gray-100 border-2 w-full p-4 rounded-lg">
            <option value="">Select the status of the product</option>
            <?php if ($product['status'] == 'available'): ?>
                <option value="available" selected>Available</option>
                <option value="sold">Sold</option>
            <?php else: ?>
                <option value="sold" selected>Sold</option>
                <option value="available">Available</option>
            <?php endif; ?>
        </select>
    </div>
    <!--        hidden input with user_id-->
    <input type="hidden" name="user_id" value="<?= $user_id ?>">

    <!--        submit button-->
    <div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">Update Product</button>
    </div>
</form>

<?php
include_once __DIR__ . '/../partials/_footer.php';
?>

<script>
    let errors = <?= json_encode($errors) ?>;
    let success = <?= json_encode($success) ?>;
    if (errors.length > 0) {
        errors.forEach(error => {
            toastr.error(error);
        });
    };
    if (success.length > 0) {
        success.forEach(success => {
            toastr.success(success);
        });
    };

</script>

</body>