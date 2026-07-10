// ============================================
//   TELEGRAM MINI APP BOT — bitta fayl
//   Ishga tushirish: node bot.js
//   Kutubxona kerak: npm install node-telegram-bot-api
// ============================================

const TelegramBot = require('node-telegram-bot-api');

// ---------- 1. SHU YERGA O'Z MA'LUMOTLARINGIZNI YOZING ----------
const BOT_TOKEN = '7371083523:AAGHRSso_Hd1Ekrvcgj-PmskMa5AI-HdGVE';
const MINI_APP_URL = 'https://mohirqollar.uz';       // https bo'lishi SHART
const CHANNEL_URL = 'https://t.me/Mohir_Qollaruz';
const CONTACT_USERNAME = 'https://t.me/MehriddinSoyibov';
const CONTACT_PHONE = '+998942551397';
// ------------------------------------------------------------------

const bot = new TelegramBot(BOT_TOKEN, { polling: true });

function mainKeyboard() {
    return {
        inline_keyboard: [
            [{ text: '🚀 Platformani ochish', web_app: { url: MINI_APP_URL } }],
            [{ text: '📖 Platforma haqida', callback_data: 'about' }],
            [{ text: '☎️ Bog\'lanish', callback_data: 'contact' }],
            [{ text: '📢 Yangiliklar', url: CHANNEL_URL }],
        ],
    };
}

// ---- /start komandasi ----
bot.onText(/^\/start/, (msg) => {
    const chatId = msg.chat.id;
    const firstName = (msg.from.first_name || 'Foydalanuvchi')
        .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

    const welcomeText =
        `👋 <b>Assalomu alaykum, ${firstName}!</b>\n\n` +
        `Bizning platformamizga xush kelibsiz! 🎉\n\n` +
        `Bu yerda siz:\n` +
        `✅ Platformaning barcha imkoniyatlaridan foydalanishingiz\n` +
        `✅ Yangiliklardan xabardor bo'lishingiz\n` +
        `✅ Bevosita biz bilan bog'lanishingiz mumkin\n\n` +
        `Quyidagi tugmalardan birini tanlang 👇`;

    bot.sendMessage(chatId, welcomeText, {
        parse_mode: 'HTML',
        reply_markup: mainKeyboard(),
    });
});

// ---- Inline tugmalar bosilganda ----
bot.on('callback_query', async (query) => {
    const chatId = query.message.chat.id;

    if (query.data === 'about') {
        await bot.sendMessage(
            chatId,
            `📖 <b>Platforma haqida</b>\n\n` +
            `Bizning platforma foydalanuvchilarga qulay va zamonaviy xizmatlarni ` +
            `bir joyda taqdim etadi. Telegram Mini App orqali to'g'ridan-to'g'ri ` +
            `botdan foydalanish imkoniyati mavjud.\n\n` +
            `Batafsil ma'lumot uchun "🚀 Platformani ochish" tugmasini bosing.`,
            { parse_mode: 'HTML', reply_markup: mainKeyboard() }
        );
    }

    if (query.data === 'contact') {
        await bot.sendMessage(
            chatId,
            `☎️ <b>Biz bilan bog'lanish</b>\n\n` +
            `📱 Telefon: ${CONTACT_PHONE}\n` +
            `💬 Telegram: ${CONTACT_USERNAME}\n\n` +
            `Savollaringiz bo'lsa, biz bilan bemalol bog'laning!`,
            { parse_mode: 'HTML', reply_markup: mainKeyboard() }
        );
    }

    bot.answerCallbackQuery(query.id);
});

bot.on('polling_error', (err) => console.error('Polling xatosi:', err.message));

console.log('✅ Bot ishga tushdi...');