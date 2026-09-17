<nav class="menu">
    <img class="logo" src="<?php echo e(asset('images/ali_logo.PNG')); ?>" alt="hiii">

    <ul>
        <li>
            <a href="<?php echo e(route('home')); ?>">
                
                <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
<lord-icon
    src="https://cdn.lordicon.com/osuxyevn.json"
    trigger="hover"
    colors="primary:#121331"
    style="width:40px;height:40px">
</lord-icon>
            </a>
        </li>
        <li>
            <a href="<?php echo e(route('wishlist')); ?>">
                <?php if(auth()->guard()->check()): ?>
                <span class="info-count"><?php echo e(count(auth()->user()->wishlist)); ?></span>

                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M10 19q-.425 0-.713-.288T9 18q0-.425.288-.713T10 17h10q.425 0 .713.288T21 18q0 .425-.288.713T20 19H10Zm0-6q-.425 0-.713-.288T9 12q0-.425.288-.713T10 11h10q.425 0 .713.288T21 12q0 .425-.288.713T20 13H10Zm0-6q-.425 0-.713-.288T9 6q0-.425.288-.713T10 5h10q.425 0 .713.288T21 6q0 .425-.288.713T20 7H10ZM5 20q-.825 0-1.413-.588T3 18q0-.825.588-1.413T5 16q.825 0 1.413.588T7 18q0 .825-.588 1.413T5 20Zm0-6q-.825 0-1.413-.588T3 12q0-.825.588-1.413T5 10q.825 0 1.413.588T7 12q0 .825-.588 1.413T5 14Zm0-6q-.825 0-1.413-.588T3 6q0-.825.588-1.413T5 4q.825 0 1.413.588T7 6q0 .825-.588 1.413T5 8Z"/></svg>            </a>
        </li>
        <li>
            <a href="<?php echo e(route('cart')); ?>">
                <span class="info-count"><?php echo e(session()->has('cart') ? count(session('cart')) : 0); ?></span>
                
                <script src="https://cdn.lordicon.com/bhenfmcm.js"></script>
                <lord-icon
                    src="https://cdn.lordicon.com/hyhnpiza.json"
                    trigger="hover"
                    colors="primary:#121331"
                    style="width:40px;height:40px">
                </lord-icon>
            </a>
        </li>
        
        <li>
            <a href="<?php echo e(route('chatting')); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 20l1.3-3.9C1.976 12.663 2.874 8.228 6.4 5.726c3.526-2.501 8.59-2.296 11.845.48c3.255 2.777 3.695 7.266 1.029 10.501C16.608 19.942 11.659 20.922 7.7 19L3 20"/></svg>            </a>
        </li>
        <li>
            <a href="<?php echo e(route('account')); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512"><path fill="currentColor" d="M472 48H40a24.028 24.028 0 0 0-24 24v368a24.028 24.028 0 0 0 24 24h88v-58.822a20.01 20.01 0 0 1 10.284-17.478l91.979-51.123L200 260.919V200a56 56 0 0 1 112 0v60.919l-30.263 75.655l91.979 51.126A20.011 20.011 0 0 1 384 405.178V464h88a24.028 24.028 0 0 0 24-24V72a24.028 24.028 0 0 0-24-24Zm-8 384h-48v-26.822a52.027 52.027 0 0 0-26.738-45.451L321.915 322.3L344 267.081V200a88 88 0 0 0-176 0v67.081l22.085 55.219l-67.347 37.432A52.027 52.027 0 0 0 96 405.178V432H48V80h416Z"/></svg>
            </a>
        </li>
    </ul>



</nav><?php /**PATH C:\xampp\htdocs\FYP-7-6-2023\resources\views/layouts/partials/nav.blade.php ENDPATH**/ ?>