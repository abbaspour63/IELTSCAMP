document.addEventListener('DOMContentLoaded', function () {
    const emailTab = document.getElementById('ielts-tab-email');
    const mobileTab = document.getElementById('ielts-tab-mobile');
    const emailPanel = document.getElementById('ielts-panel-email');
    const mobilePanel = document.getElementById('ielts-panel-mobile');

    if (!emailTab || !mobileTab || !emailPanel || !mobilePanel) {
        return;
    }

    function showTab(selected) {
        const showEmail = selected === 'email';

        emailPanel.hidden = !showEmail;
        mobilePanel.hidden = showEmail;

        emailTab.setAttribute('aria-selected', String(showEmail));
        mobileTab.setAttribute('aria-selected', String(!showEmail));

        emailTab.className = showEmail
            ? 'rounded-lg bg-blue-600 px-4 py-3 font-bold text-white'
            : 'rounded-lg bg-slate-100 px-4 py-3 font-bold text-slate-700';

        mobileTab.className = !showEmail
            ? 'rounded-lg bg-blue-600 px-4 py-3 font-bold text-white'
            : 'rounded-lg bg-slate-100 px-4 py-3 font-bold text-slate-700';
    }

    emailTab.addEventListener('click', function () {
        showTab('email');
    });

    mobileTab.addEventListener('click', function () {
        showTab('mobile');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('ielts-login-form');
    const message = document.getElementById('ielts-login-message');

    if (!form || !message) {
        return;
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const phoneInput = document.getElementById('phone');
        const phone = phoneInput.value.trim();
        const button = form.querySelector('button[type="submit"]');

        message.textContent = '';

        if (!/^09[0-9]{9}$/.test(phone)) {
            message.textContent = 'شماره موبایل را به شکل 09123456789 وارد کنید.';
            return;
        }

        if (typeof IELTSLogin === 'undefined') {
            message.textContent = 'خطا در اتصال فرم به سرور.';
            return;
        }

        button.disabled = true;
        button.textContent = 'در حال بررسی...';

        try {
            const data = new FormData();
            data.append('action', 'ielts_request_otp');
            data.append('nonce', IELTSLogin.nonce);
            data.append('phone', phone);

            const response = await fetch(IELTSLogin.ajaxUrl, {
                method: 'POST',
                credentials: 'same-origin',
                body: data
            });

            const result = await response.json();

            message.textContent =
                result.data?.message || 'پاسخی از سرور دریافت نشد.';

        } catch (error) {
            message.textContent = 'ارتباط با سرور برقرار نشد.';
        } finally {
            button.disabled = false;
            button.textContent = 'دریافت کد تأیید';
        }
    });
});
