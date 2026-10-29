<?php
/**
 * Template: About IELTSCAMP
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main dir="rtl" class="bg-white text-slate-800">

    <!-- Hero -->
    <section class="bg-slate-900 text-white py-20 px-4">
        <div class="max-w-6xl mx-auto text-center">
            <span class="text-sky-300 font-bold mb-4 block">
                درباره IELTSCAMP
            </span>

            <h1 class="text-3xl md:text-5xl font-extrabold leading-relaxed mb-6">
                مسیر فشرده، هدفمند و همراهی‌شده
                <br class="hidden md:block">
                تا نمره هدف آیلتس
            </h1>

            <p class="max-w-3xl mx-auto text-slate-300
                      text-lg leading-9">
                آیلتس کمپ یک پلتفرم تخصصی برای آماده‌سازی آزمون
                آیلتس است که با تمرکز بر کمپ‌های آموزشی فشرده،
                آزمون‌های آزمایشی و بازخورد تخصصی طراحی شده است.
            </p>
        </div>
    </section>

    <!-- Introduction -->
    <section class="py-16 px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-bold mb-6">
                داستان آیلتس کمپ
            </h2>

            <p class="text-slate-600 leading-9 text-lg">
                ما می‌دانیم رسیدن به نمره هدف آیلتس فقط با مطالعه
                پراکنده اتفاق نمی‌افتد؛ بلکه به برنامه‌ای دقیق،
                تمرین هدفمند و همراهی مستمر نیاز دارد.
                آیلتس کمپ تلاش می‌کند این مسیر را برای شما
                ساده‌تر، شفاف‌تر و قابل پیگیری کند.
            </p>
        </div>
    </section>

    <!-- Mission -->
    <section class="bg-slate-50 py-16 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="text-4xl mb-5">🎯</div>

            <h2 class="text-2xl md:text-3xl font-bold mb-6">
                ماموریت ما
            </h2>

            <p class="text-slate-600 leading-9 text-lg">
                ماموریت آیلتس کمپ این است که آماده‌سازی آیلتس را
                از مسیری گیج‌کننده به یک برنامه منظم، هدفمند
                و قابل ارزیابی تبدیل کند.
            </p>

            <p class="text-slate-600 leading-9 text-lg mt-4">
                ما می‌خواهیم هر زبان‌آموز، با هر سطح فعلی زبان،
                بداند در هر مرحله چه کاری باید انجام دهد و
                چگونه پیشرفت خود را در مسیر نمره هدف ارزیابی کند.
            </p>
        </div>
    </section>

    <!-- Services -->
    <section class="py-16 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-bold mb-4">
                    در آیلتس کمپ چه چیزی دریافت می‌کنید؟
                </h2>

                <p class="text-slate-500 leading-8">
                    ابزارها و خدمات آموزشی برای آماده‌سازی هدفمند
                    در چهار مهارت آیلتس
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2
                        lg:grid-cols-3 gap-6">

                <?php
                $services = array(
                    array(
                        'icon' => '🏕️',
                        'title' => 'کمپ‌های آموزشی',
                        'text' => 'برنامه مطالعاتی روزانه با هدف‌گذاری مشخص.'
                    ),
                    array(
                        'icon' => '📝',
                        'title' => 'آزمون‌های آزمایشی',
                        'text' => 'تمرین با آزمون‌های Mock Test در شرایط نزدیک به آزمون واقعی.'
                    ),
                    array(
                        'icon' => '✍️',
                        'title' => 'بازخورد تخصصی',
                        'text' => 'بررسی رایتینگ و اسپیکینگ همراه با بازخورد کاربردی.'
                    ),
                    array(
                        'icon' => '📚',
                        'title' => 'منابع آموزشی',
                        'text' => 'منابع و بانک سؤال برای Listening، Reading، Writing و Speaking.'
                    ),
                    array(
                        'icon' => '📊',
                        'title' => 'تحلیل عملکرد',
                        'text' => 'بررسی نقاط قوت و ضعف برای برنامه‌ریزی هدفمندتر.'
                    ),
                    array(
                        'icon' => '💬',
                        'title' => 'پشتیبانی آموزشی',
                        'text' => 'همراهی آموزشی و پاسخ‌گویی به پرسش‌های زبان‌آموزان.'
                    ),
                );

                foreach ( $services as $service ) :
                ?>
                    <article class="border border-slate-200 rounded-2xl
                                    p-6 hover:shadow-lg transition-shadow">

                        <div class="text-3xl mb-4">
                            <?php echo esc_html( $service['icon'] ); ?>
                        </div>

                        <h3 class="text-xl font-bold mb-3">
                            <?php echo esc_html( $service['title'] ); ?>
                        </h3>

                        <p class="text-slate-600 leading-8">
                            <?php echo esc_html( $service['text'] ); ?>
                        </p>

                    </article>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <!-- Why us -->
    <section class="bg-slate-50 py-16 px-4">
        <div class="max-w-5xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-10">
                چرا آیلتس کمپ؟
            </h2>

            <ul class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <?php
                $advantages = array(
                    'تمرکز بر کمپ و برنامه فشرده، نه مطالعه پراکنده',
                    'بازخورد تخصصی و کاربردی برای رایتینگ و اسپیکینگ',
                    'آزمون‌های آزمایشی با ساختار نزدیک به آزمون اصلی',
                    'برنامه مطالعاتی متناسب با نیاز هر زبان‌آموز',
                    'دسترسی آنلاین از دستگاه‌ها و شهرهای مختلف',
                    'همراهی آموزشی در مسیر آماده‌سازی آزمون',
                );

                foreach ( $advantages as $advantage ) :
                ?>
                    <li class="flex items-start gap-3 bg-white
                               rounded-xl p-5 border border-slate-200">

                        <span class="text-green-600 text-xl"
                              aria-hidden="true">✓</span>

                        <span class="leading-8">
                            <?php echo esc_html( $advantage ); ?>
                        </span>

                    </li>
                <?php endforeach; ?>

            </ul>
        </div>
    </section>

    <!-- Team -->
    <section class="py-16 px-4">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-2xl md:text-3xl font-bold mb-6">
                تیم ما
            </h2>

            <p class="text-slate-600 leading-9 text-lg">
                آیلتس کمپ با تکیه بر دانش آموزشی، تجربه مدرسان
                و بهره‌گیری از فناوری آموزشی، در تلاش است
                تجربه‌ای منظم‌تر و کاربردی‌تر برای زبان‌آموزان
                فراهم کند.
            </p>

            <p class="text-slate-600 leading-9 text-lg mt-4">
                هدف ما این است که زبان‌آموزان در مسیر آمادگی
                آزمون، راهنمایی روشن‌تر و بازخوردی متناسب با
                نیازهای خود دریافت کنند.
            </p>
        </div>
    </section>

    <!-- Vision -->
    <section class="bg-slate-900 text-white py-16 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="text-4xl mb-5">🚀</div>

            <h2 class="text-2xl md:text-3xl font-bold mb-6">
                چشم‌انداز ما
            </h2>

            <p class="text-slate-300 text-lg leading-9">
                می‌خواهیم آیلتس کمپ به یکی از مراجع معتبر
                آماده‌سازی آیلتس در ایران تبدیل شود؛ جایی که
                زبان‌آموز مسیر پیشرفت خود را شفاف‌تر ببیند،
                با برنامه‌ای هدفمند تمرین کند و برای رسیدن
                به نمره هدفش آمادگی بیشتری پیدا کند.
            </p>
        </div>
    </section>

</main>

<?php get_footer(); ?>
