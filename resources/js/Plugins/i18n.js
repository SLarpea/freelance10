import { createI18n } from 'vue-i18n';

function loadLocaleMessages() {
    const locales = import.meta.globEager('../locales/*.json');

    const messages = {};
    Object.keys(locales).forEach((key) => {
        const matched = key.match(/\.\/locales\/(\w+)\.json$/);
        if (matched && matched.length === 2) {
            const locale = matched[1];
            messages[locale] = locales[key];
        }
    });

    return messages;
}

export const i18n = createI18n({
    locale: window.currentLocale || 'en',
    fallbackLocale: 'en',
    messages: loadLocaleMessages()
});