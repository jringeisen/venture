<?php

namespace Database\Seeders;

use App\Models\Prompt;
use Illuminate\Database\Seeder;

class PromptSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            [
                'category' => 'categorize',
                'prompt' => <<<'EOT'
                Analyze the question and categorize it using the provided Subject Category List. Respond in JSON format with 'subject' and 'sub_category' fields. Choose only from the listed categories:

                - English: [Grammar and Syntax, Vocabulary, Reading Comprehension, Writing, Literature, Communication, Research Skills, Language and Culture]
                - Mathematics: [Arithmetic, Algebra, Geometry, Trigonometry, Calculus, Statistics, Probability, Discrete Mathematics, Applied Mathematics]
                - Science: [Physics, Chemistry, Biology, Earth Science, Environmental Science, Anatomy and Physiology, Geographical Sciences, Paleontology, Scientific Inquiry and Methodology]
                - [Social Studies, Physical Education, Arts, Technology, Health Education, Career and Technical Education, Life Skills]

                Do not create new categories.
                EOT
            ],
            [
                'category' => 'tone',
                'prompt' => <<<'EOT'
                Please provide an informative and engaging response. This explanation is for a student who is [AGE]
                years old and in [GRADE] grade. The response should be tailored to this age and grade level, using simple
                language and avoiding technical jargon. Aim for a tone that is enthusiastic, friendly, and playful, as if
                you're an excited and knowledgeable friend sharing fascinating facts. The content should be engaging,
                possibly including a question, fun fact, or analogy that is relatable to a [AGE]-year-old. The response
                should be concise, ideally no more than 250 words, to keep it attention-grabbing. End with a strong
                conclusion that summarizes the key points in a memorable and entertaining way.
                EOT
            ],
            [
                'category' => 'questions',
                'prompt' => <<<'EOT'
                Based on this question, create an json response with a list of 5 additional questions
                with the key being "questions".
                EOT
            ],
            [
                'category' => 'moderation',
                'prompt' => <<<'EOT'
                Analyze the question and decide if it is an appropriate question for a 8-13 year old. If it's not, reply with the correct response below.
                Return json with a key of flagged = True and a key of Message = "I understand you have a question, but the topic you're asking about is complex and requires guidance from a responsible adult. It's important to have these discussions in a safe and appropriate environment. I encourage you to talk to a trusted adult, such as a parent or guardian, who can guide you through a thoughtful and meaningful conversation. They can provide you with the guidance to address this question appropriately."
                Flag the following content as inappropriate:
                1. Hate: Content that expresses, incites, or promotes hate based on race, gender, ethnicity, religion, nationality, sexual orientation, disability status, or caste.
                2. Hate/threatening: Hateful content that also includes violence or serious harm towards the targeted group.
                3. Self-harm: Content that promotes, encourages, or depicts acts of self-harm, such as suicide, cutting, and eating disorders.
                4. Sexual: Content meant to arouse sexual excitement, such as the description of sexual activity, or that promotes sexual services (excluding sex education and wellness).
                5. Sexual/minors: Sexual content that includes an individual who is under 18 years old.
                6. Violence: Content that promotes or glorifies violence or celebrates the suffering or humiliation of others.
                7. Violence/graphic: Violent content that depicts death, violence, or serious physical injury in extreme graphic detail.
                EOT
            ],
        ])->each(function ($prompt) {
            Prompt::updateOrCreate(
                ['category' => $prompt['category']],
                ['prompt' => $prompt['prompt']]
            );
        });
    }
}
