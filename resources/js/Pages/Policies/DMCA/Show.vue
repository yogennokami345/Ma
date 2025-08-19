<script setup lang="ts">
import Empty from '@/Layouts/Empty.vue';
import { Icon } from '@iconify/vue';
import { Head, Link } from '@inertiajs/vue3';
import route from 'ziggy-js';


defineOptions({ layout: Empty })
</script>

<template>
    <Head title="DMCA"/>
    <div class="container max-w-screen-lg py-11">
        <div class="relative flex items-center justify-center">
            <Link :href="route('home')" class="absolute left-0">
                <button class=" p-3 rounded-xl bg-primary-foreground transition-colors group">
                    <Icon icon="mingcute:arrow-left-fill" width="25" height="25" class="text-white opacity-70 group-hover:opacity-85 transition-colors"/>
                </button>
            </Link>
            <h1 class="font-black text-2xl">
                DMCA
            </h1>
        </div>
        <div class="mt-24 container max-w-screen-md font-serif prose">
            <h1>DMCA NOTICE</h1>
            <p><strong>lertoons.com</strong> respects the intellectual property rights of others and takes issues of copyright infringement very seriously. We are committed to addressing the concerns of content owners while ensuring a responsible approach to the management of published materials.</p>

            <p>The book files under copyright protection are <strong>not hosted</strong> on our website. We do not promote or support digital piracy. Our goal is to introduce users to literary works while respecting copyright laws. Any book fragments available on our platform are uploaded by users for educational and informational purposes, not for commercial gain.</p>

            <h2>Understanding Copyright Infringement</h2>
            <p>Copyright infringement occurs when someone uses copyrighted material without permission from the rights holder. This includes reproduction, distribution, public display, or creation of derivative works. Unauthorized use of copyrighted content can result in legal actions, including financial penalties and removal of the material in question.</p>

            <h2>Reporting Copyright Infringement</h2>
            <p>If you believe that your copyrighted work has been used in a way that constitutes copyright infringement and is accessible on this site, you may submit a notification in accordance with the Digital Millennium Copyright Act of 1998 (DMCA).</p>

            <h3>Required Information for DMCA Notice</h3>
            <p>To file a valid DMCA complaint, you must provide the following details:</p>

            <ol>
                <li><strong>Authorized Signature:</strong> A physical or electronic signature of the person authorized to act on behalf of the copyright owner.</li>

                <li><strong>Identification of the Copyrighted Work:</strong> Clear identification of the copyrighted work claimed to have been infringed, including title, author, ISBN, edition, and any additional relevant details.</li>

                <li><strong>Identification of the Infringing Material:</strong> A detailed description of the material alleged to be infringing, along with a direct URL or specific location where it is hosted on our website.</li>

                <li><strong>Prohibited General Links:</strong> We do not accept links to search results alone as proof of infringement. The claimant must provide specific URLs or filenames that point directly to the allegedly infringing material.</li>

                <li><strong>Contact Information:</strong> Information sufficient to allow us to contact the complaining party, including:</li>
                <ul>
                    <li>Full name</li>
                    <li>Physical address</li>
                    <li>Telephone number</li>
                    <li>Email address</li>
                    <li>Company or organization name (if applicable)</li>
                </ul>

                <li><strong>Good Faith Statement:</strong> A declaration that the complainant has a good faith belief that the material in question is not authorized by the copyright owner, its agent, or the law.</li>

                <li><strong>Accuracy and Authorization Statement:</strong> A statement affirming that:</li>
                <ul>
                    <li>The information provided in the complaint is accurate.</li>
                    <li>Under penalty of perjury, the complaining party is authorized to act on behalf of the owner of the allegedly infringed copyright.</li>
                </ul>
            </ol>

            <h2>How to Submit a DMCA Notice</h2>
            <p>The above information must be submitted in writing via mail, fax, or email to our designated copyright agent:</p>

            <p><strong>DMCA Agent Contact:</strong></p>
            <p>Email: <a href="mailto:mangasvault@protonmail.com">mangasvault@protonmail.com</a></p>

            <h2>Important Considerations</h2>
            <p>Before submitting a DMCA notice, consider the following:</p>
            <ul>
                <li>Filing a false claim may result in legal consequences, including liability for damages and attorneys' fees.</li>
                <li>If you are unsure whether certain content infringes your copyright, consult a legal professional before submitting a complaint.</li>
                <li>We may share your complaint with the alleged infringing party as part of the resolution process.</li>
                <li>It may take several business days to process your request, so please be patient.</li>
            </ul>

            <h2>Counter-Notification</h2>
            <p>If you believe that the content was removed in error or that you have authorization to use the copyrighted material, you may submit a counter-notification.</p>

            <h3>Required Information for Counter-Notification:</h3>
            <ul>
                <li>Your name, address, and contact details.</li>
                <li>Identification of the material that was removed and its previous location.</li>
                <li>A statement under penalty of perjury that you have a good faith belief that the material was removed by mistake.</li>
                <li>A statement that you consent to the jurisdiction of the federal court in your district.</li>
            </ul>

            <h2>Final Note</h2>
            <p>We take all copyright claims seriously and will act promptly to remove infringing content if a valid DMCA notice is received. Our goal is to maintain a fair and lawful platform while respecting the rights of copyright holders.</p>
        </div>
    </div>
</template>
