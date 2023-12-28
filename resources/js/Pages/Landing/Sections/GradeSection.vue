<template>
    <div
        class="relative h-full bg-cover bg-center bg-fixed"
        :style="`background-image: url(${setUrl()})`"
    >
        <div class="absolute inset-0 bg-black opacity-50 h-full z-10"></div>
        <div class="pt-40 absolute z-10 w-full">
            <div class="flex justify-between max-w-4xl mx-auto z-10 px-6 lg:space-x-24 lg:px-0">
                <div @click.prevent="toggledGrade = 'lower'" class="text-white text-center space-y-1 cursor-pointer">
                    <p class="text-xs tracking-wider">GRADES K-4</p>
                    <p class="tracking-widest">LOWER SCHOOL</p>
                </div>

                <div @click.prevent="toggledGrade = 'middle'" class="text-white text-center space-y-1 cursor-pointer">
                    <p class="text-xs tracking-wider">GRADES 5-8</p>
                    <p class="tracking-widest">MIDDLE SCHOOL</p>
                </div>
                <div @click.prevent="toggledGrade = 'high'" class="text-white text-center space-y-1 cursor-pointer">
                    <p class="text-xs tracking-wider">GRADES 9-12</p>
                    <p class="tracking-widest">HIGH SCHOOL</p>
                </div>
            </div>

            <div v-if="toggledGrade === 'lower'"
                class="text-white mx-auto pt-32 space-y-2 px-6 md:right-40 lg:px-0 lg:w-1/2 xl:w-1/3">
                <h2 class="text-3xl border-b pb-2">LOWER SCHOOL (K-4)</h2>
                <p class="text-justify tracking-wider leading-5">
                    In our Lower School program, we lay the groundwork for a transformative educational journey.
                    Tailored for preschool to fourth grade, our curriculum is a vibrant mosaic of discovery and
                    foundational learning. Here, we focus on nurturing the natural inquisitiveness of young
                    learners,
                    enabling them to question, explore, and understand the world around them. This stage is
                    vital
                    for building a robust base for future education. We believe in a parent-led approach at this
                    level, where our platform acts as a guiding tool for parents, equipping them with resources
                    to
                    lead and inspire their children in this exciting phase of learning.
                </p>
            </div>

            <div v-if="toggledGrade === 'middle'"
                class="text-white mx-auto pt-32 space-y-2 px-6 md:right-40 lg:px-0 lg:w-1/2 xl:w-1/3">
                <h2 class="text-3xl border-b pb-2">MIDDLE SCHOOL (5-8)</h2>
                <p class="text-justify tracking-wider leading-5">
                    Our Middle School curriculum is designed to cater to the unique
                    needs of students navigating the transformative years of adolescence.
                    This stage is marked by an increased sense of independence and
                    growing curiosity about the world and oneself. Our educational
                    materials encourage students to engage in personal reflection
                    while exploring complex concepts and ideas. Emphasizing critical
                    thinking and creative problem-solving, the curriculum prepares
                    students for higher levels of academic pursuit, instilling confidence
                    and a sense of self-reliance. This approach not only enriches their
                    academic experience but also lays a solid foundation for personal
                    growth and intellectual curiosity.
                </p>
            </div>

            <div v-if="toggledGrade === 'high'"
                class="text-white mx-auto pt-32 space-y-2 px-6 md:right-40 lg:px-0 lg:w-1/2 xl:w-1/3">
                <h2 class="text-3xl border-b pb-2">HIGH SCHOOL (9-12)</h2>
                <p class="text-justify tracking-wider leading-5">
                    The High School experience is centered around educational freedom and real-world
                    relevancy, diverging from traditional academic rigor. Our approach is tailored
                    to empower students to explore their passions and interests, connecting learning
                    directly with real-life applications. This stage is pivotal in shaping students
                    who are not just prepared for college or careers but are also passionate lifelong
                    learners. We emphasize personalization of the curriculum, allowing students to
                    delve into subjects that genuinely captivate them. Our platform supports this journey
                    of exploration, offering resources that nurture independence, self-motivation, and
                    practical skills. By focusing on real-world interests and flexible learning paths,
                    we aim to create an environment where students feel empowered to shape their educational
                    experiences, preparing them to meet the future with confidence.
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const toggledGrade = ref('lower');

const setUrl = () => {
    if (toggledGrade.value === 'lower') {
        return '../assets/images/grades-2.jpg';
    }

    if (toggledGrade.value === 'middle') {
        return '../assets/images/grades-1.jpg';
    }

    if (toggledGrade.value === 'high') {
        return '../assets/images/grades-3.jpg';
    }
}
</script>
