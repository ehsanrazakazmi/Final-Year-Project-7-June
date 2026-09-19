{{-- The lordicon loader was repeated on every item; once is enough. --}}
<script src="https://cdn.lordicon.com/bhenfmcm.js"></script>

<aside class="side-nav">
    {{-- public/img/admin.jpg has never existed, so the old <img> rendered as a
         broken icon. A CSS monogram needs no asset. --}}
    <div class="logo">
        <span class="logo-mark" aria-hidden="true">GB</span>
        <span class="logo-text">
            Technician Panel
            <small>{{ Str::limit(Auth::user()->name ?? '', 22) }}</small>
        </span>
    </div>

    <ul>
        <li>
            <a href="{{ route('technicianpanel.introduction') }}"
               class="{{ request()->routeIs('technicianpanel.introduction') ? 'is-active' : '' }}">
                <lord-icon src="https://cdn.lordicon.com/bhfjfgqz.json" trigger="hover"
                           colors="primary:#cbd5e1"></lord-icon>
                <span>Profile</span>
            </a>
        </li>

        <li>
            <a href="{{ route('technicianpanel') }}"
               class="{{ request()->routeIs('technicianpanel') ? 'is-active' : '' }}">
                <lord-icon src="https://cdn.lordicon.com/lthhecik.json" trigger="hover"
                           colors="primary:#cbd5e1,secondary:#818cf8"></lord-icon>
                <span>Orders</span>
            </a>
        </li>

        <li>
            <a href="{{ route('technicianpanel.confirmed') }}"
               class="{{ request()->routeIs('technicianpanel.confirmed') ? 'is-active' : '' }}">
                <lord-icon src="https://cdn.lordicon.com/egiwmiit.json" trigger="hover"
                           colors="primary:#cbd5e1"></lord-icon>
                <span>Order History</span>
            </a>
        </li>

        <li>
            <a href="{{ route('chatting') }}"
               class="{{ request()->routeIs('chatting') ? 'is-active' : '' }}">
                <lord-icon src="https://cdn.lordicon.com/hpivxauj.json" trigger="hover"
                           colors="primary:#cbd5e1"></lord-icon>
                <span>Chat</span>
            </a>
        </li>

        <li>
            <a href="{{ route('technicianpanel.pages.profile') }}"
               class="{{ request()->routeIs('technicianpanel.pages.profile') ? 'is-active' : '' }}">
                <lord-icon src="https://cdn.lordicon.com/edxgdhxu.json" trigger="hover"
                           colors="primary:#cbd5e1,secondary:#818cf8"></lord-icon>
                <span>Edit Profile</span>
            </a>
        </li>
    </ul>

    <div class="logout">
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M5 21q-.825 0-1.413-.587Q3 19.825 3 19V5q0-.825.587-1.413Q4.175 3 5 3h7v2H5v14h7v2Zm11-4l-1.375-1.45l2.55-2.55H9v-2h8.175l-2.55-2.55L16 7l5 5Z"/></svg>
                <span>Log out</span>
            </button>
        </form>
    </div>
</aside>
