<template>
    <div class="flex flex-col mt-32 lg:flex-row lg:justify-center">
        <div
            class="flex justify-center w-full text-white text-5xl font-extralight space-y-1 lg:justify-end lg:pr-20 lg:w-1/4">
            <div class="text-4xl lg:text-5xl">
                <p>Frequently Asked Questions</p>
            </div>
        </div>
        <div class="w-full px-6 mt-4 lg:mt-0 lg:px-0 lg:w-1/2">
            <ul>
                <li v-for="(faq, index) in faqs" :key="index" class="text-primary-yellow tracking-wide border-b border-primary-gray py-3 cursor-pointer">
                    <div @click.prevent="toggleQuestion(index)" class="flex items-center justify-between">
                        {{ faq.question }}

                        <svg v-if="toggledQuestion !== index" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>

                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" data-slot="icon" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </div>

                    <ul v-if="toggledQuestion === index" class="bg-primary-gray p-4 mt-2 cursor-auto">
                        <li class="text-neutral-400">{{ faq.answer }}</li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const toggledQuestion = ref(null);

const toggleQuestion = (index) => {
    if (toggledQuestion.value === index) {
        toggledQuestion.value = null;
    } else {
        toggledQuestion.value = index;
    }
}

const faqs = ref([
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
        answer: 'Venture personalizes content by considering each student\'s age, interests, and reading style. This approach ensures that the educational material is engaging, age-appropriate, and tailored to the individual needs of each learner.',
    },
    {
        question: 'What role does literary comprehension play in the curriculum?',
        answer: 'Literary comprehension is a fundamental aspect of the platform. It focuses on developing strong reading and comprehension skills through engaging texts, enabling students to explore various subjects and enhance their ability to comprehend written material effectively.',
    },
    {
        question: 'How does the Venture foster interdisciplinary connections?',
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
        answer: 'Venture covers a wide range of subjects and areas, including mathematics, sciences, literature, history, arts, physical education, life skills, and more. The platform is designed to offer a comprehensive and well-rounded education that covers just about any topics your child is interested in. It\'s what makes our platform so different from the rest.',
    },
    {
        question: 'Can parents actively participate in their child\'s education with Venture?',
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
        answer: 'The founders of Venture are a dedicated husband-wife team with a strong homeschooling background. Elena, the visionary leader, has successfully built multiple companies, while Jon, a self-taught developer, brings technical expertise to the Venture. Elena\'s brother, Kai, has also joined the team, adding his unique skills. Together, they share a deep commitment to homeschooling and are passionate about making Venture a valuable resource for homeschooling families.',
    }
]);
</script>
