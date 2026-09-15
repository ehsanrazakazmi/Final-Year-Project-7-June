
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-1">Set your password</h4>
                    <p class="mb-0 text-sm text-muted">
                        Welcome, <?php echo e($user->name); ?>. Choose a password to activate your
                        <?php echo e($user->roleName() ?? 'account'); ?> account.
                    </p>
                </div>

                <div class="card-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0 ps-3">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('password.set.store')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="token" value="<?php echo e($token); ?>">

                        <div class="form-group mb-3">
                            <label for="email" class="form-control-label"><?php echo e(__('Email')); ?></label>
                            <input id="email" name="email" type="email" class="form-control"
                                   value="<?php echo e(old('email', $email)); ?>" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-control-label"><?php echo e(__('New password')); ?></label>
                            <input id="password" name="password" type="password" class="form-control"
                                   placeholder="At least 8 characters" required autofocus autocomplete="new-password">
                        </div>

                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="form-control-label"><?php echo e(__('Confirm password')); ?></label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                   class="form-control" placeholder="Repeat your password" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <?php echo e(__('Set password and continue')); ?>

                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/auth/set-password/_form.blade.php ENDPATH**/ ?>