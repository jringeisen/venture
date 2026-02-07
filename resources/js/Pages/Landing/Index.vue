<template>
    <Head title="Welcome">
        <meta name="title" content="Venture | K-12 Curiosity-led Homeschool Curriculum">
        <meta name="description" content="Personalized K-12 learning platform for homeschooling. Start your child's curiosity-led journey today!">
        <meta name="image" content="assets/images/preview-image.png">
        <meta name="type" content="website">
        <meta name="url" content="https://learnwithventure.com">
        <meta property="og:title" content="Venture | K-12 Curiosity-led Homeschool Curriculum">
        <meta property="og:description" content="Personalized K-12 learning platform for homeschooling. Start your child's curiosity-led journey today!">
        <meta property="og:image" content="assets/images/preview-image.png">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://learnwithventure.com">
    </Head>

    <div class="bg-white">
        <!-- Header -->
        <header class="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-md shadow-sm z-50">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8" aria-label="Global">
                <div class="flex lg:flex-1">
                    <a href="#" class="flex items-center gap-2 -m-1.5 p-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" version="1.1" class="block h-10">
                            <path :d="logoPath" stroke="none" fill="#0D9488" fill-rule="evenodd" />
                        </svg>
                        <span class="uppercase text-beach-ocean-deep text-xl font-semibold tracking-widest">Venture</span>
                    </a>
                </div>
                <div class="flex lg:hidden">
                    <button @click.prevent="toggleMobileMenu" type="button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-beach-teal">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                </div>
                <div class="hidden lg:flex lg:gap-x-8">
                    <a href="#about" class="text-sm font-semibold leading-6 text-beach-ocean-deep hover:text-beach-teal transition-colors">ABOUT</a>
                    <a href="#mission" class="text-sm font-semibold leading-6 text-beach-ocean-deep hover:text-beach-teal transition-colors">MISSION</a>
                    <a href="#subjects" class="text-sm font-semibold leading-6 text-beach-ocean-deep hover:text-beach-teal transition-colors">SUBJECTS</a>
                    <a href="#pricing" class="text-sm font-semibold leading-6 text-beach-ocean-deep hover:text-beach-teal transition-colors">PRICING</a>
                    <a href="#faq" class="text-sm font-semibold leading-6 text-beach-ocean-deep hover:text-beach-teal transition-colors">SUPPORT</a>
                    <Link :href="route('blog-posts.index')" class="text-sm font-semibold leading-6 text-beach-ocean-deep hover:text-beach-teal transition-colors">BLOG</Link>
                </div>
                <div class="hidden lg:flex lg:flex-1 lg:justify-end lg:items-center lg:gap-x-4">
                    <Link :href="route('auth.options')" class="text-sm font-semibold leading-6 text-beach-ocean-deep hover:text-beach-teal transition-colors">Log in <span aria-hidden="true">&rarr;</span></Link>
                    <Link :href="route('register')" class="inline-flex items-center px-5 py-2.5 bg-beach-teal border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-beach-teal-dark focus:outline-none focus:ring-2 focus:ring-beach-teal focus:ring-offset-2 transition-colors ease-in-out duration-150">Get Started</Link>
                </div>
            </nav>

            <!-- Mobile menu -->
            <div v-if="mobileMenuOpen" class="lg:hidden" role="dialog" aria-modal="true">
                <div class="fixed inset-0 z-10 bg-black/20" @click="toggleMobileMenu"></div>
                <div class="fixed inset-y-0 right-0 z-20 w-full overflow-y-auto bg-beach-ocean-deep px-6 py-6 sm:max-w-sm">
                    <div class="flex items-center justify-between">
                        <a href="#" class="-m-1.5 p-1.5 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" version="1.1" class="block h-8">
                                <path :d="logoPath" stroke="none" fill="#5EEAD4" fill-rule="evenodd" />
                            </svg>
                            <span class="uppercase text-white text-lg font-semibold tracking-widest">Venture</span>
                        </a>
                        <button @click.prevent="toggleMobileMenu" type="button" class="-m-2.5 rounded-md p-2.5 text-beach-teal-light">
                            <span class="sr-only">Close menu</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-8 flow-root">
                        <div class="-my-6 divide-y divide-white/10">
                            <div class="space-y-1 py-6">
                                <a href="#about" @click="toggleMobileMenu()" class="-mx-3 block rounded-lg px-3 py-3 text-base font-semibold text-white hover:bg-beach-ocean transition-colors">About</a>
                                <a href="#mission" @click="toggleMobileMenu()" class="-mx-3 block rounded-lg px-3 py-3 text-base font-semibold text-white hover:bg-beach-ocean transition-colors">Mission</a>
                                <a href="#subjects" @click="toggleMobileMenu()" class="-mx-3 block rounded-lg px-3 py-3 text-base font-semibold text-white hover:bg-beach-ocean transition-colors">Subjects</a>
                                <a href="#pricing" @click="toggleMobileMenu()" class="-mx-3 block rounded-lg px-3 py-3 text-base font-semibold text-white hover:bg-beach-ocean transition-colors">Pricing</a>
                                <a href="#faq" @click="toggleMobileMenu()" class="-mx-3 block rounded-lg px-3 py-3 text-base font-semibold text-white hover:bg-beach-ocean transition-colors">Support</a>
                                <Link :href="route('blog-posts.index')" class="-mx-3 block rounded-lg px-3 py-3 text-base font-semibold text-white hover:bg-beach-ocean transition-colors">Blog</Link>
                            </div>
                            <div class="py-6 space-y-1">
                                <Link :href="route('auth.options')" class="-mx-3 block rounded-lg px-3 py-3 text-base font-semibold text-white hover:bg-beach-ocean transition-colors">Log in</Link>
                                <Link :href="route('register')" class="-mx-3 block rounded-lg px-3 py-3 text-base font-semibold text-beach-teal-light hover:bg-beach-ocean transition-colors">Get Started</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero -->
        <section class="relative min-h-screen flex items-center justify-center">
            <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
                <source src="/images/landing/hero-video.mp4" type="video/mp4" />
            </video>
            <div class="absolute inset-0 bg-gradient-to-b from-beach-ocean-deep/30 via-beach-ocean-deep/50 to-beach-ocean-deep/80"></div>
            <div class="relative z-10 text-center px-6 max-w-4xl mx-auto pt-20">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-light text-white leading-tight tracking-wide">
                    Where Curiosity Leads,<br>Discovery Follows
                </h1>
                <p class="mt-6 text-lg md:text-xl text-white/80 max-w-2xl mx-auto leading-relaxed">
                    A personalized K&#8211;12 learning platform that puts your child's natural curiosity at the center of every lesson.
                </p>
                <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
                    <Link :href="route('register')" class="px-8 py-4 bg-beach-teal text-white rounded-lg font-semibold uppercase tracking-widest hover:bg-beach-teal-dark focus:outline-none focus:ring-2 focus:ring-beach-teal focus:ring-offset-2 transition-colors ease-in-out duration-150">Get Started Today</Link>
                    <a href="#mission" class="px-8 py-4 border-2 border-white text-white rounded-lg font-semibold uppercase tracking-widest hover:bg-white/10 transition-colors ease-in-out duration-150">Our Mission</a>
                </div>
            </div>
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
                <svg class="w-8 h-8 text-white/60" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </div>
        </section>

        <!-- Stats Bar -->
        <section class="bg-beach-ocean-deep py-10">
            <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-center text-white px-6">
                <div data-aos="fade-up">
                    <p class="text-4xl font-bold">30+</p>
                    <p class="mt-1 text-white/80 text-sm tracking-wider uppercase">Subjects to Explore</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="100">
                    <p class="text-4xl font-bold">100%</p>
                    <p class="mt-1 text-white/80 text-sm tracking-wider uppercase">AI-Powered Learning</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200">
                    <p class="text-4xl font-bold">K&#8211;12</p>
                    <p class="mt-1 text-white/80 text-sm tracking-wider uppercase">All Ages Welcome</p>
                </div>
            </div>
        </section>

        <!-- Why Venture -->
        <section id="about" class="scroll-mt-20 py-24 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <p class="text-beach-teal font-semibold tracking-wider uppercase text-sm">Why Venture</p>
                    <h2 class="mt-3 text-3xl md:text-5xl font-light text-beach-text tracking-wide">The Future of Education</h2>
                    <p class="mt-6 text-lg text-beach-text-light leading-relaxed">
                        We believe learning should have no limits. Our platform fosters a generation of learners who are empowered to explore, question, and innovate.
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-slate-50 rounded-2xl p-8 text-center hover:shadow-lg transition-shadow" data-aos="fade-up">
                        <div class="w-16 h-16 mx-auto bg-beach-teal/10 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-beach-teal" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-beach-text">Curiosity-Led</h3>
                        <p class="mt-3 text-beach-text-light leading-relaxed">Follow your interests and explore topics you love. Our platform adapts to what excites each learner, making every lesson an adventure.</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-8 text-center hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-16 h-16 mx-auto bg-beach-teal/10 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-beach-teal" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-beach-text">AI-Powered</h3>
                        <p class="mt-3 text-beach-text-light leading-relaxed">Personalized learning paths that adapt to each student's pace and interests. No two journeys are the same.</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-8 text-center hover:shadow-lg transition-shadow" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-16 h-16 mx-auto bg-beach-teal/10 rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-beach-teal" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-beach-text">Affordable for Everyone</h3>
                        <p class="mt-3 text-beach-text-light leading-relaxed">Quality education that fits every budget. Venture offers flexible plans so every family can access curiosity-led learning.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission -->
        <section id="mission" class="scroll-mt-20 relative">
            <video class="absolute inset-0 w-full h-full object-cover brightness-[0.35]" autoplay loop muted playsinline>
                <source src="https://static.showit.co/file/73Y8eDVBTqKMc3flhEUamw/129955/8mb_video-arz-ebptslej.mp4" type="video/mp4">
            </video>
            <div class="relative py-24">
                <div class="max-w-7xl mx-auto px-6">
                    <div class="text-white space-y-6 max-w-3xl">
                        <p class="text-beach-teal-light uppercase tracking-wider font-semibold text-sm">Our Mission</p>
                        <h2 class="text-4xl md:text-5xl font-light leading-tight">
                            Every child deserves<br>the freedom to learn.
                        </h2>
                        <p class="text-white/80 text-lg leading-relaxed max-w-xl">
                            We're bringing world-class education to all children, harnessing AI to overcome the barriers of location and access. Every topic, every learner &mdash; let's make it possible together.
                        </p>
                        <ul class="space-y-4 pt-2">
                            <li class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-beach-teal-light shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span class="font-medium">617 million children lack basic math and reading skills globally.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-beach-teal-light shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span class="font-medium">75% of students feel unprepared for future careers.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <svg class="w-6 h-6 text-beach-teal-light shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span class="font-medium">Personalized interest-led learning lifts academic success.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing -->
        <section id="pricing" class="scroll-mt-20 py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <p class="text-beach-teal font-semibold tracking-wider uppercase text-sm">Pricing</p>
                    <h2 class="mt-3 text-3xl md:text-5xl font-light text-beach-text tracking-wide">Plans for Every Family</h2>
                    <p class="mt-6 text-lg text-beach-text-light leading-relaxed">
                        Choose the plan that fits your family. Every plan includes access to our AI-powered, curiosity-led curriculum.
                    </p>
                </div>

                <!-- Billing Toggle -->
                <div class="flex items-center justify-center gap-4 mb-12">
                    <span class="text-sm font-medium" :class="billingCycle === 'monthly' ? 'text-beach-text' : 'text-beach-text-light'">Monthly</span>
                    <button
                        @click="billingCycle = billingCycle === 'monthly' ? 'yearly' : 'monthly'"
                        class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors"
                        :class="billingCycle === 'yearly' ? 'bg-beach-teal' : 'bg-gray-200'"
                        aria-label="Toggle billing cycle"
                    >
                        <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform" :class="billingCycle === 'yearly' ? 'translate-x-6' : 'translate-x-1'" />
                    </button>
                    <span class="text-sm font-medium" :class="billingCycle === 'yearly' ? 'text-beach-text' : 'text-beach-text-light'">
                        Yearly <span class="text-beach-teal font-semibold">(Save ~17%)</span>
                    </span>
                </div>

                <!-- Plan Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                    <!-- Free Plan -->
                    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm" data-aos="fade-up">
                        <h3 class="text-lg font-semibold text-beach-text">Free</h3>
                        <p class="mt-1 text-sm text-beach-text-light">Perfect for getting started</p>
                        <div class="mt-6">
                            <span class="text-4xl font-bold text-beach-text">$0</span>
                            <span class="text-beach-text-light">/forever</span>
                        </div>
                        <Link :href="route('register')" class="mt-8 block w-full rounded-lg bg-white px-4 py-3 text-center text-sm font-semibold text-beach-teal ring-1 ring-inset ring-beach-teal hover:bg-beach-teal/5 transition-colors">
                            Get Started Free
                        </Link>
                        <ul class="mt-8 space-y-3 text-sm text-beach-text-light">
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span>10 AI questions per hour</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span>1 active course per student</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span>1 student</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <XIcon class="shrink-0" />
                                <span class="line-through">Compliance reports</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <XIcon class="shrink-0" />
                                <span class="line-through">PDF downloads</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <XIcon class="shrink-0" />
                                <span class="line-through">Certificates</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Family Plan -->
                    <div class="rounded-2xl border-2 border-beach-teal bg-white p-8 shadow-lg relative" data-aos="fade-up" data-aos-delay="100">
                        <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                            <span class="inline-flex items-center rounded-full bg-beach-teal px-4 py-1 text-xs font-semibold text-white uppercase tracking-wider">Most Popular</span>
                        </div>
                        <h3 class="text-lg font-semibold text-beach-text">Family</h3>
                        <p class="mt-1 text-sm text-beach-text-light">For the whole family</p>
                        <div class="mt-6">
                            <span class="text-4xl font-bold text-beach-text">${{ billingCycle === 'monthly' ? pricing.family.monthly : pricing.family.yearly }}</span>
                            <span class="text-beach-text-light">/{{ billingCycle === 'monthly' ? 'mo' : 'yr' }}</span>
                        </div>
                        <button
                            @click="subscribe('family')"
                            :disabled="checkoutForm.processing"
                            class="mt-8 block w-full rounded-lg bg-beach-teal px-4 py-3 text-center text-sm font-semibold text-white hover:bg-beach-teal-dark transition-colors disabled:opacity-50"
                        >
                            {{ checkoutForm.processing ? 'Redirecting...' : 'Subscribe' }}
                        </button>
                        <ul class="mt-8 space-y-3 text-sm text-beach-text-light">
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span><strong class="text-beach-text">Unlimited</strong> AI questions</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span><strong class="text-beach-text">Unlimited</strong> active courses</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span>Up to <strong class="text-beach-text">5</strong> students</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span><strong class="text-beach-text">Unlimited</strong> compliance reports</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span>PDF downloads</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span>Course certificates</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Classroom Plan -->
                    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                        <h3 class="text-lg font-semibold text-beach-text">Classroom</h3>
                        <p class="mt-1 text-sm text-beach-text-light">For microschools &amp; co-ops</p>
                        <div class="mt-6">
                            <span class="text-4xl font-bold text-beach-text">${{ billingCycle === 'monthly' ? pricing.classroom.monthly : pricing.classroom.yearly }}</span>
                            <span class="text-beach-text-light">/{{ billingCycle === 'monthly' ? 'mo' : 'yr' }}</span>
                        </div>
                        <p class="mt-1 text-xs text-beach-text-light">As low as $2.36/student/mo</p>
                        <button
                            @click="subscribe('classroom')"
                            :disabled="checkoutForm.processing"
                            class="mt-8 block w-full rounded-lg bg-beach-ocean-deep px-4 py-3 text-center text-sm font-semibold text-white hover:bg-beach-ocean transition-colors disabled:opacity-50"
                        >
                            {{ checkoutForm.processing ? 'Redirecting...' : 'Subscribe' }}
                        </button>
                        <ul class="mt-8 space-y-3 text-sm text-beach-text-light">
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span>Everything in Family, plus:</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span>Up to <strong class="text-beach-text">25</strong> students</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span>Multiple student management</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span>Individual student records</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <CheckIcon class="shrink-0" />
                                <span>Attendance &amp; activity tracking</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Subjects -->
        <section id="subjects" class="scroll-mt-20 py-24 bg-slate-50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <p class="text-beach-teal font-semibold tracking-wider uppercase text-sm">Explore &amp; Discover</p>
                    <h2 class="mt-3 text-3xl md:text-5xl font-light text-beach-text tracking-wide">What Will You Learn?</h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <div v-for="subject in subjects" :key="subject.name" class="bg-white rounded-xl p-5 text-center hover:shadow-lg hover:-translate-y-1 transition-all duration-200 group cursor-default" data-aos="fade-up">
                        <img :src="subject.icon" :alt="subject.name" class="w-10 h-10 mx-auto mb-3 group-hover:scale-110 transition-transform" loading="lazy" />
                        <p class="text-sm font-medium text-beach-text">{{ subject.name }}</p>
                    </div>
                </div>
                <div class="text-center mt-12">
                    <Link :href="route('register')" class="inline-flex items-center px-8 py-4 bg-beach-teal text-white rounded-lg font-semibold uppercase tracking-widest hover:bg-beach-teal-dark transition-colors">Start Exploring</Link>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="faq" class="scroll-mt-20 py-24 bg-white">
            <div class="max-w-3xl mx-auto px-6">
                <div class="text-center mb-16">
                    <p class="text-beach-teal font-semibold tracking-wider uppercase text-sm">Support</p>
                    <h2 class="mt-3 text-3xl md:text-5xl font-light text-beach-ocean-deep tracking-wide">Frequently Asked Questions</h2>
                    <p class="mt-6 text-lg text-beach-text-light leading-relaxed">
                        Everything you need to know about Venture and our curiosity-led approach to learning.
                    </p>
                </div>
                <div class="space-y-3">
                    <div v-for="(faq, index) in faqs" :key="index" class="rounded-xl border border-slate-200 overflow-hidden" data-aos="fade-up">
                        <button @click="toggleQuestion(index)" class="w-full flex items-center justify-between px-6 py-5 text-left">
                            <span class="font-semibold text-beach-text">{{ faq.question }}</span>
                            <svg class="w-5 h-5 text-beach-teal shrink-0 ml-4 transition-transform" :class="toggledQuestion === index ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div v-if="toggledQuestion === index" class="px-6 pb-5">
                            <p class="text-beach-text-light leading-relaxed">{{ faq.answer }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Final CTA -->
        <section class="relative py-32">
            <img src="/images/landing/cta-beach.jpg" alt="Aerial view of turquoise beach and white sand" class="absolute inset-0 w-full h-full object-cover" loading="lazy" />
            <div class="absolute inset-0 bg-beach-ocean-deep/70"></div>
            <div class="relative z-10 text-center px-6 max-w-4xl mx-auto">
                <h2 class="text-3xl md:text-5xl lg:text-6xl font-light text-white tracking-wide leading-tight">
                    Learn Anything, Anytime,<br><span class="text-beach-teal-light font-semibold">Anywhere</span>
                </h2>
                <p class="mt-6 text-lg text-white/80 max-w-2xl mx-auto leading-relaxed">
                    Join curious minds around the world who are discovering, exploring, and growing with Venture every day.
                </p>
                <Link :href="route('register')" class="mt-10 inline-flex items-center px-10 py-4 bg-beach-teal text-white rounded-lg font-semibold uppercase tracking-widest hover:bg-beach-teal-dark focus:outline-none focus:ring-2 focus:ring-beach-teal focus:ring-offset-2 transition-colors ease-in-out duration-150">Join Venture Today</Link>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-beach-ocean-deep text-white py-16">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row gap-12 md:gap-16">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500" version="1.1" class="block h-10">
                                <path :d="logoPath" stroke="none" fill="#5EEAD4" fill-rule="evenodd" />
                            </svg>
                            <span class="uppercase text-white text-xl font-semibold tracking-widest">Venture</span>
                        </div>
                        <p class="mt-4 text-white/60 max-w-sm leading-relaxed">
                            Curiosity-led learning for creative minds, encouraging exploration and discovery.
                        </p>
                    </div>
                    <div class="flex gap-16">
                        <div>
                            <h3 class="font-semibold text-beach-teal-light mb-4 text-sm uppercase tracking-wider">Platform</h3>
                            <ul class="space-y-3 text-white/60">
                                <li><Link :href="route('register')" class="hover:text-white transition-colors">Get Started</Link></li>
                                <li><a href="#subjects" class="hover:text-white transition-colors">Subjects</a></li>
                                <li><Link :href="route('blog-posts.index')" class="hover:text-white transition-colors">Blog</Link></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="font-semibold text-beach-teal-light mb-4 text-sm uppercase tracking-wider">Support</h3>
                            <ul class="space-y-3 text-white/60">
                                <li><a href="#faq" class="hover:text-white transition-colors">FAQ</a></li>
                                <li><a href="#mission" class="hover:text-white transition-colors">Our Mission</a></li>
                                <li><Link :href="route('auth.options')" class="hover:text-white transition-colors">Log In</Link></li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-beach-teal-light mb-4 text-sm uppercase tracking-wider">Connect</h3>
                        <div class="flex gap-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="h-5 w-5 text-white/60 hover:text-white transition-colors cursor-pointer">
                                <path d="M288 192v-38.1c0-17.2 3.8-25.9 30.5-25.9H352V64h-55.9c-68.5 0-91.1 31.4-91.1 85.3V192h-45v64h45v192h83V256h56.4l7.6-64h-64z"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="h-5 w-5 text-white/60 hover:text-white transition-colors cursor-pointer">
                                <path d="M492 109.5c-17.4 7.7-36 12.9-55.6 15.3 20-12 35.4-31 42.6-53.6-18.7 11.1-39.4 19.2-61.5 23.5C399.8 75.8 374.6 64 346.8 64c-53.5 0-96.8 43.4-96.8 96.9 0 7.6.8 15 2.5 22.1-80.5-4-151.9-42.6-199.6-101.3-8.3 14.3-13.1 31-13.1 48.7 0 33.6 17.2 63.3 43.2 80.7-16-.4-31-4.8-44-12.1v1.2c0 47 33.4 86.1 77.7 95-8.1 2.2-16.7 3.4-25.5 3.4-6.2 0-12.3-.6-18.2-1.8 12.3 38.5 48.1 66.5 90.5 67.3-33.1 26-74.9 41.5-120.3 41.5-7.8 0-15.5-.5-23.1-1.4C62.8 432 113.7 448 168.3 448 346.6 448 444 300.3 444 172.2c0-4.2-.1-8.4-.3-12.5C462.6 146 479 129 492 109.5z"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="h-5 w-5 text-white/60 hover:text-white transition-colors cursor-pointer">
                                <circle cx="256" cy="255.833" r="80"></circle>
                                <path d="M177.805 176.887c21.154-21.154 49.28-32.93 79.195-32.93s58.04 11.838 79.195 32.992c13.422 13.42 23.01 29.55 28.232 47.55H448.5v-113c0-26.51-20.49-47-47-47h-288c-26.51 0-49 20.49-49 47v113h85.072c5.222-18 14.81-34.19 28.233-47.614zM416.5 147.7c0 7.07-5.73 12.8-12.8 12.8h-38.4c-7.07 0-12.8-5.73-12.8-12.8v-38.4c0-7.07 5.73-12.8 12.8-12.8h38.4c7.07 0 12.8 5.73 12.8 12.8v38.4zm-80.305 187.58c-21.154 21.153-49.28 32.678-79.195 32.678s-58.04-11.462-79.195-32.616c-21.115-21.115-32.76-49.842-32.803-78.842H64.5v143c0 26.51 22.49 49 49 49h288c26.51 0 47-22.49 47-49v-143h-79.502c-.043 29-11.687 57.664-32.803 78.78z"></path>
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor" class="h-5 w-5 text-white/60 hover:text-white transition-colors cursor-pointer">
                                <path d="M256 32C132.3 32 32 132.3 32 256c0 91.7 55.2 170.5 134.1 205.2-.6-15.6-.1-34.4 3.9-51.4 4.3-18.2 28.8-122.1 28.8-122.1s-7.2-14.3-7.2-35.4c0-33.2 19.2-58 43.2-58 20.4 0 30.2 15.3 30.2 33.6 0 20.5-13.1 51.1-19.8 79.5-5.6 23.8 11.9 43.1 35.4 43.1 42.4 0 71-54.5 71-119.1 0-49.1-33.1-85.8-93.2-85.8-67.9 0-110.3 50.7-110.3 107.3 0 19.5 5.8 33.3 14.8 43.9 4.1 4.9 4.7 6.9 3.2 12.5-1.1 4.1-3.5 14-4.6 18-1.5 5.7-6.1 7.7-11.2 5.6-31.3-12.8-45.9-47-45.9-85.6 0-63.6 53.7-139.9 160.1-139.9 85.5 0 141.8 61.9 141.8 128.3 0 87.9-48.9 153.5-120.9 153.5-24.2 0-46.9-13.1-54.7-27.9 0 0-13 51.6-15.8 61.6-4.7 17.3-14 34.5-22.5 48 20.1 5.9 41.4 9.2 63.5 9.2 123.7 0 224-100.3 224-224C480 132.3 379.7 32 256 32z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="mt-12 pt-8 border-t border-white/10 text-center text-white/60 text-sm">
                    <p>&copy; {{ new Date().getFullYear() }} Venture. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AOS from 'aos';
import 'aos/dist/aos.css';

const props = defineProps({
    blogCount: Number,
    plans: Object,
    pricing: Object,
});

onMounted(() => {
    AOS.init({ duration: 600, once: true });
});

const billingCycle = ref('monthly');
const mobileMenuOpen = ref(false);

const checkoutForm = useForm({
    plan: '',
    billing_cycle: 'monthly',
});

const subscribe = (plan) => {
    try {
        const checkoutUrl = route('parent.subscription.checkout');
        if (!checkoutUrl) {
            router.visit(route('register'));
            return;
        }
    } catch {
        router.visit(route('register'));
        return;
    }

    checkoutForm.plan = plan;
    checkoutForm.billing_cycle = billingCycle.value;
    checkoutForm.post(route('parent.subscription.checkout'));
};

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
};

const toggledQuestion = ref(null);

const toggleQuestion = (index) => {
    toggledQuestion.value = toggledQuestion.value === index ? null : index;
};

const logoPath = 'M 239 51.091 C 217.746 55.212, 195.337 80.006, 180.940 115.334 C 178.575 121.137, 178.544 121.165, 175.031 120.588 C 173.089 120.269, 162.725 119.727, 152 119.385 C 135.936 118.871, 130.914 119.060, 123.500 120.458 C 93.398 126.132, 76.179 143.807, 75.662 169.561 C 75.500 177.607, 75.493 177.629, 71.592 181.054 C 62.983 188.612, 60.379 202.348, 65.578 212.780 C 70.298 222.252, 78.230 226.943, 89.588 226.978 L 96.676 227 102.705 234.778 L 108.734 242.556 105.788 246.028 C 100.246 252.559, 88.083 271.540, 84.579 279.123 C 72.778 304.664, 72.705 326.870, 84.370 342.716 C 98.944 362.513, 129.789 370.091, 173.020 364.493 L 178.540 363.779 180.929 369.639 C 195.142 404.512, 215.803 427.807, 237.542 433.469 C 251.856 437.197, 266.496 433.580, 279.869 423.010 C 285.094 418.879, 285.142 418.865, 290.093 419.927 C 299.030 421.843, 309.076 418.234, 315.415 410.828 C 319.031 406.604, 322 399.016, 322 394 C 322 389.592, 319.097 381.269, 316.471 378.148 L 313.991 375.201 316.176 369.493 L 318.360 363.785 322.430 364.374 C 362.093 370.113, 391.441 364.512, 408.080 348.028 C 429.922 326.389, 425.055 290.599, 394.857 250.777 C 391.636 246.529, 389 242.807, 389 242.505 C 389 242.204, 391.192 239.154, 393.872 235.729 C 396.552 232.303, 400.350 226.976, 402.312 223.892 L 405.880 218.283 412.219 217.775 C 419.672 217.176, 425.084 214.636, 430.201 209.335 C 435.227 204.128, 437 199.405, 437 191.228 C 437 181.892, 433.741 175.349, 426.383 169.914 C 422.091 166.743, 421 165.367, 421 163.125 C 421 161.578, 420.087 157.655, 418.971 154.407 C 413.377 138.119, 400.611 127.599, 379.500 121.878 C 372.741 120.046, 368.630 119.694, 353 119.605 C 342.825 119.547, 330.831 119.880, 326.348 120.345 C 318.333 121.177, 318.183 121.151, 317.487 118.845 C 315.702 112.933, 309.296 99.575, 303.671 90.034 C 295.956 76.948, 280.856 61.117, 271.419 56.219 C 260.497 50.550, 250.196 48.921, 239 51.091 M 239 63.878 C 222.467 69.302, 206.528 87.894, 194.685 115.567 L 191.232 123.635 204.866 127.474 C 217.088 130.915, 235.493 137.267, 245 141.324 C 248.277 142.722, 248.882 142.661, 254.500 140.356 C 265.042 136.030, 280.139 130.949, 292.915 127.426 C 299.744 125.543, 305.466 123.867, 305.632 123.701 C 306.269 123.065, 296.748 103.130, 292.236 95.651 C 283.219 80.708, 274.476 71.824, 263 65.946 C 259.560 64.185, 256.498 63.573, 250 63.350 C 245.325 63.189, 240.375 63.427, 239 63.878 M 130.500 132.139 C 115.385 134.053, 103.666 139.241, 96.529 147.178 C 91.615 152.644, 89.182 158.702, 88.326 167.601 L 87.739 173.703 92.077 174.396 C 112.524 177.666, 122.575 199.862, 110.905 215.973 C 109.307 218.178, 108 220.246, 108 220.567 C 108 220.889, 109.996 223.746, 112.436 226.916 L 116.871 232.681 127.686 222.244 C 133.634 216.503, 142.721 208.363, 147.881 204.153 C 153.040 199.944, 158.324 195.600, 159.622 194.500 C 161.563 192.856, 162.245 190.721, 163.459 182.500 C 165.463 168.923, 168.488 154.376, 171.631 143.206 C 173.069 138.095, 174.078 133.749, 173.873 133.550 C 173.103 132.802, 151.376 131.005, 144.576 131.126 C 140.684 131.196, 134.350 131.652, 130.500 132.139 M 332.530 132.118 C 327.597 132.662, 323.413 133.254, 323.232 133.435 C 323.051 133.616, 324.017 137.754, 325.379 142.632 C 328.480 153.739, 332.435 172.810, 333.995 184.175 L 335.185 192.851 341.343 197.612 C 349.831 204.175, 360.846 213.848, 371.359 223.970 L 380.218 232.500 383.272 228.765 C 384.952 226.710, 388.225 222.235, 390.546 218.820 L 394.765 212.612 391.721 209.567 C 384.001 201.848, 382.213 188.892, 387.520 179.141 C 391.247 172.293, 396.034 168.348, 403.208 166.213 C 409.743 164.268, 409.832 163.782, 405.385 154.358 C 400.746 144.527, 389.098 136.664, 374.412 133.451 C 365.601 131.523, 344.134 130.840, 332.530 132.118 M 185.603 140.750 C 182.679 149.663, 175.540 182, 176.496 182 C 176.611 182, 183.039 178.042, 190.782 173.205 C 198.525 168.368, 210.959 161.281, 218.413 157.455 C 225.868 153.630, 231.975 150.229, 231.984 149.898 C 232.016 148.710, 191.686 136.017, 187.830 136.003 C 187.462 136.001, 186.460 138.137, 185.603 140.750 M 297.229 138.946 C 285.174 142.264, 265 149.174, 265 149.985 C 265 150.295, 269.387 152.719, 274.750 155.372 C 285.435 160.659, 305.171 172.065, 314.327 178.246 C 317.532 180.409, 320.332 182.002, 320.548 181.785 C 321.149 181.185, 315.364 153.562, 312.741 144.507 C 311.298 139.524, 309.875 136.439, 308.963 136.316 C 308.158 136.207, 302.878 137.391, 297.229 138.946 M 231 164.848 C 213.851 173.475, 199.715 181.757, 184.315 192.199 L 174.131 199.105 173.315 204.802 C 172.247 212.269, 172.260 272.815, 173.331 280.324 L 174.162 286.147 188.331 295.478 C 204.647 306.223, 225.260 317.947, 239.088 324.346 L 248.675 328.784 260.588 323.069 C 276.660 315.357, 293.710 305.568, 309.663 294.891 C 321.815 286.758, 322.970 285.719, 323.702 282.264 C 324.755 277.299, 324.735 209.876, 323.679 203.683 L 322.857 198.866 308.679 189.527 C 286.432 174.874, 253.126 156.955, 248.266 157.026 C 247.295 157.040, 239.525 160.560, 231 164.848 M 406.643 177.983 C 394.416 181.839, 393.663 198.867, 405.487 204.097 C 414.761 208.200, 424.993 201.592, 424.993 191.500 C 424.993 181.971, 415.700 175.126, 406.643 177.983 M 81.346 189.054 C 73.153 194.303, 73.224 207.169, 81.476 212.576 C 85.306 215.086, 91.186 215.480, 95.345 213.507 C 104.924 208.961, 105.725 194.462, 96.701 188.960 C 92.455 186.370, 85.469 186.413, 81.346 189.054 M 233.762 196.942 C 219.272 201.574, 206.851 214.319, 202.585 228.932 C 200.545 235.923, 200.551 249.110, 202.598 256.068 C 206.969 270.924, 220.100 284.056, 234.932 288.407 C 241.843 290.434, 255.098 290.457, 261.844 288.454 C 282.262 282.390, 296.249 263.622, 296.166 242.400 C 296.084 221.404, 282.665 203.043, 262.909 196.895 C 254.914 194.406, 241.627 194.428, 233.762 196.942 M 238.880 208.316 C 227.805 211.454, 217.604 221.676, 214.339 232.906 C 209.677 248.943, 218.366 267.489, 234 274.871 C 238.651 277.068, 240.889 277.469, 248.500 277.469 C 256.065 277.469, 258.377 277.059, 263.002 274.903 C 270.014 271.634, 277.080 264.574, 280.682 257.239 C 283.154 252.205, 283.495 250.395, 283.459 242.500 C 283.424 234.764, 283.029 232.697, 280.652 227.783 C 277.042 220.322, 270.297 213.483, 263.460 210.353 C 256.792 207.300, 245.706 206.381, 238.880 208.316 M 149.500 218.921 C 144 223.795, 136.223 231.120, 132.217 235.198 L 124.935 242.613 136.217 253.560 C 142.423 259.581, 150.200 266.816, 153.500 269.636 L 159.500 274.765 159.500 242.382 C 159.500 224.572, 159.500 210.013, 159.500 210.029 C 159.500 210.045, 155 214.047, 149.500 218.921 M 337.500 242.474 L 337.500 274.715 346 267.415 C 350.675 263.399, 358.452 256.128, 363.282 251.255 L 372.063 242.397 359.782 230.433 C 353.027 223.853, 345.250 216.616, 342.500 214.351 L 337.500 210.233 337.500 242.474 M 113.514 256.456 C 102.866 269.577, 93.086 288.301, 89.879 301.710 C 82.335 333.252, 100.416 351.824, 140.500 353.706 C 150.209 354.162, 172.586 352.737, 173.742 351.589 C 173.876 351.457, 172.211 344.294, 170.043 335.672 C 167.876 327.050, 165.115 313.738, 163.909 306.088 L 161.715 292.180 150.608 283.188 C 144.498 278.242, 134.392 269.295, 128.148 263.304 L 116.796 252.413 113.514 256.456 M 368.096 264.148 C 361.294 270.656, 351.128 279.630, 345.505 284.090 L 335.282 292.200 333.060 306.350 C 331.838 314.132, 329.073 327.436, 326.916 335.913 C 324.759 344.391, 323.107 351.440, 323.247 351.578 C 324.412 352.735, 346.744 354.164, 356.500 353.706 C 402.078 351.566, 419.181 327.524, 402.918 288.458 C 399.353 279.894, 392.212 267.833, 385.302 258.705 L 380.464 252.314 368.096 264.148 M 177.588 310.472 C 180.314 326.147, 186.390 349.003, 187.830 348.997 C 191.728 348.983, 232 336.291, 232 335.077 C 232 334.733, 227.613 332.281, 222.250 329.628 C 211.629 324.373, 191.846 312.946, 182.805 306.844 C 179.673 304.730, 176.925 303, 176.699 303 C 176.473 303, 176.873 306.362, 177.588 310.472 M 314.500 306.619 C 307.260 311.700, 290.315 321.705, 276.585 329.006 L 264.670 335.342 274.085 338.756 C 287.454 343.604, 309.035 349.369, 310.021 348.356 C 311.639 346.693, 321 308.553, 321 303.624 C 321 302.427, 319.466 303.134, 314.500 306.619 M 242.473 344.661 C 229.700 349.912, 212.195 355.590, 195.299 359.963 L 191.098 361.051 194.617 369.275 C 214.619 416.014, 243.139 433.476, 269.589 415.178 L 274.679 411.658 271.652 405.789 C 266.036 394.900, 268.224 382.547, 277.207 374.432 C 283.080 369.126, 287.283 367.624, 296.079 367.690 C 303.632 367.746, 303.661 367.736, 304.811 364.689 C 305.446 363.008, 305.861 361.539, 305.733 361.426 C 305.605 361.313, 301.088 360.025, 295.695 358.564 C 285.959 355.926, 263.104 348.180, 253.473 344.254 L 248.446 342.205 242.473 344.661 M 287.500 381.988 C 281.271 386.087, 279.231 394.586, 282.995 400.759 C 286.099 405.851, 290.601 408.189, 296.346 407.693 C 309.381 406.569, 313.789 388.720, 302.701 381.960 C 298.474 379.382, 291.440 379.395, 287.500 381.988';

const subjects = [
    { name: 'Science', icon: 'assets/images/icons/1.png' },
    { name: 'Language Arts', icon: 'assets/images/icons/2.png' },
    { name: 'Physical Education', icon: 'assets/images/icons/4.png' },
    { name: 'Astronomy', icon: 'assets/images/icons/5.png' },
    { name: 'Space Exploration', icon: 'assets/images/icons/6.png' },
    { name: 'Coding', icon: 'assets/images/icons/8.png' },
    { name: 'Literature', icon: 'assets/images/icons/10.png' },
    { name: 'Art', icon: 'assets/images/icons/11.png' },
    { name: 'Entrepreneurship', icon: 'assets/images/icons/13.png' },
    { name: 'Business', icon: 'assets/images/icons/14.png' },
    { name: 'Robotics', icon: 'assets/images/icons/15.png' },
    { name: 'Archaeology', icon: 'assets/images/icons/17.png' },
    { name: 'Mathematics', icon: 'assets/images/icons/19.png' },
    { name: 'Geography', icon: 'assets/images/icons/20.png' },
    { name: 'Marine Biology', icon: 'assets/images/icons/21.png' },
    { name: 'Music', icon: 'assets/images/icons/24.png' },
    { name: 'Legal Studies', icon: 'assets/images/icons/25.png' },
    { name: 'Mythology', icon: 'assets/images/icons/26.png' },
    { name: 'Vocational Skills', icon: 'assets/images/icons/28.png' },
    { name: 'Social Studies', icon: 'assets/images/icons/30.png' },
    { name: 'Ancient Cultures', icon: 'assets/images/icons/31.png' },
    { name: 'Wildlife', icon: 'assets/images/icons/32.png' },
    { name: 'Paleontology', icon: 'assets/images/icons/33.png' },
    { name: 'Computer Science', icon: 'assets/images/icons/34.png' },
];

const faqs = [
    {
        question: 'What exactly is curiosity-led learning?',
        answer: 'Curiosity-led learning is an approach that empowers students to explore and learn based on their unique interests and passions. Instead of following a fixed curriculum, students have the freedom to choose topics that genuinely intrigue them, fostering a deep sense of curiosity and motivation.',
    },
    {
        question: 'Is the Venture Curriculum suitable for all ages?',
        answer: 'Yes, the Venture Curriculum is designed to accommodate homeschoolers of all ages, from Kindergarten to 12th Grade. We recommend a parent-led approach for the earlier years that progressively becomes more independent as the years go on.',
    },
    {
        question: 'Can you explain the concept of self-paced progression?',
        answer: 'Self-paced progression allows students to learn at their own speed and depth. They have the flexibility to move through the curriculum without being rushed, ensuring they thoroughly understand the material before moving on to the next topic.',
    },
    {
        question: 'How does Venture personalize content for each student?',
        answer: "Venture personalizes content by considering each student's age, interests, and reading style. This approach ensures that the educational material is engaging, age-appropriate, and tailored to the individual needs of each learner.",
    },
    {
        question: 'What role does literary comprehension play in the curriculum?',
        answer: 'Literary comprehension is a fundamental aspect of the platform. It focuses on developing strong reading and comprehension skills through engaging texts, enabling students to explore various subjects and enhance their ability to comprehend written material effectively.',
    },
    {
        question: 'How does Venture foster interdisciplinary connections?',
        answer: 'Venture encourages students to explore how different subjects are interconnected. By examining the relationships between various disciplines, students gain a better understanding of the world and its complexities, promoting interdisciplinary connections.',
    },
    {
        question: 'How can learning progress be tracked by parents?',
        answer: 'Parents can track learning progress through the parent portal and weekly student summary that will give them a detailed breakdown of what the child has learned.',
    },
    {
        question: 'Is a structured curriculum offered on Venture?',
        answer: 'While Venture embraces a flexible and student-centered approach, it is also currently working on structured curriculum options. This way students can choose between structured pathways or explore topics of interest in a more self-directed manner.',
    },
    {
        question: 'How do you ensure content is child-safe and appropriate?',
        answer: 'Content on Venture is carefully curated to ensure it is age-appropriate and child-safe. The team behind Venture has spent a lot of time creating safeguards for content to create a safe educational environment for students.',
    },
    {
        question: 'Which subjects or areas does Venture cover?',
        answer: "Venture covers a wide range of subjects and areas, including mathematics, sciences, literature, history, arts, physical education, life skills, and more. The platform is designed to offer a comprehensive and well-rounded education that covers just about any topics your child is interested in. It's what makes our platform so different from the rest.",
    },
    {
        question: "Can parents actively participate in their child's education with Venture?",
        answer: 'Yes, parents are encouraged to actively participate in their child\'s education on Venture. And we highly recommend joining your child as they explore their interests. You might even learn something new.',
    },
    {
        question: 'How does Venture complement traditional homeschooling?',
        answer: 'Venture complements traditional homeschooling by offering a flexible and engaging curriculum that can be integrated into existing homeschooling routines. It provides additional resources and expertise to enhance the homeschooling experience.',
    },
    {
        question: 'Can Venture be used alongside regular schooling?',
        answer: 'Yes, Venture can be used alongside regular schooling as a supplementary educational resource. It offers opportunities for students to explore their interests and passions beyond traditional school hours.',
    },
    {
        question: 'How can users provide feedback to the Venture team?',
        answer: 'Users can provide feedback to the Venture team through designated channels, such as contact forms, or email. The team welcomes feedback to continuously improve the curriculum and user experience.',
    },
    {
        question: 'Who are the founders behind Venture?',
        answer: "The founders of Venture are a dedicated husband-wife team with a strong homeschooling background. Elena, the visionary leader, has successfully built multiple companies, while Jon, a self-taught developer, brings technical expertise to the Venture. Elena's brother, Kai, has also joined the team, adding his unique skills. Together, they share a deep commitment to homeschooling and are passionate about making Venture a valuable resource for homeschooling families.",
    },
];

const CheckIcon = {
    template: `<svg class="w-5 h-5 text-beach-teal" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>`,
};

const XIcon = {
    template: `<svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>`,
};
</script>
