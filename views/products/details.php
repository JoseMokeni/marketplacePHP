<!--product details page-->
<?php
    include_once __DIR__ . '/../partials/_navbar.php';
?>

<body class="">
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                <?= $product['name'] ?>
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                <?= $product['category_name'] ?>
            </p>
        </div>
        <?php
//            start session
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
//            check if user is logged in
            if (isset($_SESSION['user']['id']) and $_SESSION['user']['id'] == $product['user_id']):
        ?>
            <div class="mt-5 flex md:mt-0 md:ml-4">
                <span class="hidden sm:block">
                    <a href="/products/update?id=<?=$product['id'] ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Edit product details
                    </a>
                </span>
                <span class="hidden sm:block ml-2">
                    <a href="/products/delete?id=<?=$product['id'] ?>" id="deleteBtn" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-white  bg-red-600 hover:bg-red-500  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Delete product
                    </a>
                    <script>
                        document.getElementById('deleteBtn').addEventListener('click', function (e) {
                            if (!confirm('Are you sure you want to delete this product?')) {
                                e.preventDefault();
                            }
                        });
                    </script>
                </span>
            </div>
        <?php endif; ?>
    </div>
    <div class="mt-4">
        <div class="flex flex-col lg:flex-row">
            <div class="w-full lg:w-1/2">
                <img src="<?=$product['imagePath'] ?>" alt="Image du produit" class="w-full object-center object-contain rounded-lg shadow-md">
            </div>
            <div class="w-full lg:w-1/2 lg:ml-10 mt-4 lg:mt-0">
                <h3 class="text-gray-700 uppercase text-lg font-bold">Description</h3>
                <p class="mt-2 text-gray-600">
                    <?= $product['description'] ?>
                </p>
                <div class="mt-4">
                    <h4 class="text-gray-700 uppercase text-lg font-bold">Prix</h4>
                    <p class="mt-2 text-gray-600">
                        <?= $product['price'] ?> DT
                    </p>
                    <h4 class="text-gray-700 uppercase text-lg font-bold mt-4">Statut</h4>
                    <p class="mt-2 text-gray-600">
                        <?php if ($product['status'] == "available"): ?>
                            Available
                        <?php else: ?>
                            Not available
                        <?php endif; ?>
                    </p>
<!--                    owner phone-->
                    <h4 class="text-gray-700 uppercase text-lg font-bold mt-4">Owner phone</h4>
                    <p class="mt-2 text-gray-600">
                        <?= $product['phone'] ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include_once __DIR__ . '/../partials/_footer.php';
?>
</body>


