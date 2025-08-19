<script setup lang="ts">
import Empty from '@/Layouts/Empty.vue';
import { Icon } from '@iconify/vue';
import { Head, Link } from '@inertiajs/vue3';
import route from 'ziggy-js';


defineOptions({ layout: Empty })
</script>

<template>
    <Head title="rivacy Policy"/>
    <div class="container max-w-screen-lg py-11">
        <div class="relative flex items-center justify-center">
            <Link :href="route('home')" class="absolute left-0">
                <button class=" p-3 rounded-xl bg-primary-foreground transition-colors group">
                    <Icon icon="mingcute:arrow-left-fill" width="25" height="25" class="text-white opacity-70 group-hover:opacity-85 transition-colors"/>
                </button>
            </Link>
            <h1 class="font-black text-2xl">
                Privacy Policy
            </h1>
        </div>
        <div class="mt-24 container max-w-screen-md font-serif prose">
            <h1>Privacy Policy</h1>
            <p><strong>Last Updated:</strong> 04/03/2025</p>

            <h2>1. Introduction</h2>
            <p>Welcome to <strong>lertoons.com</strong>. Your privacy is critically important to us. This Privacy Policy document outlines the types of personal information that is received, collected, and how it is used.</p>

            <h2>2. Information We Collect</h2>
            <h3>2.1 Personal Data</h3>
            <p>We may collect and process personal data, including but not limited to:</p>
            <ul>
                <li>Name and contact information (email address, phone number, etc.).</li>
                <li>Billing and payment details.</li>
                <li>Any other information voluntarily provided by you when using our services.</li>
            </ul>

            <h3>2.2 Non-Personal Data</h3>
            <p>We also collect non-personal data such as browser type, IP address, operating system, and other technical information that helps us improve our services.</p>

            <h2>3. How We Use Your Information</h2>
            <p>Your data is used for the following purposes:</p>
            <ul>
                <li>To provide and maintain our services.</li>
                <li>To improve user experience and develop new features.</li>
                <li>To respond to inquiries and customer support requests.</li>
                <li>To comply with legal obligations.</li>
            </ul>

            <h2>4. Data Sharing & Third Parties</h2>
            <p>We do not sell, trade, or rent your personal data to third parties. However, we may share information with trusted third parties who assist us in operating our website and conducting our business, provided they agree to keep the information confidential.</p>

            <h2>5. Cookies & Tracking Technologies</h2>
            <p>We use cookies and similar tracking technologies to enhance your experience. You can control cookies through your browser settings.</p>

            <h2>6. Data Security</h2>
            <p>We implement robust security measures to protect your data from unauthorized access, alteration, disclosure, or destruction.</p>

            <h2>7. Your Rights</h2>
            <p>Depending on your location, you may have the following rights:</p>
            <ul>
                <li>The right to access, update, or delete your personal data.</li>
                <li>The right to object to certain data processing activities.</li>
                <li>The right to data portability.</li>
            </ul>

            <h2>8. Changes to This Policy</h2>
            <p>We reserve the right to modify this Privacy Policy at any time. Any changes will be posted on this page with an updated revision date.</p>

            <h2>9. Contact Us</h2>
            <p>If you have any questions about this Privacy Policy, please contact us at:</p>
            <p>Email: <a href="mailto:mangasvault@protonmail.com">mangasvault@protonmail.com</a></p>

            <p>Mas cá entre nós sua segurança é um lixo, ninguem aq realmente se importa com seus dados então kieta teu cu ae</p>
        </div>
    </div>
</template>
