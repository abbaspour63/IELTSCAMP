
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

       

        <?php if ( is_user_logged_in() ) : ?>

            <div class="flex items-center gap-3">
                <a href="<?php echo esc_url(home_url('/prof`ile/')); ?>"
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
               class="rounded-xl bg-blue-700 px-5 py-3 font-bold text-white transition hover:bg-blue-800">
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


<nav class="border-t border-slate-100">
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
</nav>

</header>
