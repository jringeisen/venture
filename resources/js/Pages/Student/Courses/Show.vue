<template>
    <Head :title="course.title" />

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <button
                    @click="router.visit('/student/courses')"
                    class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Courses
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Course Header -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                        <div class="relative h-64 overflow-hidden">
                            <!-- Course Image or Gradient Fallback -->
                            <img
                                v-if="course.image_url"
                                :src="course.image_url"
                                :alt="course.title"
                                class="w-full h-full object-cover"
                            />
                            <div
                                v-else
                                class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600"
                            ></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-6 left-6 right-6">
                                <div v-if="course.subject_category || course.difficulty_level" class="flex items-center space-x-3 mb-3">
                                    <span v-if="course.subject_category" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500 text-white">
                                        {{ formatSubject(course.subject_category) }}
                                    </span>
                                    <span v-if="course.difficulty_level" :class="difficultyColorClasses(course.difficulty_level)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium">
                                        {{ formatDifficulty(course.difficulty_level) }}
                                    </span>
                                </div>
                                <h1 class="text-3xl font-bold text-white mb-2">{{ course.title }}</h1>
                                <p class="text-gray-200 text-lg">{{ course.description }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Course Overview -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Course Overview</h2>
                        
                        <!-- Quick Stats -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ course.length_in_weeks }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Weeks</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ statistics.enrolled_count || 0 }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Students</p>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ statistics.completion_rate || 0 }}%</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Completion</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Course Curriculum -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Course Curriculum</h2>
                        
                        <div class="space-y-4">
                            <div 
                                v-for="prompt in course.course_prompts" 
                                :key="prompt.id"
                                class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 text-sm font-medium mr-3">
                                                {{ prompt.week_number }}
                                            </span>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ prompt.formatted_title }}</h3>
                                        </div>
                                        <p class="text-gray-600 dark:text-gray-400 mb-3">{{ prompt.description }}</p>
                                        
                                        <!-- Week Learning Objectives -->
                                        <div v-if="prompt.learning_objectives && prompt.learning_objectives.length > 0" class="mb-3">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-2">This week covers:</h4>
                                            <div class="flex flex-wrap gap-2">
                                                <span 
                                                    v-for="objective in prompt.learning_objectives.slice(0, 3)" 
                                                    :key="objective"
                                                    class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300"
                                                >
                                                    {{ objective }}
                                                </span>
                                                <span v-if="prompt.learning_objectives.length > 3" class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                                    +{{ prompt.learning_objectives.length - 3 }} more
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Week Status -->
                                    <div class="ml-4 shrink-0">
                                        <span v-if="isEnrolled && userProgress && userProgress.current_week > prompt.week_number" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Completed
                                        </span>
                                        <span v-else-if="isEnrolled && userProgress && userProgress.current_week === prompt.week_number" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                            Current
                                        </span>
                                        <span v-else-if="!isEnrolled || !userProgress || userProgress.current_week < prompt.week_number" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                            Locked
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Enrollment Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6 sticky top-6">
                        <div v-if="isEnrolled" class="space-y-4">
                            <div class="text-center">
                                <div v-if="userProgress?.completed_at" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 mb-4">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762z"/>
                                    </svg>
                                    Completed
                                </div>
                                <div v-else class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 mb-4">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Enrolled
                                </div>
                            </div>

                            <!-- Progress -->
                            <div v-if="userProgress">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Enrollment Status</h3>
                                <div class="space-y-3">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        <p v-if="userProgress.completed_at">
                                            Completed on {{ new Date(userProgress.completed_at).toLocaleDateString() }}
                                        </p>
                                        <p v-else>
                                            Started on {{ new Date(userProgress.started_at || userProgress.created_at).toLocaleDateString() }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <!-- Certificate Button for Completed Courses -->
                                <button
                                    v-if="userProgress?.completed_at"
                                    @click="router.visit(`/student/courses/${course.id}/certificate`)"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                    </svg>
                                    View Certificate
                                </button>

                                <button
                                    @click="continueLearning"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ userProgress?.completed_at ? 'Review Course' : 'Continue Learning' }}
                                </button>

                                <button
                                    @click="router.visit('/student/courses/enrolled')"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                >
                                    View All Courses
                                </button>
                            </div>
                        </div>

                        <div v-else class="text-center space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ready to Start Learning?</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Join thousands of students mastering new skills.</p>

                            <button
                                @click="enrollInCourse"
                                :disabled="enrolling"
                                class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <svg v-if="enrolling" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ enrolling ? 'Enrolling...' : 'Enroll Now - Free' }}
                            </button>

                            <!-- Preview Week 1 Button -->
                            <button
                                v-if="course.course_prompts && course.course_prompts.length > 0"
                                @click="showPreview = true"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                            >
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Preview Week 1
                            </button>

                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Free enrollment • Start immediately • Learn at your own pace
                            </p>
                        </div>
                    </div>

                    <!-- Course Features -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Course Features</h3>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">AI-Powered Learning</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Personalized content adapted to your learning style</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Weekly Trivia</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Test your knowledge with interactive quizzes</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Progress Tracking</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Monitor your learning journey and achievements</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">Self-Paced</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Learn at your own speed, whenever you want</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Preview Modal -->
    <Teleport to="body">
        <Transition
            enter-active-class="transition-opacity duration-300"
            leave-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            leave-to-class="opacity-0"
        >
            <div v-if="showPreview" class="fixed inset-0 z-50 overflow-y-auto bg-black/50" @click="showPreview = false">
                <div class="min-h-screen px-4 py-8 flex items-start justify-center">
                    <Transition
                        enter-active-class="transition-all duration-300"
                        leave-active-class="transition-all duration-300"
                        enter-from-class="scale-95 opacity-0"
                        leave-to-class="scale-95 opacity-0"
                    >
                        <div v-if="showPreview" class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-3xl my-8" @click.stop>
                            <!-- Modal Header -->
                            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                                <div>
                                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium mb-1">Preview</p>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ firstWeek?.formatted_title || firstWeek?.title }}</h3>
                                </div>
                                <button @click="showPreview = false" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Modal Content -->
                            <div class="p-6 max-h-[60vh] overflow-y-auto">
                                <!-- Week Description -->
                                <p v-if="firstWeek?.description" class="text-gray-600 dark:text-gray-400 mb-4">{{ firstWeek.description }}</p>

                                <!-- Learning Objectives -->
                                <div v-if="firstWeek?.learning_objectives && firstWeek.learning_objectives.length > 0" class="mb-6">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">What you'll learn:</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <span
                                            v-for="objective in firstWeek.learning_objectives"
                                            :key="objective"
                                            class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300"
                                        >
                                            {{ objective }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Preview Content (truncated) -->
                                <div v-if="previewContent" class="prose dark:prose-invert max-w-none">
                                    <div v-html="previewContent"></div>
                                </div>

                                <!-- Fade out overlay -->
                                <div class="relative mt-4">
                                    <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-white dark:from-gray-800 to-transparent pointer-events-none"></div>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 rounded-b-xl">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Enroll to access the full content
                                    </p>
                                    <div class="flex space-x-3">
                                        <button
                                            @click="showPreview = false"
                                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
                                        >
                                            Close
                                        </button>
                                        <button
                                            @click="showPreview = false; enrollInCourse()"
                                            :disabled="enrolling"
                                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 transition-colors"
                                        >
                                            Enroll Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<script setup>
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import DOMPurify from 'dompurify';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineOptions({
    layout: AuthenticatedLayout
});

const props = defineProps({
    course: Object,
    userProgress: Object,
    statistics: Object,
    isEnrolled: Boolean,
});

const enrolling = ref(false);
const showPreview = ref(false);

// Get the first week for preview
const firstWeek = computed(() => {
    if (!props.course?.course_prompts || props.course.course_prompts.length === 0) {
        return null;
    }
    return props.course.course_prompts.find(p => p.week_number === 1) || props.course.course_prompts[0];
});

// Get truncated preview content (first ~500 characters of content)
const previewContent = computed(() => {
    if (!firstWeek.value?.content) {
        return null;
    }
    const sanitized = DOMPurify.sanitize(firstWeek.value.content, {
        ALLOWED_TAGS: ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'br', 'ul', 'ol', 'li', 'strong', 'em', 'b', 'i'],
        ALLOWED_ATTR: [],
    });
    // Truncate to approximately 500 characters while preserving HTML structure
    const div = document.createElement('div');
    div.innerHTML = sanitized;
    const text = div.textContent || '';
    if (text.length <= 500) {
        return sanitized;
    }
    // Find a good breakpoint
    let truncated = text.substring(0, 500);
    const lastSpace = truncated.lastIndexOf(' ');
    if (lastSpace > 400) {
        truncated = truncated.substring(0, lastSpace);
    }
    return `<p>${truncated}...</p>`;
});

const formatSubject = (subject) => {
    if (!subject) return 'General';
    return subject.charAt(0).toUpperCase() + subject.slice(1).toLowerCase();
};

const formatDifficulty = (difficulty) => {
    if (!difficulty) return 'Beginner';
    return difficulty.charAt(0).toUpperCase() + difficulty.slice(1).toLowerCase();
};

const difficultyColorClasses = (difficulty) => {
    const colors = {
        beginner: 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200',
        intermediate: 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200',
        advanced: 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200'
    };
    return colors[difficulty?.toLowerCase()] || colors.beginner;
};

const formatTime = (minutes) => {
    if (!minutes) return '0 min';
    if (minutes < 60) return `${minutes} min`;
    const hours = Math.floor(minutes / 60);
    const remainingMinutes = minutes % 60;
    return remainingMinutes > 0 ? `${hours}h ${remainingMinutes}m` : `${hours}h`;
};

const enrollInCourse = async () => {
    enrolling.value = true;
    try {
        router.post(`/student/courses/${props.course.id}/enroll`);
    } catch (error) {
        console.error('Enrollment failed:', error);
    } finally {
        enrolling.value = false;
    }
};

const continueLearning = () => {
    const week = props.userProgress?.current_week || 1;
    router.visit(`/student/courses/${props.course.id}/learn/${week}`);
};
</script>