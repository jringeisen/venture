<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CoursePrompt;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Introduction to Astronomy',
                'description' => 'Explore the wonders of our universe! This course takes you on a journey through our solar system and beyond, discovering planets, stars, galaxies, and the mysteries of space.',
                'length_in_weeks' => 6,
                'prompts' => [
                    [
                        'week_number' => 1,
                        'title' => 'Our Solar System',
                        'description' => 'Learn about the Sun and the eight planets that orbit it.',
                        'prompt_text' => 'Explain the structure of our solar system, including the Sun, planets, and other objects.',
                        'learning_objectives' => [
                            'Identify the eight planets in order from the Sun',
                            'Understand what makes a planet different from other space objects',
                            'Learn about the Sun and its importance to our solar system',
                        ],
                        'estimated_duration_minutes' => 30,
                    ],
                    [
                        'week_number' => 2,
                        'title' => 'The Inner Planets',
                        'description' => 'Discover Mercury, Venus, Earth, and Mars - the rocky planets closest to the Sun.',
                        'prompt_text' => 'Describe the inner planets (Mercury, Venus, Earth, Mars) and what makes each one unique.',
                        'learning_objectives' => [
                            'Compare and contrast the four inner planets',
                            'Understand why these planets are called "terrestrial" planets',
                            'Learn what conditions make Earth special',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 3,
                        'title' => 'The Outer Planets',
                        'description' => 'Explore the gas giants: Jupiter, Saturn, Uranus, and Neptune.',
                        'prompt_text' => 'Explain the outer planets and their fascinating features like rings and moons.',
                        'learning_objectives' => [
                            'Identify the gas giant planets',
                            'Learn about planetary rings and major moons',
                            'Understand why these planets are so different from Earth',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 4,
                        'title' => 'Stars and Constellations',
                        'description' => 'Look beyond our solar system to discover the stars that light up our night sky.',
                        'prompt_text' => 'Teach about stars, their life cycles, and famous constellations visible from Earth.',
                        'learning_objectives' => [
                            'Understand what stars are and how they shine',
                            'Learn about the life cycle of a star',
                            'Identify common constellations',
                        ],
                        'estimated_duration_minutes' => 40,
                    ],
                    [
                        'week_number' => 5,
                        'title' => 'Galaxies and the Universe',
                        'description' => 'Zoom out to explore galaxies, including our home - the Milky Way.',
                        'prompt_text' => 'Explain what galaxies are, describe the Milky Way, and discuss the scale of the universe.',
                        'learning_objectives' => [
                            'Define what a galaxy is',
                            'Learn about our Milky Way galaxy',
                            'Understand the vast scale of the universe',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 6,
                        'title' => 'Space Exploration',
                        'description' => 'Learn about humanitys journey into space, from the first satellites to Mars rovers.',
                        'prompt_text' => 'Discuss the history and future of space exploration, including missions and discoveries.',
                        'learning_objectives' => [
                            'Learn about major milestones in space exploration',
                            'Understand how spacecraft and rovers work',
                            'Explore the future of human space travel',
                        ],
                        'estimated_duration_minutes' => 40,
                    ],
                ],
            ],
            [
                'title' => 'World History: Ancient Civilizations',
                'description' => 'Travel back in time to discover the great ancient civilizations that shaped our world. From Egypt to Rome, learn about the people, cultures, and innovations that still influence us today.',
                'length_in_weeks' => 8,
                'prompts' => [
                    [
                        'week_number' => 1,
                        'title' => 'Ancient Mesopotamia',
                        'description' => 'Explore the "Cradle of Civilization" between the Tigris and Euphrates rivers.',
                        'prompt_text' => 'Explain ancient Mesopotamia, including Sumer, Babylon, and their inventions like writing.',
                        'learning_objectives' => [
                            'Locate Mesopotamia on a map',
                            'Understand why it is called the "Cradle of Civilization"',
                            'Learn about cuneiform writing and the wheel',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 2,
                        'title' => 'Ancient Egypt',
                        'description' => 'Discover the mysteries of pharaohs, pyramids, and the Nile River.',
                        'prompt_text' => 'Teach about ancient Egypt, including pharaohs, pyramids, mummies, and daily life.',
                        'learning_objectives' => [
                            'Understand the importance of the Nile River',
                            'Learn about Egyptian pharaohs and their beliefs',
                            'Explore how the pyramids were built',
                        ],
                        'estimated_duration_minutes' => 40,
                    ],
                    [
                        'week_number' => 3,
                        'title' => 'Ancient Greece',
                        'description' => 'Learn about Greek democracy, philosophy, and mythology.',
                        'prompt_text' => 'Explain ancient Greek civilization, including democracy, Olympics, and mythology.',
                        'learning_objectives' => [
                            'Understand the origin of democracy in Athens',
                            'Learn about Greek gods and myths',
                            'Explore Greek contributions to art and science',
                        ],
                        'estimated_duration_minutes' => 40,
                    ],
                    [
                        'week_number' => 4,
                        'title' => 'The Roman Empire',
                        'description' => 'Explore the rise and fall of one of historys greatest empires.',
                        'prompt_text' => 'Teach about the Roman Empire, including its government, architecture, and legacy.',
                        'learning_objectives' => [
                            'Trace the growth of the Roman Empire',
                            'Learn about Roman engineering and roads',
                            'Understand Roman influence on modern society',
                        ],
                        'estimated_duration_minutes' => 45,
                    ],
                    [
                        'week_number' => 5,
                        'title' => 'Ancient China',
                        'description' => 'Discover the dynasties, inventions, and philosophy of ancient China.',
                        'prompt_text' => 'Explain ancient Chinese civilization, including the Great Wall, silk, and Confucius.',
                        'learning_objectives' => [
                            'Learn about major Chinese dynasties',
                            'Understand Chinese inventions like paper and gunpowder',
                            'Explore Confucianism and its influence',
                        ],
                        'estimated_duration_minutes' => 40,
                    ],
                    [
                        'week_number' => 6,
                        'title' => 'Ancient India',
                        'description' => 'Explore the Indus Valley civilization and the birth of Hinduism and Buddhism.',
                        'prompt_text' => 'Teach about ancient India, including the Indus Valley, caste system, and religions.',
                        'learning_objectives' => [
                            'Learn about the Indus Valley civilization',
                            'Understand the origins of Hinduism and Buddhism',
                            'Explore ancient Indian mathematics and science',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 7,
                        'title' => 'The Maya and Aztecs',
                        'description' => 'Journey to the Americas to discover the great Mesoamerican civilizations.',
                        'prompt_text' => 'Explain the Maya and Aztec civilizations, including their cities, calendars, and culture.',
                        'learning_objectives' => [
                            'Locate the Maya and Aztec empires on a map',
                            'Learn about their achievements in astronomy and math',
                            'Understand their religious beliefs and practices',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 8,
                        'title' => 'Comparing Ancient Civilizations',
                        'description' => 'Review and compare what we have learned about ancient civilizations.',
                        'prompt_text' => 'Compare and contrast the major ancient civilizations and their lasting impacts.',
                        'learning_objectives' => [
                            'Identify common features across ancient civilizations',
                            'Understand how ancient innovations affect us today',
                            'Reflect on what made these civilizations great',
                        ],
                        'estimated_duration_minutes' => 45,
                    ],
                ],
            ],
            [
                'title' => 'Introduction to Coding',
                'description' => 'Learn the basics of computer programming in this beginner-friendly course. Discover how computers think and start creating your own simple programs.',
                'length_in_weeks' => 6,
                'prompts' => [
                    [
                        'week_number' => 1,
                        'title' => 'What is Programming?',
                        'description' => 'Understand what coding is and how computers follow instructions.',
                        'prompt_text' => 'Explain what programming is, how computers process instructions, and why coding is important.',
                        'learning_objectives' => [
                            'Understand what a computer program is',
                            'Learn how computers follow step-by-step instructions',
                            'Discover why learning to code is valuable',
                        ],
                        'estimated_duration_minutes' => 25,
                    ],
                    [
                        'week_number' => 2,
                        'title' => 'Algorithms and Problem Solving',
                        'description' => 'Learn to break down problems into step-by-step solutions.',
                        'prompt_text' => 'Teach about algorithms using everyday examples like recipes and directions.',
                        'learning_objectives' => [
                            'Define what an algorithm is',
                            'Practice breaking problems into smaller steps',
                            'Create simple algorithms for everyday tasks',
                        ],
                        'estimated_duration_minutes' => 30,
                    ],
                    [
                        'week_number' => 3,
                        'title' => 'Variables and Data',
                        'description' => 'Discover how computers store and remember information.',
                        'prompt_text' => 'Explain variables and data types in programming using relatable examples.',
                        'learning_objectives' => [
                            'Understand what variables are',
                            'Learn about different types of data',
                            'Practice creating and using variables',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 4,
                        'title' => 'Making Decisions with Code',
                        'description' => 'Learn how programs make choices using conditions.',
                        'prompt_text' => 'Teach about conditional statements (if/else) and how programs make decisions.',
                        'learning_objectives' => [
                            'Understand conditional logic',
                            'Learn to use if/else statements',
                            'Create programs that make decisions',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 5,
                        'title' => 'Loops and Repetition',
                        'description' => 'Discover how to make computers repeat tasks efficiently.',
                        'prompt_text' => 'Explain loops and iteration, showing how repetition makes programs powerful.',
                        'learning_objectives' => [
                            'Understand why loops are useful',
                            'Learn about different types of loops',
                            'Create programs that use repetition',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 6,
                        'title' => 'Building Your First Program',
                        'description' => 'Put it all together to create a complete program.',
                        'prompt_text' => 'Guide through building a simple program that uses variables, conditions, and loops.',
                        'learning_objectives' => [
                            'Combine programming concepts together',
                            'Debug and fix problems in code',
                            'Complete a working program',
                        ],
                        'estimated_duration_minutes' => 45,
                    ],
                ],
            ],
            [
                'title' => 'Life Science: The Human Body',
                'description' => 'Discover the amazing machine that is your body! Learn how your organs work together to keep you alive, healthy, and active.',
                'length_in_weeks' => 8,
                'prompts' => [
                    [
                        'week_number' => 1,
                        'title' => 'Introduction to Body Systems',
                        'description' => 'Overview of how your body is organized into systems that work together.',
                        'prompt_text' => 'Introduce the concept of body systems and how they work together.',
                        'learning_objectives' => [
                            'Understand what a body system is',
                            'Learn the major body systems',
                            'See how systems depend on each other',
                        ],
                        'estimated_duration_minutes' => 25,
                    ],
                    [
                        'week_number' => 2,
                        'title' => 'The Skeletal System',
                        'description' => 'Learn about the 206 bones that give your body shape and protection.',
                        'prompt_text' => 'Explain the skeletal system, including bones, joints, and how bones grow.',
                        'learning_objectives' => [
                            'Identify major bones in the body',
                            'Understand how bones protect organs',
                            'Learn how joints allow movement',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 3,
                        'title' => 'The Muscular System',
                        'description' => 'Discover the muscles that help you move, lift, and even smile.',
                        'prompt_text' => 'Teach about muscles, how they work with bones, and different types of muscles.',
                        'learning_objectives' => [
                            'Learn the three types of muscles',
                            'Understand how muscles and bones work together',
                            'Explore how exercise strengthens muscles',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 4,
                        'title' => 'The Circulatory System',
                        'description' => 'Follow the journey of blood as it travels through your body.',
                        'prompt_text' => 'Explain the heart, blood vessels, and blood, and how they deliver oxygen.',
                        'learning_objectives' => [
                            'Understand how the heart pumps blood',
                            'Learn about arteries, veins, and capillaries',
                            'Discover what blood is made of',
                        ],
                        'estimated_duration_minutes' => 40,
                    ],
                    [
                        'week_number' => 5,
                        'title' => 'The Respiratory System',
                        'description' => 'Learn how you breathe and why oxygen is essential for life.',
                        'prompt_text' => 'Teach about the lungs, breathing process, and gas exchange.',
                        'learning_objectives' => [
                            'Understand the breathing process',
                            'Learn how lungs exchange gases',
                            'See how respiratory and circulatory systems work together',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 6,
                        'title' => 'The Digestive System',
                        'description' => 'Follow food on its journey from your mouth to energy for your body.',
                        'prompt_text' => 'Explain digestion from eating to nutrient absorption.',
                        'learning_objectives' => [
                            'Trace the path of food through the body',
                            'Understand how nutrients are absorbed',
                            'Learn about healthy eating habits',
                        ],
                        'estimated_duration_minutes' => 40,
                    ],
                    [
                        'week_number' => 7,
                        'title' => 'The Nervous System',
                        'description' => 'Explore your brain and nerves - the bodys control center.',
                        'prompt_text' => 'Teach about the brain, spinal cord, and nerves and how they control the body.',
                        'learning_objectives' => [
                            'Understand what the brain does',
                            'Learn how nerves send messages',
                            'Explore the five senses',
                        ],
                        'estimated_duration_minutes' => 45,
                    ],
                    [
                        'week_number' => 8,
                        'title' => 'Staying Healthy',
                        'description' => 'Learn how to take care of your amazing body.',
                        'prompt_text' => 'Discuss nutrition, exercise, sleep, and hygiene for a healthy body.',
                        'learning_objectives' => [
                            'Understand the importance of nutrition',
                            'Learn why exercise and sleep matter',
                            'Create healthy habits for life',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                ],
            ],
            [
                'title' => 'Creative Writing Basics',
                'description' => 'Unlock your imagination and learn to tell amazing stories! This course teaches the fundamentals of creative writing through fun exercises and prompts.',
                'length_in_weeks' => 6,
                'prompts' => [
                    [
                        'week_number' => 1,
                        'title' => 'The Power of Storytelling',
                        'description' => 'Discover why stories matter and what makes a story engaging.',
                        'prompt_text' => 'Introduce storytelling, its history, and the basic elements of a good story.',
                        'learning_objectives' => [
                            'Understand why humans tell stories',
                            'Identify the basic elements of a story',
                            'Start generating story ideas',
                        ],
                        'estimated_duration_minutes' => 30,
                    ],
                    [
                        'week_number' => 2,
                        'title' => 'Creating Characters',
                        'description' => 'Learn to create memorable characters that readers care about.',
                        'prompt_text' => 'Teach character development, including traits, motivations, and backstory.',
                        'learning_objectives' => [
                            'Create well-rounded characters',
                            'Develop character motivations and goals',
                            'Write character descriptions',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 3,
                        'title' => 'Building Your World',
                        'description' => 'Create vivid settings that bring your stories to life.',
                        'prompt_text' => 'Explain setting and world-building, using sensory details to create atmosphere.',
                        'learning_objectives' => [
                            'Describe settings using the five senses',
                            'Create atmosphere and mood',
                            'Build believable story worlds',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 4,
                        'title' => 'Plot and Structure',
                        'description' => 'Learn how to structure a story with a beginning, middle, and end.',
                        'prompt_text' => 'Teach story structure, conflict, and how to keep readers engaged.',
                        'learning_objectives' => [
                            'Understand story structure',
                            'Create compelling conflicts',
                            'Build tension and resolution',
                        ],
                        'estimated_duration_minutes' => 40,
                    ],
                    [
                        'week_number' => 5,
                        'title' => 'Dialogue and Voice',
                        'description' => 'Make your characters speak in unique and engaging ways.',
                        'prompt_text' => 'Teach dialogue writing, character voice, and conversation formatting.',
                        'learning_objectives' => [
                            'Write natural-sounding dialogue',
                            'Give each character a unique voice',
                            'Use dialogue to reveal character',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 6,
                        'title' => 'Writing Your Story',
                        'description' => 'Put it all together and write your own short story.',
                        'prompt_text' => 'Guide through writing a complete short story using all learned elements.',
                        'learning_objectives' => [
                            'Plan and outline a short story',
                            'Write a complete first draft',
                            'Learn basic revision techniques',
                        ],
                        'estimated_duration_minutes' => 45,
                    ],
                ],
            ],
            [
                'title' => 'Introduction to Music',
                'description' => 'Discover the language of music! Learn about rhythm, melody, and the instruments that make the sounds we love.',
                'length_in_weeks' => 6,
                'prompts' => [
                    [
                        'week_number' => 1,
                        'title' => 'What is Music?',
                        'description' => 'Explore what makes sound into music and why we love it.',
                        'prompt_text' => 'Introduce music concepts including sound, rhythm, and why humans create music.',
                        'learning_objectives' => [
                            'Understand the difference between sound and music',
                            'Learn basic musical concepts',
                            'Explore why music is universal',
                        ],
                        'estimated_duration_minutes' => 25,
                    ],
                    [
                        'week_number' => 2,
                        'title' => 'Rhythm and Beat',
                        'description' => 'Feel the beat! Learn about rhythm and how it drives music.',
                        'prompt_text' => 'Teach rhythm, beat, tempo, and time signatures with examples.',
                        'learning_objectives' => [
                            'Identify and keep a steady beat',
                            'Understand tempo (fast vs slow)',
                            'Create simple rhythmic patterns',
                        ],
                        'estimated_duration_minutes' => 30,
                    ],
                    [
                        'week_number' => 3,
                        'title' => 'Melody and Harmony',
                        'description' => 'Discover how notes combine to create tunes we remember.',
                        'prompt_text' => 'Explain melody, pitch, scales, and basic harmony.',
                        'learning_objectives' => [
                            'Understand high and low pitches',
                            'Learn what makes a melody',
                            'Hear how harmony supports melody',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 4,
                        'title' => 'Musical Instruments',
                        'description' => 'Meet the families of instruments that make music.',
                        'prompt_text' => 'Introduce instrument families: strings, woodwinds, brass, and percussion.',
                        'learning_objectives' => [
                            'Identify the four instrument families',
                            'Learn how different instruments make sound',
                            'Recognize instrument sounds',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 5,
                        'title' => 'Music Around the World',
                        'description' => 'Explore how different cultures create and enjoy music.',
                        'prompt_text' => 'Teach about music from different cultures and their unique instruments and styles.',
                        'learning_objectives' => [
                            'Discover music from various cultures',
                            'Learn about unique instruments worldwide',
                            'Appreciate musical diversity',
                        ],
                        'estimated_duration_minutes' => 35,
                    ],
                    [
                        'week_number' => 6,
                        'title' => 'Making Your Own Music',
                        'description' => 'Learn how to start creating your own musical sounds.',
                        'prompt_text' => 'Guide through basic music creation, including singing, clapping, and simple composition.',
                        'learning_objectives' => [
                            'Experiment with creating rhythms',
                            'Try basic music-making activities',
                            'Understand that anyone can make music',
                        ],
                        'estimated_duration_minutes' => 40,
                    ],
                ],
            ],
        ];

        foreach ($courses as $courseData) {
            $prompts = $courseData['prompts'];
            unset($courseData['prompts']);

            $course = Course::create($courseData);

            foreach ($prompts as $promptData) {
                $promptData['course_id'] = $course->id;
                $promptData['trivia_questions'] = $promptData['trivia_questions'] ?? null;
                $promptData['additional_resources'] = $promptData['additional_resources'] ?? null;
                CoursePrompt::create($promptData);
            }
        }
    }
}
