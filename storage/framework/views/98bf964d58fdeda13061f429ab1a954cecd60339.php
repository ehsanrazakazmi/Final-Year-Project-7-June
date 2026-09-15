<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-7 col-md-9 mx-auto">
        <div class="card mb-4 mx-4 p-3">
            <div class="card-header pb-0">
                <h5 class="mb-1" style="font-size: 22px">Add User</h5>
                <p class="text-sm text-secondary mb-0">
                    The user will receive an email invitation and choose their own password.
                </p>
            </div>

            <div class="card-body">
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0 ps-3 text-white">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('adminpanel.users.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="form-group mb-3">
                        <label for="name" class="form-control-label"><?php echo e(__('Full Name')); ?></label>
                        <input id="name" name="name" type="text" class="form-control"
                               value="<?php echo e(old('name')); ?>" placeholder="Name" required autofocus>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email" class="form-control-label"><?php echo e(__('Email')); ?></label>
                        <input id="email" name="email" type="email" class="form-control"
                               value="<?php echo e(old('email')); ?>" placeholder="name@example.com" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="role" class="form-control-label"><?php echo e(__('Role')); ?></label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="">Select a role</option>
                            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($option); ?>" <?php if(old('role') === $option): echo 'selected'; endif; ?>>
                                    <?php echo e(ucfirst($option)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?php echo e(route('adminpanel.users.index')); ?>" class="btn btn-link text-dark mb-0">Cancel</a>
                        <button type="submit" class="btn bg-gradient-primary mb-0">Create and send invitation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user_type.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/adminpanel/users/create.blade.php ENDPATH**/ ?>