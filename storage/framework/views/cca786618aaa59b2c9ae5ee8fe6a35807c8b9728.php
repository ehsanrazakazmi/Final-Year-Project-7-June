<?php $__env->startSection('content'); ?>
<div>
    <?php if(session('success')): ?>
        <div class="alert alert-success mx-4" role="alert">
            <span class="text-white"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger mx-4" role="alert">
            <span class="text-white"><?php echo e($errors->first()); ?></span>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 p-3">
                <div class="card-header pb-0">
                    <div class="mb-3 d-flex flex-row justify-content-between align-items-center">
                        <h5 class="mb-0" style="font-size: 24px">All Users</h5>
                        <a href="<?php echo e(route('adminpanel.users.create')); ?>" class="btn bg-gradient-primary btn-sm mb-0">
                            +&nbsp;Add User
                        </a>
                    </div>

                    <form method="GET" action="<?php echo e(route('adminpanel.users.index')); ?>" class="row g-2 mb-3">
                        <div class="col-sm-5">
                            <input type="text" name="search" value="<?php echo e($search); ?>" class="form-control form-control-sm"
                                   placeholder="Search name or email">
                        </div>
                        <div class="col-sm-4">
                            <select name="role" class="form-control form-control-sm">
                                <option value="">All roles</option>
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($option); ?>" <?php if($role === $option): echo 'selected'; endif; ?>>
                                        <?php echo e(ucfirst($option)); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-sm bg-gradient-secondary mb-0 w-100">Filter</button>
                        </div>
                    </form>
                </div>

                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="ps-4"><p class="text-xs font-weight-bold mb-0"><?php echo e($user->id); ?></p></td>
                                        <td><p class="text-xs font-weight-bold mb-0"><?php echo e($user->name); ?></p></td>
                                        <td><p class="text-xs text-secondary mb-0"><?php echo e($user->email); ?></p></td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-info">
                                                <?php echo e(ucfirst($user->roleName() ?? 'none')); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($user->email_verified_at): ?>
                                                <span class="badge badge-sm bg-gradient-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-sm bg-gradient-warning">Invitation pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0">
                                                <?php echo e($user->created_at?->format('d M Y') ?? '-'); ?>

                                            </p>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="<?php echo e(route('adminpanel.users.edit', $user)); ?>"
                                               class="btn btn-link text-dark px-2 mb-0">Edit</a>

                                            <?php if (! ($user->email_verified_at)): ?>
                                                <form method="POST" class="d-inline"
                                                      action="<?php echo e(route('adminpanel.users.resend', $user)); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-link text-info px-2 mb-0">
                                                        Resend invite
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <form method="POST" class="d-inline"
                                                  action="<?php echo e(route('adminpanel.users.destroy', $user)); ?>"
                                                  onsubmit="return confirm('Delete <?php echo e($user->name); ?>? This cannot be undone.');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-link text-danger px-2 mb-0">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-sm py-4">No users found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="px-4 pt-3">
                        <?php echo e($users->links('pagination::bootstrap-4')); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.user_type.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/adminpanel/users/index.blade.php ENDPATH**/ ?>