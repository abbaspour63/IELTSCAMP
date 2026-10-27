
<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="border-b border-slate-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-6 py-4">

       
<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
   class="flex items-center gap-3"
   rel="home">

    <?php if ( has_custom_logo() ) : ?>

        <?php the_custom_logo(); ?>

    <?php else : ?>

        <span class="text-2xl font-extrabold text-blue-950">
              <img
        src="<?php echo esc_url(
            get_theme_file_uri( './assets/images/logo.svg' )
        ); ?>"
        alt="IELTSCAMP"
        class="h-12 w-auto max-w-45 object-contain"
    >
        </span>

    <?php endif; ?>

</a>
<button
    id="mobile-menu-toggle"
    type="button"
    class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-2 text-slate-700 hover:bg-slate-100 md:hidden"
    aria-controls="site-navigation"
    aria-expanded="false"
    aria-label="باز کردن منو"
>
    <svg
        id="mobile-menu-icon"
        xmlns="http://www.w3.org/2000/svg"
        class="h-6 w-6"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        stroke-width="2"
        aria-hidden="true"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M4 6h16M4 12h16M4 18h16"
        />
    </svg>
</button>
       

        <?php if ( is_user_logged_in() ) : ?>

            <div class="hidden items-center gap-3 md:flex">
                <a href="<?php echo esc_url(home_url('/profile/')); ?>"
                   class="rounded-xl bg-blue-700 px-5 py-3 font-bold text-white transition hover:bg-blue-800">
                    پروفایل من
                </a>

                <a href="<?php echo esc_url(wp_logout_url(home_url('/login/'))); ?>"
                   class="rounded-xl border border-slate-300 px-5 py-3 font-bold text-slate-700 transition hover:bg-slate-100">
                    خروج
                </a>
            </div>

        <?php else : ?>

            <a href="<?php echo esc_url(home_url('/login/')); ?>"
               class="hidden rounded-xl bg-blue-700 px-5 py-3 font-bold text-white transition hover:bg-blue-800 md:inline-flex">
                ورود و ثبت نام
            </a>

        <?php endif; ?>


    </div>

    <!-- <nav class="border-t border-slate-100">
        <div class="mx-auto flex max-w-7xl flex-wrap gap-6 px-6 py-3 text-sm font-medium text-slate-700">
            <a href="<?php echo esc_url(home_url('/')); ?>">صفحه اصلی</a>
            <a href="<?php echo esc_url(home_url('/courses/')); ?>">دوره‌ها</a>
            <a href="<?php echo esc_url(home_url('/blog/')); ?>">مقالات</a>
            <a href="<?php echo esc_url(home_url('/about/')); ?>">درباره ما</a>
            <a href="<?php echo esc_url(home_url('/contact/')); ?>">تماس با ما</a>
        </div>
    </nav> -->


<!-- <nav class="border-t border-slate-100">
    <div class="mx-auto flex max-w-7xl justify-center px-6 py-3">

        <div class="flex flex-wrap items-center justify-center gap-8 text-sm font-medium text-slate-700">

            <a href="<?php echo esc_url(home_url('/')); ?>"
               class="transition hover:text-blue-700">
                صفحه اصلی
            </a>

            <a href="<?php echo esc_url(home_url('/courses/')); ?>"
               class="transition hover:text-blue-700">
                دوره‌ها
            </a>

            <a href="<?php echo esc_url(home_url('/blog/')); ?>"
               class="transition hover:text-blue-700">
                مقالات
            </a>

            <a href="<?php echo esc_url(home_url('/about/')); ?>"
               class="transition hover:text-blue-700">
                درباره ما
            </a>

            <a href="<?php echo esc_url(home_url('/contact/')); ?>"
               class="transition hover:text-blue-700">
                تماس با ما
            </a>

        </div>
    </div>
</nav> -->

<nav id="site-navigation" class="hidden border-t border-slate-100 md:block">
    <div class="mx-auto max-w-7xl px-4 py-3 md:px-6">
        <div class="flex flex-col gap-1 text-right text-sm font-medium text-slate-700 md:flex-row md:items-center md:justify-center md:gap-8">

            <a href="<?php echo esc_url(home_url('/')); ?>"
               class="rounded-lg px-3 py-3 transition hover:bg-slate-50 hover:text-blue-700 md:px-0 md:py-1 md:hover:bg-transparent">
                صفحه اصلی
            </a>

            <a href="<?php echo esc_url(home_url('/courses/')); ?>"
               class="rounded-lg px-3 py-3 transition hover:bg-slate-50 hover:text-blue-700 md:px-0 md:py-1 md:hover:bg-transparent">
                دوره‌ها
            </a>

            <a href="<?php echo esc_url(home_url('/blog/')); ?>"
               class="rounded-lg px-3 py-3 transition hover:bg-slate-50 hover:text-blue-700 md:px-0 md:py-1 md:hover:bg-transparent">
                مقالات
            </a>

            <a href="<?php echo esc_url(home_url('/about/')); ?>"
               class="rounded-lg px-3 py-3 transition hover:bg-slate-50 hover:text-blue-700 md:px-0 md:py-1 md:hover:bg-transparent">
                درباره ما
            </a>

            <a href="<?php echo esc_url(home_url('/contact/')); ?>"
               class="rounded-lg px-3 py-3 transition hover:bg-slate-50 hover:text-blue-700 md:px-0 md:py-1 md:hover:bg-transparent">
                تماس با ما
            </a>

            <div class="mt-2 border-t border-slate-100 pt-3 md:hidden">
                <?php if (is_user_logged_in()) : ?>

                    <a href="<?php echo esc_url(home_url('/profile/')); ?>"
                       class="block rounded-lg px-3 py-3 font-bold text-blue-700 hover:bg-slate-50">
                        پروفایل من
                    </a>

                    <a href="<?php echo esc_url(wp_logout_url(home_url('/login/'))); ?>"
                       class="block rounded-lg px-3 py-3 font-bold text-slate-700 hover:bg-slate-50">
                        خروج
                    </a>

                <?php else : ?>

                    <a href="<?php echo esc_url(home_url('/login/')); ?>"
                       class="block rounded-lg bg-blue-700 px-4 py-3 text-center font-bold text-white hover:bg-blue-800">
                        ورود و ثبت نام
                    </a>

                <?php endif; ?>
            </div>

        </div>
    </div>
</nav>


</header>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('site-navigation');
    const icon = document.getElementById('mobile-menu-icon');

    if (!toggle || !menu || !icon) {
        return;
    }

    function closeMenu() {
        menu.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'باز کردن منو');

        icon.innerHTML =
            '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />';
    }

    toggle.addEventListener('click', function () {
        const isOpen = toggle.getAttribute('aria-expanded') === 'true';

        if (isOpen) {
            closeMenu();
        } else {
            menu.classList.remove('hidden');
            toggle.setAttribute('aria-expanded', 'true');
            toggle.setAttribute('aria-label', 'بستن منو');

            icon.innerHTML =
                '<path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />';
        }
    });

    menu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMenu);
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            closeMenu();
        }
    });
});
</script>
