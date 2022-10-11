<?php $__env->startSection('content'); ?>
    <h1>Hello World</h1>

    <p>
        This view is loaded from module: <?php echo config('productattribute.name'); ?>

    </p>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('productattribute::layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sharmhost/babystore/Modules/ProductAttribute/Resources/views/index.blade.php ENDPATH**/ ?>