<template>
    <Head :title="`${course.title} - Week ${weekNumber}, Day ${dayNumber}`" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Left: Course Info with Breadcrumbs -->
                    <div class="flex items-center space-x-4">
                        <button
                            @click="router.visit(`/student/courses/${course.id}`)"
                            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <div>
                            <!-- Breadcrumbs -->
                            <nav class="flex items-center space-x-1 text-sm text-gray-500 dark:text-gray-400 mb-0.5">
                                <button @click="router.visit('/student/courses')" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors">Courses</button>
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                <button @click="router.visit(`/student/courses/${course.id}`)" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors truncate max-w-[150px]">{{ course.title }}</button>
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                <span class="text-gray-700 dark:text-gray-200">Week {{ weekNumber }}, Day {{ dayNumber }}</span>
                            </nav>
                            <h1 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ currentDay?.title || currentWeek?.formatted_title || currentWeek?.title }}
                            </h1>
                        </div>
                    </div>

                    <!-- Right: Progress & Time -->
                    <div class="flex items-center space-x-4">
                        <!-- Estimated reading time -->
                        <div v-if="estimatedReadingTime" class="hidden md:flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span>~{{ estimatedReadingTime }} min read</span>
                        </div>
                        <!-- Time tracking indicator -->
                        <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4" :class="{ 'text-green-500': isUserActive && isPageVisible, 'text-gray-400': !isUserActive || !isPageVisible }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ formattedElapsedTime }}</span>
                        </div>
                        <!-- Progress bar -->
                        <div class="hidden sm:flex items-center space-x-2">
                            <div class="w-32 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div
                                    class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                    :style="{ width: `${progressPercent}%` }"
                                ></div>
                            </div>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ progressPercent }}%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Sidebar: Course Navigation -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 sticky top-24">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Course Content</h3>
                        <div class="space-y-2">
                            <div v-for="prompt in course.course_prompts" :key="prompt.id">
                                <!-- Week Header (collapsible) -->
                                <button
                                    @click="toggleWeek(prompt.week_number)"
                                    :class="[
                                        'w-full text-left p-3 rounded-lg transition-colors flex items-center justify-between',
                                        prompt.week_number === weekNumber
                                            ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-900 dark:text-blue-100'
                                            : canAccessWeek(prompt.week_number)
                                                ? 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-900 dark:text-white'
                                                : 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                                    ]"
                                >
                                    <div class="flex items-center">
                                        <span :class="[
                                            'inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-medium mr-3 shrink-0',
                                            prompt.week_number === weekNumber
                                                ? 'bg-blue-600 text-white'
                                                : isWeekFullyCompleted(prompt.week_number)
                                                    ? 'bg-green-500 text-white'
                                                    : canAccessWeek(prompt.week_number)
                                                        ? 'bg-gray-200 dark:bg-gray-600 text-gray-600 dark:text-gray-300'
                                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-400'
                                        ]">
                                            <svg v-if="isWeekFullyCompleted(prompt.week_number)" class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span v-else>{{ prompt.week_number }}</span>
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium truncate">Week {{ prompt.week_number }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ prompt.title }}</p>
                                        </div>
                                    </div>
                                    <svg v-if="canAccessWeek(prompt.week_number)" :class="['w-4 h-4 transition-transform', expandedWeeks.includes(prompt.week_number) ? 'rotate-180' : '']" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                    <svg v-else class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </button>

                                <!-- Days List (expandable) -->
                                <div v-if="expandedWeeks.includes(prompt.week_number) && prompt.days?.length" class="ml-6 mt-1 space-y-1">
                                    <button
                                        v-for="day in prompt.days"
                                        :key="day.id"
                                        @click="navigateToDay(prompt.week_number, day.day_number)"
                                        :disabled="!canAccessDay(prompt.week_number, day.day_number)"
                                        :class="[
                                            'w-full text-left py-2 px-3 rounded-md text-sm transition-colors',
                                            prompt.week_number === weekNumber && day.day_number === dayNumber
                                                ? 'bg-blue-100 dark:bg-blue-800 text-blue-900 dark:text-blue-100'
                                                : canAccessDay(prompt.week_number, day.day_number)
                                                    ? 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300'
                                                    : 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                                        ]"
                                    >
                                        <div class="flex items-center">
                                            <span :class="[
                                                'w-4 h-4 rounded-full mr-2 flex items-center justify-center text-[10px]',
                                                isDayCompleted(prompt.week_number, day.day_number)
                                                    ? 'bg-green-500 text-white'
                                                    : prompt.week_number === weekNumber && day.day_number === dayNumber
                                                        ? 'bg-blue-500 text-white'
                                                        : 'bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400'
                                            ]">
                                                <svg v-if="isDayCompleted(prompt.week_number, day.day_number)" class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                                <span v-else>{{ day.day_number }}</span>
                                            </span>
                                            <span class="truncate">{{ day.title }}</span>
                                        </div>
                                    </button>
                                </div>

                                <!-- Fallback: No days, show week-level navigation -->
                                <div v-else-if="expandedWeeks.includes(prompt.week_number) && !prompt.days?.length" class="ml-6 mt-1">
                                    <button
                                        @click="navigateToDay(prompt.week_number, 1)"
                                        :disabled="!canAccessWeek(prompt.week_number)"
                                        :class="[
                                            'w-full text-left py-2 px-3 rounded-md text-sm transition-colors',
                                            prompt.week_number === weekNumber
                                                ? 'bg-blue-100 dark:bg-blue-800 text-blue-900 dark:text-blue-100'
                                                : canAccessWeek(prompt.week_number)
                                                    ? 'hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300'
                                                    : 'text-gray-400 dark:text-gray-600 cursor-not-allowed'
                                        ]"
                                    >
                                        View Week Content
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <!-- Day Header -->
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ currentDay?.title || currentWeek?.formatted_title || currentWeek?.title }}
                                    </h2>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                                        {{ currentDay?.description || currentWeek?.description }}
                                    </p>

                                    <!-- Learning Objectives -->
                                    <div v-if="activeObjectives && activeObjectives.length > 0">
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Today's Objectives:</h3>
                                        <div class="flex flex-wrap gap-2">
                                            <span
                                                v-for="objective in activeObjectives"
                                                :key="objective"
                                                class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300"
                                            >
                                                {{ objective }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Area -->
                        <div class="p-6">
                            <!-- Content Display -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Lesson Content</h3>

                                <!-- Content Output -->
                                <div
                                    v-if="sanitizedContent"
                                    class="prose dark:prose-invert max-w-none bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700"
                                    v-html="sanitizedContent"
                                ></div>

                                <!-- No Content Message -->
                                <div
                                    v-else
                                    class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 border border-gray-200 dark:border-gray-700 text-center"
                                >
                                    <p class="text-gray-500 dark:text-gray-400">Content for this lesson is coming soon.</p>
                                </div>
                            </div>


                            <!-- Day Completion Section -->
                            <div v-if="sanitizedContent && !isDayCompleted(weekNumber, dayNumber)" class="space-y-4">
                                <!-- Trivia Section -->
                                <div v-if="activeTrivia && activeTrivia.length > 0" class="border border-gray-200 dark:border-gray-700 rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Test Your Knowledge</h3>

                                    <div v-if="!triviaStarted" class="text-center">
                                        <p class="text-gray-600 dark:text-gray-400 mb-4">Ready to test what you've learned today?</p>
                                        <button
                                            @click="startTrivia"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 transition-colors"
                                        >
                                            Start Quiz ({{ activeTrivia.length }} questions)
                                        </button>
                                    </div>

                                    <div v-else-if="!triviaCompleted" class="space-y-4">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="font-medium text-gray-900 dark:text-white">Question {{ currentTriviaIndex + 1 }} of {{ activeTrivia.length }}</h4>
                                            <div class="flex items-center space-x-3">
                                                <div class="flex space-x-1">
                                                    <span
                                                        v-for="(_, idx) in activeTrivia"
                                                        :key="idx"
                                                        :class="[
                                                            'w-2 h-2 rounded-full',
                                                            idx < currentTriviaIndex
                                                                ? (triviaAnswers[idx] === activeTrivia[idx].correct_answer ? 'bg-green-500' : 'bg-red-500')
                                                                : idx === currentTriviaIndex
                                                                    ? 'bg-blue-500'
                                                                    : 'bg-gray-300 dark:bg-gray-600'
                                                        ]"
                                                    ></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                                            <h5 class="font-medium text-gray-900 dark:text-white mb-3">{{ currentTriviaQuestion?.question }}</h5>
                                            <div class="space-y-2">
                                                <button
                                                    v-for="(option, index) in currentTriviaQuestion?.options"
                                                    :key="index"
                                                    @click="selectAnswer(index)"
                                                    :disabled="answerRevealed"
                                                    :class="[
                                                        'w-full text-left p-3 rounded-lg border transition-all',
                                                        answerRevealed && index === currentTriviaQuestion?.correct_answer
                                                            ? 'border-green-500 bg-green-50 dark:bg-green-900/20 text-green-900 dark:text-green-100 ring-2 ring-green-500'
                                                            : answerRevealed && selectedAnswer === index && index !== currentTriviaQuestion?.correct_answer
                                                                ? 'border-red-500 bg-red-50 dark:bg-red-900/20 text-red-900 dark:text-red-100'
                                                                : selectedAnswer === index && !answerRevealed
                                                                    ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 text-blue-900 dark:text-blue-100'
                                                                    : 'border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-gray-900 dark:text-white',
                                                        answerRevealed ? 'cursor-default' : ''
                                                    ]"
                                                >
                                                    <div class="flex items-center justify-between">
                                                        <span>{{ option }}</span>
                                                        <svg v-if="answerRevealed && index === currentTriviaQuestion?.correct_answer" class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <svg v-else-if="answerRevealed && selectedAnswer === index && index !== currentTriviaQuestion?.correct_answer" class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                </button>
                                            </div>

                                            <!-- Answer Feedback -->
                                            <div v-if="answerRevealed" class="mt-4 p-3 rounded-lg" :class="selectedAnswer === currentTriviaQuestion?.correct_answer ? 'bg-green-100 dark:bg-green-900/30' : 'bg-amber-100 dark:bg-amber-900/30'">
                                                <p class="text-sm font-medium" :class="selectedAnswer === currentTriviaQuestion?.correct_answer ? 'text-green-800 dark:text-green-200' : 'text-amber-800 dark:text-amber-200'">
                                                    {{ selectedAnswer === currentTriviaQuestion?.correct_answer ? 'Correct!' : 'Not quite right' }}
                                                </p>
                                                <p v-if="selectedAnswer !== currentTriviaQuestion?.correct_answer" class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                                                    The correct answer is: <strong>{{ currentTriviaQuestion?.options[currentTriviaQuestion?.correct_answer] }}</strong>
                                                </p>
                                            </div>

                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ triviaScore }} correct so far
                                                </div>
                                                <button
                                                    v-if="!answerRevealed"
                                                    @click="revealAnswer"
                                                    :disabled="selectedAnswer === null"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                                >
                                                    Check Answer
                                                </button>
                                                <button
                                                    v-else
                                                    @click="nextTriviaQuestion"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors"
                                                >
                                                    {{ currentTriviaIndex === activeTrivia.length - 1 ? 'See Results' : 'Next Question' }}
                                                    <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-else class="text-center space-y-4">
                                        <div class="text-5xl mb-2">{{ triviaScore >= activeTrivia.length * 0.7 ? '🎉' : triviaScore >= activeTrivia.length * 0.5 ? '👍' : '📚' }}</div>
                                        <div>
                                            <h4 class="text-xl font-semibold text-gray-900 dark:text-white">Quiz Complete!</h4>
                                            <p class="text-gray-600 dark:text-gray-400 mt-1">You scored {{ triviaScore }} out of {{ activeTrivia.length }}</p>

                                            <!-- Score visual -->
                                            <div class="mt-4 flex justify-center">
                                                <div class="relative w-24 h-24">
                                                    <svg class="w-24 h-24 transform -rotate-90">
                                                        <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="none" class="text-gray-200 dark:text-gray-700"/>
                                                        <circle cx="48" cy="48" r="40" stroke="currentColor" stroke-width="8" fill="none"
                                                            :class="triviaScore >= activeTrivia.length * 0.7 ? 'text-green-500' : triviaScore >= activeTrivia.length * 0.5 ? 'text-yellow-500' : 'text-red-500'"
                                                            :stroke-dasharray="251.2"
                                                            :stroke-dashoffset="251.2 - (251.2 * triviaScore / activeTrivia.length)"
                                                            stroke-linecap="round"
                                                        />
                                                    </svg>
                                                    <div class="absolute inset-0 flex items-center justify-center">
                                                        <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ Math.round((triviaScore / activeTrivia.length) * 100) }}%</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <p v-if="triviaScore >= activeTrivia.length * 0.7" class="text-green-600 dark:text-green-400 mt-3 font-medium">
                                                Great job! You've mastered this lesson.
                                            </p>
                                            <p v-else class="text-amber-600 dark:text-amber-400 mt-3">
                                                Keep practicing! Review the content and try again.
                                            </p>
                                        </div>

                                        <!-- Retry button -->
                                        <button
                                            v-if="triviaScore < activeTrivia.length * 0.7"
                                            @click="retryTrivia"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Retry Quiz
                                        </button>
                                    </div>
                                </div>

                                <!-- Complete Day Button -->
                                <div class="text-center">
                                    <button
                                        @click="completeDay"
                                        :disabled="completing || (activeTrivia?.length > 0 && !triviaCompleted)"
                                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                    >
                                        <svg v-if="completing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        {{ completing ? 'Completing...' : isLastDay ? 'Complete Course' : isLastDayOfWeek ? 'Complete Week' : 'Complete Day & Continue' }}
                                    </button>
                                    <p v-if="activeTrivia?.length > 0 && !triviaCompleted" class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                        Complete the quiz to continue
                                    </p>
                                </div>
                            </div>

                            <!-- Already Completed Message -->
                            <div v-else-if="isDayCompleted(weekNumber, dayNumber) && sanitizedContent" class="text-center py-8">
                                <div class="text-4xl mb-4">✅</div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Lesson Completed!</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">You've already completed this lesson.</p>
                            </div>

                            <!-- Bottom Navigation -->
                            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <button
                                        v-if="hasPreviousDay"
                                        @click="navigateToPreviousDay"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                    >
                                        <svg class="mr-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                        </svg>
                                        Previous Lesson
                                    </button>
                                    <div v-else></div>

                                    <button
                                        v-if="hasNextDay && canAccessNextDay"
                                        @click="navigateToNextDay"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-colors"
                                    >
                                        Next Lesson
                                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </button>
                                    <button
                                        v-else-if="!hasNextDay && isDayCompleted(weekNumber, dayNumber)"
                                        @click="router.visit(`/student/courses/${course.id}`)"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 transition-colors"
                                    >
                                        <svg class="mr-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Course Complete - View Summary
                                    </button>
                                    <div v-else></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Milestone Celebration Modal -->
    <Teleport to="body">
        <Transition
            enter-active-class="transition-opacity duration-300"
            leave-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            leave-to-class="opacity-0"
        >
            <div v-if="showMilestoneModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" @click="closeMilestoneModal">
                <Transition
                    enter-active-class="transition-all duration-300"
                    leave-active-class="transition-all duration-300"
                    enter-from-class="scale-75 opacity-0"
                    leave-to-class="scale-75 opacity-0"
                >
                    <div v-if="showMilestoneModal" class="bg-white dark:bg-gray-800 rounded-2xl p-8 max-w-md mx-4 text-center shadow-2xl" @click.stop>
                        <div class="text-6xl mb-4 animate-bounce">{{ milestoneEmoji }}</div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ milestoneTitle }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">{{ milestoneMessage }}</p>
                        <div class="flex justify-center space-x-2 mb-6">
                            <span v-for="i in 5" :key="i" class="w-3 h-3 rounded-full animate-pulse" :class="['bg-yellow-400', 'bg-pink-400', 'bg-blue-400', 'bg-green-400', 'bg-purple-400'][i-1]" :style="{ animationDelay: `${i * 100}ms` }"></span>
                        </div>
                        <button
                            @click="closeMilestoneModal"
                            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Keep Learning!
                        </button>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import DOMPurify from 'dompurify';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    course: Object,
    currentWeek: Object,
    currentDay: Object,
    userProgress: Object,
    weekNumber: Number,
    dayNumber: Number,
    canAdvance: Boolean,
    isLastWeek: Boolean,
    isLastDay: Boolean,
    isLastDayOfWeek: Boolean,
    totalDays: Number,
});

// Sidebar state
const expandedWeeks = ref([props.weekNumber]);

// Trivia state
const triviaStarted = ref(false);
const triviaCompleted = ref(false);
const currentTriviaIndex = ref(0);
const triviaAnswers = ref([]);
const selectedAnswer = ref(null);
const triviaScore = ref(0);
const answerRevealed = ref(false);

// Completion state
const completing = ref(false);

// Milestone celebration state
const showMilestoneModal = ref(false);
const milestoneEmoji = ref('');
const milestoneTitle = ref('');
const milestoneMessage = ref('');

// Time tracking state
const sessionId = ref(null);
const trackingStartTime = ref(null);
const elapsedSeconds = ref(0);
const displaySeconds = ref(0);
const isPageVisible = ref(true);
const isUserActive = ref(true);
const lastActivityTime = ref(Date.now());

// Time tracking constants
const INACTIVITY_TIMEOUT = 5 * 60 * 1000;
const TRACKING_INTERVAL = 30 * 1000;
const ACTIVITY_CHECK_INTERVAL = 1000;
const DISPLAY_UPDATE_INTERVAL = 1000;

let trackingTimer = null;
let activityCheckTimer = null;
let displayTimer = null;

// Start a learning session
const startLearningSession = async () => {
    try {
        const response = await window.axios.post(
            `/student/courses/${props.course.id}/week/${props.weekNumber}/day/${props.dayNumber}/start-session`
        );
        if (response.data.success) {
            sessionId.value = response.data.session_id;
            trackingStartTime.value = Date.now();
            elapsedSeconds.value = 0;
            startTrackingTimers();
        }
    } catch (error) {
        console.error('Failed to start learning session:', error);
    }
};

// Send time update to server
const sendTimeUpdate = async () => {
    if (!sessionId.value || !isPageVisible.value || !isUserActive.value) {
        return;
    }

    const now = Date.now();
    const secondsSinceLastUpdate = Math.floor((now - trackingStartTime.value) / 1000);

    if (secondsSinceLastUpdate > 0) {
        try {
            await window.axios.post(
                `/student/courses/${props.course.id}/week/${props.weekNumber}/day/${props.dayNumber}/track-time`,
                {
                    seconds: secondsSinceLastUpdate,
                    session_id: sessionId.value,
                }
            );
            elapsedSeconds.value += secondsSinceLastUpdate;
            trackingStartTime.value = now;
        } catch (error) {
            console.error('Failed to track time:', error);
        }
    }
};

// End the learning session
const endLearningSession = async () => {
    if (!sessionId.value) return;

    const finalSeconds = Math.floor((Date.now() - trackingStartTime.value) / 1000);

    try {
        await window.axios.post(
            `/student/courses/${props.course.id}/end-session`,
            {
                session_id: sessionId.value,
                final_seconds: finalSeconds > 0 ? finalSeconds : 0,
            }
        );
    } catch (error) {
        console.error('Failed to end learning session:', error);
    }

    sessionId.value = null;
};

// Start tracking timers
const startTrackingTimers = () => {
    stopTrackingTimers();

    trackingTimer = setInterval(() => {
        if (isPageVisible.value && isUserActive.value) {
            sendTimeUpdate();
        }
    }, TRACKING_INTERVAL);

    activityCheckTimer = setInterval(() => {
        const timeSinceActivity = Date.now() - lastActivityTime.value;
        if (timeSinceActivity > INACTIVITY_TIMEOUT && isUserActive.value) {
            isUserActive.value = false;
            sendTimeUpdate();
        }
    }, ACTIVITY_CHECK_INTERVAL);

    displayTimer = setInterval(() => {
        if (isPageVisible.value && isUserActive.value && trackingStartTime.value) {
            displaySeconds.value = elapsedSeconds.value + Math.floor((Date.now() - trackingStartTime.value) / 1000);
        }
    }, DISPLAY_UPDATE_INTERVAL);
};

// Stop tracking timers
const stopTrackingTimers = () => {
    if (trackingTimer) {
        clearInterval(trackingTimer);
        trackingTimer = null;
    }
    if (activityCheckTimer) {
        clearInterval(activityCheckTimer);
        activityCheckTimer = null;
    }
    if (displayTimer) {
        clearInterval(displayTimer);
        displayTimer = null;
    }
};

// Handle user activity
const handleUserActivity = () => {
    lastActivityTime.value = Date.now();
    if (!isUserActive.value) {
        isUserActive.value = true;
        trackingStartTime.value = Date.now();
    }
};

// Handle page visibility change
const handleVisibilityChange = () => {
    isPageVisible.value = !document.hidden;

    if (document.hidden) {
        sendTimeUpdate();
    } else {
        trackingStartTime.value = Date.now();
        lastActivityTime.value = Date.now();
    }
};

// Set up activity listeners
const setupActivityListeners = () => {
    const events = ['mousemove', 'keypress', 'scroll', 'mousedown', 'touchstart', 'click'];
    events.forEach(event => {
        document.addEventListener(event, handleUserActivity, { passive: true });
    });

    document.addEventListener('visibilitychange', handleVisibilityChange);
};

// Remove activity listeners
const removeActivityListeners = () => {
    const events = ['mousemove', 'keypress', 'scroll', 'mousedown', 'touchstart', 'click'];
    events.forEach(event => {
        document.removeEventListener(event, handleUserActivity);
    });

    document.removeEventListener('visibilitychange', handleVisibilityChange);
};

// Watch for week/day changes and reset state
watch([() => props.weekNumber, () => props.dayNumber], ([newWeek, newDay], [oldWeek, oldDay]) => {
    if (newWeek !== oldWeek || newDay !== oldDay) {
        // Reset trivia state
        triviaStarted.value = false;
        triviaCompleted.value = false;
        currentTriviaIndex.value = 0;
        triviaAnswers.value = [];
        selectedAnswer.value = null;
        triviaScore.value = 0;
        answerRevealed.value = false;

        // Reset completion state
        completing.value = false;

        // Reset time tracking for new lesson
        displaySeconds.value = 0;
        elapsedSeconds.value = 0;
        trackingStartTime.value = Date.now();

        // Expand current week in sidebar
        if (!expandedWeeks.value.includes(newWeek)) {
            expandedWeeks.value.push(newWeek);
        }

        // End old session and start new one
        endLearningSession().then(() => {
            startLearningSession();
        });
    }
});

// Lifecycle hooks
onMounted(() => {
    startLearningSession();
    setupActivityListeners();
});

onUnmounted(() => {
    stopTrackingTimers();
    removeActivityListeners();
    endLearningSession();
});

// Computed
const totalWeeks = computed(() => props.course?.course_prompts?.length || props.course?.length_in_weeks || 1);

const progressPercent = computed(() => {
    const currentWeek = props.userProgress?.current_week || 1;
    const currentDay = props.userProgress?.current_day || 1;

    // Calculate based on days completed
    let daysCompleted = 0;
    let totalDaysCount = 0;

    for (const prompt of props.course?.course_prompts || []) {
        const daysInWeek = prompt.days_count || prompt.days?.length || 1;
        totalDaysCount += daysInWeek;

        if (prompt.week_number < currentWeek) {
            daysCompleted += daysInWeek;
        } else if (prompt.week_number === currentWeek) {
            daysCompleted += Math.max(0, currentDay - 1);
        }
    }

    if (totalDaysCount === 0) return 0;
    return Math.min(100, Math.round((daysCompleted / totalDaysCount) * 100));
});

// Get active content - prefer day content, fall back to week content
const sanitizedContent = computed(() => {
    const content = props.currentDay?.content || props.currentWeek?.content;
    if (!content) return '';
    return DOMPurify.sanitize(content, {
        ALLOWED_TAGS: ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'br', 'ul', 'ol', 'li', 'strong', 'em', 'b', 'i', 'u', 's', 'strike', 'a', 'img', 'blockquote', 'pre', 'code', 'hr', 'table', 'thead', 'tbody', 'tr', 'th', 'td', 'span', 'div'],
        ALLOWED_ATTR: ['href', 'src', 'alt', 'title', 'class', 'target', 'rel'],
    });
});

// Get active learning objectives - prefer day, fall back to week
const activeObjectives = computed(() => {
    return props.currentDay?.learning_objectives || props.currentWeek?.learning_objectives || [];
});

// Get active trivia - prefer day, fall back to week
const activeTrivia = computed(() => {
    return props.currentDay?.trivia_questions || props.currentWeek?.trivia_questions || [];
});

const formattedElapsedTime = computed(() => {
    const totalSeconds = displaySeconds.value;
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;

    if (minutes >= 60) {
        const hours = Math.floor(minutes / 60);
        const remainingMinutes = minutes % 60;
        return `${hours}h ${remainingMinutes}m`;
    }

    return `${minutes}m ${seconds.toString().padStart(2, '0')}s`;
});

// Estimated reading time
const estimatedReadingTime = computed(() => {
    const duration = props.currentDay?.estimated_duration_minutes || props.currentWeek?.estimated_duration_minutes;
    if (duration) return duration;

    const content = props.currentDay?.content || props.currentWeek?.content;
    if (content) {
        const wordCount = content.replace(/<[^>]*>/g, '').split(/\s+/).length;
        return Math.max(1, Math.ceil(wordCount / 200));
    }
    return null;
});

const currentTriviaQuestion = computed(() => {
    if (!activeTrivia.value || currentTriviaIndex.value >= activeTrivia.value.length) {
        return null;
    }
    return activeTrivia.value[currentTriviaIndex.value];
});

// Navigation helpers
const hasPreviousDay = computed(() => {
    if (props.dayNumber > 1) return true;
    if (props.weekNumber > 1) return true;
    return false;
});

const hasNextDay = computed(() => {
    const currentWeekPrompt = props.course.course_prompts?.find(p => p.week_number === props.weekNumber);
    const totalDaysInWeek = currentWeekPrompt?.days_count || currentWeekPrompt?.days?.length || 1;

    if (props.dayNumber < totalDaysInWeek) return true;
    if (props.weekNumber < totalWeeks.value) return true;
    return false;
});

const canAccessNextDay = computed(() => {
    const currentWeekPrompt = props.course.course_prompts?.find(p => p.week_number === props.weekNumber);
    const totalDaysInWeek = currentWeekPrompt?.days_count || currentWeekPrompt?.days?.length || 1;

    // If there's a next day in current week
    if (props.dayNumber < totalDaysInWeek) {
        return canAccessDay(props.weekNumber, props.dayNumber + 1);
    }
    // If there's a next week
    if (props.weekNumber < totalWeeks.value) {
        return canAccessDay(props.weekNumber + 1, 1);
    }
    return false;
});

// Methods
const toggleWeek = (weekNumber) => {
    if (!canAccessWeek(weekNumber)) return;

    const index = expandedWeeks.value.indexOf(weekNumber);
    if (index > -1) {
        expandedWeeks.value.splice(index, 1);
    } else {
        expandedWeeks.value.push(weekNumber);
    }
};

const canAccessWeek = (week) => {
    return week <= (props.userProgress?.current_week || 1);
};

const canAccessDay = (week, day) => {
    if (week < props.userProgress?.current_week) return true;
    if (week === props.userProgress?.current_week) {
        return day <= (props.userProgress?.current_day || 1);
    }
    return false;
};

const isDayCompleted = (week, day) => {
    if (week < props.userProgress?.current_week) return true;
    if (week === props.userProgress?.current_week) {
        return day < props.userProgress?.current_day;
    }
    return false;
};

const isWeekFullyCompleted = (week) => {
    return week < props.userProgress?.current_week;
};

const navigateToDay = (week, day) => {
    if (canAccessDay(week, day)) {
        router.visit(`/student/courses/${props.course.id}/learn/${week}/${day}`);
    }
};

const navigateToPreviousDay = () => {
    if (props.dayNumber > 1) {
        navigateToDay(props.weekNumber, props.dayNumber - 1);
    } else if (props.weekNumber > 1) {
        const prevWeekPrompt = props.course.course_prompts?.find(p => p.week_number === props.weekNumber - 1);
        const lastDayOfPrevWeek = prevWeekPrompt?.days_count || prevWeekPrompt?.days?.length || 1;
        navigateToDay(props.weekNumber - 1, lastDayOfPrevWeek);
    }
};

const navigateToNextDay = () => {
    const currentWeekPrompt = props.course.course_prompts?.find(p => p.week_number === props.weekNumber);
    const totalDaysInWeek = currentWeekPrompt?.days_count || currentWeekPrompt?.days?.length || 1;

    if (props.dayNumber < totalDaysInWeek) {
        navigateToDay(props.weekNumber, props.dayNumber + 1);
    } else if (props.weekNumber < totalWeeks.value) {
        navigateToDay(props.weekNumber + 1, 1);
    }
};

const startTrivia = () => {
    triviaStarted.value = true;
    currentTriviaIndex.value = 0;
    triviaAnswers.value = [];
    selectedAnswer.value = null;
    triviaScore.value = 0;
    answerRevealed.value = false;
};

const selectAnswer = (answerIndex) => {
    if (!answerRevealed.value) {
        selectedAnswer.value = answerIndex;
    }
};

const revealAnswer = () => {
    if (selectedAnswer.value !== null) {
        answerRevealed.value = true;
        triviaAnswers.value[currentTriviaIndex.value] = selectedAnswer.value;

        if (selectedAnswer.value === currentTriviaQuestion.value.correct_answer) {
            triviaScore.value++;
        }
    }
};

const nextTriviaQuestion = () => {
    if (currentTriviaIndex.value === activeTrivia.value.length - 1) {
        triviaCompleted.value = true;
    } else {
        currentTriviaIndex.value++;
        selectedAnswer.value = null;
        answerRevealed.value = false;
    }
};

const retryTrivia = () => {
    triviaStarted.value = true;
    triviaCompleted.value = false;
    currentTriviaIndex.value = 0;
    triviaAnswers.value = [];
    selectedAnswer.value = null;
    triviaScore.value = 0;
    answerRevealed.value = false;
};

// Milestone celebration functions
const showMilestoneCelebration = (emoji, title, message) => {
    milestoneEmoji.value = emoji;
    milestoneTitle.value = title;
    milestoneMessage.value = message;
    showMilestoneModal.value = true;
};

const closeMilestoneModal = () => {
    showMilestoneModal.value = false;
};

const completeDay = () => {
    completing.value = true;

    const payload = {};

    if (triviaCompleted.value && triviaScore.value > 0 && activeTrivia.value?.length > 0) {
        payload.trivia_score = Math.round((triviaScore.value / activeTrivia.value.length) * 100);
    }

    router.post(`/student/courses/${props.course.id}/week/${props.weekNumber}/day/${props.dayNumber}/complete`, payload, {
        onSuccess: () => {
            // Milestone check handled by server redirect
        },
        onError: (errors) => {
            console.error('Error completing day:', errors);
            completing.value = false;
        },
    });
};
</script>
