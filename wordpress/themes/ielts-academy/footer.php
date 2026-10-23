
<footer class="mt-16 bg-slate-950 text-slate-300" dir="rtl">
    <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">

        <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 lg:grid-cols-4">

            <!-- معرفی آکادمی -->
            <div>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                   class="mb-4 inline-block text-2xl font-extrabold text-white">
                    IELTSCAMP
                </a>

                <p class="max-w-xs text-sm leading-7 text-slate-400">
                    همراه شما در مسیر یادگیری زبان انگلیسی و آمادگی برای آزمون آیلتس.
                </p>
            </div>

            <!-- دسترسی سریع -->
            <div>
                <h2 class="mb-5 text-base font-bold text-white">
                    دسترسی سریع
                </h2>

                <ul class="space-y-3 text-sm">
                    <li>
                        <a class="transition hover:text-sky-400"
                           href="<?php echo esc_url( home_url( '/' ) ); ?>">
                            صفحه اصلی
                        </a>
                    </li>
                    <li>
                        <a class="transition hover:text-sky-400"
                           href="<?php echo esc_url( home_url( '/courses/' ) ); ?>">
                            دوره‌های آموزشی
                        </a>
                    </li>
                    <li>
                        <a class="transition hover:text-sky-400"
                           href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">
                            مقالات آموزشی
                        </a>
                    </li>
                </ul>
            </div>

            <!-- خدمات -->
            <div>
                <h2 class="mb-5 text-base font-bold text-white">
                    خدمات ما
                </h2>

                <ul class="space-y-3 text-sm">
                    <li>
                        <a class="transition hover:text-sky-400"
                           href="<?php echo esc_url( home_url( '/consultation/' ) ); ?>">
                            مشاوره آیلتس
                        </a>
                    </li>
                    <li>
                        <a class="transition hover:text-sky-400"
                           href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>">
                            پروفایل دانشجو
                        </a>
                    </li>
                </ul>
            </div>

            <!-- ارتباط -->
            <div>
                <h2 class="mb-5 text-base font-bold text-white">
                    ارتباط با ما
                </h2>

                <p class="text-sm leading-7 text-slate-400">
                    برای اطلاع از دوره‌ها و خدمات آموزشی، از بخش مشاوره سایت استفاده کنید.
                </p>

                <a href="<?php echo esc_url( home_url( '/consultation/' ) ); ?>"
                   class="mt-4 inline-flex rounded-lg bg-sky-500 px-4 py-2.5
                          text-sm font-semibold text-white transition hover:bg-sky-400">
                    درخواست مشاوره
                </a>
            </div>

        </div>

        <!-- کپی‌رایت -->
        <div class="mt-10 border-t border-slate-800 pt-6 text-center text-sm text-slate-400">
            <p>
                © <?php echo esc_html( wp_date( 'Y' ) ); ?>
                IELTSCAMP — تمامی حقوق محفوظ است.
            </p>
        </div>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
